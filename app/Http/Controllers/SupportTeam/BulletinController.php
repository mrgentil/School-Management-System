<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\Subject;
use App\Models\Setting;
use App\Models\SubjectGradeConfig;
use App\Helpers\Qs;
use App\Services\ImprovedProclamationCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinController extends Controller
{
    protected $year;
    protected $proclamationService;

    public function __construct(ImprovedProclamationCalculationService $proclamationService)
    {
        $this->middleware('teamSA');
        $this->year = Qs::getCurrentSession();
        $this->proclamationService = $proclamationService;
    }

    /**
     * Page principale de gestion des bulletins
     */
    public function index()
    {
        $d['my_classes'] = \App\Models\MyClass::with('section')->orderBy('name')->get();
        $d['sections'] = \App\Models\Section::all();
        $d['year'] = $this->year;
        $d['periods'] = [1 => 'Période 1', 2 => 'Période 2', 3 => 'Période 3', 4 => 'Période 4'];
        $d['semesters'] = [1 => 'Semestre 1', 2 => 'Semestre 2'];

        return view('pages.support_team.bulletins.index', $d);
    }

    /**
     * Liste des étudiants pour génération de bulletins
     */
    public function students(Request $req)
    {
        $class_id = $req->my_class_id;
        $section_id = $req->section_id;
        $period = $req->period;
        $semester = $req->semester;
        $type = $req->type ?? 'period'; // period ou semester

        $students = StudentRecord::where('my_class_id', $class_id)
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->get()
            ->sortBy('user.name');

        $d['students'] = $students;
        $d['class_id'] = $class_id;
        $d['section_id'] = $section_id;
        $d['period'] = $period;
        $d['semester'] = $semester;
        $d['type'] = $type;
        $d['year'] = $this->year;

        return view('pages.support_team.bulletins.students', $d);
    }

    /**
     * Générer le bulletin PDF d'un étudiant
     */
    public function generate($student_id, Request $req)
    {
        $type = $req->type ?? 'period';
        $period = $req->period ?? 1;
        $semester = $req->semester ?? 1;

        $student = StudentRecord::where('user_id', $student_id)
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->first();

        if (!$student) {
            return back()->with('flash_danger', 'Étudiant non trouvé.');
        }

        $bulletinData = $this->getBulletinData($student, $type, $period, $semester);
        $school = $this->getSchoolInfo();

        $data = [
            'student' => $student,
            'bulletinData' => $bulletinData['subjects'],
            'stats' => $bulletinData['stats'],
            'school' => $school,
            'year' => $this->year,
            'type' => $type,
            'period' => $period,
            'semester' => $semester,
            'rank' => $this->getStudentRank($student, $type, $period, $semester),
            'totalStudents' => $this->getTotalStudentsInClass($student),
            'appreciation' => $this->getAppreciation($bulletinData['stats']['average']),
            'generated_at' => now()->format('d/m/Y à H:i'),
        ];

        $pdf = Pdf::loadView('pages.support_team.bulletins.pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Bulletin_' . str_replace(' ', '_', $student->user->name) . '_' . 
                    ($type == 'period' ? 'P'.$period : 'S'.$semester) . '_' . 
                    str_replace('/', '-', $this->year) . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Générer les bulletins en lot (ZIP)
     */
    public function generateBatch(Request $req)
    {
        $class_id = $req->my_class_id;
        $section_id = $req->section_id;
        $type = $req->type ?? 'period';
        $period = $req->period ?? 1;
        $semester = $req->semester ?? 1;

        $students = StudentRecord::where('my_class_id', $class_id)
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->get();

        if ($students->isEmpty()) {
            return back()->with('flash_danger', 'Aucun étudiant trouvé.');
        }

        $school = $this->getSchoolInfo();
        $totalStudents = $students->count();

        // Créer un ZIP
        $zipFileName = 'Bulletins_' . ($type == 'period' ? 'P'.$period : 'S'.$semester) . '_' . 
                       str_replace('/', '-', $this->year) . '.zip';
        $zipPath = storage_path('app/public/bulletins/' . $zipFileName);

        // Créer le dossier s'il n'existe pas
        if (!file_exists(storage_path('app/public/bulletins'))) {
            mkdir(storage_path('app/public/bulletins'), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('flash_danger', 'Impossible de créer le fichier ZIP.');
        }

        foreach ($students as $student) {
            $bulletinData = $this->getBulletinData($student, $type, $period, $semester);
            
            $data = [
                'student' => $student,
                'bulletinData' => $bulletinData['subjects'],
                'stats' => $bulletinData['stats'],
                'school' => $school,
                'year' => $this->year,
                'type' => $type,
                'period' => $period,
                'semester' => $semester,
                'rank' => $this->getStudentRank($student, $type, $period, $semester),
                'totalStudents' => $totalStudents,
                'appreciation' => $this->getAppreciation($bulletinData['stats']['average']),
                'generated_at' => now()->format('d/m/Y à H:i'),
            ];

            $pdf = Pdf::loadView('pages.support_team.bulletins.pdf', $data);
            $pdfContent = $pdf->output();
            
            $filename = str_replace(' ', '_', $student->user->name) . '.pdf';
            $zip->addFromString($filename, $pdfContent);
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Prévisualiser le bulletin (HTML)
     */
    public function preview($student_id, Request $req)
    {
        $type = $req->type ?? 'period';
        $period = $req->period ?? 1;
        $semester = $req->semester ?? 1;

        $student = StudentRecord::where('user_id', $student_id)
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->first();

        if (!$student) {
            return back()->with('flash_danger', 'Étudiant non trouvé.');
        }

        $bulletinData = $this->getBulletinData($student, $type, $period, $semester);
        $school = $this->getSchoolInfo();

        $d['student'] = $student;
        $d['bulletinData'] = $bulletinData['subjects'];
        $d['stats'] = $bulletinData['stats'];
        $d['school'] = $school;
        $d['year'] = $this->year;
        $d['type'] = $type;
        $d['period'] = $period;
        $d['semester'] = $semester;
        $d['rank'] = $this->getStudentRank($student, $type, $period, $semester);
        $d['totalStudents'] = $this->getTotalStudentsInClass($student);
        $d['appreciation'] = $this->getAppreciation($bulletinData['stats']['average']);
        $d['generated_at'] = now()->format('d/m/Y à H:i');

        return view('pages.support_team.bulletins.preview', $d);
    }

    /**
     * Récupérer les données du bulletin - UTILISE LE SERVICE DE PROCLAMATION AMÉLIORÉ
     * Combine: devoirs + interrogations (t1-t4) + TCA comme la feuille de tabulation
     */
    protected function getBulletinData($student, $type, $period, $semester)
    {
        // Récupérer les matières de la classe
        $classSubjects = Subject::where('my_class_id', $student->my_class_id)
            ->orderBy('name')
            ->get();

        $subjects = [];
        $totalPercentage = 0;
        $subjectCount = 0;

        // Utiliser le service de proclamation pour récupérer les vraies moyennes
        if ($type == 'period') {
            $averageData = $this->proclamationService->calculateStudentPeriodAverage(
                $student->user_id,
                $student->my_class_id,
                $period,
                $this->year
            );
        } else {
            $averageData = $this->proclamationService->calculateStudentSemesterAverage(
                $student->user_id,
                $student->my_class_id,
                $semester,
                $this->year
            );
        }

        foreach ($classSubjects as $subject) {
            // Configuration des cotes
            $gradeConfig = SubjectGradeConfig::getConfig(
                $student->my_class_id, 
                $subject->id, 
                $this->year
            );
            
            $periodMaxPoints = $gradeConfig ? $gradeConfig->period_max_points : 20;
            $examMaxPoints = $gradeConfig ? $gradeConfig->exam_max_points : 40;

            $data = [
                'subject' => $subject->name,
                'coefficient' => $subject->coefficient ?? 1,
                'period_max' => $periodMaxPoints,
                'exam_max' => $examMaxPoints,
            ];

            // Récupérer la moyenne calculée par le service de proclamation
            $subjectAverage = null;
            if ($averageData && isset($averageData['subject_averages'][$subject->id])) {
                $subjectAverage = $averageData['subject_averages'][$subject->id];
            }

            if ($type == 'period') {
                // Note de période depuis le service de proclamation
                if ($subjectAverage !== null) {
                    $data['total_obtained'] = round($subjectAverage['points'], 2);
                    $data['total_max'] = $periodMaxPoints;
                    $data['percentage'] = round($subjectAverage['percentage'], 2);
                    
                    // Ajouter les détails si disponibles
                    if (isset($subjectAverage['details'])) {
                        $data['details'] = $subjectAverage['details'];
                    }
                } else {
                    $data['total_obtained'] = null;
                    $data['total_max'] = $periodMaxPoints;
                    $data['percentage'] = null;
                }

            } else {
                // Semestre: moyenne des périodes + examen
                if ($subjectAverage !== null) {
                    // Pour le semestre, le service retourne period_average, exam_average, semester_percentage
                    $data['period_average'] = isset($subjectAverage['period_average']) 
                        ? round($subjectAverage['period_average'], 2) : null;
                    $data['exam_average'] = isset($subjectAverage['exam_average']) 
                        ? round($subjectAverage['exam_average'], 2) : null;
                    $data['percentage'] = isset($subjectAverage['semester_percentage']) 
                        ? round($subjectAverage['semester_percentage'], 2) 
                        : (isset($subjectAverage['percentage']) ? round($subjectAverage['percentage'], 2) : null);
                    $data['total_obtained'] = isset($subjectAverage['semester_points']) 
                        ? round($subjectAverage['semester_points'], 2) 
                        : (isset($subjectAverage['points']) ? round($subjectAverage['points'], 2) : null);
                    $data['total_max'] = $periodMaxPoints + $periodMaxPoints + $examMaxPoints;
                } else {
                    $data['period_average'] = null;
                    $data['exam_average'] = null;
                    $data['percentage'] = null;
                    $data['total_obtained'] = null;
                    $data['total_max'] = $periodMaxPoints + $periodMaxPoints + $examMaxPoints;
                }
            }

            // Appréciation
            $data['grade'] = $this->getGradeLetter($data['percentage']);
            $data['remark'] = $this->getSubjectRemark($data['percentage']);

            if ($data['percentage'] !== null) {
                $totalPercentage += $data['percentage'];
                $subjectCount++;
            }

            $subjects[] = $data;
        }

        // Moyenne générale depuis le service de proclamation
        $overallPercentage = ($averageData && isset($averageData['overall_percentage'])) 
            ? round($averageData['overall_percentage'], 2) 
            : ($subjectCount > 0 ? round($totalPercentage / $subjectCount, 2) : 0);

        return [
            'subjects' => $subjects,
            'stats' => [
                'average' => $overallPercentage,
                'total_subjects' => count($subjects),
                'passed' => collect($subjects)->where('percentage', '>=', 50)->count(),
                'failed' => collect($subjects)->where('percentage', '<', 50)->whereNotNull('percentage')->count(),
                'total_points' => round($totalPercentage, 2),
                'total_coef' => $subjectCount,
            ]
        ];
    }

    /**
     * Obtenir le rang de l'étudiant - UTILISE LE SERVICE DE PROCLAMATION
     */
    protected function getStudentRank($student, $type, $period, $semester)
    {
        // Utiliser le service de proclamation pour calculer le classement
        if ($type == 'period') {
            $rankingData = $this->proclamationService->calculateClassRankingForPeriod(
                $student->my_class_id,
                $period,
                $this->year
            );
        } else {
            $rankingData = $this->proclamationService->calculateClassRankingForSemester(
                $student->my_class_id,
                $semester,
                $this->year
            );
        }

        // Trouver le rang de l'étudiant dans le classement
        if ($rankingData && isset($rankingData['rankings'])) {
            foreach ($rankingData['rankings'] as $ranking) {
                if ($ranking['student_id'] == $student->user_id) {
                    return $ranking['rank'];
                }
            }
        }

        return 0; // Non classé
    }

    /**
     * Nombre total d'étudiants dans la classe
     */
    protected function getTotalStudentsInClass($student)
    {
        return StudentRecord::where('my_class_id', $student->my_class_id)
            ->where('section_id', $student->section_id)
            ->where('session', $this->year)
            ->count();
    }

    /**
     * Informations de l'école
     */
    protected function getSchoolInfo()
    {
        return [
            'name' => Setting::where('type', 'system_name')->value('description') ?? 'École',
            'address' => Setting::where('type', 'address')->value('description') ?? '',
            'phone' => Setting::where('type', 'phone')->value('description') ?? '',
            'email' => Setting::where('type', 'email')->value('description') ?? '',
            'logo' => Setting::where('type', 'logo')->value('description') ?? asset('global_assets/images/logo.png'),
            'motto' => Setting::where('type', 'motto')->value('description') ?? '',
        ];
    }

    /**
     * Appréciation générale (basée sur pourcentage 0-100)
     */
    protected function getAppreciation($percentage)
    {
        if ($percentage >= 90) return ['text' => 'Excellent', 'class' => 'success'];
        if ($percentage >= 80) return ['text' => 'Très Bien', 'class' => 'info'];
        if ($percentage >= 70) return ['text' => 'Bien', 'class' => 'primary'];
        if ($percentage >= 60) return ['text' => 'Assez Bien', 'class' => 'secondary'];
        if ($percentage >= 50) return ['text' => 'Passable', 'class' => 'warning'];
        if ($percentage >= 40) return ['text' => 'Insuffisant', 'class' => 'danger'];
        return ['text' => 'Très Insuffisant', 'class' => 'danger'];
    }

    /**
     * Note lettre (basée sur pourcentage 0-100)
     */
    protected function getGradeLetter($percentage)
    {
        if ($percentage === null) return '-';
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        return 'E';
    }

    /**
     * Remarque par matière (basée sur pourcentage 0-100)
     */
    protected function getSubjectRemark($percentage)
    {
        if ($percentage === null) return 'Non évalué';
        if ($percentage >= 80) return 'Excellent';
        if ($percentage >= 70) return 'Très Bien';
        if ($percentage >= 60) return 'Bien';
        if ($percentage >= 50) return 'Passable';
        if ($percentage >= 40) return 'Insuffisant';
        return 'Très Faible';
    }
}
