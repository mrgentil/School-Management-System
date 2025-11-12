<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use App\Models\User;
use App\Events\BookRequestStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSAT');
    }

    /**
     * Affiche la liste des demandes de livres
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = BookRequest::with(['etudiant', 'bibliothecaire']);

        // Filtrage par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('etudiant', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $demandes = $query->latest()->paginate(15);
        
        return view('pages.support_team.book_requests.index', compact('demandes'));
    }

    /**
     * Affiche les détails d'une demande spécifique
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $demande = BookRequest::findOrFail($id);
        return view('pages.support_team.book_requests.show', compact('demande'));
    }

    /**
     * Approuve une demande de livre
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id, Request $request)
    {
        $request->validate([
            'reponse' => 'nullable|string|max:1000'
        ]);

        $demande = BookRequest::findOrFail($id);
        $demande->marquerCommeApprouve(Auth::id(), $request->reponse);
        
        // Déclencher l'événement de notification
        event(new BookRequestStatusUpdated($demande, 'approuve', $demande->etudiant_id));

        return redirect()
            ->route('book-requests.show', $demande->id)
            ->with('success', 'La demande a été approuvée avec succès. L\'étudiant a été notifié.');
    }

    /**
     * Refuse une demande de livre
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id, Request $request)
    {
        $request->validate([
            'raison' => 'required|string|max:1000'
        ]);

        $demande = BookRequest::findOrFail($id);
        $demande->marquerCommeRefuse(Auth::id(), $request->raison);
        
        // Déclencher l'événement de notification
        event(new BookRequestStatusUpdated($demande, 'refuse', $demande->etudiant_id));

        return redirect()
            ->route('book-requests.show', $demande->id)
            ->with('success', 'La demande a été refusée. L\'étudiant a été notifié.');
    }

    /**
     * Supprime une demande de livre
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $demande = BookRequest::findOrFail($id);
        $demande->delete();

        return redirect()
            ->route('book-requests.index')
            ->with('success', 'La demande a été supprimée avec succès.');
    }
}
