<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use MessageTrait;

    /**
     * Affiche la boîte de réception
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $messages = $this->getMessages($filter, 15);
        $unreadCount = $this->getUnreadCount();
        
        $sentCount = Message::where('sender_id', auth()->id())->count();
        $inboxCount = MessageRecipient::where('recipient_id', auth()->id())->count();

        return view('pages.teacher.messages.index', compact(
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
        
        return view('pages.teacher.messages.create', compact('recipients'));
    }

    /**
     * Enregistre un nouveau message
     */
    public function store(Request $request)
    {
        $request->validate($this->messageValidationRules(), $this->validationMessages());

        try {
            $message = $this->createMessage($request, $request->recipients);
            
            return redirect()->route('teacher.messages.index')
                ->with('success', '✅ Message envoyé avec succès !');
                
        } catch (\Exception $e) {
            \Log::error('Erreur envoi message enseignant: ' . $e->getMessage());
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
            return redirect()->route('teacher.messages.index')
                ->with('error', '❌ Message introuvable ou accès refusé.');
        }

        // Marquer comme lu
        $this->markAsRead($message);
        
        // Récupérer le fil de conversation complet
        $conversation = $this->getConversationThread($message);

        return view('pages.teacher.messages.show', compact('message', 'conversation'));
    }

    /**
     * Répond à un message
     */
    public function reply(Request $request, $id = null)
    {
        $request->validate($this->replyValidationRules(), $this->validationMessages());

        $originalMessage = $this->getMessage($request, $id);
        
        if (!$originalMessage) {
            return redirect()->route('teacher.messages.index')
                ->with('error', '❌ Message d\'origine introuvable.');
        }

        try {
            $reply = $this->createReply($request, $originalMessage);
            
            return redirect()->route('teacher.messages.show', $reply->id)
                ->with('success', '✅ Réponse envoyée avec succès !');
                
        } catch (\Exception $e) {
            \Log::error('Erreur réponse message enseignant: ' . $e->getMessage());
            return redirect()->route('teacher.messages.show', $originalMessage->id)
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
            return redirect()->route('teacher.messages.index')
                ->with('error', '❌ Message introuvable.');
        }

        if ($message->sender_id != auth()->id()) {
            return redirect()->route('teacher.messages.index')
                ->with('error', '❌ Vous ne pouvez supprimer que vos propres messages.');
        }

        try {
            $this->deleteMessage($message);
            
            return redirect()->route('teacher.messages.index')
                ->with('success', '✅ Message supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur lors de la suppression.');
        }
    }
}
