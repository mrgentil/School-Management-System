<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\Assignment\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $assignments = Assignment::where(function($query) use ($student) {
                $query->where('my_class_id', $student->class_id)
                      ->orWhereNull('my_class_id');
            })
            ->where(function($query) use ($student) {
                $query->where('section_id', $student->section_id)
                      ->orWhereNull('section_id');
            })
            ->where('due_date', '>=', now())
            ->where('status', 'active')
            ->with(['subject', 'submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('due_date')
            ->paginate(10);

        return view('pages.student.assignments.index', compact('assignments'));
    }

    public function show($id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $assignment = Assignment::with(['subject', 'attachments'])
            ->where('id', $id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->firstOrFail();

        $submission = AssignmentSubmission::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->first();

        return view('pages.student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, $id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $request->validate([
            'content' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $student = auth()->user()->student;
        
        $submission = AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $id,
                'student_id' => $student->id,
            ],
            [
                'content' => $request->content,
                'submitted_at' => now(),
            ]
        );

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('assignments/submissions', 'public');
                $submission->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Devoir soumis avec succès.');
    }
}
