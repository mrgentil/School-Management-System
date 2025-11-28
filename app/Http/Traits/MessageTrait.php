<?php

namespace App\Http\Traits;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Trait MessageTrait
 * 
 * Logique commune pour la messagerie partagée entre tous les contrôleurs
 * (Student, Teacher, Admin, SuperAdmin)
 */
trait MessageTrait
{
    /**
     * Récupère les messages pour l'utilisateur connecté
     * 
     * @param string $filter 'all', 'inbox', 'sent'
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function getMessages(string $filter = 'all', int $perPage = 15)
    {
        $userId = auth()->id();
        
        $query = Message::with(['sender', 'recipients.recipient', 'attachments'])
            ->where(function($q) use ($userId, $filter) {
                if ($filter === 'inbox') {
                    // Messages reçus uniquement
                    $q->whereHas('recipients', function($sub) use ($userId) {
                        $sub->where('recipient_id', $userId);
                    });
                } elseif ($filter === 'sent') {
                    // Messages envoyés uniquement
                    $q->where('sender_id', $userId);
                } else {
                    // Tous les messages (reçus + envoyés)
                    $q->whereHas('recipients', function($sub) use ($userId) {
                        $sub->where('recipient_id', $userId);
                    })->orWhere('sender_id', $userId);
                }
            })
            ->orderBy('created_at', 'desc');
            
        return $query->paginate($perPage);
    }

    /**
     * Compte les messages non lus
     * 
     * @return int
     */
    protected function getUnreadCount(): int
    {
        return MessageRecipient::where('recipient_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Récupère un message par son ID avec vérification d'accès
     * 
     * @param Request $request
     * @param mixed $id
     * @return Message|null
     */
    protected function getMessage(Request $request, $id = null)
    {
        // Récupérer l'ID depuis l'URL si le paramètre est vide
        if (empty($id)) {
            $parts = explode('/', trim($request->path(), '/'));
            $id = end($parts);
            
            // Si c'est "reply", prendre l'avant-dernier
            if ($id === 'reply' && count($parts) >= 2) {
                $id = $parts[count($parts) - 2];
            }
        }
        
        $message = Message::with(['sender', 'recipients.recipient', 'attachments', 'replies.sender'])
            ->find($id);
            
        if (!$message) {
            return null;
        }
        
        // Vérifier l'accès
        $userId = auth()->id();
        $hasAccess = ($message->sender_id == $userId) ||
                     $message->recipients->contains('recipient_id', $userId);
                     
        if (!$hasAccess) {
            return null;
        }
        
        return $message;
    }

    /**
     * Marque un message comme lu
     * 
     * @param Message $message
     */
    protected function markAsRead(Message $message): void
    {
        if ($message->sender_id != auth()->id()) {
            MessageRecipient::where('message_id', $message->id)
                ->where('recipient_id', auth()->id())
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
        }
    }

    /**
     * Crée un nouveau message
     * 
     * @param Request $request
     * @param array $recipients Liste des IDs des destinataires
     * @return Message
     * @throws \Exception
     */
    protected function createMessage(Request $request, array $recipients): Message
    {
        DB::beginTransaction();
        
        try {
            // Créer le message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'subject' => $request->subject,
                'content' => $request->content,
                'message' => $request->content, // Compatibilité avec l'ancienne colonne
                'priority' => $request->priority ?? 'normal',
                'parent_id' => $request->parent_id ?? null,
            ]);

            // Ajouter les destinataires
            foreach ($recipients as $recipientId) {
                if ($recipientId != auth()->id()) { // Ne pas s'envoyer à soi-même
                    MessageRecipient::create([
                        'message_id' => $message->id,
                        'recipient_id' => $recipientId,
                        'is_read' => false,
                    ]);
                }
            }

            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            
            return $message;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Crée une réponse à un message existant
     * 
     * @param Request $request
     * @param Message $originalMessage
     * @return Message
     * @throws \Exception
     */
    protected function createReply(Request $request, Message $originalMessage): Message
    {
        // Collecter les destinataires de la réponse
        $recipients = $originalMessage->recipients->pluck('recipient_id')->toArray();
        
        // Ajouter l'expéditeur original s'il n'est pas déjà dans la liste
        if (!in_array($originalMessage->sender_id, $recipients) && $originalMessage->sender_id != auth()->id()) {
            $recipients[] = $originalMessage->sender_id;
        }
        
        // Retirer l'utilisateur courant des destinataires
        $recipients = array_filter($recipients, fn($id) => $id != auth()->id());

        DB::beginTransaction();
        
        try {
            // Créer la réponse
            $reply = Message::create([
                'sender_id' => auth()->id(),
                'subject' => 'Re: ' . preg_replace('/^Re:\s*/i', '', $originalMessage->subject),
                'content' => $request->content,
                'message' => $request->content,
                'parent_id' => $originalMessage->id,
                'priority' => $originalMessage->priority,
            ]);

            // Ajouter les destinataires
            foreach ($recipients as $recipientId) {
                MessageRecipient::create([
                    'message_id' => $reply->id,
                    'recipient_id' => $recipientId,
                    'is_read' => false,
                ]);
            }

            // Gérer les pièces jointes
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('messages/attachments', 'public');
                    
                    MessageAttachment::create([
                        'message_id' => $reply->id,
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Supprime un message
     * 
     * @param Message $message
     * @return bool
     */
    protected function deleteMessage(Message $message): bool
    {
        // Supprimer les pièces jointes du stockage
        foreach ($message->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->path);
        }
        
        return $message->delete();
    }

    /**
     * Récupère la liste des utilisateurs disponibles pour l'envoi de messages
     * selon le type d'utilisateur connecté
     * 
     * @return array
     */
    protected function getAvailableRecipients(): array
    {
        $userId = auth()->id();
        $userType = auth()->user()->user_type;
        
        $recipients = [];
        
        // Selon le type d'utilisateur, on filtre les destinataires possibles
        switch ($userType) {
            case 'student':
                // Les étudiants peuvent contacter : enseignants, admin
                $recipients['teachers'] = User::where('user_type', 'teacher')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['admins'] = User::whereIn('user_type', ['admin', 'super_admin'])
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                break;
                
            case 'teacher':
                // Les enseignants peuvent contacter : étudiants, autres enseignants, admin, parents
                $recipients['students'] = User::where('user_type', 'student')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['teachers'] = User::where('user_type', 'teacher')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['admins'] = User::whereIn('user_type', ['admin', 'super_admin'])
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['parents'] = User::where('user_type', 'parent')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                break;
                
            case 'admin':
            case 'super_admin':
                // Les admins peuvent contacter tout le monde
                $recipients['students'] = User::where('user_type', 'student')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['teachers'] = User::where('user_type', 'teacher')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['admins'] = User::whereIn('user_type', ['admin', 'super_admin'])
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['parents'] = User::where('user_type', 'parent')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['accountants'] = User::where('user_type', 'accountant')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                $recipients['librarians'] = User::where('user_type', 'librarian')
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
                break;
                
            default:
                // Par défaut, uniquement les admins
                $recipients['admins'] = User::whereIn('user_type', ['admin', 'super_admin'])
                    ->where('id', '!=', $userId)
                    ->orderBy('name')
                    ->get();
        }
        
        return $recipients;
    }

    /**
     * Récupère le fil de conversation complet d'un message
     * 
     * @param Message $message
     * @return \Illuminate\Support\Collection
     */
    protected function getConversationThread(Message $message)
    {
        // Trouver le message racine
        $rootMessage = $message;
        while ($rootMessage->parent_id) {
            $rootMessage = Message::find($rootMessage->parent_id) ?? $rootMessage;
            if (!$rootMessage->parent_id) break;
        }
        
        // Récupérer tous les messages du fil
        $messages = collect([$rootMessage]);
        $this->collectReplies($rootMessage, $messages);
        
        return $messages->sortBy('created_at');
    }

    /**
     * Collecte récursivement les réponses
     */
    private function collectReplies(Message $message, &$collection)
    {
        $replies = Message::with(['sender', 'attachments'])
            ->where('parent_id', $message->id)
            ->get();
            
        foreach ($replies as $reply) {
            $collection->push($reply);
            $this->collectReplies($reply, $collection);
        }
    }

    /**
     * Validation des règles pour un nouveau message
     */
    protected function messageValidationRules(): array
    {
        return [
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|in:low,normal,high',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    /**
     * Validation des règles pour une réponse
     */
    protected function replyValidationRules(): array
    {
        return [
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function validationMessages(): array
    {
        return [
            'recipients.required' => 'Veuillez sélectionner au moins un destinataire.',
            'recipients.min' => 'Veuillez sélectionner au moins un destinataire.',
            'subject.required' => 'Le sujet est obligatoire.',
            'content.required' => 'Le message ne peut pas être vide.',
            'attachments.*.max' => 'Chaque fichier ne doit pas dépasser 10 Mo.',
        ];
    }
}
