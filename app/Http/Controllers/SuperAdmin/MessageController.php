<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    use MessageTrait;

    /**
     * Affiche la boîte de réception (messages reçus + envoyés)
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $messages = $this->getMessages($filter, 20);
        $unreadCount = $this->getUnreadCount();
        
        // Compter les messages envoyés
        $sentCount = Message::where('sender_id', auth()->id())->count();
        
        // Compter les messages reçus
        $inboxCount = MessageRecipient::where('recipient_id', auth()->id())->count();

        return view('pages.super_admin.messages.index', compact(
            'messages', 
            'filter', 
            'unreadCount', 
            'sentCount', 
            'inboxCount'
        ));
    }

    /**
     * Formulaire de création d'un nouveau message
     */
    public function create()
    {
        $recipients = $this->getAvailableRecipients();
        
        // Grouper tous les utilisateurs par type pour le select
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get()
            ->groupBy('user_type');

        return view('pages.super_admin.messages.create', compact('users', 'recipients'));
    }

    /**
     * Enregistre un nouveau message
     */
    public function store(Request $request)
    {
        // Validation de base
        $rules = [
            'recipient_type' => 'required|in:all,all_students,all_parents,all_teachers,one_student,one_parent,one_teacher,individual',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|in:low,normal,high',
            'attachments.*' => 'nullable|file|max:10240',
        ];
        
        // Validation conditionnelle selon le type
        switch ($request->recipient_type) {
            case 'one_student':
                $rules['student_id'] = 'required|exists:users,id';
                break;
            case 'one_parent':
                $rules['parent_id'] = 'required|exists:users,id';
                break;
            case 'one_teacher':
                $rules['teacher_id'] = 'required|exists:users,id';
                break;
            case 'individual':
                $rules['recipients'] = 'required|array|min:1';
                $rules['recipients.*'] = 'exists:users,id';
                break;
        }
        
        $request->validate($rules);

        try {
            // Déterminer les destinataires selon le type
            $recipientIds = [];
            
            switch ($request->recipient_type) {
                case 'all':
                    // Tous les utilisateurs
                    $recipientIds = User::where('id', '!=', auth()->id())->pluck('id')->toArray();
                    break;
                    
                case 'all_students':
                    // Tous les étudiants
                    $recipientIds = User::where('user_type', 'student')
                        ->where('id', '!=', auth()->id())
                        ->pluck('id')
                        ->toArray();
                    break;
                    
                case 'all_parents':
                    // Tous les parents
                    $recipientIds = User::where('user_type', 'parent')
                        ->where('id', '!=', auth()->id())
                        ->pluck('id')
                        ->toArray();
                    break;
                    
                case 'all_teachers':
                    // Tous les enseignants
                    $recipientIds = User::where('user_type', 'teacher')
                        ->where('id', '!=', auth()->id())
                        ->pluck('id')
                        ->toArray();
                    break;
                    
                case 'one_student':
                    // Un seul étudiant
                    $recipientIds = [$request->student_id];
                    break;
                    
                case 'one_parent':
                    // Un seul parent
                    $recipientIds = [$request->parent_id];
                    break;
                    
                case 'one_teacher':
                    // Un seul enseignant
                    $recipientIds = [$request->teacher_id];
                    break;
                    
                case 'individual':
                    // Sélection multiple libre
                    $recipientIds = $request->recipients ?? [];
                    break;
            }

            if (empty($recipientIds)) {
                return back()->with('error', 'Veuillez sélectionner au moins un destinataire.')->withInput();
            }

            $message = $this->createMessage($request, $recipientIds);
            
            return redirect()->route('super_admin.messages.index')
                ->with('success', '✅ Message envoyé à ' . count($recipientIds) . ' destinataire(s) !');
                
        } catch (\Exception $e) {
            \Log::error('Erreur envoi message admin: ' . $e->getMessage());
            return back()->with('error', '❌ Erreur: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Affiche un message et son fil de discussion
     */
    public function show(Request $request, $id = null)
    {
        $message = $this->getMessage($request, $id);
        
        if (!$message) {
            return redirect()->route('super_admin.messages.index')
                ->with('error', '❌ Message introuvable ou accès refusé.');
        }

        // Marquer comme lu
        $this->markAsRead($message);
        
        // Récupérer le fil de conversation complet
        $conversation = $this->getConversationThread($message);

        return view('pages.super_admin.messages.show', compact('message', 'conversation'));
    }

    /**
     * Répond à un message
     */
    public function reply(Request $request, $id = null)
    {
        $request->validate($this->replyValidationRules(), $this->validationMessages());

        $originalMessage = $this->getMessage($request, $id);
        
        if (!$originalMessage) {
            return redirect()->route('super_admin.messages.index')
                ->with('error', '❌ Message d\'origine introuvable.');
        }

        try {
            $reply = $this->createReply($request, $originalMessage);
            
            return redirect()->route('super_admin.messages.show', $reply->id)
                ->with('success', '✅ Réponse envoyée avec succès !');
                
        } catch (\Exception $e) {
            \Log::error('Erreur réponse message admin: ' . $e->getMessage());
            return redirect()->route('super_admin.messages.show', $originalMessage->id)
                ->with('error', '❌ Erreur lors de l\'envoi de la réponse.');
        }
    }

    /**
     * Supprime un message
     */
    public function destroy(Request $request, $id = null)
    {
        $message = $this->getMessage($request, $id);
        
        if (!$message) {
            return redirect()->route('super_admin.messages.index')
                ->with('error', '❌ Message introuvable.');
        }

        // Seul l'expéditeur peut supprimer
        if ($message->sender_id != auth()->id()) {
            return redirect()->route('super_admin.messages.index')
                ->with('error', '❌ Vous ne pouvez supprimer que vos propres messages.');
        }

        try {
            $this->deleteMessage($message);
            
            return redirect()->route('super_admin.messages.index')
                ->with('success', '✅ Message supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur lors de la suppression.');
        }
    }

    /**
     * Marque un message comme lu (AJAX)
     */
    public function markRead(Request $request, $id)
    {
        $message = $this->getMessage($request, $id);
        
        if ($message) {
            $this->markAsRead($message);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
