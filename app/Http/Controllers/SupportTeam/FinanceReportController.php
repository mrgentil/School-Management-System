<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceReportController extends Controller
{
    protected $year;

    public function __construct()
    {
        // Accessible aux admins et comptables
        $this->middleware(function ($request, $next) {
            if (!Qs::userIsTeamSA() && !Qs::userIsTeamAccount()) {
                return redirect()->route('dashboard')->with('flash_danger', 'Accès non autorisé');
            }
            return $next($request);
        });
        $this->year = Qs::getCurrentSession();
    }

    /**
     * Dashboard financier
     */
    public function index()
    {
        // Statistiques globales
        $stats = $this->getGlobalStats();

        // Paiements par mois
        $monthlyData = $this->getMonthlyPayments();

        // Paiements par classe
        $classData = $this->getPaymentsByClass();

        // Élèves en retard de paiement
        $overdueStudents = $this->getOverdueStudents();

        // Derniers paiements
        $recentPayments = PaymentRecord::with(['student', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages.support_team.finance.dashboard', compact(
            'stats', 'monthlyData', 'classData', 'overdueStudents', 'recentPayments'
        ));
    }

    /**
     * Statistiques globales
     */
    protected function getGlobalStats()
    {
        $totalPaid = PaymentRecord::sum('amt_paid');
        $totalBalance = PaymentRecord::sum('balance');
        $totalExpected = $totalPaid + $totalBalance;
        
        $studentsWithBalance = PaymentRecord::where('balance', '>', 0)->distinct('student_id')->count('student_id');
        $studentsFullyPaid = PaymentRecord::where('balance', 0)->distinct('student_id')->count('student_id');

        $thisMonth = PaymentRecord::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amt_paid');

        $lastMonth = PaymentRecord::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amt_paid');

        $monthlyGrowth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;

        return [
            'total_expected' => $totalExpected,
            'total_paid' => $totalPaid,
            'total_balance' => $totalBalance,
            'collection_rate' => $totalExpected > 0 ? round(($totalPaid / $totalExpected) * 100, 1) : 0,
            'students_with_balance' => $studentsWithBalance,
            'students_fully_paid' => $studentsFullyPaid,
            'this_month' => $thisMonth,
            'monthly_growth' => $monthlyGrowth,
        ];
    }

    /**
     * Paiements par mois
     */
    protected function getMonthlyPayments()
    {
        $data = PaymentRecord::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amt_paid) as total')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $result = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $result[] = [
                'month' => $months[$i - 1],
                'amount' => $data[$i] ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Paiements par classe
     */
    protected function getPaymentsByClass()
    {
        $classes = MyClass::all();
        $data = [];

        foreach ($classes as $class) {
            $studentIds = StudentRecord::where('my_class_id', $class->id)
                ->where('session', $this->year)
                ->pluck('user_id');

            $paid = PaymentRecord::whereIn('student_id', $studentIds)->sum('amt_paid');
            $balance = PaymentRecord::whereIn('student_id', $studentIds)->sum('balance');

            if ($paid > 0 || $balance > 0) {
                $data[] = [
                    'class' => $class->name,
                    'paid' => $paid,
                    'balance' => $balance,
                    'rate' => ($paid + $balance) > 0 ? round(($paid / ($paid + $balance)) * 100, 1) : 0,
                ];
            }
        }

        usort($data, fn($a, $b) => $b['paid'] <=> $a['paid']);

        return $data;
    }

    /**
     * Élèves en retard de paiement
     */
    protected function getOverdueStudents()
    {
        return PaymentRecord::with(['student', 'payment'])
            ->where('balance', '>', 0)
            ->orderBy('balance', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($record) {
                return [
                    'student' => $record->student,
                    'balance' => $record->balance,
                    'paid' => $record->amt_paid,
                    'title' => $record->payment->title ?? 'Frais scolaires',
                ];
            });
    }

    /**
     * Rapport détaillé par classe
     */
    public function byClass(Request $request)
    {
        $classes = MyClass::orderBy('name')->get();
        $selectedClass = $request->get('class_id');
        $students = collect();
        $classStats = null;

        if ($selectedClass) {
            $studentIds = StudentRecord::where('my_class_id', $selectedClass)
                ->where('session', $this->year)
                ->pluck('user_id');

            $students = PaymentRecord::with(['student'])
                ->whereIn('student_id', $studentIds)
                ->get()
                ->groupBy('student_id')
                ->map(function ($records) {
                    $first = $records->first();
                    return [
                        'student' => $first->student,
                        'total_paid' => $records->sum('amt_paid'),
                        'total_balance' => $records->sum('balance'),
                        'payments_count' => $records->count(),
                    ];
                });

            $classStats = [
                'total_students' => $students->count(),
                'total_paid' => $students->sum('total_paid'),
                'total_balance' => $students->sum('total_balance'),
                'fully_paid' => $students->where('total_balance', 0)->count(),
            ];
        }

        return view('pages.support_team.finance.by_class', compact('classes', 'selectedClass', 'students', 'classStats'));
    }

    /**
     * Export des données
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'all');

        if ($type === 'overdue') {
            $data = $this->getOverdueStudents();
            $filename = 'Retards_Paiement_' . date('Y-m-d') . '.csv';
        } else {
            $data = PaymentRecord::with(['student', 'payment'])
                ->orderBy('created_at', 'desc')
                ->get();
            $filename = 'Paiements_' . date('Y-m-d') . '.csv';
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data, $type) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            if ($type === 'overdue') {
                fputcsv($file, ['Élève', 'Montant Payé', 'Solde Restant', 'Type'], ';');
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item['student']->name ?? 'N/A',
                        $item['paid'],
                        $item['balance'],
                        $item['title'],
                    ], ';');
                }
            } else {
                fputcsv($file, ['Date', 'Élève', 'Montant', 'Solde', 'Référence'], ';');
                foreach ($data as $record) {
                    fputcsv($file, [
                        $record->created_at->format('d/m/Y'),
                        $record->student->name ?? 'N/A',
                        $record->amt_paid,
                        $record->balance,
                        $record->ref_no ?? '',
                    ], ';');
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
