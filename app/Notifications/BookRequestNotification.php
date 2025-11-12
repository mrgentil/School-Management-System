<?php

namespace App\Notifications;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bookRequest;
    public $book;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(BookRequest $bookRequest, Book $book, string $status)
    {
        $this->bookRequest = $bookRequest;
        $this->book = $book;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $subject = match($this->status) {
            'pending' => 'Demande de livre en attente',
            'approved' => 'Votre demande de livre a été approuvée',
            'rejected' => 'Mise à jour de votre demande de livre',
            default => 'Mise à jour de votre demande de livre'
        };

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . $notifiable->name . ',');

        switch ($this->status) {
            case 'pending':
                $message->line('Votre demande pour le livre **' . $this->book->name . '** a bien été enregistrée.')
                    ->line('Nous vous informerons dès que votre demande sera traitée.');
                break;
                
            case 'approved':
                $message->line('Votre demande pour le livre **' . $this->book->name . '** a été approuvée.')
                    ->line('Vous pouvez venir récupérer le livre à la bibliothèque aux heures d\'ouverture.');
                break;
                
            case 'rejected':
                $message->line('Votre demande pour le livre **' . $this->book->name . '** a été refusée.')
                    ->line('Raison: ' . ($this->bookRequest->rejection_reason ?? 'Non spécifiée'));
                break;
        }

        $message->action('Voir mes demandes', url('/student/library/requests'))
                ->line('Merci d\'utiliser notre bibliothèque !');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $message = match($this->status) {
            'pending' => 'Votre demande pour le livre "' . $this->book->name . '" est en attente de traitement.',
            'approved' => 'Votre demande pour le livre "' . $this->book->name . '" a été approuvée.',
            'rejected' => 'Votre demande pour le livre "' . $this->book->name . '" a été refusée.',
            default => 'Mise à jour de votre demande de livre.'
        };

        return [
            'book_id' => $this->book->id,
            'request_id' => $this->bookRequest->id,
            'status' => $this->status,
            'message' => $message,
            'url' => '/student/library/requests',
        ];
    }
}
