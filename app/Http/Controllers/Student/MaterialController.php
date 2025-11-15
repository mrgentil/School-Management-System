<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student_record;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('flash_danger', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $search = $request->input('search');
        $subjectId = $request->input('subject_id');
        $fileType = $request->input('file_type');

        $materials = StudyMaterial::query()
            ->where(function($query) use ($student) {
                // Matériaux pour la classe de l'étudiant ou publics (sans classe spécifique)
                $query->where('my_class_id', $student->my_class_id)
                      ->orWhere('is_public', true);
            })
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($subjectId, function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            })
            ->when($fileType, function($query) use ($fileType) {
                $query->where('file_type', $fileType);
            })
            ->with(['subject', 'myClass', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
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

    public function show(StudyMaterial $studyMaterial)
    {
        $student = auth()->user()->student_record;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('flash_danger', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        // Vérifier que l'étudiant a accès à ce matériel
        if (!$studyMaterial->is_public && $studyMaterial->my_class_id != $student->my_class_id) {
            return redirect()->route('student.materials.index')->with('flash_danger', 'Vous n\'avez pas accès à ce matériel.');
        }

        $studyMaterial->load(['subject', 'myClass', 'uploader']);

        return view('pages.student.materials.show', ['material' => $studyMaterial]);
    }

    /**
     * Télécharger un matériel pédagogique
     */
    public function download(StudyMaterial $studyMaterial)
    {
        $student = auth()->user()->student_record;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('flash_danger', 'Aucun profil étudiant trouvé.');
        }

        // Vérifier l'accès
        if (!$studyMaterial->is_public && $studyMaterial->my_class_id != $student->my_class_id) {
            return back()->with('flash_danger', 'Vous n\'avez pas accès à ce fichier.');
        }

        if (!\Storage::disk('public')->exists($studyMaterial->file_path)) {
            return back()->with('flash_danger', 'Le fichier n\'existe plus sur le serveur.');
        }

        // Incrémenter le compteur de téléchargements
        $studyMaterial->incrementDownloadCount();

        return \Storage::disk('public')->download($studyMaterial->file_path, $studyMaterial->file_name);
    }
}
