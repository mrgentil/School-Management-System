<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Models\Mark;
use App\Repositories\ExamRepo;
use App\Repositories\StudentRepo;
use App\Repositories\MyClassRepo;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    protected $student, $exam, $my_class;

    public function __construct(StudentRepo $student, ExamRepo $exam, MyClassRepo $my_class)
    {
        $this->middleware('student');
        $this->student = $student;
        $this->exam = $exam;
        $this->my_class = $my_class;
    }

    public function index()
    {
        $student_id = Auth::id();
        $d['sr'] = $sr = $this->student->getRecord(['user_id' => $student_id])
            ->with(['my_class.academicSection', 'my_class.option', 'section'])
            ->first();

        if (!$sr) {
            return redirect()->route('dashboard')->with('flash_danger', 'Aucune information trouvée');
        }

        // Données de progression
        $current_year = Qs::getSetting('current_session');
        $d['current_year'] = $current_year;
        
        // Récupérer les notes directement depuis la table marks
        $marks = Mark::where('student_id', $student_id)
                    ->where('my_class_id', $sr->my_class_id)
                    ->where('year', $current_year)
                    ->with('subject')
                    ->get();
        
        // Moyennes par période pour l'année en cours (utiliser t1, t2, t3, t4)
        $d['period_averages'] = [];
        for ($period = 1; $period <= 4; $period++) {
            $avg = $this->calculatePeriodAverage($marks, $period);
            $d['period_averages'][$period] = $avg;
        }
        
        // Moyennes par semestre
        $d['semester_averages'] = [];
        $d['semester_averages'][1] = ($d['period_averages'][1] !== null && $d['period_averages'][2] !== null) 
            ? round(($d['period_averages'][1] + $d['period_averages'][2]) / 2, 2) 
            : null;
        $d['semester_averages'][2] = ($d['period_averages'][3] !== null && $d['period_averages'][4] !== null) 
            ? round(($d['period_averages'][3] + $d['period_averages'][4]) / 2, 2) 
            : null;
        
        // Progression par période
        $d['progression_data'] = [];
        for ($period = 1; $period <= 4; $period++) {
            if ($d['period_averages'][$period] !== null) {
                $d['progression_data'][] = [
                    'exam' => 'Période ' . $period,
                    'semester' => $period <= 2 ? 1 : 2,
                    'average' => $d['period_averages'][$period],
                    'position' => $this->calculateRankForPeriod($student_id, $sr->my_class_id, $period, $current_year),
                    'class_avg' => $this->calculateClassAverageForPeriod($sr->my_class_id, $period, $current_year),
                ];
            }
        }
        
        // Meilleures et pires matières (basé sur t1 ou p1_avg)
        $subject_averages = [];
        foreach ($marks as $mark) {
            if (!$mark->subject) continue;
            
            $avg = $mark->t1 ?? $mark->p1_avg ?? $mark->t2 ?? $mark->p2_avg ?? 0;
            if ($avg > 0) {
                $subject_averages[] = [
                    'subject' => $mark->subject,
                    'average' => $avg,
                ];
            }
        }
        
        usort($subject_averages, fn($a, $b) => $b['average'] <=> $a['average']);
        
        $d['best_subjects'] = array_slice($subject_averages, 0, 3);
        $d['worst_subjects'] = array_slice(array_reverse($subject_averages), 0, 3);
        
        // Recommandations
        $d['recommendations'] = $this->generateRecommendations($d);
        
        // Historique
        $d['all_years'] = Mark::where('student_id', $student_id)
                            ->distinct()
                            ->pluck('year')
                            ->sort()
                            ->values();

        return view('pages.student.progress.index', $d);
    }

    /**
     * Calculer la moyenne d'une période à partir des notes
     */
    private function calculatePeriodAverage($marks, $period)
    {
        $total = 0;
        $count = 0;
        
        foreach ($marks as $mark) {
            $value = null;
            switch ($period) {
                case 1: $value = $mark->t1 ?? $mark->p1_avg; break;
                case 2: $value = $mark->t2 ?? $mark->p2_avg; break;
                case 3: $value = $mark->t3 ?? $mark->p3_avg; break;
                case 4: $value = $mark->t4 ?? $mark->p4_avg; break;
            }
            
            if ($value !== null) {
                $total += $value;
                $count++;
            }
        }
        
        return $count > 0 ? round($total / $count, 2) : null;
    }

    /**
     * Calculer le rang de l'étudiant pour une période
     */
    private function calculateRankForPeriod($studentId, $classId, $period, $year)
    {
        $allMarks = Mark::where('my_class_id', $classId)
                        ->where('year', $year)
                        ->get()
                        ->groupBy('student_id');

        $averages = [];
        $col = 't' . $period;
        $colAlt = 'p' . $period . '_avg';
        
        foreach ($allMarks as $sid => $marks) {
            $total = 0;
            $count = 0;
            foreach ($marks as $mark) {
                $value = $mark->$col ?? $mark->$colAlt;
                if ($value !== null) {
                    $total += $value;
                    $count++;
                }
            }
            if ($count > 0) {
                $averages[$sid] = $total / $count;
            }
        }

        arsort($averages);
        
        $rank = 1;
        foreach ($averages as $sid => $avg) {
            if ($sid == $studentId) {
                return $rank;
            }
            $rank++;
        }

        return null;
    }

    /**
     * Calculer la moyenne de classe pour une période
     */
    private function calculateClassAverageForPeriod($classId, $period, $year)
    {
        $allMarks = Mark::where('my_class_id', $classId)
                        ->where('year', $year)
                        ->get();

        $col = 't' . $period;
        $colAlt = 'p' . $period . '_avg';
        
        $total = 0;
        $count = 0;
        
        foreach ($allMarks as $mark) {
            $value = $mark->$col ?? $mark->$colAlt;
            if ($value !== null) {
                $total += $value;
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : null;
    }

    private function getTotalFromMark($mark)
    {
        // Utiliser t1 ou p1_avg comme référence
        return $mark->t1 ?? $mark->p1_avg ?? $mark->t2 ?? $mark->p2_avg ?? 0;
    }

    private function generateRecommendations($data)
    {
        $recommendations = [];
        
        // Basé sur les performances
        $avg = collect($data['progression_data'])->avg('average');
        
        if ($avg < 50) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => 'Vos résultats nécessitent une attention particulière. Consultez vos enseignants pour un soutien supplémentaire.',
            ];
        } elseif ($avg >= 50 && $avg < 70) {
            $recommendations[] = [
                'type' => 'info',
                'message' => 'Vous êtes sur la bonne voie ! Continuez vos efforts pour améliorer vos résultats.',
            ];
        } else {
            $recommendations[] = [
                'type' => 'success',
                'message' => 'Excellentes performances ! Maintenez ce niveau d\'excellence.',
            ];
        }
        
        // Basé sur les matières faibles
        if (count($data['worst_subjects']) > 0) {
            $worst = $data['worst_subjects'][0];
            $recommendations[] = [
                'type' => 'info',
                'message' => "Concentrez-vous particulièrement sur {$worst['subject']->name} où vous pouvez vous améliorer.",
            ];
        }
        
        return $recommendations;
    }
}
