<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index(Request $request)
    {
        $student = Auth::user();
        
        // Default to current month and year
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // Get attendance records for the selected month
        $attendances = Attendance::forStudent($student->id)
                                ->forMonth($year, $month)
                                ->orderBy('attendance_date')
                                ->get();

        // Calculate statistics
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $excusedDays = $attendances->where('status', 'excused')->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;

        // Get monthly statistics for the year
        $monthlyStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthAttendances = Attendance::forStudent($student->id)
                                        ->forMonth($year, $m)
                                        ->get();
            
            $monthTotal = $monthAttendances->count();
            $monthPresent = $monthAttendances->where('status', 'present')->count();
            
            $monthlyStats[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->format('M'),
                'total' => $monthTotal,
                'present' => $monthPresent,
                'percentage' => $monthTotal > 0 ? round(($monthPresent / $monthTotal) * 100, 2) : 0
            ];
        }

        $data = [
            'attendances' => $attendances,
            'year' => $year,
            'month' => $month,
            'month_name' => Carbon::create()->month($month)->format('F'),
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'late_days' => $lateDays,
            'excused_days' => $excusedDays,
            'attendance_percentage' => $attendancePercentage,
            'monthly_stats' => $monthlyStats
        ];

        return view('pages.student.attendance.index', $data);
    }

    public function calendar(Request $request)
    {
        $student = Auth::user();
        $year = $request->get('year', date('Y'));
        
        $attendances = Attendance::forStudent($student->id)
                                ->whereYear('attendance_date', $year)
                                ->get()
                                ->keyBy('attendance_date');

        // Format for calendar
        $calendarData = [];
        foreach ($attendances as $date => $attendance) {
            $calendarData[] = [
                'title' => ucfirst($attendance->status),
                'start' => $attendance->attendance_date->format('Y-m-d'),
                'className' => 'attendance-' . $attendance->status,
                'backgroundColor' => $this->getStatusColor($attendance->status)
            ];
        }

        return response()->json($calendarData);
    }

    private function getStatusColor($status)
    {
        $colors = [
            'present' => '#28a745',
            'absent' => '#dc3545',
            'late' => '#ffc107',
            'excused' => '#17a2b8'
        ];

        return $colors[$status] ?? '#6c757d';
    }
}
