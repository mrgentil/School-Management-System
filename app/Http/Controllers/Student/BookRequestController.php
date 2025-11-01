<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookRequestController extends Controller
{
    /**
     * Affiche la liste des demandes de l'étudiant connecté
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = BookRequest::where('student_id', Auth::id())
            ->with('book')
            ->latest()
            ->paginate(15);

        return view('pages.student.book_requests.index', compact('requests'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle demande
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $books = Book::where('available', true)
            ->orderBy('name')
            ->get();
            
        return view('pages.student.book_requests.create', compact('books'));
    }

    /**
     * Enregistre une nouvelle demande de livre
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Vérifier si l'utilisateur a déjà une demande en attente pour ce livre
            $existingRequest = BookRequest::where('student_id', Auth::id())
                ->where('book_id', $validated['book_id'])
                ->whereIn('status', [
                    BookRequest::STATUS_PENDING,
                    BookRequest::STATUS_APPROVED,
                    BookRequest::STATUS_BORROWED
                ])
                ->exists();

            if ($existingRequest) {
                return back()->with('error', 'Vous avez déjà une demande en cours pour ce livre.');
            }

            // Créer la demande
            $bookRequest = BookRequest::create([
                'student_id' => Auth::id(),
                'book_id' => $validated['book_id'],
                'status' => BookRequest::STATUS_PENDING,
                'remarks' => $validated['remarks'] ?? null,
                'expected_return_date' => now()->addDays(14),
            ]);

            // Marquer le livre comme non disponible
            Book::where('id', $validated['book_id'])->update(['available' => false]);

            DB::commit();
            
            return redirect()
                ->route('student.library.requests.index')
                ->with('success', 'Votre demande de livre a été soumise avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la création de la demande de livre: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Une erreur est survenue lors de la soumission de votre demande.')
                ->withInput();
        }
    }

    /**
     * Affiche les détails d'une demande spécifique
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $request = BookRequest::where('student_id', Auth::id())
            ->with('book')
            ->findOrFail($id);

        return view('pages.student.book_requests.show', compact('request'));
    }

    /**
     * Annuler une demande de livre
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        DB::beginTransaction();
        
        try {
            $bookRequest = BookRequest::where('student_id', Auth::id())
                ->findOrFail($id);

            // Vérifier si la demande peut être annulée
            if (!$bookRequest->canBeCancelled()) {
                return back()->with('error', 'Cette demande ne peut pas être annulée.');
            }

            // Annuler la demande
            $bookRequest->status = BookRequest::STATUS_REJECTED;
            $bookRequest->remarks = ($bookRequest->remarks ?? '') . "\n[Annulée par l'étudiant le " . now()->format('d/m/Y à H:i') . "]";
            $bookRequest->save();

            // Rendre le livre disponible si la demande était approuvée
            if ($bookRequest->book && in_array($bookRequest->status, [BookRequest::STATUS_PENDING, BookRequest::STATUS_APPROVED])) {
                $bookRequest->book->update(['available' => true]);
            }

            DB::commit();
            
            return redirect()
                ->route('student.library.requests.index')
                ->with('success', 'Votre demande a été annulée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de l\'annulation de la demande: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation de votre demande.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Rediriger vers la méthode cancel
        return $this->cancel($id);
    }
}
