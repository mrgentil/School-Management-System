<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Helpers\Qs;
use App\Models\User;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\Mark;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;
use PDF;

class PrintCenterController extends Controller
{
    public function __construct()
    {
        // Accès: Admin, Enseignant, Comptable
        $this->middleware(function ($request, $next) {
            if (!\Auth::check()) {
                return redirect()->route('login');
            }
            
            $userType = \Auth::user()->user_type;
            $allowed = ['super_admin', 'admin', 'teacher', 'accountant'];
            
            if (!in_array($userType, $allowed)) {
                return redirect()->route('dashboard')->with('flash_error', 'Accès non autorisé');
            }
            
            return $next($request);
        });
    }

    /**
     * Page principale du centre d'impression
     */
    public function index()
    {
        $user = \Auth::user();
        $year = Qs::getCurrentSession();

        // Filtrer les classes selon le rôle
        if ($user->user_type == 'teacher') {
            // Enseignant: ses classes (titulaire + matières enseignées)
            $teacherSubjectIds = Subject::where('teacher_id', $user->id)->pluck('id');
            $teachingClassIds = Mark::whereIn('subject_id', $teacherSubjectIds)
                ->where('year', $year)
                ->distinct()
                ->pluck('my_class_id');
            
            $classes = MyClass::where('teacher_id', $user->id)
                ->orWhereIn('id', $teachingClassIds)
                ->with(['class_type'])
                ->get();
            
            $subjects = Subject::where('teacher_id', $user->id)->get();
        } else {
            // Admin/Comptable: toutes les classes
            $classes = MyClass::with(['class_type'])->get();
            $subjects = Subject::all();
        }

        $userType = $user->user_type;

        return view('pages.support_team.print_center.index', compact('classes', 'year', 'userType', 'subjects'));
    }

    /**
     * Liste des élèves par classe (PDF)
     */
    public function classList(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:my_classes,id']);

        $class = MyClass::with(['class_type', 'teacher'])->find($request->class_id);
        $year = Qs::getCurrentSession();

        $students = StudentRecord::where('my_class_id', $class->id)
            ->where('session', $year)
            ->with(['user'])
            ->get()
            ->sortBy('user.name');

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.class_list', [
            'class' => $class,
            'students' => $students,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
            'schoolAddress' => Qs::getSetting('system_address'),
        ]);

        return $pdf->stream("liste_{$class->name}_{$year}.pdf");
    }

    /**
     * État de paiement par classe (PDF)
     */
    public function paymentStatus(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:my_classes,id']);

        $class = MyClass::find($request->class_id);
        $year = Qs::getCurrentSession();

        $students = StudentRecord::where('my_class_id', $class->id)
            ->where('session', $year)
            ->with(['user'])
            ->get();

        $paymentData = [];
        foreach ($students as $student) {
            $payments = PaymentRecord::whereHas('payment', function($q) use ($year) {
                $q->where('year', $year);
            })->where('student_id', $student->user_id)->get();

            $paymentData[] = [
                'student' => $student->user,
                'total_paid' => $payments->sum('amt_paid'),
                'balance' => $payments->sum('balance'),
            ];
        }

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.payment_status', [
            'class' => $class,
            'paymentData' => $paymentData,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
        ]);

        return $pdf->stream("paiements_{$class->name}_{$year}.pdf");
    }

    /**
     * Fiche de notes par matière (PDF)
     */
    public function gradeSheet(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:my_classes,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $class = MyClass::find($request->class_id);
        $subject = Subject::find($request->subject_id);
        $year = Qs::getCurrentSession();

        $marks = Mark::where('my_class_id', $class->id)
            ->where('subject_id', $subject->id)
            ->where('year', $year)
            ->with(['user'])
            ->get()
            ->sortBy('user.name');

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.grade_sheet', [
            'class' => $class,
            'subject' => $subject,
            'marks' => $marks,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
        ]);

        return $pdf->stream("notes_{$subject->name}_{$class->name}_{$year}.pdf");
    }

    /**
     * Attestation de scolarité (PDF)
     */
    public function certificate(Request $request)
    {
        $request->validate(['student_id' => 'required|exists:users,id']);

        $student = User::find($request->student_id);
        $record = StudentRecord::where('user_id', $student->id)
            ->where('session', Qs::getCurrentSession())
            ->with(['my_class'])
            ->first();

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.certificate', [
            'student' => $student,
            'record' => $record,
            'year' => Qs::getCurrentSession(),
            'schoolName' => Qs::getSetting('system_name'),
            'schoolAddress' => Qs::getSetting('system_address'),
            'date' => now()->format('d/m/Y'),
        ]);

        return $pdf->stream("attestation_{$student->name}.pdf");
    }

    /**
     * Cartes d'élèves par classe (PDF)
     */
    public function studentCards(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:my_classes,id']);

        $class = MyClass::find($request->class_id);
        $year = Qs::getCurrentSession();

        $students = StudentRecord::where('my_class_id', $class->id)
            ->where('session', $year)
            ->with(['user'])
            ->get()
            ->sortBy('user.name');

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.student_cards', [
            'class' => $class,
            'students' => $students,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream("cartes_{$class->name}_{$year}.pdf");
    }

    /**
     * Emploi du temps par classe (PDF)
     */
    public function timetable(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:my_classes,id']);

        $class = MyClass::with(['class_type'])->find($request->class_id);
        $year = Qs::getCurrentSession();

        $timetables = \App\Models\TimeTable::where('my_class_id', $class->id)
            ->with(['subject', 'teacher'])
            ->orderBy('time_start')
            ->get()
            ->groupBy('day');

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.timetable', [
            'class' => $class,
            'timetables' => $timetables,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream("emploi_du_temps_{$class->name}.pdf");
    }

    /**
     * Récapitulatif général (PDF)
     */
    public function summary(Request $request)
    {
        $year = Qs::getCurrentSession();

        $classes = MyClass::with(['class_type', 'teacher'])->get();
        $classStats = [];

        foreach ($classes as $class) {
            $studentCount = StudentRecord::where('my_class_id', $class->id)
                ->where('session', $year)
                ->count();

            if ($studentCount > 0) {
                $classStats[] = [
                    'class' => $class,
                    'students' => $studentCount,
                    'boys' => StudentRecord::where('my_class_id', $class->id)
                        ->where('session', $year)
                        ->whereHas('user', fn($q) => $q->where('gender', 'Male'))
                        ->count(),
                    'girls' => StudentRecord::where('my_class_id', $class->id)
                        ->where('session', $year)
                        ->whereHas('user', fn($q) => $q->where('gender', 'Female'))
                        ->count(),
                ];
            }
        }

        $pdf = PDF::loadView('pages.support_team.print_center.pdf.summary', [
            'classStats' => $classStats,
            'year' => $year,
            'schoolName' => Qs::getSetting('system_name'),
            'totalStudents' => StudentRecord::where('session', $year)->count(),
            'totalTeachers' => User::where('user_type', 'teacher')->count(),
        ]);

        return $pdf->stream("recapitulatif_{$year}.pdf");
    }
}
