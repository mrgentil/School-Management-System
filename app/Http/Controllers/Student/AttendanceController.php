<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        // Filters
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        $status = $request->status;

        // Build query
        $query = Attendance::where('student_id', $user->id);
        
        if ($month) {
            $query->whereMonth('date', $month);
        }
        
        if ($year) {
            $query->whereYear('date', $year);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        // Stats for current filters
        $statsQuery = Attendance::where('student_id', $user->id);
        if ($month) $statsQuery->whereMonth('date', $month);
        if ($year) $statsQuery->whereYear('date', $year);
        
        $allAttendances = $statsQuery->get();
        
        $stats = [
            'present' => $allAttendances->where('status', 'present')->count(),
            'absent' => $allAttendances->where('status', 'absent')->count(),
            'late' => $allAttendances->where('status', 'late')->count(),
            'excused' => $allAttendances->where('status', 'excused')->count(),
        ];

        $filters = compact('month', 'year', 'status');
        
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return view('pages.student.attendance.index', compact('attendances', 'stats', 'filters', 'months'));
    }

    public function calendar()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // student_id in attendances table refers to user_id, not student_record.id
        $attendances = Attendance::where('student_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->date)->format('Y-m-d');
            });

        return view('pages.student.attendance.calendar', compact('attendances'));
    }
}
