<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index(Request $request)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        $query = StudyMaterial::query();
        
        // Filter by class if student has a class
        if ($studentRecord && $studentRecord->my_class_id) {
            $query->where(function($q) use ($studentRecord) {
                $q->where('my_class_id', $studentRecord->my_class_id)
                  ->orWhere('is_public', true);
            });
        } else {
            $query->where('is_public', true);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by file type
        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        $materials = $query->with(['subject', 'myClass', 'uploader'])
                          ->latest()
                          ->paginate(12);

        // Get subjects for filter
        $subjects = Subject::orderBy('name')->get();

        $data = [
            'materials' => $materials,
            'subjects' => $subjects,
            'search' => $request->search,
            'subject_id' => $request->subject_id,
            'file_type' => $request->file_type
        ];

        return view('pages.student.materials.index', $data);
    }

    public function download(StudyMaterial $material)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        // Check if student can access this material
        if (!$material->is_public && 
            (!$studentRecord || $material->my_class_id !== $studentRecord->my_class_id)) {
            return back()->with('flash_danger', 'Vous n\'avez pas accès à ce document.');
        }

        // Check if file exists
        if (!Storage::exists($material->file_path)) {
            return back()->with('flash_danger', 'Le fichier n\'existe plus.');
        }

        // Increment download count
        $material->incrementDownloadCount();

        return Storage::download($material->file_path, $material->file_name);
    }

    public function show(StudyMaterial $material)
    {
        $student = Auth::user();
        $studentRecord = $student->student_record;
        
        // Check if student can access this material
        if (!$material->is_public && 
            (!$studentRecord || $material->my_class_id !== $studentRecord->my_class_id)) {
            return back()->with('flash_danger', 'Vous n\'avez pas accès à ce document.');
        }

        return view('pages.student.materials.show', compact('material'));
    }
}
