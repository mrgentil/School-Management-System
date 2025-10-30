<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRequestController extends Controller
{
    /**
     * Affiche la liste des demandes de l'étudiant connecté
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $demandes = BookRequest::where('etudiant_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pages.student.book_requests.index', compact('demandes'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle demande
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages.student.book_requests.create');
    }

    /**
     * Enregistre une nouvelle demande de livre
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Afficher les données de la requête directement dans le navigateur
            dd([
                'request_data' => $request->all(),
                'user' => Auth::user(),
                'validation_rules' => [
                    'titre' => 'required|string|max:255',
                    'auteur' => 'required|string|max:255',
                    'isbn' => 'nullable|string|max:20',
                    'description' => 'nullable|string',
                ]
            ]);
            
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'auteur' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:20',
                'description' => 'nullable|string',
            ]);
            
            // Vérification de l'utilisateur connecté
            if (!Auth::check()) {
                return back()->with('error', 'Vous devez être connecté pour effectuer cette action.');
            }
            
            // Création de la demande
            $demande = BookRequest::create([
                'titre' => $validated['titre'],
                'auteur' => $validated['auteur'],
                'isbn' => $validated['isbn'] ?? null,
                'description' => $validated['description'] ?? null,
                'etudiant_id' => Auth::id(),
                'statut' => 'en_attente',
                'date_demande' => now(),
            ]);
            
            return redirect()
                ->route('student.book-requests.index')
                ->with('success', 'Votre demande de livre a été soumise avec succès.');
                
        } catch (\Exception $e) {
            // Afficher l'erreur directement dans le navigateur
            dd([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
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
        $demande = BookRequest::where('etudiant_id', Auth::id())
            ->findOrFail($id);

        return view('pages.student.book_requests.show', compact('demande'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
