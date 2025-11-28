<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Setting;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Liste des sessions académiques
     */
    public function index()
    {
        $sessions = AcademicSession::getAllOrdered();
        
        // Mettre à jour les statistiques de chaque session
        foreach ($sessions as $session) {
            $session->updateStatistics();
        }

        return view('pages.support_team.academic_sessions.index', compact('sessions'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $nextSession = AcademicSession::getNextSessionName();
        return view('pages.support_team.academic_sessions.create', compact('nextSession'));
    }

    /**
     * Enregistrer nouvelle session
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:academic_sessions,name|regex:/^\d{4}-\d{4}$/',
            'label' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,upcoming,archived',
        ], [
            'name.regex' => 'Le format doit être AAAA-AAAA (ex: 2025-2026)',
            'name.unique' => 'Cette année scolaire existe déjà',
        ]);

        $session = AcademicSession::create([
            'name' => $request->name,
            'label' => $request->label ?? 'Année Scolaire ' . $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'status' => $request->status,
            'is_current' => false,
        ]);

        return redirect()->route('academic_sessions.index')
            ->with('flash_success', 'Année scolaire ' . $session->name . ' créée avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(AcademicSession $academicSession)
    {
        return view('pages.support_team.academic_sessions.edit', compact('academicSession'));
    }

    /**
     * Mettre à jour une session
     */
    public function update(Request $request, AcademicSession $academicSession)
    {
        $request->validate([
            'label' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date',
            'exam_start' => 'nullable|date',
            'exam_end' => 'nullable|date',
        ]);

        $academicSession->update($request->only([
            'label', 'start_date', 'end_date', 'description',
            'registration_start', 'registration_end', 'exam_start', 'exam_end'
        ]));

        return redirect()->route('academic_sessions.index')
            ->with('flash_success', 'Année scolaire mise à jour !');
    }

    /**
     * Définir comme session courante
     */
    public function setCurrent(AcademicSession $academicSession)
    {
        $academicSession->setAsCurrent();

        return redirect()->route('academic_sessions.index')
            ->with('flash_success', 'L\'année ' . $academicSession->name . ' est maintenant l\'année courante !');
    }

    /**
     * Archiver une session
     */
    public function archive(AcademicSession $academicSession)
    {
        if ($academicSession->is_current) {
            return back()->with('flash_danger', 'Impossible d\'archiver l\'année courante. Changez d\'abord l\'année active.');
        }

        $academicSession->archive();

        return redirect()->route('academic_sessions.index')
            ->with('flash_success', 'Année scolaire ' . $academicSession->name . ' archivée.');
    }

    /**
     * Voir les détails d'une session
     */
    public function show(AcademicSession $academicSession)
    {
        $academicSession->updateStatistics();

        // Statistiques détaillées
        $stats = [
            'students_by_class' => StudentRecord::where('session', $academicSession->name)
                ->select('my_class_id', DB::raw('count(*) as total'))
                ->groupBy('my_class_id')
                ->with('my_class')
                ->get(),
            'students_by_gender' => StudentRecord::where('session', $academicSession->name)
                ->join('users', 'student_records.user_id', '=', 'users.id')
                ->select('users.gender', DB::raw('count(*) as total'))
                ->groupBy('users.gender')
                ->pluck('total', 'gender'),
            'performance_by_period' => $this->getPerformanceByPeriod($academicSession->name),
        ];

        return view('pages.support_team.academic_sessions.show', compact('academicSession', 'stats'));
    }

    /**
     * Performances par période
     */
    protected function getPerformanceByPeriod($session)
    {
        $data = [];
        for ($p = 1; $p <= 4; $p++) {
            $col = "p{$p}_avg";
            $avg = Mark::where('year', $session)->whereNotNull($col)->avg($col);
            $data["P{$p}"] = round($avg ?? 0, 1);
        }
        return $data;
    }

    /**
     * Formulaire de copie de structure
     */
    public function copyStructureForm(AcademicSession $academicSession)
    {
        $nextSession = AcademicSession::getNextSessionName();
        $classes = MyClass::with(['sections', 'subjects'])->get();

        return view('pages.support_team.academic_sessions.copy_structure', compact('academicSession', 'nextSession', 'classes'));
    }

    /**
     * Copier la structure vers une nouvelle année
     */
    public function copyStructure(Request $request, AcademicSession $academicSession)
    {
        $request->validate([
            'target_session' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'copy_classes' => 'boolean',
            'copy_students' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Créer la nouvelle session si elle n'existe pas
            $targetSession = AcademicSession::firstOrCreate(
                ['name' => $request->target_session],
                [
                    'label' => 'Année Scolaire ' . $request->target_session,
                    'status' => AcademicSession::STATUS_UPCOMING,
                ]
            );

            $copied = [
                'students' => 0,
            ];

            // Copier les étudiants si demandé
            if ($request->copy_students) {
                $students = StudentRecord::where('session', $academicSession->name)->get();
                
                foreach ($students as $student) {
                    // Vérifier si l'étudiant n'existe pas déjà dans la nouvelle session
                    $exists = StudentRecord::where('user_id', $student->user_id)
                        ->where('session', $request->target_session)
                        ->exists();

                    if (!$exists) {
                        StudentRecord::create([
                            'user_id' => $student->user_id,
                            'my_class_id' => $student->my_class_id,
                            'section_id' => $student->section_id,
                            'my_parent_id' => $student->my_parent_id,
                            'session' => $request->target_session,
                            'adm_no' => $student->adm_no,
                            'gender' => $student->gender,
                            'age' => $student->age,
                            'dorm_id' => $student->dorm_id,
                            'dorm_room_no' => $student->dorm_room_no,
                        ]);
                        $copied['students']++;
                    }
                }
            }

            DB::commit();

            return redirect()->route('academic_sessions.index')
                ->with('flash_success', "Structure copiée ! {$copied['students']} élève(s) transféré(s) vers {$request->target_session}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('flash_danger', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une session (avec confirmation)
     */
    public function destroy(AcademicSession $academicSession)
    {
        if ($academicSession->is_current) {
            return back()->with('flash_danger', 'Impossible de supprimer l\'année courante.');
        }

        // Vérifier s'il y a des données liées
        $studentCount = StudentRecord::where('session', $academicSession->name)->count();
        if ($studentCount > 0) {
            return back()->with('flash_danger', "Impossible de supprimer. {$studentCount} élève(s) inscrit(s) pour cette année.");
        }

        $name = $academicSession->name;
        $academicSession->delete();

        return redirect()->route('academic_sessions.index')
            ->with('flash_success', "Année scolaire {$name} supprimée.");
    }

    /**
     * Préparer la nouvelle année scolaire
     */
    public function prepareNewYear()
    {
        $currentSession = AcademicSession::current();
        $nextSessionName = AcademicSession::getNextSessionName();
        $classes = MyClass::with('sections')->get();
        
        // Statistiques actuelles
        $stats = [
            'current_students' => StudentRecord::where('session', $currentSession->name ?? '')->count(),
            'current_classes' => $classes->count(),
        ];

        return view('pages.support_team.academic_sessions.prepare_new_year', compact('currentSession', 'nextSessionName', 'classes', 'stats'));
    }

    /**
     * Exécuter la préparation nouvelle année
     */
    public function executeNewYear(Request $request)
    {
        $request->validate([
            'new_session_name' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'archive_current' => 'boolean',
            'promote_students' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $currentSession = AcademicSession::current();

            // Créer la nouvelle session
            $newSession = AcademicSession::create([
                'name' => $request->new_session_name,
                'label' => 'Année Scolaire ' . $request->new_session_name,
                'status' => AcademicSession::STATUS_ACTIVE,
                'is_current' => false,
            ]);

            // Archiver l'année courante si demandé
            if ($request->archive_current && $currentSession) {
                $currentSession->archive();
            }

            // Définir la nouvelle comme courante
            $newSession->setAsCurrent();

            DB::commit();

            return redirect()->route('academic_sessions.index')
                ->with('flash_success', "Nouvelle année {$request->new_session_name} préparée et activée !");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('flash_danger', 'Erreur: ' . $e->getMessage());
        }
    }
}
