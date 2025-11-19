<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Qs;

class ExamResultsNotification extends Mailable
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
        
        return $this->subject('Résultats d\'Examen - ' . $this->data['exam_name'])
                    ->view('emails.exam_results_notification')
                    ->with([
                        'student_name' => $this->data['student_name'],
                        'exam_name' => $this->data['exam_name'],
                        'average' => $this->data['average'],
                        'position' => $this->data['position'],
                        'class_name' => $this->data['class_name'],
                        'school_name' => $school_name,
                        'mention' => $this->getMention($this->data['average']),
                        'status' => $this->getStatus($this->data['average'])
                    ]);
    }

    private function getMention($average)
    {
        if ($average >= 90) {
            return 'EXCELLENT';
        } elseif ($average >= 80) {
            return 'TRÈS BIEN';
        } elseif ($average >= 70) {
            return 'BIEN';
        } elseif ($average >= 60) {
            return 'ASSEZ BIEN';
        } elseif ($average >= 50) {
            return 'PASSABLE';
        } else {
            return 'INSUFFISANT';
        }
    }

    private function getStatus($average)
    {
        return $average >= 50 ? 'ADMIS' : 'ÉCHEC';
    }
}
