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
        
        $books = $query->orderBy('name')->paginate(15);
        
        // Récupérer les demandes de l'utilisateur connecté
        $my_requests = collect();
        if (auth()->check() && auth()->user()->user_type === 'student') {
            $my_requests = \App\Models\BookRequest::where('student_id', auth()->id())
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }
        
        // Initialiser book_type s'il n'est pas défini
        $book_type = $book_type ?? '';

        return view('pages.student.library.index', compact('books', 'search', 'book_type', 'my_requests'));
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
            $message = 'Utilisateur non connecté';
            \Log::warning($message);
            return response()->json([
                'success' => false,
                'message' => 'Veuillez vous connecter pour effectuer cette action.',
                'debug' => $message
            ], 401);
        }

        try {
            DB::beginTransaction();

            // Vérifier si le livre est disponible
            if (!$book->available) {
                $message = "Livre non disponible. Statut: " . ($book->available ? 'disponible' : 'indisponible');
                \Log::warning($message, ['book' => $book->toArray()]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Désolé, ce livre n\'est plus disponible pour le moment.',
                    'debug' => $message,
                    'book_status' => $book->available ? 'available' : 'unavailable'
                ], 400);
            }

            // Vérifier si l'utilisateur a déjà une demande en attente pour ce livre
            $existingRequest = BookRequest::where('student_id', auth()->id())
                ->where('book_id', $book->id)
                ->whereIn('status', [
                    BookRequest::STATUS_PENDING,
                    BookRequest::STATUS_APPROVED,
                    BookRequest::STATUS_BORROWED
                ])
                ->exists();

            if ($existingRequest) {
                $message = 'Demande existante pour ce livre et cet utilisateur';
                \Log::info($message, [
                    'user_id' => auth()->id(),
                    'book_id' => $book->id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà une demande en attente pour ce livre.',
                    'debug' => $message
                ], 400);
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
                
                // Désactivation temporaire de l'envoi d'email
                // TODO: Configurer SMTP pour activer les notifications par email
                \Log::info('Notification par email désactivée. À configurer dans .env');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Votre demande d\'emprunt a été enregistrée avec succès.',
                    'request_id' => $bookRequest->id,
                    'book_id' => $book->id,
                    'status' => BookRequest::STATUS_PENDING,
                    'status_text' => BookRequest::getStatuses()[BookRequest::STATUS_PENDING] ?? 'Inconnu',
                    'badge_class' => $bookRequest->badge_class
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                $errorTrace = $e->getTraceAsString();
                $errorCode = $e->getCode();
                
                \Log::error('ERREUR LORS DE LA CRÉATION DE LA DEMANDE', [
                    'error' => $errorMessage,
                    'code' => $errorCode,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $errorTrace,
                    'request_data' => $requestData ?? null,
                    'user' => auth()->user() ? auth()->user()->id : null,
                    'book' => $book->id ?? null
                ]);
                
                $response = [
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création de la demande.',
                    'error' => $errorMessage,
                    'error_details' => [
                        'code' => $errorCode,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => config('app.debug') ? $errorTrace : null
                    ]
                ];
                
                \Log::info('Réponse d\'erreur envoyée:', $response);
                
                return response()->json($response, 500);
            }

            // Marquer le livre comme réservé (non disponible pour les autres demandes)
            $book->update(['available' => false]);

            // Envoyer une notification à l'étudiant
            auth()->user()->notify(new \App\Notifications\BookRequestNotification(
                $bookRequest,
                $book,
                'pending'
            ));

            // Notifier l'administrateur ou le bibliothécaire
            $admin = \App\Models\User::where('user_type', 'admin')->first();
            if ($admin) {
                $admin->notify(new \App\Notifications\BookRequestNotification(
                    $bookRequest,
                    $book,
                    'pending',
                    auth()->user()
                ));
            }

            DB::commit();

            return back()->with([
                'success' => 'Votre demande d\'emprunt a été enregistrée avec succès. Vous serez notifié par email dès qu\'elle sera traitée.'
            ]);

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
}
