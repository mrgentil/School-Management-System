<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvent;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\StudentRecord;
use App\Services\WhatsAppService;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Page de gestion des rappels
     */
    public function index()
    {
        // Prochains événements
        $upcomingEvents = SchoolEvent::upcoming()
            ->limit(10)
            ->get();

        // Statistiques
        $stats = [
            'pending_events' => SchoolEvent::upcoming()->count(),
            'notifications_today' => UserNotification::whereDate('created_at', today())->count(),
            'total_users' => User::count(),
        ];

        return view('pages.support_team.reminders.index', compact('upcomingEvents', 'stats'));
    }

    /**
     * Envoyer un rappel manuel
     */
    public function sendManual(Request $request)
    {
        $request->validate([
            'type' => 'required|in:event,custom',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:all,students,teachers,parents',
            'send_whatsapp' => 'boolean',
        ]);

        $users = User::when($request->target !== 'all', function($q) use ($request) {
            $q->where('user_type', rtrim($request->target, 's'));
        })->get();

        $count = 0;
        $whatsappCount = 0;
        $whatsapp = new WhatsAppService();

        foreach ($users as $user) {
            // Notification in-app
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'reminder',
                'title' => $request->title,
                'message' => $request->message,
            ]);
            $count++;

            // WhatsApp si demandé et configuré
            if ($request->send_whatsapp && $whatsapp->isConfigured() && $user->phone) {
                try {
                    $whatsapp->sendMessage($user->phone, "📢 {$request->title}\n\n{$request->message}");
                    $whatsappCount++;
                } catch (\Exception $e) {
                    \Log::error("WhatsApp rappel: " . $e->getMessage());
                }
            }
        }

        $msg = "✅ {$count} notification(s) envoyée(s)";
        if ($whatsappCount > 0) {
            $msg .= " + {$whatsappCount} WhatsApp";
        }

        return back()->with('flash_success', $msg);
    }

    /**
     * Envoyer rappel pour un événement spécifique
     */
    public function sendForEvent(SchoolEvent $event)
    {
        $audience = $event->target_audience ?? 'all';
        
        $users = User::when($audience !== 'all', function($q) use ($audience) {
            $q->where('user_type', rtrim($audience, 's'));
        })->get();

        foreach ($users as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'reminder',
                'title' => "📅 Rappel: {$event->title}",
                'message' => $event->description ?? "Événement le " . $event->formatted_date,
                'data' => ['event_id' => $event->id],
            ]);
        }

        return back()->with('flash_success', "Rappel envoyé à {$users->count()} utilisateur(s) !");
    }

    /**
     * Rappel de paiement aux parents
     */
    public function sendPaymentReminders()
    {
        // Récupérer les étudiants avec paiements en retard
        $students = StudentRecord::where('session', Qs::getCurrentSession())
            ->with(['user', 'my_parent'])
            ->get();

        $count = 0;
        
        foreach ($students as $student) {
            if ($student->my_parent) {
                UserNotification::create([
                    'user_id' => $student->my_parent_id,
                    'type' => 'payment_reminder',
                    'title' => '💰 Rappel de paiement',
                    'message' => "Merci de vérifier le statut des paiements pour {$student->user->name}.",
                ]);
                $count++;
            }
        }

        return back()->with('flash_success', "{$count} rappel(s) de paiement envoyé(s)");
    }
}
