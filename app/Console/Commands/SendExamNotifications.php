<?php

namespace App\Console\Commands;

use App\Models\ExamNotification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExamNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les notifications d\'examens en attente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifications = ExamNotification::where('sent', false)->get();

        if ($notifications->count() == 0) {
            $this->info('Aucune notification en attente.');
            return 0;
        }

        $count = 0;

        foreach ($notifications as $notification) {
            try {
                // Récupérer les destinataires
                $recipients = $this->getRecipients($notification);

                foreach ($recipients as $user) {
                    // Ici vous pouvez implémenter l'envoi d'email, SMS, etc.
                    // Pour l'instant, on marque juste comme envoyé
                    $this->sendNotificationToUser($user, $notification);
                }

                // Marquer comme envoyé
                $notification->update([
                    'sent' => true,
                    'sent_at' => now(),
                ]);

                $count++;
                $this->info("Notification '{$notification->title}' envoyée à " . $recipients->count() . " destinataires.");
                
            } catch (\Exception $e) {
                $this->error("Erreur lors de l'envoi de la notification {$notification->id}: " . $e->getMessage());
            }
        }

        $this->info("Total: $count notification(s) envoyée(s).");
        return 0;
    }

    private function getRecipients($notification)
    {
        $recipients = $notification->recipients;

        if (in_array('all_students', $recipients)) {
            return User::where('user_type', 'student')->get();
        }

        // Sinon, récupérer les étudiants des classes spécifiées
        if (is_array($recipients) && count($recipients) > 0) {
            return User::whereHas('student_record', function($q) use ($recipients) {
                $q->whereIn('my_class_id', $recipients);
            })->get();
        }

        return collect();
    }

    private function sendNotificationToUser($user, $notification)
    {
        // À implémenter: envoi d'email, SMS, notification in-app, etc.
        // Pour l'instant, c'est un placeholder
        
        // Exemple d'envoi d'email (décommenter si configuré):
        /*
        Mail::to($user->email)->send(new \App\Mail\ExamNotification([
            'title' => $notification->title,
            'message' => $notification->message,
            'exam' => $notification->exam,
        ]));
        */
    }
}
