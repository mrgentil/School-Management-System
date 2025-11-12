<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Afficher le formulaire d'ajout d'un avis
     */
    public function create(Book $book)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a bien emprunté ce livre
        if (!$book->wasBorrowedBy($user)) {
            return back()->with('error', 'Vous devez avoir emprunté ce livre pour pouvoir le noter.');
        }
        
        // Vérifier que l'utilisateur n'a pas déjà noté ce livre
        if ($book->wasReviewedBy($user)) {
            return back()->with('error', 'Vous avez déjà noté ce livre.');
        }
        
        return view('pages.library.reviews.create', [
            'book' => $book
        ]);
    }
    
    /**
     * Enregistrer un nouvel avis
     */
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a bien emprunté ce livre
        if (!$book->wasBorrowedBy($user)) {
            return back()->with('error', 'Vous devez avoir emprunté ce livre pour pouvoir le noter.');
        }
        
        // Vérifier que l'utilisateur n'a pas déjà noté ce livre
        if ($book->wasReviewedBy($user)) {
            return back()->with('error', 'Vous avez déjà noté ce livre.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);
        
        // Créer l'avis
        $review = new BookReview([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Les avis doivent être approuvés par un modérateur
            'review_date' => now()
        ]);
        
        $review->save();
        
        // Mettre à jour la note moyenne du livre
        $book->updateRating();
        
        return redirect()
            ->route('library.books.show', $book)
            ->with('success', 'Votre avis a été soumis avec succès. Il sera publié après modération.');
    }
    
    /**
     * Afficher le formulaire de modification d'un avis
     */
    public function edit(BookReview $review)
    {
        $user = Auth::user();
        
        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($review->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('pages.library.reviews.edit', [
            'review' => $review,
            'book' => $review->book
        ]);
    }
    
    /**
     * Mettre à jour un avis existant
     */
    public function update(Request $request, BookReview $review)
    {
        $user = Auth::user();
        
        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($review->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);
        
        // Mettre à jour l'avis
        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false // L'avis doit être réapprouvé après modification
        ]);
        
        // Mettre à jour la note moyenne du livre
        $review->book->updateRating();
        
        return redirect()
            ->route('library.books.show', $review->book)
            ->with('success', 'Votre avis a été mis à jour avec succès. Il sera à nouveau soumis à modération.');
    }
    
    /**
     * Supprimer un avis
     */
    public function destroy(BookReview $review)
    {
        $user = Auth::user();
        $book = $review->book;
        
        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($review->user_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }
        
        // Supprimer l'avis
        $review->delete();
        
        // Mettre à jour la note moyenne du livre
        $book->updateRating();
        
        return redirect()
            ->route('library.books.show', $book)
            ->with('success', 'Votre avis a été supprimé avec succès.');
    }
}
