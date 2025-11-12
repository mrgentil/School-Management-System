<?php

namespace App\Listeners;

use App\Events\BookRequestStatusUpdated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendBookRequestNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BookRequestStatusUpdated  $event
     * @return void
     */
    public function handle(BookRequestStatusUpdated $event)
    {
        $bookRequest = $event->bookRequest;
        $status = $event->status;
        $userId = $event->userId;

        // Créer une notification en base de données
        $notification = new Notification();
        $notification->id = (string) Str::uuid();
        $notification->type = 'App\Notifications\BookRequestStatusUpdated';
        $notification->notifiable_type = 'App\Models\User';
        $notification->notifiable_id = $userId;
        $notification->data = json_encode([
            'title' => 'Mise à jour de votre demande de livre',
            'message' => $this->getStatusMessage($status, $bookRequest),
            'url' => route('student.book-requests.show', $bookRequest->id),
            'book_request_id' => $bookRequest->id,
            'status' => $status
        ]);
        $notification->save();

        // Mettre à jour le statut de notification dans la demande
        $bookRequest->update([
            'is_notified' => true,
            'notification_type' => $status
        ]);
    }

    /**
     * Génère le message de notification en fonction du statut
     *
     * @param string $status
     * @param \App\Models\BookRequest $bookRequest
     * @return string
     */
    protected function getStatusMessage($status, $bookRequest)
    {
        $messages = [
            'en_attente' => 'Votre demande pour le livre "' . $bookRequest->book->title . '" est en attente de traitement.',
            'approuve' => 'Félicitations ! Votre demande pour le livre "' . $bookRequest->book->title . '" a été approuvée.',
            'refuse' => 'Votre demande pour le livre "' . $bookRequest->book->title . '" a été refusée.'
        ];

        return $messages[$status] ?? 'Le statut de votre demande de livre a été mis à jour.';
    }
}
