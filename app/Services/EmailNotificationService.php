<?php

namespace App\Services;

use App\Models\User;
use App\Models\StudentRecord;
use App\Models\UserNotification;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailNotificationService
{
    /**
     * Envoyer une notification (in-app + email si configuré)
     */
    public function send(User $user, string $subject, string $message, array $data = [], bool $sendEmail = true)
    {
        try {
            // Toujours créer la notification in-app
            UserNotification::create([
                'user_id' => $user->id,
                'title' => $subject,
                'message' => $message,
                'type' => $data['type'] ?? 'info',
                'data' => json_encode($data),
            ]);

            // Envoyer l'email seulement si configuré et demandé
            if ($sendEmail && $this->isMailConfigured() && $user->email) {
                Mail::send('emails.notification', [
                    'user' => $user,
                    'subject' => $subject,
                    'messageContent' => $message,
                    'data' => $data,
                    'schoolName' => Qs::getSetting('system_name'),
                ], function ($mail) use ($user, $subject) {
                    $mail->to($user->email, $user->name)
                         ->subject($subject);
                });

                Log::info("EmailNotification envoyé à {$user->email}: {$subject}");
            }

            return true;

        } catch (\Exception $e) {
            Log::error("EmailNotification erreur: " . $e->getMessage());
            // La notification in-app a quand même été créée
            return true;
        }
    }

    /**
     * Vérifier si le mail est configuré
     */
    protected function isMailConfigured(): bool
    {
        $mailer = config('mail.default');
        
        // Si c'est "log" ou "array", le mail n'est pas vraiment configuré
        if (in_array($mailer, ['log', 'array'])) {
            return false;
        }

        // Vérifier qu'il y a un host SMTP configuré
        $host = config('mail.mailers.smtp.host');
        
        return !empty($host) && $host !== 'mailpit' && $host !== 'localhost';
    }

    /**
     * Notifier les parents d'une nouvelle note
     */
    public function notifyNewGrade($studentId, $subjectName, $grade, $period)
    {
        $student = StudentRecord::where('user_id', $studentId)->first();
        if (!$student || !$student->my_parent_id) return;

        $parent = User::find($student->my_parent_id);
        if (!$parent) return;

        $studentUser = User::find($studentId);
        
        $subject = "📝 Nouvelle note pour {$studentUser->name}";
        $message = "Votre enfant {$studentUser->name} a reçu une nouvelle note en {$subjectName}: {$grade}/20 (Période {$period}).";

        $this->send($parent, $subject, $message, [
            'type' => 'grade',
            'student_id' => $studentId,
            'subject' => $subjectName,
            'grade' => $grade,
        ]);
    }

    /**
     * Notifier les parents d'une absence
     */
    public function notifyAbsence($studentId, $date, $subjectName = null)
    {
        $student = StudentRecord::where('user_id', $studentId)->first();
        if (!$student || !$student->my_parent_id) return;

        $parent = User::find($student->my_parent_id);
        if (!$parent) return;

        $studentUser = User::find($studentId);
        
        $subject = "⚠️ Absence de {$studentUser->name}";
        $message = "Votre enfant {$studentUser->name} a été marqué absent le {$date}";
        if ($subjectName) {
            $message .= " en {$subjectName}";
        }
        $message .= ".";

        $this->send($parent, $subject, $message, [
            'type' => 'absence',
            'student_id' => $studentId,
            'date' => $date,
        ]);
    }

    /**
     * Notifier d'un événement à venir
     */
    public function notifyUpcomingEvent($event, $users)
    {
        $subject = "📅 Rappel: {$event->title}";
        $message = "L'événement \"{$event->title}\" aura lieu le {$event->event_date->format('d/m/Y')}.";
        
        if ($event->description) {
            $message .= "\n\nDétails: {$event->description}";
        }

        foreach ($users as $user) {
            $this->send($user, $subject, $message, [
                'type' => 'event',
                'event_id' => $event->id,
            ]);
        }
    }

    /**
     * Notifier d'un retard de paiement
     */
    public function notifyPaymentDue($parentId, $studentName, $amount, $paymentTitle)
    {
        $parent = User::find($parentId);
        if (!$parent) return;

        $subject = "💰 Rappel de paiement";
        $message = "Un solde de {$amount} FC reste à payer pour {$studentName} ({$paymentTitle}).";

        $this->send($parent, $subject, $message, [
            'type' => 'payment',
            'amount' => $amount,
        ]);
    }

    /**
     * Notifier d'un nouveau message
     */
    public function notifyNewMessage($recipientId, $senderName, $messageSubject)
    {
        $recipient = User::find($recipientId);
        if (!$recipient) return;

        $subject = "📩 Nouveau message de {$senderName}";
        $message = "Vous avez reçu un nouveau message: \"{$messageSubject}\".";

        $this->send($recipient, $subject, $message, [
            'type' => 'message',
            'sender' => $senderName,
        ]);
    }

    /**
     * Notifier que le bulletin est disponible
     */
    public function notifyBulletinAvailable($studentId, $period)
    {
        $student = StudentRecord::where('user_id', $studentId)->first();
        if (!$student || !$student->my_parent_id) return;

        $parent = User::find($student->my_parent_id);
        if (!$parent) return;

        $studentUser = User::find($studentId);
        
        $subject = "📄 Bulletin disponible pour {$studentUser->name}";
        $message = "Le bulletin de la période {$period} de votre enfant {$studentUser->name} est maintenant disponible.";

        $this->send($parent, $subject, $message, [
            'type' => 'bulletin',
            'student_id' => $studentId,
            'period' => $period,
        ]);
    }

    /**
     * Envoi en masse à tous les parents
     */
    public function notifyAllParents(string $subject, string $message, array $data = [])
    {
        $parents = User::where('user_type', 'parent')->get();
        
        foreach ($parents as $parent) {
            $this->send($parent, $subject, $message, $data);
        }

        return $parents->count();
    }

    /**
     * Envoi en masse à tous les enseignants
     */
    public function notifyAllTeachers(string $subject, string $message, array $data = [])
    {
        $teachers = User::where('user_type', 'teacher')->get();
        
        foreach ($teachers as $teacher) {
            $this->send($teacher, $subject, $message, $data);
        }

        return $teachers->count();
    }
}
