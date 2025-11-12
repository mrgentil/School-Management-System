<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookLoanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Emprunter un livre
     */
    public function borrow(Request $request, Book $book)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur peut emprunter plus de livres
        $maxLoans = config('library.max_loans_per_user', 5);
        $currentLoans = $user->bookLoans()->where('status', 'borrowed')->count();
        
        if ($currentLoans >= $maxLoans) {
            return back()->with('error', "Vous avez atteint la limite de {$maxLoans} emprunts simultanés.");
        }
        
        // Vérifier si le livre est disponible
        if (!$book->isAvailable()) {
            return back()->with('error', 'Ce livre n\'est pas disponible pour le moment.');
        }
        
        // Vérifier si l'utilisateur a déjà emprunté ce livre
        $hasBorrowed = $book->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->exists();
            
        if ($hasBorrowed) {
            return back()->with('error', 'Vous avez déjà emprunté ce livre.');
        }
        
        // Calculer la date de retour (2 semaines par défaut)
        $dueDate = now()->addWeeks(2);
        
        // Créer l'emprunt
        $loan = $book->borrow($user, $dueDate);
        
        if ($loan) {
            // Si c'est un livre numérique, on peut le télécharger directement
            if ($book->type === 'numerique') {
                return redirect()
                    ->route('library.books.download', $book)
                    ->with('success', 'Livre emprunté avec succès. Vous pouvez maintenant le télécharger.');
            }
            
            return back()->with('success', 'Livre emprunté avec succès. Date de retour: ' . $dueDate->format('d/m/Y'));
        }
        
        return back()->with('error', 'Une erreur est survenue lors de l\'emprunt du livre.');
    }
    
    /**
     * Renouveler un emprunt
     */
    public function renew(BookLoan $loan)
    {
        $user = Auth::user();
        
        // Vérifier que l'emprunt appartient bien à l'utilisateur
        if ($loan->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier que le livre n'est pas en retard
        if ($loan->is_overdue) {
            return back()->with('error', 'Vous ne pouvez pas renouveler un livre en retard.');
        }
        
        // Vérifier si le livre a déjà été renouvelé
        if ($loan->renewal_count >= config('library.max_renewals', 1)) {
            return back()->with('error', 'Vous avez atteint le nombre maximum de renouvellements pour ce livre.');
        }
        
        // Calculer la nouvelle date de retour (1 semaine supplémentaire)
        $newDueDate = $loan->due_date->copy()->addWeek();
        
        // Mettre à jour l'emprunt
        $loan->update([
            'due_date' => $newDueDate,
            'renewal_count' => $loan->renewal_count + 1
        ]);
        
        return back()->with('success', 'Emprunt renouvelé jusqu\'au ' . $newDueDate->format('d/m/Y'));
    }
    
    /**
     * Retourner un livre
     */
    public function returnBook(BookLoan $loan)
    {
        $user = Auth::user();
        
        // Vérifier que l'emprunt appartient bien à l'utilisateur ou que l'utilisateur est un bibliothécaire
        if ($loan->user_id !== $user->id && !$user->hasRole('librarian')) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Marquer le livre comme retourné
        $loan->markAsReturned();
        
        // Si c'est un livre physique, on peut afficher un message différent
        if ($loan->book->type === 'physique') {
            return back()->with('success', 'Livre marqué comme retourné avec succès.');
        }
        
        return back()->with('success', 'Livre retourné avec succès.');
    }
    
    /**
     * Afficher l'historique des emprunts de l'utilisateur
     */
    public function history()
    {
        $user = Auth::user();
        
        $loans = $user->bookLoans()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pages.library.loans.history', [
            'loans' => $loans
        ]);
    }
    
    /**
     * Afficher les emprunts en cours
     */
    public function current()
    {
        $user = Auth::user();
        
        $loans = $user->bookLoans()
            ->with('book')
            ->where('status', 'borrowed')
            ->orderBy('due_date')
            ->paginate(15);
            
        return view('pages.library.loans.current', [
            'loans' => $loans
        ]);
    }
}
