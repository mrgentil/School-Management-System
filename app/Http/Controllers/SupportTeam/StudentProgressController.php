<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\Subject;
use App\Models\MyClass;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentProgressController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->middleware('teamSAT');
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Page de sélection d'élève
     */
    public function index(Request $request)
    {
        $classes = MyClass::with('section')->orderBy('name')->get();
        $students = collect();
        $selectedClass = null;

        if ($request->filled('class_id')) {
            $selectedClass = $request->class_id;
            $query = StudentRecord::where('my_class_id', $request->class_id)
                ->where('session', $this->year)
                ->with(['user', 'section']);

            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            $students = $query->get();
        }

        return view('pages.support_team.student_progress.index', compact('classes', 'students', 'selectedClass'));
    }

    /**
     * Rapport de progression d'un élève
     */
    public function show($student_id)
    {
        $student = StudentRecord::where('user_id', $student_id)
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->firstOrFail();

        // Récupérer les notes
        $marks = Mark::where('student_id', $student_id)
            ->where('year', $this->year)
            ->with('subject')
            ->get();

        // Données de progression par période
        $progressData = $this->calculateProgressData($marks);

        // Données par matière
        $subjectData = $this->calculateSubjectData($marks);

        // Statistiques générales
        $stats = $this->calculateStats($marks, $student);

        // Tendance
        $trend = $this->calculateTrend($progressData);

        return view('pages.support_team.student_progress.show', compact(
            'student', 'progressData', 'subjectData', 'stats', 'trend'
        ));
    }

    /**
     * Calculer les données de progression par période
     */
    protected function calculateProgressData($marks)
    {
        $data = [
            'periods' => ['P1', 'P2', 'P3', 'P4'],
            'averages' => [],
            'class_averages' => [],
        ];

        for ($p = 1; $p <= 4; $p++) {
            $col = "p{$p}_avg";
            $validMarks = $marks->whereNotNull($col);
            
            if ($validMarks->count() > 0) {
                $data['averages'][] = round($validMarks->avg($col), 1);
            } else {
                $data['averages'][] = null;
            }

            // Moyenne de classe pour comparaison
            $classAvg = Mark::where('year', $this->year)
                ->whereNotNull($col)
                ->avg($col);
            $data['class_averages'][] = round($classAvg ?? 0, 1);
        }

        return $data;
    }

    /**
     * Calculer les données par matière
     */
    protected function calculateSubjectData($marks)
    {
        $data = [];

        foreach ($marks as $mark) {
            if (!$mark->subject) continue;

            $avg = 0;
            $count = 0;

            for ($p = 1; $p <= 4; $p++) {
                $col = "p{$p}_avg";
                if ($mark->$col !== null) {
                    $avg += $mark->$col;
                    $count++;
                }
            }

            if ($count > 0) {
                $data[] = [
                    'subject' => $mark->subject->name,
                    'average' => round($avg / $count, 1),
                    'p1' => $mark->p1_avg,
                    'p2' => $mark->p2_avg,
                    'p3' => $mark->p3_avg,
                    'p4' => $mark->p4_avg,
                ];
            }
        }

        // Trier par moyenne décroissante
        usort($data, fn($a, $b) => $b['average'] <=> $a['average']);

        return $data;
    }

    /**
     * Calculer les statistiques
     */
    protected function calculateStats($marks, $student)
    {
        $allAverages = [];
        
        foreach ($marks as $mark) {
            for ($p = 1; $p <= 4; $p++) {
                $col = "p{$p}_avg";
                if ($mark->$col !== null) {
                    $allAverages[] = $mark->$col;
                }
            }
        }

        $generalAvg = count($allAverages) > 0 ? array_sum($allAverages) / count($allAverages) : 0;

        // Points forts (>= 14)
        $strengths = collect($this->calculateSubjectData($marks))
            ->filter(fn($s) => $s['average'] >= 14)
            ->pluck('subject')
            ->toArray();

        // Points faibles (< 10)
        $weaknesses = collect($this->calculateSubjectData($marks))
            ->filter(fn($s) => $s['average'] < 10)
            ->pluck('subject')
            ->toArray();

        // Rang dans la classe
        $classRank = $this->calculateClassRank($student, $generalAvg);

        return [
            'general_average' => round($generalAvg, 1),
            'total_subjects' => $marks->count(),
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'class_rank' => $classRank['rank'],
            'class_total' => $classRank['total'],
        ];
    }

    /**
     * Calculer le rang dans la classe
     */
    protected function calculateClassRank($student, $studentAvg)
    {
        $classStudents = StudentRecord::where('my_class_id', $student->my_class_id)
            ->where('session', $this->year)
            ->pluck('user_id');

        $averages = [];
        foreach ($classStudents as $studentId) {
            $marks = Mark::where('student_id', $studentId)
                ->where('year', $this->year)
                ->get();

            $sum = 0;
            $count = 0;
            foreach ($marks as $mark) {
                for ($p = 1; $p <= 4; $p++) {
                    $col = "p{$p}_avg";
                    if ($mark->$col !== null) {
                        $sum += $mark->$col;
                        $count++;
                    }
                }
            }
            
            if ($count > 0) {
                $averages[$studentId] = $sum / $count;
            }
        }

        arsort($averages);
        $rank = 1;
        foreach ($averages as $id => $avg) {
            if ($id == $student->user_id) break;
            $rank++;
        }

        return ['rank' => $rank, 'total' => count($averages)];
    }

    /**
     * Calculer la tendance
     */
    protected function calculateTrend($progressData)
    {
        $averages = array_filter($progressData['averages'], fn($v) => $v !== null);
        
        if (count($averages) < 2) {
            return ['status' => 'neutral', 'change' => 0, 'message' => 'Données insuffisantes'];
        }

        $values = array_values($averages);
        $first = $values[0];
        $last = end($values);
        $change = $last - $first;

        if ($change > 1) {
            return [
                'status' => 'up',
                'change' => round($change, 1),
                'message' => 'En progression (+' . round($change, 1) . ' pts)',
                'icon' => 'icon-arrow-up7',
                'color' => 'success',
            ];
        } elseif ($change < -1) {
            return [
                'status' => 'down',
                'change' => round($change, 1),
                'message' => 'En régression (' . round($change, 1) . ' pts)',
                'icon' => 'icon-arrow-down7',
                'color' => 'danger',
            ];
        } else {
            return [
                'status' => 'stable',
                'change' => round($change, 1),
                'message' => 'Stable',
                'icon' => 'icon-minus3',
                'color' => 'info',
            ];
        }
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf($student_id)
    {
        $student = StudentRecord::where('user_id', $student_id)
            ->where('session', $this->year)
            ->with(['user', 'my_class', 'section'])
            ->firstOrFail();

        $marks = Mark::where('student_id', $student_id)
            ->where('year', $this->year)
            ->with('subject')
            ->get();

        $progressData = $this->calculateProgressData($marks);
        $subjectData = $this->calculateSubjectData($marks);
        $stats = $this->calculateStats($marks, $student);
        $trend = $this->calculateTrend($progressData);

        $data = compact('student', 'progressData', 'subjectData', 'stats', 'trend');
        $data['year'] = $this->year;
        $data['school_name'] = Qs::getSetting('system_name');
        $data['generated_at'] = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView('pages.support_team.student_progress.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Progression_' . str_replace(' ', '_', $student->user->name) . '_' . $this->year . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Comparer plusieurs élèves
     */
    public function compare(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:2|max:5',
            'students.*' => 'exists:users,id',
        ]);

        $studentsData = [];

        foreach ($request->students as $studentId) {
            $student = StudentRecord::where('user_id', $studentId)
                ->where('session', $this->year)
                ->with(['user', 'my_class'])
                ->first();

            if (!$student) continue;

            $marks = Mark::where('student_id', $studentId)
                ->where('year', $this->year)
                ->get();

            $progressData = $this->calculateProgressData($marks);

            $studentsData[] = [
                'student' => $student,
                'averages' => $progressData['averages'],
            ];
        }

        return view('pages.support_team.student_progress.compare', compact('studentsData'));
    }
}
