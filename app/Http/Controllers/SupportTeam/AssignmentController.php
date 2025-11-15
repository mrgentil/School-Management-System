<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\Assignment\AssignmentSubmission;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AssignmentController extends Controller
{
    /**
     * Display list of assignments
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build query
        $query = Assignment::with(['myClass', 'section', 'subject', 'teacher']);
        
        // If teacher, show only their assignments
        if (Qs::userIsTeacher()) {
            $query->where('teacher_id', $user->id);
        }
        
        // Filters
        if ($request->my_class_id) {
            $query->where('my_class_id', $request->my_class_id);
        }
        
        if ($request->section_id) {
            $query->where('section_id', $request->section_id);
        }
        
        if ($request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }
        
        if ($request->period) {
            $query->where('period', $request->period);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->paginate(20);

        $data['assignments'] = $assignments;
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        $data['filters'] = [
            'my_class_id' => $request->my_class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'period' => $request->period,
            'status' => $request->status
        ];

        return view('pages.support_team.assignments.index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        
        return view('pages.support_team.assignments.create', $data);
    }

    /**
     * Store new assignment
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'period' => 'required|integer|in:1,2,3,4',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|integer|min:1|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240'
        ]);

        $data = $request->except('file');
        $data['teacher_id'] = Auth::id();
        $data['status'] = 'active';

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('assignments', 'public');
            $data['file_path'] = $path;
        }

        Assignment::create($data);

        return redirect()->route('assignments.index')
            ->with('flash_success', 'Devoir créé avec succès!');
    }

    /**
     * Show assignment details with submissions
     */
    public function show(Assignment $assignment)
    {
        \Log::info('AssignmentController::show called', ['id' => $assignment->id, 'user' => Auth::id()]);
        
        $assignment->load(['myClass', 'section', 'subject', 'teacher']);

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        // Get submissions
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->with('student')
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Get students who haven't submitted
        $students = StudentRecord::where('my_class_id', $assignment->my_class_id)
            ->where('section_id', $assignment->section_id)
            ->where('session', Qs::getCurrentSession())
            ->with('user')
            ->get();

        $submittedIds = $submissions->pluck('student_id')->toArray();
        $notSubmitted = $students->filter(function($student) use ($submittedIds) {
            return !in_array($student->user_id, $submittedIds);
        });

        $data['assignment'] = $assignment;
        $data['submissions'] = $submissions;
        $data['notSubmitted'] = $notSubmitted;
        $data['stats'] = [
            'total' => $students->count(),
            'submitted' => $submissions->count(),
            'graded' => $submissions->where('status', 'graded')->count(),
            'pending' => $submissions->where('status', 'submitted')->count(),
            'late' => $submissions->where('status', 'late')->count(),
        ];

        return view('pages.support_team.assignments.show', $data);
    }

    /**
     * Show edit form
     */
    public function edit(Assignment $assignment)
    {

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        $data['assignment'] = $assignment;
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['subjects'] = Subject::orderBy('name')->get();
        $data['sections'] = Section::where('my_class_id', $assignment->my_class_id)->get();

        return view('pages.support_team.assignments.edit', $data);
    }

    /**
     * Update assignment
     */
    public function update(Request $request, Assignment $assignment)
    {

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'period' => 'required|integer|in:1,2,3,4',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:1000',
            'status' => 'required|in:active,closed,draft',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240'
        ]);

        $data = $request->except('file');

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            
            $file = $request->file('file');
            $path = $file->store('assignments', 'public');
            $data['file_path'] = $path;
        }

        $assignment->update($data);

        return redirect()->route('assignments.show', $id)
            ->with('flash_success', 'Devoir mis à jour avec succès!');
    }

    /**
     * Grade a submission
     */
    public function grade(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);
        $assignment = $submission->assignment;

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        $request->validate([
            'score' => 'required|integer|min:0|max:' . $assignment->max_score,
            'teacher_feedback' => 'nullable|string|max:1000'
        ]);

        $submission->update([
            'score' => $request->score,
            'teacher_feedback' => $request->teacher_feedback,
            'status' => 'graded'
        ]);

        return back()->with('flash_success', 'Note enregistrée avec succès!');
    }

    /**
     * Delete assignment
     */
    public function destroy(Assignment $assignment)
    {

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        // Delete file if exists
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        // Delete submissions files
        foreach ($assignment->submissions as $submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }

        $assignment->delete();

        return redirect()->route('assignments.index')
            ->with('flash_success', 'Devoir supprimé avec succès!');
    }

    /**
     * Export submissions to Excel
     */
    public function export(Assignment $assignment)
    {
        $assignment->load(['myClass', 'section', 'subject']);

        // Check permission
        if (Qs::userIsTeacher() && $assignment->teacher_id != Auth::id()) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->with('student')
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', 'Résultats du Devoir: ' . $assignment->title);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Info
        $sheet->setCellValue('A2', 'Classe: ' . $assignment->myClass->name);
        $sheet->setCellValue('C2', 'Section: ' . $assignment->section->name);
        $sheet->setCellValue('E2', 'Matière: ' . $assignment->subject->name);

        // Headers
        $sheet->setCellValue('A4', 'N°');
        $sheet->setCellValue('B4', 'Nom Étudiant');
        $sheet->setCellValue('C4', 'Date Soumission');
        $sheet->setCellValue('D4', 'Statut');
        $sheet->setCellValue('E4', 'Note');
        $sheet->setCellValue('F4', 'Note Max');
        $sheet->setCellValue('G4', 'Feedback');

        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4CAF50');

        // Data
        $row = 5;
        foreach ($submissions as $index => $submission) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $submission->student->name ?? 'N/A');
            $sheet->setCellValue('C' . $row, $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A');
            $sheet->setCellValue('D' . $row, ucfirst($submission->status));
            $sheet->setCellValue('E' . $row, $submission->score ?? '-');
            $sheet->setCellValue('F' . $row, $assignment->max_score);
            $sheet->setCellValue('G' . $row, $submission->teacher_feedback ?? '-');
            $row++;
        }

        // Auto-size
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'devoir_' . $assignment->id . '_' . date('Y-m-d') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
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
}
