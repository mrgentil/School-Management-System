<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\Assignment\AssignmentSubmission;
use App\Models\Subject;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index(Request $request)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        if (!$studentRecord) {
            return back()->with('flash_danger', 'Profil étudiant non trouvé.');
        }

        $query = Assignment::where('my_class_id', $studentRecord->my_class_id)
                          ->where('section_id', $studentRecord->section_id)
                          ->where('status', 'active');

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by period
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'submitted') {
                $query->whereHas('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                });
            } elseif ($request->status === 'pending') {
                $query->whereDoesntHave('submissions', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                });
            } elseif ($request->status === 'overdue') {
                $query->where('due_date', '<', now())
                     ->whereDoesntHave('submissions', function($q) use ($student) {
                         $q->where('student_id', $student->id);
                     });
            }
        }

        $assignments = $query->with(['subject', 'teacher', 'submissions' => function($q) use ($student) {
                             $q->where('student_id', $student->id);
                         }])
                         ->latest()
                         ->paginate(10);

        // Get subjects for filter - get unique subjects from assignments
        $subjectIds = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                ->where('section_id', $studentRecord->section_id)
                                ->where('status', 'active')
                                ->distinct()
                                ->pluck('subject_id');
        
        $subjects = Subject::whereIn('id', $subjectIds)
                          ->orderBy('name')
                          ->get();

        // Get statistics
        $totalAssignments = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                    ->where('section_id', $studentRecord->section_id)
                                    ->where('status', 'active')
                                    ->count();

        $submittedCount = AssignmentSubmission::where('student_id', $student->id)->count();
        $pendingCount = $totalAssignments - $submittedCount;
        $overdueCount = Assignment::where('my_class_id', $studentRecord->my_class_id)
                                 ->where('section_id', $studentRecord->section_id)
                                 ->where('due_date', '<', now())
                                 ->whereDoesntHave('submissions', function($q) use ($student) {
                                     $q->where('student_id', $student->id);
                                 })
                                 ->count();

        $data = [
            'assignments' => $assignments,
            'subjects' => $subjects,
            'total_assignments' => $totalAssignments,
            'submitted_count' => $submittedCount,
            'pending_count' => $pendingCount,
            'overdue_count' => $overdueCount,
            'selected_subject' => $request->subject_id,
            'selected_period' => $request->period,
            'selected_status' => $request->status
        ];

        return view('pages.student.assignments.index', $data);
    }

    public function show(Assignment $assignment)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        // Check if student can access this assignment
        if (!$studentRecord || 
            $assignment->my_class_id !== $studentRecord->my_class_id ||
            $assignment->section_id !== $studentRecord->section_id) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        return view('pages.student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        // Check if student can submit to this assignment
        if (!$studentRecord || 
            $assignment->my_class_id !== $studentRecord->my_class_id ||
            $assignment->section_id !== $studentRecord->section_id) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        // Check if assignment is still active
        if ($assignment->status !== 'active') {
            return back()->with('flash_danger', 'Ce devoir n\'est plus actif.');
        }

        // Check if already submitted
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
                                                 ->where('student_id', $student->id)
                                                 ->first();

        if ($existingSubmission) {
            return back()->with('flash_danger', 'Vous avez déjà soumis ce devoir.');
        }

        $request->validate([
            'submission_text' => 'required_without:submission_file|string',
            'submission_file' => 'required_without:submission_text|file|max:10240' // 10MB max
        ]);

        $filePath = null;
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('assignments/submissions', 'public');
        }

        $status = now()->isAfter($assignment->due_date) ? 'late' : 'submitted';

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'submission_text' => $request->submission_text,
            'file_path' => $filePath,
            'submitted_at' => now(),
            'status' => $status
        ]);

        return back()->with('flash_success', 'Votre devoir a été soumis avec succès.');
    }

    public function downloadFile(Assignment $assignment)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        // Check access
        if (!$studentRecord || 
            $assignment->my_class_id !== $studentRecord->my_class_id ||
            $assignment->section_id !== $studentRecord->section_id) {
            return back()->with('flash_danger', 'Accès non autorisé.');
        }

        if (!$assignment->file_path || !Storage::exists($assignment->file_path)) {
            return back()->with('flash_danger', 'Fichier non trouvé.');
        }

        return Storage::download($assignment->file_path);
    }
}
