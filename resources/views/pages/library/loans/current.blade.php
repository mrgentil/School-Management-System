@extends('layouts.app')

@section('title', 'Mes emprunts en cours')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Bibliothèque</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mes emprunts</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book-reader"></i> Mes emprunts en cours
                    </h4>
                    <div>
                        <a href="{{ route('library.loans.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-history"></i> Historique
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($loans->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                            <h4>Vous n'avez aucun emprunt en cours</h4>
                            <p class="text-muted">
                                Parcourez notre catalogue pour trouver votre prochaine lecture !
                            </p>
                            <a href="{{ route('library.search') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-search"></i> Explorer la bibliothèque
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Livre</th>
                                        <th>Date d'emprunt</th>
                                        <th>Date de retour prévue</th>
                                        <th>Jours restants</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr class="{{ $loan->is_overdue ? 'table-danger' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $loan->book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
                                                         alt="{{ $loan->book->title }}" 
                                                         style="width: 40px; height: 60px; object-fit: cover;"
                                                         class="me-3 rounded">
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="{{ route('library.books.show', $loan->book) }}" class="text-decoration-none">
                                                                {{ $loan->book->title }}
                                                            </a>
                                                        </div>
                                                        <div class="text-muted small">
                                                            {{ $loan->book->author }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                                            <td>
                                                {{ $loan->due_date->format('d/m/Y') }}
                                                @if($loan->is_overdue)
                                                    <span class="badge bg-danger ms-1">En retard</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($loan->is_overdue)
                                                    <span class="text-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> {{ $loan->days_overdue }} jour(s) de retard
                                                    </span>
                                                @else
                                                    {{ $loan->days_remaining }} jour(s)
                                                @endif
                                            </td>
                                            <td>
                                                @if($loan->is_overdue)
                                                    <span class="badge bg-danger">En retard</span>
                                                @else
                                                    <span class="badge bg-success">En cours</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if($loan->canBeRenewed())
                                                        <form action="{{ route('library.loans.renew', $loan) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-primary" 
                                                                    title="Renouveler l'emprunt">
                                                                <i class="fas fa-sync-alt"></i> Renouveler
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($loan->book->type === 'numerique')
                                                        <a href="{{ route('library.books.download', $loan->book) }}" 
                                                           class="btn btn-outline-success"
                                                           title="Télécharger le livre">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($loan->book->type === 'physique')
                                                        <form action="{{ route('library.loans.return', $loan) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-danger" 
                                                                    title="Marquer comme retourné"
                                                                    onclick="return confirm('Êtes-vous sûr de vouloir marquer ce livre comme retourné ?')">
                                                                <i class="fas fa-undo"></i> Retourner
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Résumé des emprunts -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $loans->count() }}</h5>
                                        <p class="card-text text-muted small mb-0">Emprunt(s) en cours</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-success">
                                            {{ $loans->where('is_overdue', false)->count() }}
                                        </h5>
                                        <p class="card-text text-muted small mb-0">Emprunt(s) en règle</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title {{ $overdueCount > 0 ? 'text-danger' : 'text-muted' }}">
                                            {{ $overdueCount }}
                                        </h5>
                                        <p class="card-text text-muted small mb-0">Retard(s)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations importantes -->
                        @if($overdueCount > 0)
                            <div class="alert alert-warning mt-4">
                                <h5><i class="fas fa-exclamation-triangle"></i> Attention</h5>
                                <p class="mb-0">
                                    Vous avez {{ $overdueCount }} livre(s) en retard. Veuillez les retourner dès que possible 
                                    pour éviter des frais de retard supplémentaires.
                                </p>
                            </div>
                        @endif
                        
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-info-circle"></i> Informations importantes</h5>
                            <ul class="mb-0">
                                <li>La durée maximale d'un emprunt est de 14 jours.</li>
                                <li>Vous pouvez renouveler un emprunt une seule fois, pour une durée équivalente.</li>
                                <li>Les livres numériques sont automatiquement retournés à la fin de la période d'emprunt.</li>
                                <li>En cas de retard, des frais de 0,50€ par jour et par livre seront appliqués.</li>
                            </ul>
                        </div>
                    @endif
                </div>
                
                @if(!$loans->isEmpty())
                    <div class="card-footer text-muted">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Affichage de {{ $loans->firstItem() }} à {{ $loans->lastItem() }} sur {{ $loans->total() }} emprunt(s)
                            </div>
                            <div>
                                {{ $loans->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aide -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">
                    <i class="fas fa-question-circle"></i> Aide - Gestion des emprunts
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Comment renouveler un emprunt ?</h6>
                <p>Cliquez sur le bouton "Renouveler" à côté du livre que vous souhaitez conserver plus longtemps. 
                Vous pouvez renouveler un emprunt une seule fois, pour une durée équivalente à la période d'emprunt initiale.</p>
                
                <h6 class="mt-4">Que faire en cas de retard ?</h6>
                <p>Si vous avez des livres en retard, veuillez les retourner dès que possible pour éviter des frais supplémentaires. 
                Les frais de retard s'élèvent à 0,50€ par jour et par livre.</p>
                
                <h6 class="mt-4">Comment retourner un livre numérique ?</h6>
                <p>Les livres numériques sont automatiquement retournés à la fin de la période d'emprunt. 
                Vous n'avez aucune action à effectuer.</p>
                
                <h6 class="mt-4">Besoin d'aide supplémentaire ?</h6>
                <p class="mb-0">Contactez le service de la bibliothèque à l'adresse 
                <a href="mailto:bibliotheque@ecole.fr">bibliotheque@ecole.fr</a> ou présentez-vous à l'accueil.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Afficher une notification si l'utilisateur a des retards
    @if($overdueCount > 0)
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Retard de retour',
            html: 'Vous avez {{ $overdueCount }} livre(s) en retard. Veuillez les retourner dès que possible pour éviter des frais supplémentaires.',
            confirmButtonText: 'Compris',
            confirmButtonColor: '#0d6efd',
            allowOutsideClick: false
        });
    });
    @endif
    
    // Gestion des renouvellements
    document.querySelectorAll('.renew-loan').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Confirmer le renouvellement',
                text: 'Êtes-vous sûr de vouloir renouveler cet emprunt ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, renouveler',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
