<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookRequest;
use App\Http\Requests\StoreBookRequestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $book_type = $request->input('book_type');

        // Récupérer tous les livres (pas seulement les disponibles)
        $query = Book::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($book_type) {
            $query->where('book_type', $book_type);
        }

        $books = $query->orderBy('name')->paginate(15);

        // Récupérer les demandes de l'utilisateur connecté pour déterminer le statut de chaque livre
        $userRequests = collect();
        $bookStatuses = collect();

        if (auth()->check() && auth()->user()->user_type === 'student') {
            $userRequests = \App\Models\BookRequest::where('student_id', auth()->id())
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Créer un mapping des statuts par livre
            $allUserRequests = \App\Models\BookRequest::where('student_id', auth()->id())
                ->whereIn('status', [
                    BookRequest::STATUS_PENDING,
                    BookRequest::STATUS_APPROVED,
                    BookRequest::STATUS_BORROWED,
                    BookRequest::STATUS_RETURNED
                ])
                ->get()
                ->keyBy('book_id');

            // Déterminer le statut pour chaque livre affiché
            foreach ($books as $book) {
                $status = $this->getBookStatusForUser($book, $allUserRequests->get($book->id));
                $bookStatuses->put($book->id, $status);
            }
        }

        // Initialiser book_type s'il n'est pas défini
        $book_type = $book_type ?? '';

        return view('pages.student.library.index', compact('books', 'search', 'book_type', 'userRequests', 'bookStatuses'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $book_type = $request->input('book_type', '');

        $query = Book::where('available', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($book_type) {
            $query->where('book_type', $book_type);
        }

        $books = $query->orderBy('name')
                     ->paginate(15)
                     ->appends($request->query());

        // Récupérer les demandes de l'utilisateur connecté
        $my_requests = collect();
        if (auth()->check() && auth()->user()->user_type === 'student') {
            $my_requests = \App\Models\BookRequest::where('student_id', auth()->id())
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('pages.student.library.index', compact('books', 'search', 'book_type', 'my_requests'));
    }

    public function show(Book $book)
    {
        if (!$book->available) {
            abort(404);
        }

        $relatedBooks = Book::where('id', '!=', $book->id)
            ->where('available', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('pages.student.library.show', compact('book', 'relatedBooks'));
    }

    /**
     * Traite la demande d'emprunt d'un livre
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function requestBook(Request $request, Book $book)
    {
        // Log de débogage détaillé
        \Log::info('=== DÉBUT DE LA MÉTHODE requestBook ===');
        \Log::info('Utilisateur connecté:', ['user_id' => auth()->id(), 'user' => auth()->user()]);
        \Log::info('Livre concerné:', ['book_id' => $book->id, 'book' => $book->toArray()]);
        \Log::info('Données de la requête:', $request->all());
        \Log::info('En-têtes de la requête:', $request->headers->all());
        \Log::info('Méthode de la requête:', ['method' => $request->method()]);
        \Log::info('URL de la requête:', ['url' => $request->fullUrl()]);

        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            \Log::warning('Utilisateur non connecté');
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour effectuer cette action.');
        }

        try {
            DB::beginTransaction();

            // Vérifier si le livre est disponible
            if (!$book->available) {
                \Log::warning("Livre non disponible", ['book_id' => $book->id]);

                return back()->with('error', 'Désolé, ce livre n\'est plus disponible pour le moment.');
            }

            // Vérifier si l'utilisateur a déjà une demande en attente pour ce livre
            $existingRequest = BookRequest::where('student_id', auth()->id())
                ->where('book_id', $book->id)
                ->whereIn('status', [
                    BookRequest::STATUS_PENDING,
                    BookRequest::STATUS_APPROVED,
                    BookRequest::STATUS_BORROWED
                ])
                ->first();

            if ($existingRequest) {
                \Log::info('Demande existante pour ce livre', [
                    'user_id' => auth()->id(),
                    'book_id' => $book->id,
                    'status' => $existingRequest->status
                ]);

                return back()->with('warning', 'Vous avez déjà une demande en attente pour ce livre.');
            }

            try {
                // Calculer la date de retour attendue (par exemple, 14 jours plus tard)
                $expectedReturnDate = now()->addDays(14);

                // Log des données avant création
                $requestData = [
                    'student_id' => auth()->id(),
                    'book_id' => $book->id,
                    'request_date' => now(),
                    'expected_return_date' => $expectedReturnDate,
                    'status' => BookRequest::STATUS_PENDING,
                    'remarks' => 'Demande initiale de prêt',
                    'approved_by' => null
                ];

                \Log::info('Données de la demande à créer:', $requestData);

                // Créer une instance de BookRequest pour accéder aux propriétés
                $bookRequestModel = new BookRequest();
                \Log::info('Modèle BookRequest fillable:', $bookRequestModel->getFillable());

                // Créer la demande d'emprunt
                $bookRequest = BookRequest::create($requestData);
                \Log::info('Demande créée avec succès:', $bookRequest->toArray());
                \Log::info('Demande de livre créée avec succès', [
                    'request_id' => $bookRequest->id,
                    'book_id' => $book->id,
                    'user_id' => auth()->id()
                ]);

                // Marquer le livre comme non disponible
                $book->update(['available' => false]);

                DB::commit();

                // Rediriger avec message de succès
                return redirect()->route('student.library.requests')
                    ->with('success', 'Votre demande d\'emprunt a été enregistrée avec succès. Vous serez notifié dès qu\'elle sera traitée.');
            } catch (\Exception $e) {
                DB::rollBack();

                $errorMessage = $e->getMessage();

                \Log::error('ERREUR LORS DE LA CRÉATION DE LA DEMANDE', [
                    'error' => $errorMessage,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request_data' => $requestData ?? null,
                    'user' => auth()->user() ? auth()->user()->id : null,
                    'book' => $book->id ?? null
                ]);

                // Rediriger avec message d'erreur (pas de JSON)
                return back()->with('error', 'Une erreur est survenue lors de la création de votre demande. Veuillez réessayer.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la demande d\'emprunt : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du traitement de votre demande. Veuillez réessayer.');
        }
    }

    /**
     * Affiche les demandes d'emprunt de l'utilisateur connecté
     */
    public function myRequests()
    {
        $requests = \App\Models\BookRequest::with('book')
            ->where('student_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.student.library.requests', compact('requests'));
    }

    /**
     * Détermine le statut d'un livre pour un utilisateur spécifique
     */
    private function getBookStatusForUser(Book $book, ?BookRequest $userRequest = null)
    {
        if ($userRequest) {
            switch ($userRequest->status) {
                case BookRequest::STATUS_PENDING:
                    return [
                        'status' => 'pending',
                        'text' => 'Demande en attente',
                        'badge_class' => 'badge-warning',
                        'can_request' => false,
                        'action_text' => 'Demande en cours'
                    ];
                case BookRequest::STATUS_APPROVED:
                    return [
                        'status' => 'approved',
                        'text' => 'Demande approuvée',
                        'badge_class' => 'badge-success',
                        'can_request' => false,
                        'action_text' => 'Prêt à emprunter'
                    ];
                case BookRequest::STATUS_BORROWED:
                    return [
                        'status' => 'borrowed',
                        'text' => 'Emprunté',
                        'badge_class' => 'badge-info',
                        'can_request' => false,
                        'action_text' => 'En votre possession'
                    ];
                case BookRequest::STATUS_RETURNED:
                    return [
                        'status' => 'returned',
                        'text' => 'Retourné',
                        'badge_class' => 'badge-secondary',
                        'can_request' => true,
                        'action_text' => 'Demander à nouveau'
                    ];
                case BookRequest::STATUS_REJECTED:
                    return [
                        'status' => 'rejected',
                        'text' => 'Demande rejetée',
                        'badge_class' => 'badge-danger',
                        'can_request' => true,
                        'action_text' => 'Demander à nouveau'
                    ];
            }
        }

        // Si pas de demande active, vérifier la disponibilité générale
        if ($book->available) {
            return [
                'status' => 'available',
                'text' => 'Disponible',
                'badge_class' => 'badge-success',
                'can_request' => true,
                'action_text' => 'Demander ce livre'
            ];
        } else {
            return [
                'status' => 'unavailable',
                'text' => 'Non disponible',
                'badge_class' => 'badge-danger',
                'can_request' => false,
                'action_text' => 'Indisponible'
            ];
        }
    }
}
