<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Récupérer les messages reçus ET envoyés
        $conversations = Message::where(function($query) use ($userId) {
                // Messages reçus
                $query->whereHas('recipients', function($q) use ($userId) {
                    $q->where('recipient_id', $userId);
                })
                // OU messages envoyés
                ->orWhere('sender_id', $userId);
            })
            ->with(['sender', 'recipients', 'attachments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.student.messages.index', compact('conversations'));
    }
    
    public function inbox()
    {
        return $this->index();
    }

    public function create()
    {
        $teachers = User::where('user_type', 'teacher')
            ->where('id', '!=', auth()->id())
            ->get();
            
        $admins = User::whereIn('user_type', ['admin', 'super_admin'])
            ->where('id', '!=', auth()->id())
            ->get();

        return view('pages.student.messages.create', compact('teachers', 'admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        DB::beginTransaction();
        
        try {
            // Créer le message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'subject' => $request->subject,
                'content' => $request->content,
                'message' => $request->content, // Pour compatibilité avec l'ancienne colonne
            ]);

            // Ajouter les destinataires
            foreach ($request->recipients as $recipientId) {
                MessageRecipient::create([
                    'message_id' => $message->id,
                    'recipient_id' => $recipientId,
                    'is_read' => false,
                ]);
            }

            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    
                    \App\Models\MessageAttachment::create([
                        'message_id' => $message->id,
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('student.messages.index')
                ->with('success', '✅ Message envoyé avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur envoi message: ' . $e->getMessage());
            return back()->with('error', '❌ Erreur: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Request $request, $id = null)
    {
        // Récupérer l'ID depuis l'URL si le paramètre est vide
        if (empty($id)) {
            $urlParts = explode('/', $request->path());
            $id = end($urlParts);
        }
        
        try {
            // Récupérer le message
            $message = Message::with(['sender', 'recipients.recipient', 'attachments'])
                ->findOrFail($id);
            
            // Vérifier l'accès
            $hasAccess = ($message->sender_id == auth()->id()) || 
                         $message->recipients->contains('recipient_id', auth()->id());
            
            if (!$hasAccess) {
                return redirect()->route('student.messages.index')
                    ->with('error', '❌ Vous n\'avez pas accès à ce message.');
            }

            // Marquer le message comme lu
            if ($message->sender_id != auth()->id()) {
                MessageRecipient::where('message_id', $message->id)
                    ->where('recipient_id', auth()->id())
                    ->update(['is_read' => true]);
            }

            // Pour l'instant, afficher juste ce message
            $conversation = collect([$message]);

            return view('pages.student.messages.show', compact('message', 'conversation'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur affichage message ID=' . $id . ': ' . $e->getMessage());
            return redirect()->route('student.messages.index')
                ->with('error', '❌ Message introuvable.');
        }
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $originalMessage = Message::findOrFail($id);
        $recipients = $originalMessage->recipients->pluck('recipient_id')->toArray();
        
        // Ajouter l'expéditeur original comme destinataire s'il n'est pas déjà dans la liste
        if (!in_array($originalMessage->sender_id, $recipients) && $originalMessage->sender_id != auth()->id()) {
            $recipients[] = $originalMessage->sender_id;
        }

        DB::beginTransaction();
        
        try {
            // Créer la réponse
            $reply = Message::create([
                'sender_id' => auth()->id(),
                'subject' => $originalMessage->subject,
                'content' => $request->content,
                'parent_id' => $originalMessage->id,
            ]);

            // Ajouter les destinataires
            foreach ($recipients as $recipientId) {
                if ($recipientId != auth()->id()) { // Ne pas s'envoyer le message à soi-même
                    MessageRecipient::create([
                        'message_id' => $reply->id,
                        'recipient_id' => $recipientId,
                        'is_read' => false,
                    ]);
                }
            }

            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    $reply->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('student.messages.show', $reply->id)
                ->with('success', 'Réponse envoyée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi de la réponse.')->withInput();
        }
    }
}
