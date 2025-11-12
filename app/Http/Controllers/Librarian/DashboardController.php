<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('librarian');
    }

    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('available', 1)->count(),
            'borrowed_books' => BookRequest::whereIn('status', ['approved', 'borrowed'])->count(),
            'pending_requests' => BookRequest::where('status', 'pending')->count(),
            'overdue_books' => BookRequest::where('status', 'borrowed')
                ->where('expected_return_date', '<', now())
                ->count(),
            'total_students' => User::where('user_type', 'student')->count(),
        ];

        // Demandes récentes (10 dernières)
        $recent_requests = BookRequest::with(['student', 'book'])
            ->latest()
            ->take(10)
            ->get();

        // Demandes en attente
        $pending_requests = BookRequest::with(['student', 'book'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Livres en retard
        $overdue_books = BookRequest::with(['student', 'book'])
            ->where('status', 'borrowed')
            ->where('expected_return_date', '<', now())
            ->orderBy('expected_return_date')
            ->take(10)
            ->get();

        // Livres les plus empruntés (ce mois)
        $popular_books = Book::withCount([
            'requests' => function($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            }
        ])
        ->orderBy('requests_count', 'desc')
        ->take(5)
        ->get();

        // Statistiques mensuelles
        $monthly_stats = $this->getMonthlyStats();

        // Activités récentes
        $recent_activities = $this->getRecentActivities();

        return view('pages.librarian.dashboard', compact(
            'stats',
            'recent_requests',
            'pending_requests',
            'overdue_books',
            'popular_books',
            'monthly_stats',
            'recent_activities'
        ));
    }

    private function getMonthlyStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'borrowed_this_month' => BookRequest::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->whereIn('status', ['approved', 'borrowed'])
                ->count(),
            
            'returned_this_month' => BookRequest::whereMonth('updated_at', $currentMonth)
                ->whereYear('updated_at', $currentYear)
                ->where('status', 'returned')
                ->count(),
            
            'new_books_this_month' => Book::whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
        ];
    }

    private function getRecentActivities()
    {
        return BookRequest::with(['student', 'book'])
            ->whereIn('status', ['approved', 'borrowed', 'returned', 'rejected'])
            ->latest('updated_at')
            ->take(15)
            ->get()
            ->map(function($request) {
                return [
                    'type' => $request->status,
                    'student' => $request->student->name ?? 'N/A',
                    'book' => $request->book->title ?? $request->titre ?? 'N/A',
                    'date' => $request->updated_at,
                    'icon' => $this->getStatusIcon($request->status),
                    'color' => $this->getStatusColor($request->status),
                ];
            });
    }

    private function getStatusIcon($status)
    {
        return match($status) {
            'approved' => 'icon-checkmark-circle',
            'borrowed' => 'icon-book',
            'returned' => 'icon-undo2',
            'rejected' => 'icon-cross-circle',
            default => 'icon-info',
        };
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'approved' => 'success',
            'borrowed' => 'info',
            'returned' => 'primary',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
