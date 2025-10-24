<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT', ['except' => ['store']]);
    }

    public function index(Request $request)
    {
        $query = BookRequest::with(['user', 'book']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('book', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        $data['requests'] = $query->latest()->paginate(15);
        
        return view('pages.support_team.book_requests.index', $data);
    }

    public function create()
    {
        $data['books'] = Book::where('status', 'available')->orderBy('title')->get();
        $data['students'] = User::where('user_type', 'student')->orderBy('name')->get();
        
        return view('pages.support_team.book_requests.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'nullable|exists:users,id',
            'request_date' => 'nullable|date',
            'expected_return_date' => 'nullable|date|after:request_date'
        ]);

        $data = $request->all();
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        $data['request_date'] = $data['request_date'] ?? now();
        $data['status'] = 'pending';

        BookRequest::create($data);

        return redirect()->route('book-requests.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show(BookRequest $bookRequest)
    {
        return view('pages.support_team.book_requests.show', compact('bookRequest'));
    }

    public function approve(BookRequest $bookRequest)
    {
        $bookRequest->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        // Mettre à jour le statut du livre
        $bookRequest->book->update(['status' => 'borrowed']);

        return redirect()->back()->with('flash_success', 'Demande approuvée avec succès.');
    }

    public function reject(BookRequest $bookRequest)
    {
        $bookRequest->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => Auth::id()
        ]);

        return redirect()->back()->with('flash_success', 'Demande rejetée.');
    }

    public function returnBook(BookRequest $bookRequest)
    {
        $bookRequest->update([
            'status' => 'returned',
            'returned_at' => now(),
            'returned_to' => Auth::id()
        ]);

        // Remettre le livre disponible
        $bookRequest->book->update(['status' => 'available']);

        return redirect()->back()->with('flash_success', 'Livre retourné avec succès.');
    }

    public function destroy(BookRequest $bookRequest)
    {
        $bookRequest->delete();

        return redirect()->route('book-requests.index')->with('flash_success', __('msg.del_ok'));
    }
}
