<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Réserver un livre
     */
    public function reserve(Request $request, Book $book)
    {
        $user = Auth::user();
        
        // Vérifier si le livre est disponible
        if ($book->isAvailable()) {
            return redirect()
                ->route('library.books.show', $book)
                ->with('info', 'Ce livre est disponible à l\'emprunt. Vous pouvez l\'emprunter directement.');
        }
        
        // Vérifier si l'utilisateur a déjà une réservation en cours pour ce livre
        $existingReservation = BookReservation::where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
            
        if ($existingReservation) {
            return back()->with('error', 'Vous avez déjà une réservation en cours pour ce livre.');
        }
        
        // Vérifier le nombre maximum de réservations
        $maxReservations = config('library.max_reservations_per_user', 3);
        $currentReservations = $user->bookReservations()
            ->whereIn('status', ['pending', 'approved'])
            ->count();
            
        if ($currentReservations >= $maxReservations) {
            return back()->with('error', "Vous ne pouvez pas avoir plus de {$maxReservations} réservations en même temps.");
        }
        
        // Créer la réservation
        $reservation = $book->reserve($user);
        
        // Envoyer une notification au bibliothécaire
        // TODO: Implémenter le système de notification
        
        return redirect()
            ->route('library.reservations.show', $reservation)
            ->with('success', 'Réservation effectuée avec succès. Vous serez notifié lorsque le livre sera disponible.');
    }
    
    /**
     * Annuler une réservation
     */
    public function cancel(BookReservation $reservation)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient bien à l'utilisateur
        if ($reservation->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Vérifier que la réservation peut être annulée
        if (!in_array($reservation->status, ['pending', 'approved'])) {
            return back()->with('error', 'Cette réservation ne peut pas être annulée.');
        }
        
        // Annuler la réservation
        $reservation->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Réservation annulée avec succès.');
    }
    
    /**
     * Afficher les détails d'une réservation
     */
    public function show(BookReservation $reservation)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient bien à l'utilisateur
        if ($reservation->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $reservation->load(['book', 'user']);
        
        return view('pages.library.reservations.show', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * Afficher l'historique des réservations de l'utilisateur
     */
    public function history()
    {
        $user = Auth::user();
        
        $reservations = $user->bookReservations()
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pages.library.reservations.history', [
            'reservations' => $reservations
        ]);
    }
    
    /**
     * Afficher les réservations actives de l'utilisateur
     */
    public function active()
    {
        $user = Auth::user();
        
        $reservations = $user->bookReservations()
            ->with('book')
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('pages.library.reservations.active', [
            'reservations' => $reservations
        ]);
    }
}
