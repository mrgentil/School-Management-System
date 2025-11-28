<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des notifications
     */
    public function index()
    {
        $d['notifications'] = UserNotification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $d['unreadCount'] = UserNotification::where('user_id', Auth::id())->unread()->count();

        return view('pages.student.notifications.index', $d);
    }

    /**
     * Récupérer les notifications non lues (pour AJAX)
     */
    public function getUnread()
    {
        $notifications = UserNotification::where('user_id', Auth::id())
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $count = UserNotification::where('user_id', Auth::id())->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count,
        ]);
    }

    /**
     * Afficher une notification
     */
    public function show($notification)
    {
        $notif = UserNotification::where('user_id', Auth::id())
            ->where('id', $notification)
            ->first();

        if (!$notif) {
            return redirect()->route('student.notifications.index')
                ->with('flash_danger', 'Notification non trouvée.');
        }

        // Marquer comme lue automatiquement
        $notif->markAsRead();

        // Si la notification a une URL, rediriger vers celle-ci
        if ($notif->data && isset($notif->data['url'])) {
            return redirect($notif->data['url']);
        }

        // Sinon afficher la liste des notifications
        return redirect()->route('student.notifications.index');
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($notification)
    {
        $notif = UserNotification::where('user_id', Auth::id())
            ->where('id', $notification)
            ->first();

        if (!$notif) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Notification non trouvée'], 404);
            }
            return redirect()->route('student.notifications.index')
                ->with('flash_danger', 'Notification non trouvée ou déjà supprimée.');
        }

        $notif->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        // Rediriger vers l'URL si présente dans les données
        if ($notif->data && isset($notif->data['url'])) {
            return redirect($notif->data['url']);
        }

        return redirect()->route('student.notifications.index');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('flash_success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprimer une notification
     */
    public function destroy($notification)
    {
        \Log::info('Destroy notification', ['id' => $notification, 'user_id' => Auth::id()]);
        
        $notif = UserNotification::where('user_id', Auth::id())
            ->where('id', $notification)
            ->first();

        if (!$notif) {
            // Debug: vérifier si la notification existe pour un autre utilisateur
            $existsForOther = UserNotification::where('id', $notification)->first();
            \Log::warning('Notification not found', [
                'requested_id' => $notification,
                'auth_user_id' => Auth::id(),
                'exists_for_other' => $existsForOther ? $existsForOther->user_id : 'not exists'
            ]);
            
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Notification non trouvée'], 404);
            }
            return redirect()->route('student.notifications.index')
                ->with('flash_danger', "Notification #$notification non trouvée pour l'utilisateur #" . Auth::id());
        }

        $notif->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('student.notifications.index')->with('flash_success', 'Notification supprimée.');
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function clearRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->read()
            ->delete();

        return redirect()->back()->with('flash_success', 'Notifications lues supprimées.');
    }
}
