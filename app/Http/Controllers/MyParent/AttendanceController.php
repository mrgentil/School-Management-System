<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\StudentRecord;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('my_parent');
    }

    /**
     * Vue d'ensemble des présences des enfants
     */
    public function index()
    {
        $parent = Auth::user();
        $year = Qs::getCurrentSession();

        $children = StudentRecord::where('my_parent_id', $parent->id)
            ->where('session', $year)
            ->with(['user', 'my_class', 'section'])
            ->get();

        $childrenData = [];
        foreach ($children as $child) {
            $childrenData[] = [
                'info' => $child,
                'stats' => $this->getAttendanceStats($child->user_id),
                'monthly' => $this->getMonthlyStats($child->user_id),
            ];
        }

        return view('pages.parent.attendance.index', compact('childrenData', 'year'));
    }

    /**
     * Détail des présences d'un enfant
     */
    public function show($studentId, Request $request)
    {
        $parent = Auth::user();
        $year = Qs::getCurrentSession();

        // Vérifier que c'est bien l'enfant du parent
        $child = StudentRecord::where('user_id', $studentId)
            ->where('my_parent_id', $parent->id)
            ->with(['user', 'my_class', 'section'])
            ->first();

        if (!$child) {
            return redirect()->route('parent.attendance.index')
                ->with('flash_danger', 'Accès non autorisé');
        }

        $month = $request->get('month', now()->month);
        $yearNum = $request->get('year', now()->year);

        // Présences du mois
        $attendances = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $yearNum)
            ->with('subject')
            ->orderBy('date', 'desc')
            ->get();

        // Stats du mois
        $stats = $this->getMonthStats($studentId, $month, $yearNum);

        // Données pour le calendrier
        $calendarData = $this->getCalendarData($studentId, $month, $yearNum);

        return view('pages.parent.attendance.show', compact(
            'child', 'attendances', 'stats', 'calendarData', 'month', 'yearNum'
        ));
    }

    /**
     * Statistiques globales
     */
    protected function getAttendanceStats($studentId)
    {
        $total = Attendance::where('student_id', $studentId)->count();
        $present = Attendance::where('student_id', $studentId)->where('status', 'present')->count();
        $absent = Attendance::where('student_id', $studentId)->where('status', 'absent')->count();
        $late = Attendance::where('student_id', $studentId)->where('status', 'late')->count();
        $excused = Attendance::where('student_id', $studentId)->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'rate' => $total > 0 ? round((($present + $late) / $total) * 100, 1) : 100,
        ];
    }

    /**
     * Stats mensuelles (6 derniers mois)
     */
    protected function getMonthlyStats($studentId)
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;

            $total = Attendance::where('student_id', $studentId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count();

            $present = Attendance::where('student_id', $studentId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->whereIn('status', ['present', 'late'])
                ->count();

            $data[] = [
                'month' => $date->format('M'),
                'rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ];
        }
        return $data;
    }

    /**
     * Stats d'un mois spécifique
     */
    protected function getMonthStats($studentId, $month, $year)
    {
        $total = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();

        $present = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'present')
            ->count();

        $absent = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'absent')
            ->count();

        $late = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'late')
            ->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'rate' => $total > 0 ? round((($present + $late) / $total) * 100, 1) : 100,
        ];
    }

    /**
     * Données pour le calendrier
     */
    protected function getCalendarData($studentId, $month, $year)
    {
        $attendances = Attendance::where('student_id', $studentId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $data = [];
        foreach ($attendances as $att) {
            $day = Carbon::parse($att->date)->day;
            $data[$day] = $att->status;
        }
        return $data;
    }
}
