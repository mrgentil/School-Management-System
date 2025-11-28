<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\User;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Mark;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Attendance;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Dashboard des statistiques
     */
    public function index()
    {
        $year = Qs::getCurrentSession();

        // Stats générales
        $generalStats = $this->getGeneralStats($year);

        // Stats par classe
        $classStats = $this->getClassStats($year);

        // Top élèves
        $topStudents = $this->getTopStudents($year, 10);

        // Élèves en difficulté
        $strugglingStudents = $this->getStrugglingStudents($year, 10);

        // Stats de présence
        $attendanceStats = $this->getAttendanceStats($year);

        // Stats financières
        $financeStats = $this->getFinanceStats($year);

        // Evolution mensuelle des inscriptions
        $enrollmentTrend = $this->getEnrollmentTrend($year);

        // Performance par matière
        $subjectPerformance = $this->getSubjectPerformance($year);

        return view('pages.support_team.statistics.index', compact(
            'generalStats', 'classStats', 'topStudents', 'strugglingStudents',
            'attendanceStats', 'financeStats', 'enrollmentTrend', 'subjectPerformance', 'year'
        ));
    }

    /**
     * Statistiques générales
     */
    protected function getGeneralStats($year)
    {
        return [
            'total_students' => StudentRecord::where('session', $year)->count(),
            'total_teachers' => User::where('user_type', 'teacher')->count(),
            'total_parents' => User::where('user_type', 'parent')->count(),
            'total_classes' => MyClass::count(),
            'total_subjects' => Subject::count(),
            'boys' => StudentRecord::where('session', $year)
                ->whereHas('user', fn($q) => $q->where('gender', 'Male'))
                ->count(),
            'girls' => StudentRecord::where('session', $year)
                ->whereHas('user', fn($q) => $q->where('gender', 'Female'))
                ->count(),
        ];
    }

    /**
     * Statistiques par classe
     */
    protected function getClassStats($year)
    {
        $classes = MyClass::with(['class_type'])->get();
        $stats = [];

        foreach ($classes as $class) {
            $studentCount = StudentRecord::where('my_class_id', $class->id)
                ->where('session', $year)
                ->count();

            if ($studentCount == 0) continue;

            // Calculer la moyenne de la classe
            $marks = Mark::where('my_class_id', $class->id)
                ->where('year', $year)
                ->get();

            $averages = [];
            foreach ($marks as $mark) {
                $avg = $mark->p1_avg ?? $mark->p2_avg ?? $mark->t1 ?? $mark->t2 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            $classAverage = count($averages) > 0 ? array_sum($averages) / count($averages) : 0;

            $stats[] = [
                'id' => $class->id,
                'name' => $class->full_name ?: $class->name,
                'type' => $class->class_type->name ?? '',
                'students' => $studentCount,
                'average' => round($classAverage, 1),
                'teacher' => $class->teacher->name ?? 'Non assigné',
            ];
        }

        // Trier par moyenne décroissante
        usort($stats, fn($a, $b) => $b['average'] <=> $a['average']);

        return $stats;
    }

    /**
     * Top élèves
     */
    protected function getTopStudents($year, $limit = 10)
    {
        $marks = Mark::where('year', $year)
            ->with(['user', 'my_class'])
            ->get()
            ->groupBy('student_id');

        $students = [];

        foreach ($marks as $studentId => $studentMarks) {
            $averages = [];
            foreach ($studentMarks as $mark) {
                $avg = $mark->p1_avg ?? $mark->p2_avg ?? $mark->t1 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            if (count($averages) > 0) {
                $first = $studentMarks->first();
                $students[] = [
                    'student' => $first->user,
                    'class' => $first->my_class,
                    'average' => round(array_sum($averages) / count($averages), 1),
                ];
            }
        }

        usort($students, fn($a, $b) => $b['average'] <=> $a['average']);

        return array_slice($students, 0, $limit);
    }

    /**
     * Élèves en difficulté
     */
    protected function getStrugglingStudents($year, $limit = 10)
    {
        $marks = Mark::where('year', $year)
            ->with(['user', 'my_class'])
            ->get()
            ->groupBy('student_id');

        $students = [];

        foreach ($marks as $studentId => $studentMarks) {
            $averages = [];
            foreach ($studentMarks as $mark) {
                $avg = $mark->p1_avg ?? $mark->p2_avg ?? $mark->t1 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            if (count($averages) > 0) {
                $globalAvg = array_sum($averages) / count($averages);
                if ($globalAvg < 10) {
                    $first = $studentMarks->first();
                    $students[] = [
                        'student' => $first->user,
                        'class' => $first->my_class,
                        'average' => round($globalAvg, 1),
                    ];
                }
            }
        }

        usort($students, fn($a, $b) => $a['average'] <=> $b['average']);

        return array_slice($students, 0, $limit);
    }

    /**
     * Statistiques de présence
     */
    protected function getAttendanceStats($year)
    {
        $total = Attendance::whereYear('date', explode('-', $year)[0])->count();
        
        if ($total == 0) {
            return [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'rate' => 0,
            ];
        }

        $present = Attendance::whereYear('date', explode('-', $year)[0])
            ->where('status', 'present')->count();
        $absent = Attendance::whereYear('date', explode('-', $year)[0])
            ->where('status', 'absent')->count();
        $late = Attendance::whereYear('date', explode('-', $year)[0])
            ->where('status', 'late')->count();
        $excused = Attendance::whereYear('date', explode('-', $year)[0])
            ->where('status', 'excused')->count();

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'rate' => round(($present / $total) * 100, 1),
        ];
    }

    /**
     * Statistiques financières
     */
    protected function getFinanceStats($year)
    {
        $payments = PaymentRecord::whereHas('payment', fn($q) => $q->where('year', $year))->get();

        $totalExpected = $payments->sum(fn($p) => $p->amt_paid + $p->balance);
        $totalPaid = $payments->sum('amt_paid');
        $totalBalance = $payments->sum('balance');

        return [
            'total_expected' => $totalExpected,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'collection_rate' => $totalExpected > 0 ? round(($totalPaid / $totalExpected) * 100, 1) : 0,
            'students_paid_full' => $payments->where('balance', 0)->count(),
            'students_with_balance' => $payments->where('balance', '>', 0)->count(),
        ];
    }

    /**
     * Tendance des inscriptions
     */
    protected function getEnrollmentTrend($year)
    {
        $trend = [];
        $startYear = explode('-', $year)[0];

        for ($month = 1; $month <= 12; $month++) {
            $count = StudentRecord::where('session', $year)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $startYear)
                ->count();
            
            $trend[] = [
                'month' => Carbon::create()->month($month)->format('M'),
                'count' => $count,
            ];
        }

        return $trend;
    }

    /**
     * Performance par matière
     */
    protected function getSubjectPerformance($year)
    {
        $subjects = Subject::all();
        $performance = [];

        foreach ($subjects as $subject) {
            $marks = Mark::where('subject_id', $subject->id)
                ->where('year', $year)
                ->get();

            $averages = [];
            foreach ($marks as $mark) {
                $avg = $mark->p1_avg ?? $mark->p2_avg ?? $mark->t1 ?? null;
                if ($avg !== null) {
                    $averages[] = $avg;
                }
            }

            if (count($averages) > 0) {
                $performance[] = [
                    'name' => $subject->name,
                    'average' => round(array_sum($averages) / count($averages), 1),
                    'students' => count($averages),
                ];
            }
        }

        usort($performance, fn($a, $b) => $b['average'] <=> $a['average']);

        return array_slice($performance, 0, 15);
    }

    /**
     * Export des statistiques
     */
    public function export()
    {
        $year = Qs::getCurrentSession();
        
        $filename = "statistiques_{$year}_" . date('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $classStats = $this->getClassStats($year);

        $callback = function() use ($classStats) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Classe', 'Type', 'Élèves', 'Moyenne', 'Titulaire']);
            
            foreach ($classStats as $stat) {
                fputcsv($file, [
                    $stat['name'],
                    $stat['type'],
                    $stat['students'],
                    $stat['average'],
                    $stat['teacher'],
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
