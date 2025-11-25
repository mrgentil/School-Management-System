<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Http\Requests\Mark\MarkSelector;
use App\Models\Setting;
use App\Repositories\ExamRepo;
use App\Repositories\MarkRepo;
use App\Repositories\MyClassRepo;
use App\Http\Controllers\Controller;
use App\Repositories\StudentRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MarkController extends Controller
{
    protected $my_class, $exam, $student, $year, $user, $mark;

    public function __construct(MyClassRepo $my_class, ExamRepo $exam, StudentRepo $student, MarkRepo $mark)
    {
        $this->exam =  $exam;
        $this->mark =  $mark;
        $this->student =  $student;
        $this->my_class =  $my_class;
        $this->year =  Qs::getSetting('current_session');

       // $this->middleware('teamSAT', ['except' => ['show', 'year_selected', 'year_selector', 'print_view'] ]);
    }

    /**
     * Trouve une section avec des étudiants pour une classe donnée
     */
    private function findSectionWithStudents($classId)
    {
        // D'abord, essayer de trouver la première section de la classe
        $class = $this->my_class->find($classId);
        if (!$class) {
            return null;
        }

        $sections = $class->section;
        if (!$sections || $sections->count() == 0) {
            return null;
        }

        // Vérifier chaque section pour trouver celle qui a des étudiants
        foreach ($sections as $section) {
            $studentCount = $this->student->getRecord([
                'my_class_id' => $classId,
                'section_id' => $section->id,
                'session' => $this->year
            ])->count();

            if ($studentCount > 0) {
                return $section->id;
            }
        }

        // Si aucune section n'a d'étudiants, retourner la première section
        return $sections->first()->id;
    }

    public function index()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['subjects'] = $this->my_class->getAllSubjects();
        $d['selected'] = false;

        return view('pages.support_team.marks.index', $d);
    }

    public function year_selector($student_id)
    {
       return $this->verifyStudentExamYear($student_id);
    }

    public function year_selected(Request $req, $student_id)
    {
        if(!$this->verifyStudentExamYear($student_id, $req->year)){
            return $this->noStudentRecord();
        }

        $student_id = Qs::hash($student_id);
        return redirect()->route('marks.show', [$student_id, $req->year]);
    }

    public function show($student_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if(Auth::user()->id != $student_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($student_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if(Mk::examIsLocked() && !Qs::userIsTeamSA()){
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if(!$this->checkPinVerified($student_id)){
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if(!$this->verifyStudentExamYear($student_id, $year)){
            return $this->noStudentRecord();
        }

        $wh = ['student_id' => $student_id, 'year' => $year ];
        $d['marks'] = $this->exam->getMark($wh);
        $d['exam_records'] = $exr = $this->exam->getRecord($wh);
        $d['exams'] = $this->exam->getExam(['year' => $year]);
        $d['sr'] = $this->student->getRecord(['user_id' => $student_id])->first();
        $d['my_class'] = $mc = $this->my_class->getMC(['id' => $exr->first()->my_class_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        //$d['ct'] = $d['class_type']->code;
        //$d['mark_type'] = Qs::getMarkType($d['ct']);

        return view('pages.support_team.marks.show.index', $d);
    }

    public function print_view($student_id, $exam_id, $year)
    {
        /* Prevent Other Students/Parents from viewing Result of others */
        if(Auth::user()->id != $student_id && !Qs::userIsTeamSA() && !Qs::userIsMyChild($student_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        if(Mk::examIsLocked() && !Qs::userIsTeamSA()){
            Session::put('marks_url', route('marks.show', [Qs::hash($student_id), $year]));

            if(!$this->checkPinVerified($student_id)){
                return redirect()->route('pins.enter', Qs::hash($student_id));
            }
        }

        if(!$this->verifyStudentExamYear($student_id, $year)){
            return $this->noStudentRecord();
        }

        $wh = ['student_id' => $student_id, 'exam_id' => $exam_id, 'year' => $year ];
        $d['marks'] = $mks = $this->exam->getMark($wh);
        $d['exr'] = $exr = $this->exam->getRecord($wh)->first();
        $d['my_class'] = $mc = $this->my_class->find($exr->my_class_id);
        $d['section_id'] = $exr->section_id;
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex'.$exam->semester;
        $d['sr'] = $sr =$this->student->getRecord(['user_id' => $student_id])->first();
        $d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        $d['subjects'] = $this->my_class->findSubjectByClass($mc->id);

        $d['ct'] = $ct = $d['class_type']->code;
        $d['year'] = $year;
        $d['student_id'] = $student_id;
        $d['exam_id'] = $exam_id;

        $d['skills'] = $this->exam->getSkillByClassType() ?: NULL;
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });

        //$d['mark_type'] = Qs::getMarkType($ct);

        return view('pages.support_team.marks.print.index', $d);
    }

    public function selector(MarkSelector $req)
    {
        // Nouvelle logique : gérer les différents types d'évaluation
        $evaluationType = $req->evaluation_type;
        $examId = null;
        $assignmentId = null;
        
        if ($evaluationType === 'examen') {
            // Pour les examens : exam_id obligatoire
            $examId = $req->exam_id;
            if (!$examId) {
                return back()->with('pop_error', 'Veuillez sélectionner un examen.');
            }
        } elseif ($evaluationType === 'devoir') {
            // Pour les devoirs : assignment_id obligatoire
            $assignmentId = $req->assignment_id;
            if (!$assignmentId) {
                return back()->with('pop_error', 'Veuillez sélectionner un devoir.');
            }
            
            // Rediriger vers l'interface de notation des devoirs
            return $this->handleAssignmentMarks($req);
        } elseif ($evaluationType === 'interrogation') {
            // Pour les interrogations : créer un examen temporaire ou utiliser l'interface classique
            // avec des paramètres spéciaux pour les interrogations
            return $this->handleInterrogationMarks($req);
        }
        
        $data = $req->only(['my_class_id', 'section_id', 'subject_id']);
        $data['exam_id'] = $examId;
        
        $d2 = $req->only(['my_class_id', 'section_id']);
        $d2['exam_id'] = $examId;
        
        // Si section_id est vide, on trouve automatiquement une section avec des étudiants
        if (empty($req->section_id)) {
            $sectionWithStudents = $this->findSectionWithStudents($req->my_class_id);
            if ($sectionWithStudents) {
                $data['section_id'] = $d2['section_id'] = $sectionWithStudents;
                // IMPORTANT: Mettre à jour la request pour que only() récupère la bonne valeur
                $req->merge(['section_id' => $sectionWithStudents]);
            }
        }
        
        $d = $req->only(['my_class_id', 'section_id']);
        $d['session'] = $data['year'] = $d2['year'] = $this->year;

        $students = $this->student->getRecord($d)->get();
        if($students->count() < 1){
            return back()->with('pop_error', __('msg.rnf'));
        }

        foreach ($students as $s){
            $data['student_id'] = $d2['student_id'] = $s->user_id;
            $this->exam->createMark($data);
            $this->exam->createRecord($d2);
        }

        return redirect()->route('marks.manage', [$req->exam_id, $req->my_class_id, $data['section_id'], $req->subject_id]);
    }

    public function manage($exam_id, $class_id, $section_id, $subject_id)
    {
        $d = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $d['m'] = $this->exam->getMark($d);
        $d['marks'] = \App\Models\Mark::where([
            'exam_id' => $exam_id, 
            'my_class_id' => $class_id, 
            'section_id' => $section_id, 
            'subject_id' => $subject_id,
            'year' => $this->year
        ])->with(['user', 'user.student_record'])->get();

        if($d['marks']->count() < 1){
            return redirect()->route('marks.index')->with('flash_danger', __('msg.srnf'));
        }

        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['subjects'] = $this->my_class->findSubjectByClass($class_id);
        $d['sections'] = $this->my_class->getClassSections($class_id);
        $d['selected'] = true;
        $d['class_type'] = $this->my_class->findTypeByClass($class_id);

        // Ajouter la configuration des cotes RDC
        $d['grade_config'] = \App\Models\SubjectGradeConfig::getConfig($class_id, $subject_id, $this->year);
        
        // Récupérer le type d'évaluation depuis la session (si provient d'une interrogation)
        $d['evaluation_type'] = session('evaluation_type', null);
        $d['evaluation_period'] = session('evaluation_period', null);
        $d['interrogation_max_score'] = session('interrogation_max_score', null);
        
        // Déterminer si c'est un examen de période ou de semestre
        $exam = $this->exam->find($exam_id);
        
        // Si c'est une interrogation, afficher l'interface de période
        if ($d['evaluation_type'] === 'interrogation') {
            $d['is_semester_exam'] = false;
            $d['current_period'] = $d['evaluation_period'];
        } else {
            $d['is_semester_exam'] = $exam && $exam->semester ? true : false;
            $d['current_semester'] = $exam ? $exam->semester : null;
        }

        return view('pages.support_team.marks.manage', $d);
    }

    public function update(Request $req, $exam_id, $class_id, $section_id, $subject_id)
    {
        $p = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'subject_id' => $subject_id, 'year' => $this->year];

        $d = $d3 = $all_st_ids = [];

        $exam = $this->exam->find($exam_id);
        $marks = $this->exam->getMark($p);
        $class_type = $this->my_class->findTypeByClass($class_id);

        $mks = $req->all();
        
        // Détecter le type d'évaluation (nouveau système RDC)
        $evaluationType = $req->session()->get('evaluation_type', null);
        $evaluationPeriod = $req->session()->get('evaluation_period', null);

        /** Traitement selon le type d'évaluation **/
        foreach($marks->sortBy('user.name') as $mk)
        {
            $all_st_ids[] = $mk->student_id;
            $d = [];

            // NOUVEAU SYSTÈME RDC - INTERROGATIONS
            if ($evaluationType === 'interrogation' && $evaluationPeriod) {
                // Mise à jour uniquement de la colonne de période concernée
                $periodColumn = 't' . $evaluationPeriod;
                $fieldName = $periodColumn . '_' . $mk->id;
                
                if (isset($mks[$fieldName])) {
                    $d[$periodColumn] = $mks[$fieldName];
                }
            }
            // NOUVEAU SYSTÈME RDC - EXAMENS SEMESTRIELS
            elseif ($exam && $exam->semester) {
                // Mise à jour de la colonne d'examen semestriel
                $examColumn = 's' . $exam->semester . '_exam';
                $fieldName = $examColumn . '_' . $mk->id;
                
                if (isset($mks[$fieldName])) {
                    $d[$examColumn] = $mks[$fieldName];
                }
            }
            // ANCIEN SYSTÈME (fallback pour compatibilité)
            else {
                if(isset($mks['t1_'.$mk->id])) $d['t1'] = $t1 = $mks['t1_'.$mk->id];
                if(isset($mks['t2_'.$mk->id])) $d['t2'] = $t2 = $mks['t2_'.$mk->id];
                if(isset($mks['tca_'.$mk->id])) {
                    $d['tca'] = $mks['tca_'.$mk->id];
                } elseif(isset($t1) && isset($t2)) {
                    $d['tca'] = $tca = $t1 + $t2;
                }
                if(isset($mks['exm_'.$mk->id])) $d['exm'] = $exm = $mks['exm_'.$mk->id];

                // Calcul ancien système
                if(isset($tca) && isset($exm) && $exam) {
                    $total = $tca + $exm;
                    $d['tex'.$exam->semester] = $total;

                    if($total > 100){
                        $d['tex'.$exam->semester] = $d['t1'] = $d['t2'] = $d['t3'] = $d['t4'] = $d['tca'] = $d['exm'] = NULL;
                    }

                    $grade = $this->mark->getGrade($total, $class_type->id);
                    $d['grade_id'] = $grade ? $grade->id : NULL;
                }
            }

            // Mise à jour uniquement si des données existent
            if (!empty($d)) {
                $this->exam->updateMark($mk->id, $d);
            }
        }

        /** Sub Position Begin  **/

        foreach($marks->sortBy('user.name') as $mk)
        {

            $d2['sub_pos'] = $this->mark->getSubPos($mk->student_id, $exam, $class_id, $subject_id, $this->year);

            $this->exam->updateMark($mk->id, $d2);
        }

        /*Sub Position End*/

        /* Exam Record Update */

        unset( $p['subject_id'] );

        foreach ($all_st_ids as $st_id) {

            $p['student_id'] =$st_id;
            $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id, $class_id, $this->year);
            $d3['ave'] = $this->mark->getExamAvgTerm($exam, $st_id, $class_id, $section_id, $this->year);
            $d3['class_ave'] = $this->mark->getClassAvg($exam, $class_id, $this->year);
            $d3['pos'] = $this->mark->getPos($st_id, $exam, $class_id, $section_id, $this->year);

            $this->exam->updateRecord($p, $d3);
        }
        /*Exam Record End*/

       return Qs::jsonUpdateOk();
    }

    public function batch_fix()
    {
        $d['exams'] = $this->exam->getExam(['year' => $this->year]);
        $d['my_classes'] = $this->my_class->all();
        $d['sections'] = $this->my_class->getAllSections();
        $d['selected'] = false;

        return view('pages.support_team.marks.batch_fix', $d);
    }

    public function batch_update(Request $req): \Illuminate\Http\JsonResponse
    {
        $correction_type = $req->correction_type ?? 'exam';
        $class_id = $req->my_class_id;
        $section_id = $req->section_id;

        // Selon le type de correction
        switch ($correction_type) {
            case 'period':
                return $this->batchFixPeriod($class_id, $section_id, $req->period);
            case 'semester':
                return $this->batchFixSemester($class_id, $section_id, $req->semester);
            case 'exam':
            default:
                return $this->batchFixExam($class_id, $section_id, $req->exam_id);
        }
    }

    /**
     * Correction en lot pour une période (P1, P2, P3, P4)
     */
    protected function batchFixPeriod($class_id, $section_id, $period = null): \Illuminate\Http\JsonResponse
    {
        $periods = $period ? [$period] : [1, 2, 3, 4];
        $count = 0;

        // Récupérer les étudiants de la classe
        $students = \App\Models\StudentRecord::where('my_class_id', $class_id)
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->where('session', $this->year)
            ->get();

        foreach ($students as $student) {
            // Recalculer la moyenne de période pour chaque matière
            \App\Helpers\PeriodCalculator::updateAllPeriodAveragesForStudent(
                $student->user_id,
                $class_id,
                $student->section_id,
                $this->year
            );
            $count++;
        }

        return response()->json([
            'ok' => true,
            'msg' => "Moyennes de période recalculées pour {$students->count()} étudiants."
        ]);
    }

    /**
     * Correction en lot pour un semestre (S1, S2)
     */
    protected function batchFixSemester($class_id, $section_id, $semester = null): \Illuminate\Http\JsonResponse
    {
        $semesters = $semester ? [$semester] : [1, 2];
        
        // Récupérer les étudiants de la classe
        $students = \App\Models\StudentRecord::where('my_class_id', $class_id)
            ->when($section_id, fn($q) => $q->where('section_id', $section_id))
            ->where('session', $this->year)
            ->get();

        foreach ($students as $student) {
            // Recalculer toutes les moyennes de période d'abord
            \App\Helpers\PeriodCalculator::updateAllPeriodAveragesForStudent(
                $student->user_id,
                $class_id,
                $student->section_id,
                $this->year
            );
        }

        return response()->json([
            'ok' => true,
            'msg' => "Moyennes semestrielles recalculées pour {$students->count()} étudiants."
        ]);
    }

    /**
     * Correction en lot pour un examen (logique originale)
     */
    protected function batchFixExam($class_id, $section_id, $exam_id): \Illuminate\Http\JsonResponse
    {
        if (!$exam_id) {
            return response()->json(['ok' => false, 'msg' => 'Veuillez sélectionner un examen.']);
        }

        $w = ['exam_id' => $exam_id, 'my_class_id' => $class_id, 'section_id' => $section_id, 'year' => $this->year];

        $exam = $this->exam->find($exam_id);
        $exrs = $this->exam->getRecord($w);
        $marks = $this->exam->getMark($w);

        /** Marks Fix Begin **/
        $class_type = $this->my_class->findTypeByClass($class_id);
        $tex = 'tex'.$exam->semester;

        foreach($marks as $mk){
            $total = $mk->$tex;
            $d['grade_id'] = $this->mark->getGrade($total, $class_type->id);
            $this->exam->updateMark($mk->id, $d);
        }
        /* Marks Fix End*/

        /** Exam Record Update  **/
        foreach($exrs as $exr){
            $st_id = $exr->student_id;

            $d3['total'] = $this->mark->getExamTotalTerm($exam, $st_id, $class_id, $this->year);
            $d3['ave'] = $this->mark->getExamAvgTerm($exam, $st_id, $class_id, $section_id, $this->year);
            $d3['class_ave'] = $this->mark->getClassAvg($exam, $class_id, $this->year);
            $d3['pos'] = $this->mark->getPos($st_id, $exam, $class_id, $section_id, $this->year);

            $this->exam->updateRecord(['id' => $exr->id], $d3);
        }
        /** END Exam Record Update END **/

        return Qs::jsonUpdateOk();
    }

    public function comment_update(Request $req, $exr_id)
    {
        $d = Qs::userIsTeamSA() ? $req->only(['t_comment', 'p_comment']) : $req->only(['t_comment']);

        $this->exam->updateRecord(['id' => $exr_id], $d);
        return Qs::jsonUpdateOk();
    }

    public function skills_update(Request $req, $skill, $exr_id)
    {
        $d = [];
        if($skill == 'AF' || $skill == 'PS'){
            $sk = strtolower($skill);
            $d[$skill] = implode(',', $req->$sk);
        }

        $this->exam->updateRecord(['id' => $exr_id], $d);
        return Qs::jsonUpdateOk();
    }

    public function bulk($class_id = NULL, $section_id = NULL)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;

        if($class_id && $section_id){
            $d['sections'] = $this->my_class->getAllSections()->where('my_class_id', $class_id);
            $d['students'] = $st = $this->student->getRecord(['my_class_id' => $class_id, 'section_id' => $section_id])->get()->sortBy('user.name');
            if($st->count() < 1){
                return redirect()->route('marks.bulk')->with('flash_danger', __('msg.srnf'));
            }
            $d['selected'] = true;
            $d['my_class_id'] = $class_id;
            $d['section_id'] = $section_id;
        }

        return view('pages.support_team.marks.bulk', $d);
    }

    public function bulk_select(Request $req)
    {
        return redirect()->route('marks.bulk', [$req->my_class_id, $req->section_id]);
    }

    public function tabulation(Request $request)
    {
        $d['my_classes'] = $this->my_class->all();
        $d['selected'] = false;
        $d['year'] = $this->year;
        $d['rankings'] = []; // Initialiser rankings par défaut

        // Récupérer les paramètres depuis l'URL
        $evaluationType = $request->query('evaluation_type');
        $period = $request->query('period');
        $semester = $request->query('semester');
        $classId = $request->query('class_id');
        $sectionId = $request->query('section_id');

        if($evaluationType && $classId && $sectionId){

            $d['selected'] = true;
            $d['evaluation_type'] = $evaluationType;
            $d['my_class_id'] = $classId;
            $d['section_id'] = $sectionId;
            $d['my_class'] = $this->my_class->find($classId);
            $d['section'] = $this->my_class->findSection($sectionId);

            // Récupérer les étudiants
            $d['students'] = \App\Models\StudentRecord::where('my_class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('session', $this->year)
                ->with('user')
                ->get();

            // Récupérer les matières
            $d['subjects'] = $this->my_class->findSubjectByClass($classId);
            $d['sections'] = $this->my_class->getAllSections();

            // Utiliser le service de proclamation pour les calculs
            $proclamationService = app(\App\Services\ImprovedProclamationCalculationService::class);
            
            if($evaluationType === 'period'){
                $d['period'] = $period;
                $d['title'] = "Période $period";
                
                $rankings = [];
                foreach($d['students'] as $student) {
                    $average = $proclamationService->calculateStudentPeriodAverage(
                        $student->user_id,
                        $classId,
                        $period,
                        $this->year
                    );
                    
                    if($average) {
                        $rankings[$student->user_id] = [
                            'overall_percentage' => $average['overall_percentage'],
                            'overall_points' => $average['overall_points'],
                            'subject_averages' => $average['subject_averages']
                        ];
                    }
                }
                
                $d['rankings'] = $rankings;
                
            } elseif($evaluationType === 'semester'){
                $d['semester'] = $semester;
                $d['title'] = "Semestre $semester";
                
                $rankings = [];
                foreach($d['students'] as $student) {
                    $average = $proclamationService->calculateStudentSemesterAverage(
                        $student->user_id,
                        $classId,
                        $semester,
                        $this->year
                    );
                    
                    if($average) {
                        $rankings[$student->user_id] = [
                            'overall_percentage' => $average['overall_percentage'],
                            'overall_points' => $average['overall_points'],
                            'subject_averages' => $average['subject_averages']
                        ];
                    }
                }
                
                $d['rankings'] = $rankings;
            } else {
                // Type d'évaluation invalide
                $d['rankings'] = [];
                $d['title'] = "Type invalide";
            }
        }

        return view('pages.support_team.marks.tabulation.index', $d);
    }

    public function print_tabulation($exam_id, $class_id, $section_id)
    {
        $wh = ['my_class_id' => $class_id, 'section_id' => $section_id, 'exam_id' => $exam_id, 'year' => $this->year];

        $sub_ids = $this->mark->getSubjectIDs($wh);
        $st_ids = $this->mark->getStudentIDs($wh);

        if(count($sub_ids) < 1 OR count($st_ids) < 1) {
            return Qs::goWithDanger('marks.tabulation', __('msg.srnf'));
        }

        $d['subjects'] = $this->my_class->getSubjectsByIDs($sub_ids);
        $d['students'] = $this->student->getRecordByUserIDs($st_ids)->get()->sortBy('user.name');

        $d['my_class_id'] = $class_id;
        $d['exam_id'] = $exam_id;
        $d['year'] = $this->year;
        $wh = ['exam_id' => $exam_id, 'my_class_id' => $class_id];
        $d['marks'] = $mks = $this->exam->getMark($wh);
        $d['exr'] = $exr = $this->exam->getRecord($wh);

        $d['my_class'] = $mc = $this->my_class->find($class_id);
        $d['section']  = $this->my_class->findSection($section_id);
        $d['ex'] = $exam = $this->exam->find($exam_id);
        $d['tex'] = 'tex'.$exam->semester;
        $d['s'] = Setting::all()->flatMap(function($s){
            return [$s->type => $s->description];
        });
        //$d['class_type'] = $this->my_class->findTypeByClass($mc->id);
        //$d['ct'] = $ct = $d['class_type']->code;

        return view('pages.support_team.marks.tabulation.print', $d);
    }

    public function tabulation_select(Request $req)
    {
        // Validation
        $req->validate([
            'evaluation_type' => 'required|in:period,semester',
            'my_class_id' => 'required|integer',
            'section_id' => 'required|integer',
        ]);

        // Validation conditionnelle
        if ($req->evaluation_type === 'period') {
            $req->validate(['period' => 'required|integer|min:1|max:4']);
        } elseif ($req->evaluation_type === 'semester') {
            $req->validate(['semester' => 'required|integer|min:1|max:2']);
        }

        // Redirection avec query parameters
        $params = [
            'evaluation_type' => $req->evaluation_type,
            'class_id' => $req->my_class_id,
            'section_id' => $req->section_id
        ];
        
        if ($req->evaluation_type === 'period') {
            $params['period'] = $req->period;
        } else {
            $params['semester'] = $req->semester;
        }

        return redirect()->route('marks.tabulation', $params);
    }

    protected function verifyStudentExamYear($student_id, $year = null)
    {
        $years = $this->exam->getExamYears($student_id);
        $student_exists = $this->student->exists($student_id);

        if(!$year){
            if($student_exists && $years->count() > 0)
            {
                $d =['years' => $years, 'student_id' => Qs::hash($student_id)];

                return view('pages.support_team.marks.select_year', $d);
            }

            return $this->noStudentRecord();
        }

        return ($student_exists && $years->contains('year', $year)) ? true  : false;
    }

    protected function noStudentRecord()
    {
        // Si c'est un étudiant, afficher une page informative au lieu de rediriger
        if(Qs::userIsStudent()) {
            return view('pages.support_team.marks.no_records');
        }
        
        return redirect()->route('dashboard')->with('flash_danger', __('msg.srnf'));
    }

    protected function checkPinVerified($st_id)
    {
        return Session::has('pin_verified') && Session::get('pin_verified') == $st_id;
    }

    /**
     * Handle assignment marks (devoirs)
     */
    protected function handleAssignmentMarks($req)
    {
        // Pour l'instant, rediriger vers l'interface des devoirs
        // Plus tard, on pourra créer une interface spécifique
        $assignmentId = $req->assignment_id;
        $classId = $req->my_class_id;
        $subjectId = $req->subject_id;
        
        // Rediriger vers l'interface de notation des devoirs
        return redirect()->route('assignments.show', $assignmentId)
                        ->with('flash_info', 'Redirection vers l\'interface de notation des devoirs.');
    }

    /**
     * Handle interrogation marks (interrogations)
     */
    protected function handleInterrogationMarks($req)
    {
        // Pour les interrogations, on utilise l'interface classique des notes
        // mais avec des paramètres spéciaux pour identifier que c'est une interrogation
        
        $classId = $req->my_class_id;
        $subjectId = $req->subject_id;
        $period = $req->period;
        $sectionId = $req->section_id;
        $interrogationMaxScore = $req->interrogation_max_score;
        
        // Si section_id est vide, trouver automatiquement une section avec des étudiants
        if (empty($sectionId)) {
            $sectionId = $this->findSectionWithStudents($classId);
            if (!$sectionId) {
                return back()->with('pop_error', 'Aucune section avec des étudiants trouvée pour cette classe.');
            }
        }
        
        // Trouver ou créer un examen générique pour les interrogations de cette période
        $interrogationExam = $this->findOrCreateInterrogationExam($period, $interrogationMaxScore);
        
        if ($interrogationExam) {
            // Rediriger vers l'interface classique avec l'examen d'interrogation
            // Ajouter un paramètre pour indiquer que c'est une interrogation
            return redirect()->route('marks.manage', [
                'exam' => $interrogationExam->id,
                'class' => $classId,
                'section' => $sectionId,
                'subject' => $subjectId
            ])
            ->with('evaluation_type', 'interrogation')
            ->with('evaluation_period', $period)
            ->with('interrogation_max_score', $interrogationMaxScore)
            ->with('flash_info', 'Interface de saisie des notes d\'interrogation (Période ' . $period . ' - /' . $interrogationMaxScore . ')');
        }
        
        return back()->with('pop_error', 'Impossible de créer l\'interface d\'interrogation.');
    }

    /**
     * Find or create a generic exam for interrogations
     */
    protected function findOrCreateInterrogationExam($period, $maxScore = null)
    {
        $semester = $period <= 2 ? 1 : 2; // P1-P2 = S1, P3-P4 = S2
        
        // D'abord chercher par la contrainte unique réelle (semester + year)
        $exam = \App\Models\Exam::where([
            'semester' => $semester,
            'year' => $this->year
        ])->first();
        
        if ($exam) {
            // Un examen existe déjà pour ce semestre/année, le réutiliser
            return $exam;
        }
        
        // Sinon créer un nouvel examen pour les interrogations
        try {
            $examName = "Interrogations Période {$period}";
            $description = "Examen automatique pour les interrogations de la période {$period}";
            if ($maxScore) {
                $description .= " (Notée sur {$maxScore})";
            }
            
            $exam = \App\Models\Exam::create([
                'name' => $examName,
                'year' => $this->year,
                'semester' => $semester,
                'category_id' => 1, // Catégorie par défaut
                'description' => $description
            ]);
            
            return $exam;
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Si création échoue à cause de la contrainte, récupérer l'existant
            return \App\Models\Exam::where([
                'semester' => $semester,
                'year' => $this->year
            ])->first();
        }
    }

}
