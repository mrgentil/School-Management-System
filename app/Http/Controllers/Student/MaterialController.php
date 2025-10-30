<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $search = $request->input('search');
        $subjectId = $request->input('subject_id');
        $fileType = $request->input('file_type');

        $materials = LearningMaterial::where('is_published', true)
            ->where(function($query) use ($student) {
                $query->where('class_id', $student->class_id)
                      ->orWhereNull('class_id');
            })
            ->where(function($query) use ($student) {
                $query->where('section_id', $student->section_id)
                      ->orWhereNull('section_id');
            })
            ->when($search, function($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($subjectId, function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            })
            ->when($fileType, function($query) use ($fileType) {
                $query->where('file_type', $fileType);
            })
            ->with(['subject', 'attachments', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->except('page'));

        $subjects = \App\Models\Subject::all();

        return view('pages.student.materials.index', [
            'materials' => $materials,
            'search' => $search,
            'subject_id' => $subjectId,
            'file_type' => $fileType,
            'subjects' => $subjects
        ]);
    }

    public function show($id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $material = LearningMaterial::with(['subject', 'attachments', 'user'])
            ->where('id', $id)
            ->where('is_published', true)
            ->where(function($query) use ($student) {
                $query->where('class_id', $student->class_id)
                      ->orWhereNull('class_id');
            })
            ->where(function($query) use ($student) {
                $query->where('section_id', $student->section_id)
                      ->orWhereNull('section_id');
            })
            ->firstOrFail();

        return view('pages.student.materials.show', compact('material'));
    }
}
