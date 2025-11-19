<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Qs;

class ExcellentPerformanceNotification extends Mailable
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
        
        return $this->subject('FÉLICITATIONS - Excellents Résultats de ' . $this->data['student_name'])
                    ->view('emails.excellent_performance_notification')
                    ->with([
                        'student_name' => $this->data['student_name'],
                        'exam_name' => $this->data['exam_name'],
                        'average' => $this->data['average'],
                        'position' => $this->data['position'],
                        'class_name' => $this->data['class_name'],
                        'school_name' => $school_name,
                        'mention' => $this->getMention($this->data['average']),
                        'encouragements' => $this->getEncouragements()
                    ]);
    }

    private function getMention($average)
    {
        if ($average >= 90) {
            return 'EXCELLENT';
        } elseif ($average >= 80) {
            return 'TRÈS BIEN';
        } else {
            return 'BIEN';
        }
    }

    private function getEncouragements()
    {
        return [
            'Continuez sur cette excellente voie !',
            'Votre travail et votre persévérance portent leurs fruits',
            'Vous êtes un exemple pour vos camarades',
            'Maintenez cette motivation pour les prochains examens',
            'L\'équipe pédagogique vous félicite chaleureusement'
        ];
    }
}
