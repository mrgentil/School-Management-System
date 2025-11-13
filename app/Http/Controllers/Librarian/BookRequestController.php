<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('librarian');
    }

    public function index(Request $request)
    {
        $query = BookRequest::with(['student', 'book']);

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('titre', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(20);
        
        // Statistiques
        $stats = [
            'pending' => BookRequest::where('status', 'pending')->count(),
            'approved' => BookRequest::where('status', 'approved')->count(),
            'borrowed' => BookRequest::where('status', 'borrowed')->count(),
            'returned' => BookRequest::where('status', 'returned')->count(),
            'overdue' => BookRequest::where('status', 'borrowed')
                ->where('expected_return_date', '<', now())
                ->count(),
        ];

        return view('pages.librarian.book_requests.index', compact('requests', 'stats'));
    }

    public function show(BookRequest $bookRequest)
    {
        $bookRequest->load(['student', 'book']);
        $request = $bookRequest;
        return view('pages.librarian.book_requests.show', compact('request'));
    }

    public function approve($id, Request $request)
    {
        $bookRequest = BookRequest::findOrFail($id);

        if ($bookRequest->status !== 'pending') {
            return back()->with('flash_error', 'Cette demande a déjà été traitée.');
        }

        // Vérifier la disponibilité du livre
        if ($bookRequest->book && !$bookRequest->book->available) {
            return back()->with('flash_error', 'Ce livre n\'est plus disponible.');
        }

        $validated = $request->validate([
            'expected_return_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        // Approuver la demande
        $bookRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'expected_return_date' => $validated['expected_return_date'],
            'remarks' => $validated['notes'] ?? null,
        ]);

        // Incrémenter les copies empruntées
        if ($bookRequest->book) {
            $bookRequest->book->increment('issued_copies');
        }

        return redirect()->route('librarian.book-requests.index')
            ->with('flash_success', 'Demande approuvée avec succès!');
    }

    public function reject($id, Request $request)
    {
        $bookRequest = BookRequest::findOrFail($id);

        if ($bookRequest->status !== 'pending') {
            return back()->with('flash_error', 'Cette demande a déjà été traitée.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $bookRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'librarian_notes' => $validated['reason'],
        ]);

        return redirect()->route('librarian.book-requests.index')
            ->with('flash_success', 'Demande rejetée.');
    }

    public function markAsBorrowed($id)
    {
        $bookRequest = BookRequest::findOrFail($id);

        if ($bookRequest->status !== 'approved') {
            return back()->with('flash_error', 'Cette demande doit d\'abord être approuvée.');
        }

        $bookRequest->update([
            'status' => 'borrowed',
            'borrowed_at' => now(),
        ]);

        return back()->with('flash_success', 'Livre marqué comme emprunté!');
    }

    public function markAsReturned($id, Request $request)
    {
        $bookRequest = BookRequest::findOrFail($id);

        if ($bookRequest->status !== 'borrowed') {
            return back()->with('flash_error', 'Ce livre n\'est pas marqué comme emprunté.');
        }

        $validated = $request->validate([
            'condition' => 'required|in:excellent,good,fair,damaged',
            'notes' => 'nullable|string|max:500',
        ]);

        $bookRequest->update([
            'status' => 'returned',
            'returned_at' => now(),
            'return_condition' => $validated['condition'],
            'return_notes' => $validated['notes'] ?? null,
        ]);

        // Décrémenter les copies empruntées
        if ($bookRequest->book) {
            $bookRequest->book->decrement('issued_copies');
        }

        // Calculer les pénalités si en retard
        if ($bookRequest->expected_return_date && $bookRequest->expected_return_date < now()) {
            $daysLate = now()->diffInDays($bookRequest->expected_return_date);
            // Note: Les colonnes penalty_amount et days_late n'existent pas dans la table
            // On peut ajouter l'info dans remarks
            $penaltyInfo = "Retard: {$daysLate} jour(s). Pénalité: " . ($daysLate * 100) . " $";
            $bookRequest->update([
                'remarks' => ($bookRequest->remarks ? $bookRequest->remarks . ' | ' : '') . $penaltyInfo,
            ]);
        }

        return redirect()->route('librarian.book-requests.index')
            ->with('flash_success', 'Livre retourné avec succès!');
    }

    public function overdue()
    {
        $overdueRequests = BookRequest::with(['student', 'book'])
            ->where('status', 'borrowed')
            ->where('expected_return_date', '<', now())
            ->orderBy('expected_return_date')
            ->paginate(20);

        return view('pages.librarian.book_requests.overdue', compact('overdueRequests'));
    }

    public function sendReminder($id)
    {
        $bookRequest = BookRequest::with('student')->findOrFail($id);

        if ($bookRequest->status !== 'borrowed') {
            return back()->with('flash_error', 'Ce livre n\'est pas emprunté.');
        }

        // TODO: Envoyer une notification/email à l'étudiant
        // Notification::send($bookRequest->student, new BookReturnReminder($bookRequest));

        $bookRequest->update([
            'reminder_sent_at' => now(),
        ]);

        return back()->with('flash_success', 'Rappel envoyé à l\'étudiant!');
    }
}
