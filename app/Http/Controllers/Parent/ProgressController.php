<?php

namespace App\Http\Controllers\Parent;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('parent');
    }

    /**
     * Liste des enfants du parent
     */
    public function index()
    {
        $parent = Auth::user();
        
        // Récupérer les enfants du parent
        $children = StudentRecord::where('my_parent_id', $parent->id)
            ->where('session', Qs::getCurrentSession())
            ->with(['user', 'my_class', 'section'])
            ->get();

        return view('pages.parent.progress.index', compact('children'));
    }

    /**
     * Voir la progression d'un enfant
     */
    public function show($student_id)
    {
        $parent = Auth::user();
        $year = Qs::getCurrentSession();

        // Vérifier que c'est bien l'enfant du parent
        $student = StudentRecord::where('user_id', $student_id)
            ->where('my_parent_id', $parent->id)
            ->with(['user', 'my_class', 'section'])
            ->first();

        if (!$student) {
            return redirect()->route('parent.progress.index')
                ->with('flash_danger', 'Accès non autorisé');
        }

        // Récupérer les notes
        $marks = Mark::where('student_id', $student_id)
            ->where('year', $year)
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

        return view('pages.parent.progress.show', compact(
            'student', 'progressData', 'subjectData', 'stats', 'trend', 'year'
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
        ];

        for ($p = 1; $p <= 4; $p++) {
            $col = "p{$p}_avg";
            $colAlt = "t{$p}";
            
            $total = 0;
            $count = 0;
            
            foreach ($marks as $mark) {
                $value = $mark->$col ?? $mark->$colAlt;
                if ($value !== null) {
                    $total += $value;
                    $count++;
                }
            }
            
            $data['averages'][] = $count > 0 ? round($total / $count, 1) : null;
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
                $colAlt = "t{$p}";
                $value = $mark->$col ?? $mark->$colAlt;
                
                if ($value !== null) {
                    $avg += $value;
                    $count++;
                }
            }

            if ($count > 0) {
                $data[] = [
                    'subject' => $mark->subject->name,
                    'average' => round($avg / $count, 1),
                    'p1' => $mark->p1_avg ?? $mark->t1,
                    'p2' => $mark->p2_avg ?? $mark->t2,
                    'p3' => $mark->p3_avg ?? $mark->t3,
                    'p4' => $mark->p4_avg ?? $mark->t4,
                ];
            }
        }

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
                $colAlt = "t{$p}";
                $value = $mark->$col ?? $mark->$colAlt;
                
                if ($value !== null) {
                    $allAverages[] = $value;
                }
            }
        }

        $generalAvg = count($allAverages) > 0 ? array_sum($allAverages) / count($allAverages) : 0;

        // Points forts (>= 14)
        $subjectData = $this->calculateSubjectData($marks);
        $strengths = collect($subjectData)
            ->filter(fn($s) => $s['average'] >= 14)
            ->pluck('subject')
            ->toArray();

        // Points faibles (< 10)
        $weaknesses = collect($subjectData)
            ->filter(fn($s) => $s['average'] < 10)
            ->pluck('subject')
            ->toArray();

        return [
            'general_average' => round($generalAvg, 1),
            'total_subjects' => $marks->count(),
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
        ];
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
}
