<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Affiche la page d'accueil de la bibliothèque
     */
    public function index()
    {
        $categories = BookCategory::withCount('books')->get();
        $recentBooks = Book::with('category')
            ->available()
            ->latest()
            ->take(8)
            ->get();

        $popularBooks = Book::with('category')
            ->withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.library.index', [
            'categories' => $categories,
            'recentBooks' => $recentBooks,
            'popularBooks' => $popularBooks
        ]);
    }

    /**
     * Affiche les résultats de la recherche
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');
        $availableOnly = $request->boolean('available_only', false);

        $books = Book::with('category')
            ->when($query, function($q) use ($query) {
                return $q->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('author', 'like', "%{$query}%")
                      ->orWhere('isbn', 'like', "%{$query}%");
                });
            })
            ->when($category, function($q) use ($category) {
                return $q->where('category_id', $category);
            })
            ->when($availableOnly, function($q) {
                return $q->available();
            })
            ->paginate(12);

        $categories = BookCategory::all();

        return view('pages.library.search', [
            'books' => $books,
            'categories' => $categories,
            'searchQuery' => $query,
            'selectedCategory' => $category,
            'availableOnly' => $availableOnly
        ]);
    }

    /**
     * Affiche les détails d'un livre
     */
    public function show(Book $book)
    {
        $book->load(['category', 'reviews' => function($query) {
            $query->approved()->latest()->take(5);
        }]);

        $user = Auth::user();
        $hasBorrowed = $book->wasBorrowedBy($user);
        $hasReviewed = $book->wasReviewedBy($user);
        $canBorrow = $book->isAvailable() && !$hasBorrowed;

        return view('pages.library.books.show', [
            'book' => $book,
            'hasBorrowed' => $hasBorrowed,
            'hasReviewed' => $hasReviewed,
            'canBorrow' => $canBorrow
        ]);
    }

    /**
     * Télécharge un livre numérique
     */
    public function download(Book $book)
    {
        if (!$book->file_path || !$book->isAvailable()) {
            abort(404);
        }

        // Vérifier que l'utilisateur a le droit de télécharger ce livre
        $user = Auth::user();
        $hasAccess = $book->loans()
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Vous devez emprunter ce livre pour le télécharger.');
        }

        // Enregistrer le téléchargement
        $book->increment('download_count');

        return response()->download(
            storage_path('app/' . $book->file_path),
            $book->slug . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION)
        );
    }
}
