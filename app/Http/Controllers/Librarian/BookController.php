<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\MyClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('librarian');
    }

    public function index(Request $request)
    {
        $query = Book::query();

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // Filtre par type de livre
        if ($request->filled('book_type')) {
            $query->where('book_type', $request->book_type);
        }

        // Filtre par disponibilité
        if ($request->filled('availability')) {
            if ($request->availability == 'available') {
                $query->where('available', 1);
            } elseif ($request->availability == 'unavailable') {
                $query->where('available', 0);
            }
        }

        $books = $query->latest()->paginate(15);
        $classes = MyClass::all();

        return view('pages.librarian.books.index', compact('books', 'classes'));
    }

    public function create()
    {
        $classes = MyClass::all();
        return view('pages.librarian.books.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'book_type' => 'nullable|string|max:255',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'total_copies' => 'nullable|integer|min:1',
            'issued_copies' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'available' => 'nullable|boolean',
        ]);
        
        // Par défaut, le livre est disponible
        $validated['available'] = $request->has('available') ? 1 : 0;

        // Upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('librarian.books.index')
            ->with('flash_success', 'Livre ajouté avec succès!');
    }

    public function show($id)
    {
        $book = Book::with('requests')->findOrFail($id);
        return view('pages.librarian.books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $classes = MyClass::all();
        return view('pages.librarian.books.edit', compact('book', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'book_type' => 'nullable|string|max:255',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'total_copies' => 'nullable|integer|min:1',
            'issued_copies' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'available' => 'nullable|boolean',
        ]);
        
        // Par défaut, le livre est disponible
        $validated['available'] = $request->has('available') ? 1 : 0;

        // Upload de la nouvelle image
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('books/covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('librarian.books.index')
            ->with('flash_success', 'Livre mis à jour avec succès!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Vérifier s'il y a des emprunts actifs
        if ($book->requests()->whereIn('status', ['approved', 'borrowed'])->exists()) {
            return back()->with('flash_error', 'Impossible de supprimer ce livre car il est actuellement emprunté.');
        }

        // Supprimer l'image
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('librarian.books.index')
            ->with('flash_success', 'Livre supprimé avec succès!');
    }
}
