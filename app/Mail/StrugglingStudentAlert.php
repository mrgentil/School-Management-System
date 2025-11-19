<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Qs;

class StrugglingStudentAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $school_name = Qs::getSetting('system_name');
        
        return $this->subject('ALERTE - Difficultés Scolaires de ' . $this->data['student_name'])
                    ->view('emails.struggling_student_alert')
                    ->with([
                        'student_name' => $this->data['student_name'],
                        'exam_name' => $this->data['exam_name'],
                        'average' => $this->data['average'],
                        'position' => $this->data['position'],
                        'class_name' => $this->data['class_name'],
                        'school_name' => $school_name,
                        'recommendations' => $this->getRecommendations()
                    ]);
    }

    private function getRecommendations()
    {
        return [
            'Prendre rendez-vous avec le professeur principal',
            'Organiser des séances de rattrapage',
            'Vérifier l\'assiduité de l\'élève',
            'Réviser les méthodes d\'apprentissage à la maison',
            'Consulter le conseiller pédagogique si nécessaire'
        ];
    }
}
