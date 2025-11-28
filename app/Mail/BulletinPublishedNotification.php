<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulletinPublishedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $className;
    public $typeLabel;
    public $year;
    public $schoolName;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->studentName = $data['student_name'];
        $this->className = $data['class_name'];
        $this->typeLabel = $data['type_label'];
        $this->year = $data['year'];
        $this->schoolName = $data['school_name'] ?? config('app.name');
        $this->url = $data['url'] ?? '#';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "📋 Bulletin de {$this->studentName} disponible - {$this->typeLabel}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bulletin_published',
        );
    }
}
