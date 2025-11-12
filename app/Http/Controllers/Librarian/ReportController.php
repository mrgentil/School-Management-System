<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('librarian');
    }

    public function index()
    {
        return view('pages.librarian.reports.index');
    }

    /**
     * Rapport des livres les plus empruntés
     */
    public function popularBooks(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->input('end_date', Carbon::now());

        $popularBooks = Book::withCount([
            'requests' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->whereIn('status', ['approved', 'borrowed', 'returned']);
            }
        ])
        ->having('requests_count', '>', 0)
        ->orderBy('requests_count', 'desc')
        ->take(20)
        ->get();

        return view('pages.librarian.reports.popular_books', compact('popularBooks', 'startDate', 'endDate'));
    }

    /**
     * Rapport des étudiants actifs
     */
    public function activeStudents(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->input('end_date', Carbon::now());

        $activeStudents = User::where('user_type', 'student')
            ->withCount([
                'bookRequests' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->having('book_requests_count', '>', 0)
            ->orderBy('book_requests_count', 'desc')
            ->take(50)
            ->get();

        return view('pages.librarian.reports.active_students', compact('activeStudents', 'startDate', 'endDate'));
    }

    /**
     * Rapport mensuel
     */
    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $stats = [
            'total_requests' => BookRequest::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count(),
            
            'approved' => BookRequest::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', 'approved')
                ->count(),
            
            'borrowed' => BookRequest::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', 'borrowed')
                ->count(),
            
            'returned' => BookRequest::whereMonth('updated_at', $month)
                ->whereYear('updated_at', $year)
                ->where('status', 'returned')
                ->count(),
            
            'rejected' => BookRequest::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('status', 'rejected')
                ->count(),
            
            'overdue' => BookRequest::where('status', 'borrowed')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where('due_date', '<', now())
                ->count(),
            
            'new_books' => Book::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count(),
            
            'total_penalties' => BookRequest::whereMonth('returned_at', $month)
                ->whereYear('returned_at', $year)
                ->sum('penalty_amount'),
        ];

        // Graphique par jour
        $dailyStats = BookRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('pages.librarian.reports.monthly', compact('stats', 'dailyStats', 'month', 'year'));
    }

    /**
     * Rapport d'inventaire
     */
    public function inventory()
    {
        $inventory = [
            'total_books' => Book::count(),
            'total_copies' => Book::sum('total_copies'),
            'available_copies' => Book::sum('available_copies'),
            'borrowed_copies' => Book::sum('total_copies') - Book::sum('available_copies'),
            
            'by_category' => Book::select('category', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_copies) as total'))
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get(),
            
            'low_stock' => Book::where('available_copies', '<=', 2)
                ->where('total_copies', '>', 0)
                ->orderBy('available_copies')
                ->get(),
            
            'out_of_stock' => Book::where('available_copies', 0)
                ->where('total_copies', '>', 0)
                ->get(),
        ];

        return view('pages.librarian.reports.inventory', compact('inventory'));
    }

    /**
     * Rapport des retards et pénalités
     */
    public function penalties(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(1));
        $endDate = $request->input('end_date', Carbon::now());

        $penalties = BookRequest::with(['student', 'book'])
            ->whereBetween('returned_at', [$startDate, $endDate])
            ->where('penalty_amount', '>', 0)
            ->orderBy('penalty_amount', 'desc')
            ->get();

        $totalPenalties = $penalties->sum('penalty_amount');
        $totalDaysLate = $penalties->sum('days_late');

        return view('pages.librarian.reports.penalties', compact('penalties', 'totalPenalties', 'totalDaysLate', 'startDate', 'endDate'));
    }

    /**
     * Exporter un rapport en PDF
     */
    public function export(Request $request)
    {
        $type = $request->input('type');
        
        // TODO: Implémenter l'export PDF
        
        return back()->with('flash_info', 'Fonctionnalité d\'export en cours de développement.');
    }
}
