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
        return $this->inbox();
    }
    
    public function inbox()
    {
        $userId = auth()->id();
        
        // Récupérer les conversations
        $conversations = Message::select('messages.*')
            ->join('message_recipients', function($join) use ($userId) {
                $join->on('messages.id', '=', 'message_recipients.message_id')
                    ->where('message_recipients.recipient_id', $userId);
            })
            ->with(['sender', 'recipients'])
            ->orderBy('messages.created_at', 'desc')
            ->groupBy('messages.id')
            ->paginate(15);

        return view('pages.student.messages.inbox', compact('conversations'));
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
                    $message->attachments()->create([
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('student.messages.show', $message->id)
                ->with('success', 'Message envoyé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'envoi du message.')->withInput();
        }
    }

    public function show($id)
    {
        $message = Message::with(['sender', 'recipients', 'attachments'])
            ->where('id', $id)
            ->where(function($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhereHas('recipients', function($q) {
                        $q->where('recipient_id', auth()->id());
                    });
            })
            ->firstOrFail();

        // Marquer le message comme lu
        if ($message->sender_id != auth()->id()) {
            $message->recipients()->where('recipient_id', auth()->id())->update(['is_read' => true]);
        }

        // Récupérer la conversation complète
        $conversation = Message::where('subject', $message->subject)
            ->where(function($query) use ($message) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('sender_id', $message->sender_id);
            })
            ->orWhereIn('id', function($query) use ($message) {
                $query->select('message_id')
                    ->from('message_recipients')
                    ->whereIn('message_id', function($q) use ($message) {
                        $q->select('id')
                            ->from('messages')
                            ->where('subject', $message->subject)
                            ->where(function($q2) {
                                $q2->where('sender_id', auth()->id())
                                    ->orWhereHas('recipients', function($q3) {
                                        $q3->where('recipient_id', auth()->id());
                                    });
                            });
                    });
            })
            ->with(['sender', 'recipients', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('pages.student.messages.show', compact('message', 'conversation'));
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
