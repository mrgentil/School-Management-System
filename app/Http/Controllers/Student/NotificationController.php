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
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = UserNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        // Rediriger vers l'URL si présente dans les données
        if ($notification->data && isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->back();
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
    public function destroy($id)
    {
        $notification = UserNotification::where('user_id', Auth::id())
            ->findOrFail($id);

        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('flash_success', 'Notification supprimée.');
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
