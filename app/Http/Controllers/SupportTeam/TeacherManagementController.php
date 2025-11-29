<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MyClass;
use App\Models\Subject;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Liste des professeurs avec leurs attributions
     */
    public function index()
    {
        $teachers = User::where('user_type', 'teacher')
            ->with(['subjects.my_class'])
            ->orderBy('name')
            ->get();

        // Statistiques
        $stats = [
            'total_teachers' => $teachers->count(),
            'teachers_with_classes' => $teachers->filter(fn($t) => $t->subjects->count() > 0)->count(),
            'teachers_without_classes' => $teachers->filter(fn($t) => $t->subjects->count() == 0)->count(),
        ];

        return view('pages.support_team.teachers.management', compact('teachers', 'stats'));
    }

    /**
     * Voir les détails d'un professeur
     */
    public function show($teacher_id)
    {
        $teacher = User::where('user_type', 'teacher')
            ->with(['subjects.my_class'])
            ->findOrFail($teacher_id);

        $classes = MyClass::orderBy('name')->get();
        $allSubjects = Subject::with('my_class')->get()->groupBy('my_class_id');

        // Classes où le prof enseigne
        $teachingClasses = $teacher->subjects->pluck('my_class')->unique('id');

        // Classe dont il est titulaire
        $titularClass = MyClass::where('teacher_id', $teacher->id)->first();

        return view('pages.support_team.teachers.show', compact('teacher', 'classes', 'allSubjects', 'teachingClasses', 'titularClass'));
    }

    /**
     * Formulaire d'attribution de classes/matières
     */
    public function edit($teacher_id)
    {
        $teacher = User::where('user_type', 'teacher')->findOrFail($teacher_id);
        $classes = MyClass::orderBy('name')->get();
        
        // Matières groupées par classe
        $subjectsByClass = Subject::with('my_class', 'teacher')
            ->get()
            ->groupBy('my_class_id');

        // IDs des matières déjà attribuées à ce prof
        $assignedSubjectIds = Subject::where('teacher_id', $teacher->id)->pluck('id')->toArray();

        return view('pages.support_team.teachers.edit', compact('teacher', 'classes', 'subjectsByClass', 'assignedSubjectIds'));
    }

    /**
     * Mettre à jour les attributions
     */
    public function update(Request $request, $teacher_id)
    {
        $teacher = User::where('user_type', 'teacher')->findOrFail($teacher_id);

        $request->validate([
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
            'titular_class_id' => 'nullable|exists:my_classes,id',
        ]);

        // Retirer ce prof de toutes ses matières actuelles
        Subject::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);

        // Attribuer les nouvelles matières
        if ($request->subject_ids) {
            Subject::whereIn('id', $request->subject_ids)->update(['teacher_id' => $teacher->id]);
        }

        // Gérer la classe titulaire
        // Retirer comme titulaire de l'ancienne classe
        MyClass::where('teacher_id', $teacher->id)->update(['teacher_id' => null]);
        
        // Attribuer la nouvelle classe titulaire
        if ($request->titular_class_id) {
            MyClass::where('id', $request->titular_class_id)->update(['teacher_id' => $teacher->id]);
        }

        return redirect()->route('teachers.management.show', $teacher->id)
            ->with('flash_success', '✅ Attributions mises à jour avec succès !');
    }

    /**
     * Attribution rapide d'une matière
     */
    public function assignSubject(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        Subject::where('id', $request->subject_id)->update(['teacher_id' => $request->teacher_id]);

        return back()->with('flash_success', '✅ Matière attribuée !');
    }

    /**
     * Retirer une matière d'un prof
     */
    public function removeSubject(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        Subject::where('id', $request->subject_id)->update(['teacher_id' => null]);

        return back()->with('flash_success', '✅ Matière retirée !');
    }

    /**
     * API: Obtenir les matières d'une classe
     */
    public function getSubjectsByClass($classId)
    {
        $subjects = Subject::where('my_class_id', $classId)
            ->with('teacher')
            ->get();

        return response()->json($subjects);
    }
}
