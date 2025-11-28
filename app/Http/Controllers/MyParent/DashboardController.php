<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\PaymentRecord;
use App\Models\Attendance;
use App\Models\SchoolEvent;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('my_parent');
    }

    /**
     * Dashboard parent avec vue d'ensemble
     */
    public function index()
    {
        $parent = Auth::user();
        $year = Qs::getCurrentSession();

        // Récupérer les enfants
        $children = StudentRecord::where('my_parent_id', $parent->id)
            ->where('session', $year)
            ->with(['user', 'my_class', 'section'])
            ->get();

        // Données pour chaque enfant
        $childrenData = [];
        foreach ($children as $child) {
            $childrenData[] = [
                'info' => $child,
                'grades' => $this->getRecentGrades($child->user_id, $year),
                'attendance' => $this->getAttendanceStats($child->user_id),
                'finance' => $this->getFinanceStatus($child->user_id),
            ];
        }

        // Événements à venir
        $upcomingEvents = SchoolEvent::where('event_date', '>=', now())
            ->where(function($q) {
                $q->where('target_audience', 'all')
                  ->orWhere('target_audience', 'parents');
            })
            ->orderBy('event_date')
            ->limit(5)
            ->get();

        // Notifications récentes
        $notifications = UserNotification::where('user_id', $parent->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques globales
        $stats = [
            'total_children' => $children->count(),
            'total_balance' => $this->getTotalBalance($children),
            'unread_notifications' => UserNotification::where('user_id', $parent->id)->where('is_read', false)->count(),
        ];

        return view('pages.parent.dashboard', compact(
            'childrenData', 'upcomingEvents', 'notifications', 'stats', 'year'
        ));
    }

    /**
     * Notes récentes d'un enfant
     */
    protected function getRecentGrades($studentId, $year)
    {
        $marks = Mark::where('student_id', $studentId)
            ->where('year', $year)
            ->with('subject')
            ->get();

        $grades = [];
        foreach ($marks as $mark) {
            if (!$mark->subject) continue;
            
            // Dernière note disponible
            $lastGrade = $mark->p4_avg ?? $mark->t4 ?? $mark->p3_avg ?? $mark->t3 ?? 
                         $mark->p2_avg ?? $mark->t2 ?? $mark->p1_avg ?? $mark->t1;
            
            if ($lastGrade !== null) {
                $grades[] = [
                    'subject' => $mark->subject->name,
                    'grade' => $lastGrade,
                    'status' => $lastGrade >= 10 ? 'success' : 'danger',
                ];
            }
        }

        // Trier par note et prendre les 5 dernières
        usort($grades, fn($a, $b) => $b['grade'] <=> $a['grade']);
        
        return array_slice($grades, 0, 5);
    }

    /**
     * Statistiques de présence
     */
    protected function getAttendanceStats($studentId)
    {
        $thisMonth = now()->month;
        $thisYear = now()->year;

        $total = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();

        $present = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->where('status', 'present')
            ->count();

        $absent = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->where('status', 'absent')
            ->count();

        $late = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->where('status', 'late')
            ->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 100,
        ];
    }

    /**
     * Situation financière d'un enfant
     */
    protected function getFinanceStatus($studentId)
    {
        $records = PaymentRecord::where('student_id', $studentId)->get();

        return [
            'total_paid' => $records->sum('amt_paid'),
            'total_balance' => $records->sum('balance'),
            'is_up_to_date' => $records->sum('balance') == 0,
        ];
    }

    /**
     * Total des soldes pour tous les enfants
     */
    protected function getTotalBalance($children)
    {
        $total = 0;
        foreach ($children as $child) {
            $total += PaymentRecord::where('student_id', $child->user_id)->sum('balance');
        }
        return $total;
    }
}
