<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $currentYear = Carbon::now()->year;
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereYear('attendance_date', $currentYear)
            ->orderBy('attendance_date', 'desc')
            ->paginate(15);

        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
        ];

        return view('pages.student.attendance.index', compact('attendances', 'stats'));
    }

    public function calendar()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('attendance_date', $currentMonth)
            ->whereYear('attendance_date', $currentYear)
            ->orderBy('attendance_date')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->attendance_date)->format('Y-m-d');
            });

        return view('pages.student.attendance.calendar', compact('attendances'));
    }
}
