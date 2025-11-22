<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Services\ProclamationCalculationService;
use App\Models\MyClass;
use App\Models\Exam;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class ProclamationController extends Controller
{
    protected $proclamationService;

    public function __construct(ProclamationCalculationService $proclamationService)
    {
        $this->middleware('teamSA');
        $this->proclamationService = $proclamationService;
    }

    /**
     * Afficher la page principale des proclamations
     */
    public function index()
    {
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['current_year'] = Qs::getSetting('current_session');
        
        return view('pages.support_team.proclamations.index', $data);
    }

    /**
     * Calculer et afficher les proclamations par période
     */
    public function periodRankings(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'period' => 'required|integer|min:1|max:4',
            'year' => 'nullable|string'
        ]);

        $classId = $request->class_id;
        $period = $request->period;
        $year = $request->year ?: Qs::getSetting('current_session');

        // Calculer les classements
        $rankings = $this->proclamationService->calculateClassRankingForPeriod($classId, $period, $year);
        
        $data['rankings'] = $rankings;
        $data['selected_class'] = MyClass::find($classId);
        $data['period'] = $period;
        $data['year'] = $year;
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['current_year'] = Qs::getSetting('current_session');

        return view('pages.support_team.proclamations.period_rankings', $data);
    }

    /**
     * Calculer et afficher les proclamations par semestre
     */
    public function semesterRankings(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'semester' => 'required|integer|min:1|max:2',
            'year' => 'nullable|string'
        ]);

        $classId = $request->class_id;
        $semester = $request->semester;
        $year = $request->year ?: Qs::getSetting('current_session');

        // Calculer les classements
        $rankings = $this->proclamationService->calculateClassRankingForSemester($classId, $semester, $year);
        
        $data['rankings'] = $rankings;
        $data['selected_class'] = MyClass::find($classId);
        $data['semester'] = $semester;
        $data['year'] = $year;
        $data['my_classes'] = MyClass::orderBy('name')->get();
        $data['current_year'] = Qs::getSetting('current_session');

        return view('pages.support_team.proclamations.semester_rankings', $data);
    }

    /**
     * Afficher le détail d'un étudiant
     */
    public function studentDetail(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'class_id' => 'required|exists:my_classes,id',
            'period' => 'nullable|integer|min:1|max:4',
            'semester' => 'nullable|integer|min:1|max:2',
            'year' => 'nullable|string'
        ]);

        $studentId = $request->student_id;
        $classId = $request->class_id;
        $year = $request->year ?: Qs::getSetting('current_session');

        $data['student_details'] = [];
        
        if ($request->period) {
            // Détail par période
            $data['student_details'] = $this->proclamationService->calculateStudentPeriodAverage(
                $studentId, $classId, $request->period, $year
            );
            $data['type'] = 'period';
            $data['period'] = $request->period;
        } elseif ($request->semester) {
            // Détail par semestre
            $data['student_details'] = $this->proclamationService->calculateStudentSemesterAverage(
                $studentId, $classId, $request->semester, $year
            );
            $data['type'] = 'semester';
            $data['semester'] = $request->semester;
        }

        $data['selected_class'] = MyClass::find($classId);
        $data['year'] = $year;

        return view('pages.support_team.proclamations.student_detail', $data);
    }

    /**
     * Recalculer toutes les moyennes pour une classe
     */
    public function recalculateClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'year' => 'nullable|string'
        ]);

        $classId = $request->class_id;
        $year = $request->year ?: Qs::getSetting('current_session');

        try {
            // Recalculer pour toutes les périodes et semestres
            $results = [
                'periods' => [],
                'semesters' => []
            ];

            // Périodes 1-4
            for ($period = 1; $period <= 4; $period++) {
                $results['periods'][$period] = $this->proclamationService->calculateClassRankingForPeriod(
                    $classId, $period, $year
                );
            }

            // Semestres 1-2
            for ($semester = 1; $semester <= 2; $semester++) {
                $results['semesters'][$semester] = $this->proclamationService->calculateClassRankingForSemester(
                    $classId, $semester, $year
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Recalcul terminé avec succès',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du recalcul: ' . $e->getMessage()
            ], 500);
        }
    }
}
