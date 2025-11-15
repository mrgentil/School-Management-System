<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AttendanceController extends Controller
{
    /**
     * Display attendance management page
     */
    public function index()
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        
        return view('pages.support_team.attendance.index', $data);
    }

    /**
     * Get students for attendance
     */
    public function getStudents(Request $request)
    {
        try {
            $request->validate([
                'my_class_id' => 'required|exists:my_classes,id',
                'section_id' => 'nullable|exists:sections,id',
                'date' => 'required|date'
            ]);

            $class_id = $request->my_class_id;
            $section_id = $request->section_id;
            $date = $request->date;

            // Get students
            $currentSession = Qs::getCurrentSession();
            
            // Debug: check all students in this class
            $allStudents = StudentRecord::where('my_class_id', $class_id)->get();
            
            // Try with current session first
            $query = StudentRecord::where('my_class_id', $class_id)
                ->where('session', $currentSession)
                ->with('user');

            if ($section_id) {
                $query->where('section_id', $section_id);
            }

            $students = $query->get();
            
            // If no students found with current session, get all students from this class
            if ($students->isEmpty()) {
                $query = StudentRecord::where('my_class_id', $class_id)
                    ->with('user');

                if ($section_id) {
                    $query->where('section_id', $section_id);
                }

                $students = $query->get();
            }

            // Get existing attendance for this date
            $attendances = Attendance::where('class_id', $class_id)
                ->where('date', $date)
                ->when($section_id, function($q) use ($section_id) {
                    return $q->where('section_id', $section_id);
                })
                ->get()
                ->keyBy('student_id');

            $data = [];
            foreach ($students as $student) {
                // Skip if user doesn't exist
                if (!$student->user) {
                    continue;
                }
                
                $attendance = $attendances->get($student->user_id);
                
                $data[] = [
                    'id' => $student->user_id,
                    'name' => $student->user->name,
                    'adm_no' => $student->adm_no,
                    'status' => $attendance ? $attendance->status : null,
                    'notes' => $attendance ? $attendance->notes : null,
                    'attendance_id' => $attendance ? $attendance->id : null
                ];
            }

            return response()->json([
                'success' => true,
                'students' => $data,
                'debug' => [
                    'current_session' => $currentSession,
                    'total_in_class' => $allStudents->count(),
                    'filtered_count' => $students->count(),
                    'sessions_found' => $allStudents->pluck('session')->unique()->values(),
                    'class_id' => $class_id,
                    'section_id' => $section_id
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store or update attendance
     */
    public function store(Request $request)
    {
        $request->validate([
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'attendance' => 'required|array|min:1',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.status' => 'nullable|in:present,absent,late,excused,late_justified,absent_justified',
            'attendance.*.notes' => 'nullable|string|max:500'
        ]);

        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $subject_id = $request->subject_id;
        $date = $request->date;
        $recorded_by = Auth::id();

        $created = 0;
        $updated = 0;

        foreach ($request->attendance as $att) {
            // Skip if no status selected
            if (empty($att['status'])) {
                continue;
            }
            
            $data = [
                'student_id' => $att['student_id'],
                'class_id' => $class_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id,
                'date' => $date,
                'status' => $att['status'],
                'notes' => $att['notes'] ?? null,
                'recorded_by' => $recorded_by
            ];

            $existing = Attendance::where('student_id', $att['student_id'])
                ->where('date', $date)
                ->where('class_id', $class_id)
                ->when($subject_id, function($q) use ($subject_id) {
                    return $q->where('subject_id', $subject_id);
                })
                ->first();

            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                Attendance::create($data);
                $created++;
            }
        }

        $message = "Présence enregistrée avec succès! ";
        if ($created > 0) $message .= "{$created} nouvelle(s) entrée(s). ";
        if ($updated > 0) $message .= "{$updated} mise(s) à jour.";

        return back()->with('flash_success', $message);
    }

    /**
     * View attendance records
     */
    public function view(Request $request)
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        
        // Filters
        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $subject_id = $request->subject_id;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $query = Attendance::with(['student', 'class', 'section', 'subject', 'takenBy']);

        if ($class_id) {
            $query->where('class_id', $class_id);
            $data['sections'] = Section::where('my_class_id', $class_id)->get();
        }

        if ($section_id) {
            $query->where('section_id', $section_id);
        }

        if ($subject_id) {
            $query->where('subject_id', $subject_id);
        }

        if ($date_from) {
            $query->where('date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('date', '<=', $date_to);
        }

        $data['attendances'] = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $data['filters'] = compact('class_id', 'section_id', 'subject_id', 'date_from', 'date_to');

        return view('pages.support_team.attendance.view', $data);
    }

    /**
     * Get statistics
     */
    public function statistics(Request $request)
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        
        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        if ($class_id) {
            $data['sections'] = Section::where('my_class_id', $class_id)->get();
            
            // Get students - try current session first, then all if none found
            $currentSession = Qs::getCurrentSession();
            $students = StudentRecord::where('my_class_id', $class_id)
                ->where('session', $currentSession)
                ->when($section_id, function($q) use ($section_id) {
                    return $q->where('section_id', $section_id);
                })
                ->with('user')
                ->get();
            
            // If no students found with current session, get all students from this class
            if ($students->isEmpty()) {
                $students = StudentRecord::where('my_class_id', $class_id)
                    ->when($section_id, function($q) use ($section_id) {
                        return $q->where('section_id', $section_id);
                    })
                    ->with('user')
                    ->get();
            }

            $stats = [];
            foreach ($students as $student) {
                $attendances = Attendance::where('student_id', $student->user_id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->get();

                $total = $attendances->count();
                $present = $attendances->where('status', 'present')->count();
                $absent = $attendances->where('status', 'absent')->count();
                $late = $attendances->where('status', 'late')->count();
                $excused = $attendances->where('status', 'excused')->count();

                $stats[] = [
                    'student' => $student->user,
                    'adm_no' => $student->adm_no,
                    'total' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'excused' => $excused,
                    'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0
                ];
            }

            $data['stats'] = collect($stats)->sortByDesc('percentage');
        }

        $data['filters'] = compact('class_id', 'section_id', 'month', 'year');
        $data['months'] = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return view('pages.support_team.attendance.statistics', $data);
    }

    /**
     * Delete attendance record
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return back()->with('flash_success', 'Présence supprimée avec succès.');
    }

    /**
     * Get sections for a class (AJAX)
     */
    public function getSections($class_id)
    {
        $sections = Section::where('my_class_id', $class_id)->get();
        
        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    /**
     * Export attendances to Excel
     */
    public function export(Request $request)
    {
        $class_id = $request->my_class_id;
        $section_id = $request->section_id;
        $subject_id = $request->subject_id;
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        $query = Attendance::with(['student', 'class', 'section', 'subject', 'takenBy']);

        if ($class_id) {
            $query->where('class_id', $class_id);
        }

        if ($section_id) {
            $query->where('section_id', $section_id);
        }

        if ($subject_id) {
            $query->where('subject_id', $subject_id);
        }

        if ($date_from) {
            $query->where('date', '>=', $date_from);
        }

        if ($date_to) {
            $query->where('date', '<=', $date_to);
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set title
        $sheet->setCellValue('A1', 'Rapport de Présence');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set headers
        $sheet->setCellValue('A3', 'Date');
        $sheet->setCellValue('B3', 'Étudiant');
        $sheet->setCellValue('C3', 'N° Admission');
        $sheet->setCellValue('D3', 'Classe');
        $sheet->setCellValue('E3', 'Section');
        $sheet->setCellValue('F3', 'Matière');
        $sheet->setCellValue('G3', 'Statut');
        $sheet->setCellValue('H3', 'Notes');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50']
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A3:H3')->applyFromArray($headerStyle);

        // Add data
        $row = 4;
        foreach ($attendances as $attendance) {
            $sheet->setCellValue('A' . $row, $attendance->date ? $attendance->date->format('d/m/Y') : 'N/A');
            $sheet->setCellValue('B' . $row, $attendance->student->name ?? 'N/A');
            $sheet->setCellValue('C' . $row, $attendance->student->student_record->adm_no ?? 'N/A');
            $sheet->setCellValue('D' . $row, $attendance->class->name ?? 'N/A');
            $sheet->setCellValue('E' . $row, $attendance->section->name ?? '-');
            $sheet->setCellValue('F' . $row, $attendance->subject->name ?? '-');
            
            $statusText = match($attendance->status) {
                'present' => 'Présent',
                'absent' => 'Absent',
                'late' => 'Retard',
                'excused' => 'Excusé',
                'late_justified' => 'Retard Justifié',
                'absent_justified' => 'Absent Justifié',
                default => ucfirst($attendance->status)
            };
            $sheet->setCellValue('G' . $row, $statusText);
            $sheet->setCellValue('H' . $row, $attendance->notes ?? '-');
            
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'presences_' . date('Y-m-d_His') . '.xlsx';

        // Create writer and download
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
