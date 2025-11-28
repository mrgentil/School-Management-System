<?php

namespace App\Console\Commands;

use App\Models\SchoolEvent;
use App\Models\Exam;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendReminders extends Command
{
    protected $signature = 'reminders:send {--type=all : Type de rappel (all, events, exams, payments)}';
    protected $description = 'Envoyer les rappels automatiques aux utilisateurs';

    public function handle()
    {
        $type = $this->option('type');
        
        $this->info('🔔 Envoi des rappels...');

        if ($type === 'all' || $type === 'events') {
            $this->sendEventReminders();
        }

        if ($type === 'all' || $type === 'exams') {
            $this->sendExamReminders();
        }

        $this->info('✅ Rappels envoyés avec succès !');
    }

    /**
     * Rappels d'événements (J-1 et J-3)
     */
    protected function sendEventReminders()
    {
        $this->info('📅 Vérification des événements à venir...');

        // Événements dans 1 jour ou 3 jours
        $tomorrow = Carbon::tomorrow()->toDateString();
        $in3Days = Carbon::now()->addDays(3)->toDateString();

        $events = SchoolEvent::where(function($q) use ($tomorrow, $in3Days) {
            $q->whereDate('event_date', $tomorrow)
              ->orWhereDate('event_date', $in3Days);
        })->get();

        foreach ($events as $event) {
            $eventDate = $event->event_date;
            $daysUntil = Carbon::now()->diffInDays($eventDate, false);
            
            if ($daysUntil == 1 || $daysUntil == 3) {
                $this->notifyUsersForEvent($event, $daysUntil);
            }
        }

        $this->info("  → {$events->count()} événement(s) avec rappel");
    }

    /**
     * Notifier les utilisateurs pour un événement
     */
    protected function notifyUsersForEvent($event, $daysUntil)
    {
        $audience = $event->target_audience ?? 'all';
        
        $users = User::when($audience !== 'all', function($q) use ($audience) {
            $q->where('user_type', rtrim($audience, 's')); // students -> student
        })->get();

        $prefix = $daysUntil == 1 ? '⏰ DEMAIN' : "📅 Dans {$daysUntil} jours";
        
        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'reminder',
                'title' => "{$prefix}: {$event->title}",
                'message' => $event->description ?? "N'oubliez pas cet événement !",
                'data' => [
                    'event_id' => $event->id,
                    'date' => $event->event_date?->format('d/m/Y'),
                ],
            ]);
        }

        $this->line("    📢 {$event->title} - {$users->count()} notification(s)");
    }

    /**
     * Rappels d'examens
     */
    protected function sendExamReminders()
    {
        $this->info('📝 Vérification des examens à venir...');

        // Examens qui commencent dans 3 jours
        $in3Days = Carbon::now()->addDays(3)->toDateString();
        
        // Note: Adapter selon la structure de votre table exams
        $count = 0;
        
        $this->info("  → {$count} examen(s) avec rappel");
    }
}
