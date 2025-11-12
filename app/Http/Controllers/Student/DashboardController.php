<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Models\BookRequest;
use App\Models\Attendance\Attendance;
use App\Models\LearningMaterial;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        // Statistiques générales
        $stats = $this->getGeneralStats($student);
        
        // Devoirs à venir (5 prochains)
        $upcomingAssignments = $this->getUpcomingAssignments($student);
        
        // Livres empruntés actuellement
        $borrowedBooks = $this->getBorrowedBooks($student);
        
        // Solde financier
        $financialSummary = $this->getFinancialSummary($student);
        
        // Présences du mois
        $attendanceStats = $this->getAttendanceStats($student);
        
        // Derniers supports pédagogiques (5 derniers)
        $recentMaterials = $this->getRecentMaterials($student);
        
        // Notifications récentes (10 dernières)
        $recentNotifications = $this->getRecentNotifications();
        
        // Messages non lus
        $unreadMessagesCount = $this->getUnreadMessagesCount();
        
        // Emploi du temps du jour
        $todaySchedule = $this->getTodaySchedule($student);

        return view('pages.student.dashboard', compact(
            'stats',
            'upcomingAssignments',
            'borrowedBooks',
            'financialSummary',
            'attendanceStats',
            'recentMaterials',
            'recentNotifications',
            'unreadMessagesCount',
            'todaySchedule',
            'student'
        ));
    }

    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats($student)
    {
        return [
            'total_assignments' => Assignment::where('my_class_id', $student->my_class_id)
                ->where('section_id', $student->section_id)
                ->where('status', 'active')
                ->count(),
            
            'pending_assignments' => Assignment::where('my_class_id', $student->my_class_id)
                ->where('section_id', $student->section_id)
                ->where('status', 'active')
                ->where('due_date', '>=', now())
                ->whereDoesntHave('submissions', function($query) use ($student) {
                    $query->where('student_id', $student->id);
                })
                ->count(),
            
            'borrowed_books' => BookRequest::where('student_id', auth()->id())
                ->whereIn('status', [BookRequest::STATUS_APPROVED, BookRequest::STATUS_BORROWED])
                ->count(),
            
            'unread_messages' => Message::where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->count(),
            
            'attendance_rate' => $this->calculateAttendanceRate($student),
        ];
    }

    /**
     * Calculer le taux de présence
     */
    private function calculateAttendanceRate($student)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $totalDays = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();
        
        if ($totalDays == 0) {
            return 100;
        }
        
        $presentDays = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->where('status', 'present')
            ->count();
        
        return round(($presentDays / $totalDays) * 100, 1);
    }

    /**
     * Obtenir les devoirs à venir
     */
    private function getUpcomingAssignments($student)
    {
        // Vérifier si l'étudiant a un my_class_id et section_id
        if (!$student->my_class_id || !$student->section_id) {
            return collect();
        }

        return Assignment::where('my_class_id', $student->my_class_id)
            ->where('section_id', $student->section_id)
            ->where('status', 'active')
            ->where('due_date', '>=', now())
            ->with(['subject'])
            ->withCount(['submissions' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Obtenir les livres empruntés
     */
    private function getBorrowedBooks($student)
    {
        return BookRequest::where('student_id', auth()->id())
            ->whereIn('status', [BookRequest::STATUS_APPROVED, BookRequest::STATUS_BORROWED])
            ->with('book')
            ->orderBy('expected_return_date', 'asc')
            ->get();
    }

    /**
     * Obtenir le résumé financier
     */
    private function getFinancialSummary($student)
    {
        $currentYear = Carbon::now()->year;
        
        $payments = $student->paymentRecords()
            ->whereYear('created_at', $currentYear)
            ->get();
        
        $totalAmount = $payments->sum('amount');
        $totalPaid = $payments->sum('amt_paid');
        $totalBalance = $payments->sum('balance');
        
        return [
            'total_amount' => $totalAmount,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'payment_status' => $totalBalance <= 0 ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid'),
        ];
    }

    /**
     * Obtenir les statistiques de présence
     */
    private function getAttendanceStats($student)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->get();
        
        return [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
            'total' => $attendances->count(),
        ];
    }

    /**
     * Obtenir les supports pédagogiques récents
     */
    private function getRecentMaterials($student)
    {
        // Récupérer les supports de la classe de l'étudiant ou les supports généraux
        return LearningMaterial::where(function($query) use ($student) {
                $query->where('class_id', $student->my_class_id)
                      ->orWhereNull('class_id');
            })
            ->with(['subject', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Obtenir les notifications récentes
     */
    private function getRecentNotifications()
    {
        return auth()->user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Obtenir le nombre de messages non lus
     */
    private function getUnreadMessagesCount()
    {
        return Message::where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    /**
     * Obtenir l'emploi du temps du jour
     */
    private function getTodaySchedule($student)
    {
        // Pour l'instant, retourner une collection vide
        // Cette fonctionnalité sera implémentée dans la phase 2
        return collect();
    }
}
