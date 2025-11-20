<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Controllers\Controller;
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
        
        // Examens de l'année en cours
        $d['exams'] = $this->exam->getExam(['year' => $current_year]);
        
        // Historique de toutes les années
        $years = $this->exam->getExamYears($student_id);
        $d['all_years'] = $years->pluck('year')->unique()->sort()->values();
        
        // Moyennes par période pour l'année en cours
        $d['period_averages'] = [];
        for ($period = 1; $period <= 4; $period++) {
            $avg = $this->calculatePeriodClassAverage($student_id, $period, $current_year);
            $d['period_averages'][$period] = $avg;
        }
        
        // Moyennes par semestre
        $d['semester_averages'] = [];
        for ($semester = 1; $semester <= 2; $semester++) {
            $avg = Mk::getSemesterAverage($student_id, $semester, $current_year);
            $d['semester_averages'][$semester] = $avg;
        }
        
        // Progression globale (toutes années)
        $d['progression_data'] = [];
        foreach ($d['exams'] as $exam) {
            $record = $this->exam->getRecord([
                'exam_id' => $exam->id,
                'student_id' => $student_id,
                'year' => $current_year
            ])->first();
            
            if ($record) {
                $d['progression_data'][] = [
                    'exam' => $exam->name,
                    'semester' => $exam->semester,
                    'average' => $record->ave,
                    'position' => $record->pos,
                    'class_avg' => $record->class_ave,
                ];
            }
        }
        
        // Meilleures et pires matières
        $marks = $this->exam->getMark([
            'student_id' => $student_id,
            'year' => $current_year
        ]);
        
        if ($marks->count() > 0) {
            $subject_averages = [];
            foreach ($marks as $mark) {
                $total = $this->getTotalFromMark($mark);
                if (!isset($subject_averages[$mark->subject_id])) {
                    $subject_averages[$mark->subject_id] = [
                        'subject' => $mark->subject,
                        'total' => 0,
                        'count' => 0,
                    ];
                }
                $subject_averages[$mark->subject_id]['total'] += $total;
                $subject_averages[$mark->subject_id]['count']++;
            }
            
            foreach ($subject_averages as $key => $data) {
                $subject_averages[$key]['average'] = $data['count'] > 0 
                    ? round($data['total'] / $data['count'], 2) 
                    : 0;
            }
            
            usort($subject_averages, fn($a, $b) => $b['average'] <=> $a['average']);
            
            $d['best_subjects'] = array_slice($subject_averages, 0, 3);
            $d['worst_subjects'] = array_slice(array_reverse($subject_averages), 0, 3);
        } else {
            $d['best_subjects'] = [];
            $d['worst_subjects'] = [];
        }
        
        // Recommandations
        $d['recommendations'] = $this->generateRecommendations($d);

        return view('pages.student.progress.index', $d);
    }

    private function calculatePeriodClassAverage($student_id, $period, $year)
    {
        $marks = $this->exam->getMark([
            'student_id' => $student_id,
            'year' => $year
        ]);

        $p_col = 'p'.$period.'_avg';
        $averages = $marks->pluck($p_col)->filter(fn($v) => $v > 0);
        
        return $averages->count() > 0 ? round($averages->avg(), 2) : null;
    }

    private function getTotalFromMark($mark)
    {
        // Essayer les différents semestres
        return $mark->tex1 ?? $mark->tex2 ?? ($mark->tca + $mark->exm) ?? 0;
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
