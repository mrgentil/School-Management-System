<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\User;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\Exam;
use App\Models\Payment;
use App\Models\Attendance;
use App\Helpers\Qs;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $year;

    public function __construct()
    {
        $this->middleware('teamSA');
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Dashboard principal avec statistiques
     */
    public function index()
    {
        $data = [];

        // Statistiques générales
        $data['stats'] = $this->getGeneralStats();

        // Répartition par classe
        $data['studentsByClass'] = $this->getStudentsByClass();

        // Répartition par genre
        $data['studentsByGender'] = $this->getStudentsByGender();

        // Performances académiques
        $data['performanceData'] = $this->getPerformanceData();

        // Présences du mois
        $data['attendanceData'] = $this->getAttendanceData();

        // Tendance des inscriptions
        $data['enrollmentTrend'] = $this->getEnrollmentTrend();

        // Top 5 élèves
        $data['topStudents'] = $this->getTopStudents();

        // Alertes et notifications
        $data['alerts'] = $this->getAlerts();

        return view('pages.support_team.dashboard_enhanced', $data);
    }

    /**
     * Statistiques générales
     */
    protected function getGeneralStats()
    {
        return [
            'total_students' => StudentRecord::where('session', $this->year)->count(),
            'total_teachers' => User::where('user_type', 'teacher')->count(),
            'total_parents' => User::where('user_type', 'parent')->count(),
            'total_classes' => MyClass::count(),
            'total_subjects' => Subject::count(),
            'active_exams' => Exam::where('year', $this->year)->count(),
        ];
    }

    /**
     * Étudiants par classe
     */
    protected function getStudentsByClass()
    {
        return StudentRecord::where('session', $this->year)
            ->select('my_class_id', DB::raw('count(*) as total'))
            ->groupBy('my_class_id')
            ->with('my_class')
            ->get()
            ->map(function ($item) {
                return [
                    'class' => $item->my_class->name ?? 'N/A',
                    'count' => $item->total,
                ];
            });
    }

    /**
     * Étudiants par genre
     */
    protected function getStudentsByGender()
    {
        $students = StudentRecord::where('session', $this->year)
            ->with('user')
            ->get();

        $male = $students->filter(fn($s) => $s->user && $s->user->gender === 'Male')->count();
        $female = $students->filter(fn($s) => $s->user && $s->user->gender === 'Female')->count();

        return [
            'male' => $male,
            'female' => $female,
        ];
    }

    /**
     * Données de performance (moyennes par période)
     */
    protected function getPerformanceData()
    {
        $data = [];
        
        for ($period = 1; $period <= 4; $period++) {
            $avgField = "p{$period}_avg";
            $avg = Mark::where('year', $this->year)
                ->whereNotNull($avgField)
                ->avg($avgField);
            
            $data[] = [
                'period' => "P{$period}",
                'average' => round($avg ?? 0, 1),
            ];
        }

        return $data;
    }

    /**
     * Données de présence du mois courant
     */
    protected function getAttendanceData()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $attendance = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'present' => $attendance['present'] ?? 0,
            'absent' => $attendance['absent'] ?? 0,
            'late' => $attendance['late'] ?? 0,
            'excused' => $attendance['excused'] ?? 0,
        ];
    }

    /**
     * Tendance des inscriptions (derniers mois)
     */
    protected function getEnrollmentTrend()
    {
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = StudentRecord::where('session', $this->year)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $data[] = [
                'month' => $date->translatedFormat('M'),
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Top 5 meilleurs élèves
     */
    protected function getTopStudents()
    {
        // Calculer la moyenne générale par étudiant
        $students = Mark::where('year', $this->year)
            ->select('student_id', DB::raw('AVG(COALESCE(p1_avg, 0) + COALESCE(p2_avg, 0) + COALESCE(p3_avg, 0) + COALESCE(p4_avg, 0)) / 4 as avg_score'))
            ->groupBy('student_id')
            ->orderByDesc('avg_score')
            ->limit(5)
            ->get();

        return $students->map(function ($item) {
            $student = StudentRecord::where('user_id', $item->student_id)
                ->with(['user', 'my_class'])
                ->first();
            
            return [
                'name' => $student->user->name ?? 'N/A',
                'class' => $student->my_class->name ?? 'N/A',
                'average' => round($item->avg_score, 1),
            ];
        });
    }

    /**
     * Alertes importantes
     */
    protected function getAlerts()
    {
        $alerts = [];

        // Élèves sans parent assigné
        $noParent = StudentRecord::where('session', $this->year)
            ->whereNull('my_parent_id')
            ->count();
        if ($noParent > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'icon-warning',
                'message' => "{$noParent} élève(s) sans parent assigné",
            ];
        }

        // Bulletins non publiés
        $unpublishedCount = MyClass::count() * 4; // 4 périodes par classe
        if ($unpublishedCount > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'icon-file-text',
                'message' => "Vérifiez la publication des bulletins",
            ];
        }

        // Absences élevées ce mois
        $highAbsence = Attendance::where('status', 'absent')
            ->whereMonth('date', Carbon::now()->month)
            ->count();
        if ($highAbsence > 10) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'icon-user-minus',
                'message' => "{$highAbsence} absences ce mois",
            ];
        }

        return $alerts;
    }

    /**
     * API pour graphiques dynamiques
     */
    public function getChartData($type)
    {
        switch ($type) {
            case 'students-by-class':
                return response()->json($this->getStudentsByClass());
            case 'performance':
                return response()->json($this->getPerformanceData());
            case 'attendance':
                return response()->json($this->getAttendanceData());
            case 'enrollment':
                return response()->json($this->getEnrollmentTrend());
            default:
                return response()->json([]);
        }
    }
}
