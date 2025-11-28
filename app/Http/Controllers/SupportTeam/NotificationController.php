<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Services\EmailNotificationService;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $emailService;

    public function __construct(EmailNotificationService $emailService)
    {
        $this->middleware('teamSA');
        $this->emailService = $emailService;
    }

    /**
     * Page de gestion des notifications
     */
    public function index()
    {
        // Statistiques
        $stats = [
            'total_sent' => UserNotification::count(),
            'today_sent' => UserNotification::whereDate('created_at', today())->count(),
            'unread' => UserNotification::where('is_read', false)->count(),
            'parents_count' => User::where('user_type', 'parent')->count(),
            'teachers_count' => User::where('user_type', 'teacher')->count(),
            'students_count' => User::where('user_type', 'student')->count(),
        ];

        // Dernières notifications envoyées
        $recentNotifications = UserNotification::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('pages.support_team.notifications.index', compact('stats', 'recentNotifications'));
    }

    /**
     * Envoyer une notification personnalisée
     */
    public function send(Request $request)
    {
        $request->validate([
            'target' => 'required|in:all,parents,teachers,students,user',
            'user_id' => 'required_if:target,user|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'send_email' => 'boolean',
        ]);

        $count = 0;
        $subject = $request->subject;
        $message = $request->message;
        $sendEmail = $request->boolean('send_email', true);

        switch ($request->target) {
            case 'all':
                $users = User::all();
                break;
            case 'parents':
                $users = User::where('user_type', 'parent')->get();
                break;
            case 'teachers':
                $users = User::where('user_type', 'teacher')->get();
                break;
            case 'students':
                $users = User::where('user_type', 'student')->get();
                break;
            case 'user':
                $users = User::where('id', $request->user_id)->get();
                break;
            default:
                $users = collect();
        }

        foreach ($users as $user) {
            // Notification in-app
            UserNotification::create([
                'user_id' => $user->id,
                'title' => $subject,
                'message' => $message,
                'type' => 'info',
            ]);

            // Email si demandé
            if ($sendEmail && $user->email) {
                $this->emailService->send($user, $subject, $message, ['type' => 'info']);
            }

            $count++;
        }

        return back()->with('flash_success', "Notification envoyée à {$count} utilisateur(s)");
    }

    /**
     * Statistiques des notifications
     */
    public function stats()
    {
        $monthlyStats = UserNotification::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $byType = UserNotification::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        return response()->json([
            'monthly' => $monthlyStats,
            'by_type' => $byType,
        ]);
    }
}
