<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Payment;
use App\Models\Exam;
use App\Models\SchoolEvent;
use App\Models\BookRequest;
use App\Models\Notice;
use App\Helpers\Qs;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_students' => StudentRecord::where('session', Qs::getSetting('current_session'))->count(),
            'total_teachers' => User::where('user_type', 'teacher')->count(),
            'total_admins' => User::whereIn('user_type', ['admin', 'super_admin'])->count(),
            'total_parents' => User::where('user_type', 'parent')->count(),
            'total_classes' => MyClass::count(),
            'total_staff' => User::whereIn('user_type', ['teacher', 'admin', 'super_admin', 'librarian', 'accountant'])->count(),
        ];

        // Paiements du mois en cours
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Vérifier si la table payment_records existe, sinon utiliser payments
        $payments_this_month = 0;
        $payments_count = 0;
        
        try {
            if (DB::getSchemaBuilder()->hasTable('payment_records')) {
                $payments_this_month = DB::table('payment_records')
                    ->whereMonth('paid_at', $currentMonth)
                    ->whereYear('paid_at', $currentYear)
                    ->sum('amt_paid');
                    
                $payments_count = DB::table('payment_records')
                    ->whereMonth('paid_at', $currentMonth)
                    ->whereYear('paid_at', $currentYear)
                    ->count();
            } else {
                $payments_this_month = Payment::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->sum('amount');
                    
                $payments_count = Payment::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count();
            }
        } catch (\Exception $e) {
            \Log::error('Erreur calcul paiements: ' . $e->getMessage());
        }

        // Événements à venir (7 prochains jours)
        $upcoming_events = SchoolEvent::where('event_date', '>=', Carbon::now())
            ->where('event_date', '<=', Carbon::now()->addDays(7))
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        // Dernières annonces
        $recent_notices = Notice::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Demandes de livres en attente
        $pending_book_requests = BookRequest::where('status', BookRequest::STATUS_PENDING)
            ->with(['student', 'book'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques par classe
        $students_by_class = StudentRecord::select('my_class_id', DB::raw('count(*) as total'))
            ->where('session', Qs::getSetting('current_session'))
            ->groupBy('my_class_id')
            ->with('my_class')
            ->get();

        // Répartition par genre
        $students_by_gender = StudentRecord::join('users', 'student_records.user_id', '=', 'users.id')
            ->select('users.gender', DB::raw('count(*) as total'))
            ->where('student_records.session', Qs::getSetting('current_session'))
            ->groupBy('users.gender')
            ->get();

        // Examens à venir
        $upcoming_exams = Exam::where('year', Qs::getSetting('current_session'))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Activité récente (derniers utilisateurs créés)
        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Graphique des paiements (6 derniers mois)
        $payment_chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $amount = 0;
            
            try {
                if (DB::getSchemaBuilder()->hasTable('payment_records')) {
                    $amount = DB::table('payment_records')
                        ->whereMonth('paid_at', $month->month)
                        ->whereYear('paid_at', $month->year)
                        ->sum('amt_paid');
                } else {
                    $amount = Payment::whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->sum('amount');
                }
            } catch (\Exception $e) {
                \Log::error('Erreur graphique paiements: ' . $e->getMessage());
            }
            
            $payment_chart[] = [
                'month' => $month->format('M Y'),
                'amount' => $amount
            ];
        }

        return view('pages.super_admin.dashboard', compact(
            'stats',
            'payments_this_month',
            'payments_count',
            'upcoming_events',
            'recent_notices',
            'pending_book_requests',
            'students_by_class',
            'students_by_gender',
            'upcoming_exams',
            'recent_users',
            'payment_chart'
        ));
    }
}
