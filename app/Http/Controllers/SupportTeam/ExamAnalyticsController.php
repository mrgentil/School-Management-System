<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Controllers\Controller;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\MarkRepo;
use App\Models\User;
use App\Models\ExamRecord;
use App\Models\Mark;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamResultsExport;
use App\Imports\MarksImport;

class ExamAnalyticsController extends Controller
{
    protected $exam, $my_class, $student, $mark, $year;

    public function __construct(ExamRepo $exam, MyClassRepo $my_class, StudentRepo $student, MarkRepo $mark)
    {
        $this->middleware('teamSA');
        $this->exam = $exam;
        $this->my_class = $my_class;
        $this->student = $student;
        $this->mark = $mark;
        $this->year = Qs::getSetting('current_session');
    }

    public function index()
    {
        $year = Qs::getSetting('current_session');
        $d['exams'] = $this->exam->getExam(['year' => $year]);
        $d['my_classes'] = $this->my_class->all();
        
        return view('pages.support_team.exam_analytics.index', $d);
    }

    public function overview($exam_id)
    {
        $exam = $this->exam->find($exam_id);
        $d['exam'] = $exam;
        
        // Récupérer toutes les notes pour cet examen
        $marks = $this->exam->getMark(['exam_id' => $exam_id, 'year' => $exam->year]);
        $records = $this->exam->getRecord(['exam_id' => $exam_id, 'year' => $exam->year]);
        
        // Statistiques globales
        $d['total_students'] = $records->count();
        $d['total_subjects'] = $marks->pluck('subject_id')->unique()->count();
        $d['avg_class_average'] = round($records->avg('ave'), 2);
        
        // Distribution des moyennes
        $d['grade_distribution'] = [
            'A' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'A')->count(),
            'B' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'B')->count(),
            'C' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'C')->count(),
            'D' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'D')->count(),
            'F' => $marks->filter(fn($m) => $this->getGradeFromMark($m) == 'F')->count(),
        ];
        
        // Top 10 étudiants
        $d['top_students'] = $records->sortByDesc('ave')->take(10);
        
        // Statistiques par classe
        $d['class_stats'] = [];
        foreach ($this->my_class->all() as $class) {
            $class_records = $records->where('my_class_id', $class->id);
            if ($class_records->count() > 0) {
                $d['class_stats'][] = [
                    'class_name' => $class->name,
                    'students' => $class_records->count(),
                    'average' => round($class_records->avg('ave'), 2),
                    'highest' => round($class_records->max('ave'), 2),
                    'lowest' => round($class_records->min('ave'), 2),
                ];
            }
        }
        
        // Statistiques par matière
        $d['subject_stats'] = [];
        $subjects = $this->my_class->getAllSubjects();
        foreach ($subjects as $subject) {
            $subject_marks = $marks->where('subject_id', $subject->id);
            if ($subject_marks->count() > 0) {
                $tex = 'tex'.$exam->semester;
                $d['subject_stats'][] = [
                    'subject_name' => $subject->name,
                    'average' => round($subject_marks->avg($tex), 2),
                    'highest' => round($subject_marks->max($tex), 2),
                    'lowest' => round($subject_marks->min($tex), 2),
                    'students' => $subject_marks->count(),
                ];
            }
        }

        return view('pages.support_team.exam_analytics.overview', $d);
    }

    public function classAnalysis($exam_id, $class_id)
    {
        $exam = $this->exam->find($exam_id);
        $class = $this->my_class->find($class_id);
        
        $d['exam'] = $exam;
        $d['my_class'] = $class;
        
        $marks = $this->exam->getMark(['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $exam->year]);
        $records = $this->exam->getRecord(['exam_id' => $exam_id, 'my_class_id' => $class_id, 'year' => $exam->year]);
        
        $d['records'] = $records->sortBy('pos');
        $d['marks'] = $marks;
        
        // Statistiques
        $d['class_average'] = round($records->avg('ave'), 2);
        $d['highest_score'] = round($records->max('ave'), 2);
        $d['lowest_score'] = round($records->min('ave'), 2);
        $d['pass_rate'] = round(($records->where('ave', '>=', 50)->count() / max($records->count(), 1)) * 100, 1);
        
        return view('pages.support_team.exam_analytics.class_analysis', $d);
    }

    public function studentProgress($student_id)
    {
        $student = $this->student->find($student_id);
        $d['student'] = $student;
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        
        // Récupérer tous les examens de l'étudiant
        $years = $this->exam->getExamYears($student_id);
        $d['progress_data'] = [];
        
        foreach ($years as $year_data) {
            $year = $year_data->year;
            $exams = $this->exam->getExam(['year' => $year]);
            
            foreach ($exams as $exam) {
                $record = $this->exam->getRecord([
                    'exam_id' => $exam->id,
                    'student_id' => $student_id,
                    'year' => $year
                ])->first();
                
                if ($record) {
                    $d['progress_data'][] = [
                        'exam_name' => $exam->name,
                        'year' => $year,
                        'semester' => $exam->semester,
                        'average' => $record->ave,
                        'position' => $record->pos,
                        'total' => $record->total,
                    ];
                }
            }
        }
        
        return view('pages.support_team.exam_analytics.student_progress', $d);
    }

    /**
     * Tableau de bord interactif avec analytics avancés
     */
    public function dashboard()
    {
        $d['total_students'] = StudentRecord::where('session', $this->year)->count();
        $d['total_exams'] = $this->exam->getExam(['year' => $this->year])->count();
        $d['total_classes'] = $this->my_class->all()->count();
        
        // Évolution des moyennes par mois
        $d['monthly_averages'] = $this->getMonthlyAverages();
        
        // Comparaisons inter-classes
        $d['class_comparisons'] = $this->getClassComparisons();
        
        // Étudiants en difficulté
        $d['struggling_students'] = $this->getStrugglingStudents();
        
        // Top performers
        $d['top_performers'] = $this->getTopPerformers();
        
        // Statistiques par matière
        $d['subject_performance'] = $this->getSubjectPerformance();
        
        return view('pages.support_team.exam_analytics.dashboard', $d);
    }

    /**
     * Graphiques de progression par étudiant
     */
    public function studentProgressChart($student_id)
    {
        $student = User::find($student_id);
        $records = ExamRecord::where('student_id', $student_id)
            ->with('exam')
            ->orderBy('year')
            ->orderBy('created_at')
            ->get();
        
        $progression_data = [];
        $semester_data = [];
        
        foreach ($records as $record) {
            $exam_date = $record->created_at->format('Y-m');
            $progression_data[] = [
                'date' => $exam_date,
                'average' => $record->ave,
                'position' => $record->pos,
                'exam_name' => $record->exam->name,
                'semester' => $record->exam->semester
            ];
            
            // Données par semestre pour comparaison
            $semester_key = $record->year . '-S' . $record->exam->semester;
            if (!isset($semester_data[$semester_key])) {
                $semester_data[$semester_key] = [];
            }
            $semester_data[$semester_key][] = $record->ave;
        }
        
        // Calcul des moyennes par semestre
        foreach ($semester_data as $key => $averages) {
            $semester_data[$key] = round(array_sum($averages) / count($averages), 2);
        }
        
        $d = [
            'student' => $student,
            'progression_data' => $progression_data,
            'semester_averages' => $semester_data,
            'trend_analysis' => $this->analyzeTrend($progression_data)
        ];
        
        return view('pages.support_team.exam_analytics.student_progress_chart', $d);
    }

    /**
     * Comparaisons historiques et inter-classes
     */
    public function historicalComparison()
    {
        $years = DB::table('exams')->distinct()->pluck('year')->sort();
        $classes = $this->my_class->all();
        
        $comparison_data = [];
        
        foreach ($years as $year) {
            foreach ($classes as $class) {
                $avg = ExamRecord::where('year', $year)
                    ->where('my_class_id', $class->id)
                    ->avg('ave');
                
                if ($avg) {
                    $comparison_data[] = [
                        'year' => $year,
                        'class' => $class->name,
                        'average' => round($avg, 2),
                        'students_count' => ExamRecord::where('year', $year)
                            ->where('my_class_id', $class->id)
                            ->distinct('student_id')
                            ->count()
                    ];
                }
            }
        }
        
        $d = [
            'comparison_data' => $comparison_data,
            'years' => $years,
            'classes' => $classes,
            'trends' => $this->calculateClassTrends($comparison_data)
        ];
        
        return view('pages.support_team.exam_analytics.historical_comparison', $d);
    }

    /**
     * Détection automatique des étudiants en difficulté
     */
    public function strugglingStudentsAlert()
    {
        $struggling_criteria = [
            'low_average' => 40, // Moyenne < 40%
            'declining_trend' => 3, // Baisse sur 3 examens consécutifs
            'frequent_absence' => 20, // Plus de 20% d'absence
        ];
        
        $struggling_students = [];
        $all_students = StudentRecord::where('session', $this->year)->with('user')->get();
        
        foreach ($all_students as $student) {
            $recent_records = ExamRecord::where('student_id', $student->user_id)
                ->where('year', $this->year)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            if ($recent_records->count() >= 3) {
                $avg_performance = $recent_records->avg('ave');
                $is_declining = $this->checkDecliningTrend($recent_records);
                
                $risk_factors = [];
                $risk_score = 0;
                
                if ($avg_performance < $struggling_criteria['low_average']) {
                    $risk_factors[] = 'Moyenne faible (' . round($avg_performance, 1) . '%)';
                    $risk_score += 3;
                }
                
                if ($is_declining) {
                    $risk_factors[] = 'Tendance à la baisse';
                    $risk_score += 2;
                }
                
                // Vérifier les absences si le système d'attendance existe
                if (class_exists('App\Models\Attendance\Attendance')) {
                    $absence_rate = $this->calculateAbsenceRate($student->user_id);
                    if ($absence_rate > $struggling_criteria['frequent_absence']) {
                        $risk_factors[] = 'Absences fréquentes (' . $absence_rate . '%)';
                        $risk_score += 2;
                    }
                }
                
                if ($risk_score >= 3) {
                    $struggling_students[] = [
                        'student' => $student,
                        'risk_score' => $risk_score,
                        'risk_factors' => $risk_factors,
                        'recent_average' => round($avg_performance, 1),
                        'last_exam_date' => $recent_records->first()->created_at,
                        'recommendations' => $this->generateRecommendations($risk_factors)
                    ];
                }
            }
        }
        
        // Trier par score de risque décroissant
        usort($struggling_students, function($a, $b) {
            return $b['risk_score'] - $a['risk_score'];
        });
        
        $d = [
            'struggling_students' => $struggling_students,
            'total_at_risk' => count($struggling_students),
            'criteria' => $struggling_criteria
        ];
        
        return view('pages.support_team.exam_analytics.struggling_students', $d);
    }

    /**
     * Import Excel pour saisie rapide des notes
     */
    public function importMarks(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id'
        ]);
        
        try {
            Excel::import(new MarksImport($request->exam_id, $request->class_id, $request->section_id), $request->file('excel_file'));
            
            return back()->with('flash_success', 'Notes importées avec succès!');
        } catch (\Exception $e) {
            return back()->with('flash_danger', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * Export Excel des résultats
     */
    public function exportResults(Request $request)
    {
        $exam_id = $request->exam_id;
        $class_id = $request->class_id;
        $format = $request->format ?? 'xlsx';
        
        $exam = $this->exam->find($exam_id);
        $class = $this->my_class->find($class_id);
        
        $filename = 'resultats_' . str_replace(' ', '_', $exam->name) . '_' . str_replace(' ', '_', $class->name) . '.' . $format;
        
        return Excel::download(new ExamResultsExport($exam_id, $class_id), $filename);
    }

    /**
     * Rapports statistiques par matière/enseignant
     */
    public function subjectTeacherReports()
    {
        $subjects = $this->my_class->getAllSubjects();
        $teachers = User::where('user_type', 'teacher')->get();
        
        $subject_stats = [];
        $teacher_stats = [];
        
        foreach ($subjects as $subject) {
            $marks = Mark::where('subject_id', $subject->id)
                ->whereHas('examRecord', function($q) {
                    $q->where('year', $this->year);
                })
                ->get();
            
            if ($marks->count() > 0) {
                $tex_fields = ['tex1', 'tex2']; // Semestres 1 et 2
                $total_average = 0;
                $count = 0;
                
                foreach ($tex_fields as $tex) {
                    $avg = $marks->where($tex, '>', 0)->avg($tex);
                    if ($avg) {
                        $total_average += $avg;
                        $count++;
                    }
                }
                
                $subject_stats[] = [
                    'subject' => $subject,
                    'average' => $count > 0 ? round($total_average / $count, 2) : 0,
                    'students_count' => $marks->pluck('student_id')->unique()->count(),
                    'pass_rate' => $this->calculatePassRate($marks, $tex_fields),
                    'grade_distribution' => $this->getGradeDistribution($marks, $tex_fields)
                ];
            }
        }
        
        foreach ($teachers as $teacher) {
            $teacher_subjects = $this->my_class->findSubjectByTeacher($teacher->id);
            $teacher_marks = Mark::whereIn('subject_id', $teacher_subjects->pluck('id'))
                ->whereHas('examRecord', function($q) {
                    $q->where('year', $this->year);
                })
                ->get();
            
            if ($teacher_marks->count() > 0) {
                $teacher_stats[] = [
                    'teacher' => $teacher,
                    'subjects' => $teacher_subjects,
                    'students_count' => $teacher_marks->pluck('student_id')->unique()->count(),
                    'average_performance' => $this->calculateTeacherPerformance($teacher_marks),
                    'classes_taught' => $teacher_marks->pluck('my_class_id')->unique()->count()
                ];
            }
        }
        
        $d = [
            'subject_stats' => $subject_stats,
            'teacher_stats' => $teacher_stats,
            'year' => $this->year
        ];
        
        return view('pages.support_team.exam_analytics.subject_teacher_reports', $d);
    }

    /**
     * Classements et palmarès
     */
    public function rankings()
    {
        $current_semester = 1; // ou dynamique
        $exams = $this->exam->getExam(['year' => $this->year, 'semester' => $current_semester]);
        
        $rankings = [];
        
        foreach ($this->my_class->all() as $class) {
            $class_rankings = [];
            
            foreach ($exams as $exam) {
                $records = ExamRecord::where('exam_id', $exam->id)
                    ->where('my_class_id', $class->id)
                    ->where('year', $this->year)
                    ->with('student')
                    ->orderBy('pos')
                    ->take(10)
                    ->get();
                
                $class_rankings[$exam->name] = $records;
            }
            
            // Classement général de la classe
            $general_ranking = ExamRecord::where('my_class_id', $class->id)
                ->where('year', $this->year)
                ->select('student_id', DB::raw('AVG(ave) as avg_performance'))
                ->groupBy('student_id')
                ->orderBy('avg_performance', 'desc')
                ->with('student')
                ->take(10)
                ->get();
            
            $rankings[$class->name] = [
                'by_exam' => $class_rankings,
                'general' => $general_ranking
            ];
        }
        
        // Palmarès général de l'école
        $school_top_performers = ExamRecord::where('year', $this->year)
            ->select('student_id', DB::raw('AVG(ave) as avg_performance'))
            ->groupBy('student_id')
            ->orderBy('avg_performance', 'desc')
            ->with(['student.user', 'student.my_class'])
            ->take(20)
            ->get();
        
        $d = [
            'class_rankings' => $rankings,
            'school_top_performers' => $school_top_performers,
            'year' => $this->year
        ];
        
        return view('pages.support_team.exam_analytics.rankings', $d);
    }

    /**
     * Notifications automatiques aux parents
     */
    public function sendParentNotifications(Request $request)
    {
        $notification_type = $request->type; // 'results_published', 'struggling_student', 'excellent_performance'
        $exam_id = $request->exam_id;
        
        $exam = $this->exam->find($exam_id);
        $records = ExamRecord::where('exam_id', $exam_id)->with(['student.user', 'student.parent'])->get();
        
        $sent_count = 0;
        
        foreach ($records as $record) {
            $parent = $record->student->parent ?? null;
            
            if ($parent && $parent->email) {
                $notification_data = [
                    'student_name' => $record->student->user->name,
                    'exam_name' => $exam->name,
                    'average' => $record->ave,
                    'position' => $record->pos,
                    'class_name' => $record->student->my_class->name
                ];
                
                try {
                    switch ($notification_type) {
                        case 'results_published':
                            Mail::to($parent->email)->send(new \App\Mail\ExamResultsNotification($notification_data));
                            break;
                        case 'struggling_student':
                            if ($record->ave < 40) {
                                Mail::to($parent->email)->send(new \App\Mail\StrugglingStudentAlert($notification_data));
                            }
                            break;
                        case 'excellent_performance':
                            if ($record->ave >= 80) {
                                Mail::to($parent->email)->send(new \App\Mail\ExcellentPerformanceNotification($notification_data));
                            }
                            break;
                    }
                    $sent_count++;
                } catch (\Exception $e) {
                    \Log::error('Erreur envoi notification parent: ' . $e->getMessage());
                }
            }
        }
        
        return back()->with('flash_success', $sent_count . ' notifications envoyées aux parents.');
    }

    // Méthodes utilitaires privées
    private function getMonthlyAverages()
    {
        $monthly_data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $avg = ExamRecord::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->avg('ave');
            
            $monthly_data[] = [
                'month' => $date->format('M Y'),
                'average' => $avg ? round($avg, 2) : 0
            ];
        }
        return $monthly_data;
    }

    private function getClassComparisons()
    {
        $comparisons = [];
        foreach ($this->my_class->all() as $class) {
            $avg = ExamRecord::where('my_class_id', $class->id)
                ->where('year', $this->year)
                ->avg('ave');
            
            if ($avg) {
                $comparisons[] = [
                    'class_name' => $class->name,
                    'average' => round($avg, 2),
                    'students_count' => ExamRecord::where('my_class_id', $class->id)
                        ->where('year', $this->year)
                        ->distinct('student_id')
                        ->count()
                ];
            }
        }
        return $comparisons;
    }

    private function getStrugglingStudents()
    {
        return ExamRecord::where('year', $this->year)
            ->where('ave', '<', 40)
            ->with(['student.user'])
            ->orderBy('ave')
            ->take(10)
            ->get();
    }

    private function getTopPerformers()
    {
        return ExamRecord::where('year', $this->year)
            ->where('ave', '>=', 80)
            ->with(['student.user'])
            ->orderBy('ave', 'desc')
            ->take(10)
            ->get();
    }

    private function getSubjectPerformance()
    {
        $subjects = $this->my_class->getAllSubjects();
        $performance = [];
        
        foreach ($subjects as $subject) {
            $marks = Mark::where('subject_id', $subject->id)
                ->whereHas('examRecord', function($q) {
                    $q->where('year', $this->year);
                })
                ->get();
            
            if ($marks->count() > 0) {
                $avg_s1 = $marks->where('tex1', '>', 0)->avg('tex1');
                $avg_s2 = $marks->where('tex2', '>', 0)->avg('tex2');
                
                $performance[] = [
                    'subject_name' => $subject->name,
                    'semester1_avg' => $avg_s1 ? round($avg_s1, 2) : 0,
                    'semester2_avg' => $avg_s2 ? round($avg_s2, 2) : 0,
                    'overall_avg' => ($avg_s1 && $avg_s2) ? round(($avg_s1 + $avg_s2) / 2, 2) : ($avg_s1 ?: $avg_s2 ?: 0)
                ];
            }
        }
        
        return $performance;
    }

    private function checkDecliningTrend($records)
    {
        if ($records->count() < 3) return false;
        
        $averages = $records->pluck('ave')->toArray();
        $declining_count = 0;
        
        for ($i = 1; $i < count($averages); $i++) {
            if ($averages[$i] < $averages[$i-1]) {
                $declining_count++;
            }
        }
        
        return $declining_count >= 2;
    }

    private function calculateAbsenceRate($student_id)
    {
        if (!class_exists('App\Models\Attendance\Attendance')) {
            return 0;
        }
        
        $total_days = \App\Models\Attendance\Attendance::where('student_id', $student_id)
            ->whereMonth('date', Carbon::now()->month)
            ->count();
        
        if ($total_days == 0) return 0;
        
        $absent_days = \App\Models\Attendance\Attendance::where('student_id', $student_id)
            ->whereMonth('date', Carbon::now()->month)
            ->where('status', 'absent')
            ->count();
        
        return round(($absent_days / $total_days) * 100, 1);
    }

    private function generateRecommendations($risk_factors)
    {
        $recommendations = [];
        
        foreach ($risk_factors as $factor) {
            if (strpos($factor, 'Moyenne faible') !== false) {
                $recommendations[] = 'Séances de rattrapage recommandées';
                $recommendations[] = 'Suivi pédagogique renforcé';
            }
            if (strpos($factor, 'Tendance à la baisse') !== false) {
                $recommendations[] = 'Entretien avec l\'élève et les parents';
                $recommendations[] = 'Révision des méthodes d\'apprentissage';
            }
            if (strpos($factor, 'Absences fréquentes') !== false) {
                $recommendations[] = 'Suivi de l\'assiduité';
                $recommendations[] = 'Contact avec la famille';
            }
        }
        
        return array_unique($recommendations);
    }

    private function analyzeTrend($progression_data)
    {
        if (count($progression_data) < 2) {
            return ['trend' => 'insufficient_data', 'message' => 'Données insuffisantes'];
        }
        
        $recent_avg = array_slice(array_column($progression_data, 'average'), -3);
        $older_avg = array_slice(array_column($progression_data, 'average'), 0, 3);
        
        $recent_mean = array_sum($recent_avg) / count($recent_avg);
        $older_mean = array_sum($older_avg) / count($older_avg);
        
        $difference = $recent_mean - $older_mean;
        
        if ($difference > 5) {
            return ['trend' => 'improving', 'message' => 'Progression positive (+' . round($difference, 1) . '%)'];
        } elseif ($difference < -5) {
            return ['trend' => 'declining', 'message' => 'Tendance à la baisse (' . round($difference, 1) . '%)'];
        } else {
            return ['trend' => 'stable', 'message' => 'Performance stable'];
        }
    }

    private function calculateClassTrends($comparison_data)
    {
        $trends = [];
        $classes = array_unique(array_column($comparison_data, 'class'));
        
        foreach ($classes as $class) {
            $class_data = array_filter($comparison_data, function($item) use ($class) {
                return $item['class'] === $class;
            });
            
            if (count($class_data) >= 2) {
                $years = array_column($class_data, 'year');
                $averages = array_column($class_data, 'average');
                
                $first_avg = reset($averages);
                $last_avg = end($averages);
                
                $trends[$class] = [
                    'change' => $last_avg - $first_avg,
                    'percentage_change' => $first_avg > 0 ? round((($last_avg - $first_avg) / $first_avg) * 100, 1) : 0
                ];
            }
        }
        
        return $trends;
    }

    private function calculatePassRate($marks, $tex_fields)
    {
        $total_marks = 0;
        $passing_marks = 0;
        
        foreach ($tex_fields as $tex) {
            $field_marks = $marks->where($tex, '>', 0);
            $total_marks += $field_marks->count();
            $passing_marks += $field_marks->where($tex, '>=', 50)->count();
        }
        
        return $total_marks > 0 ? round(($passing_marks / $total_marks) * 100, 1) : 0;
    }

    private function getGradeDistribution($marks, $tex_fields)
    {
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
        
        foreach ($tex_fields as $tex) {
            foreach ($marks->where($tex, '>', 0) as $mark) {
                $grade = $this->getGradeFromMark($mark, $tex);
                $distribution[$grade]++;
            }
        }
        
        return $distribution;
    }

    private function calculateTeacherPerformance($marks)
    {
        $tex_fields = ['tex1', 'tex2'];
        $total_avg = 0;
        $count = 0;
        
        foreach ($tex_fields as $tex) {
            $avg = $marks->where($tex, '>', 0)->avg($tex);
            if ($avg) {
                $total_avg += $avg;
                $count++;
            }
        }
        
        return $count > 0 ? round($total_avg / $count, 2) : 0;
    }

    private function getGradeFromMark($mark, $tex_field = 'tex1')
    {
        $total = $mark->$tex_field ?? 0;
        
        if ($total >= 80) return 'A';
        if ($total >= 70) return 'B';
        if ($total >= 60) return 'C';
        if ($total >= 50) return 'D';
        return 'F';
    }
}
