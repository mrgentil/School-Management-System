@extends('layouts.app')

@section('title', 'Historique de mes emprunts')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Bibliothèque</a></li>
            <li class="breadcrumb-item active" aria-current="page">Historique des emprunts</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history"></i> Historique de mes emprunts
                    </h4>
                    <div>
                        <a href="{{ route('library.loans.current') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-book-reader"></i> Emprunts en cours
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('library.loans.history') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Statut</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="" {{ !request('status') ? 'selected' : '' }}>Tous les statuts</option>
                                        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Retournés</option>
                                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>En retard</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulés</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="year" class="form-label">Année</label>
                                    <select name="year" id="year" class="form-select">
                                        <option value="" {{ !request('year') ? 'selected' : '' }}>Toutes les années</option>
                                        @php $currentYear = now()->year @endphp
                                        @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Rechercher un livre</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control" 
                                               id="search" 
                                               name="search" 
                                               placeholder="Titre ou auteur..."
                                               value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-filter"></i> Filtrer
                                    </button>
                                    <a href="{{ route('library.loans.history') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                            <h4>Aucun emprunt trouvé</h4>
                            <p class="text-muted">
                                Aucun emprunt ne correspond à vos critères de recherche.
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
                                        <th>Date de retour</th>
                                        <th>Statut</th>
                                        <th>Retourné avec retard</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr>
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
                                                @if($loan->status === 'returned' && $loan->returned_date)
                                                    {{ $loan->returned_date->format('d/m/Y') }}
                                                @elseif($loan->status === 'cancelled')
                                                    <span class="text-muted">-</span>
                                                @else
                                                    {{ $loan->due_date->format('d/m/Y') }}
                                                    <div class="text-muted small">(prévu)</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($loan->status === 'returned')
                                                    <span class="badge bg-success">Retourné</span>
                                                @elseif($loan->status === 'overdue')
                                                    <span class="badge bg-danger">En retard</span>
                                                @elseif($loan->status === 'cancelled')
                                                    <span class="badge bg-secondary">Annulé</span>
                                                @endif
                                                
                                                @if($loan->renewal_count > 0)
                                                    <div class="text-muted small mt-1">Renouvelé {{ $loan->renewal_count }} fois</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($loan->status === 'returned' && $loan->returned_date->gt($loan->due_date))
                                                    <span class="text-danger">
                                                        <i class="fas fa-exclamation-triangle"></i> Oui ({{ $loan->returned_date->diffInDays($loan->due_date) }} jours)
                                                    </span>
                                                @elseif($loan->status === 'returned')
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i> Non
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('library.books.show', $loan->book) }}" 
                                                       class="btn btn-outline-primary"
                                                       title="Voir le livre">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($loan->status === 'returned' && !$loan->was_reviewed && $loan->book->reviews_enabled)
                                                        <a href="{{ route('library.books.reviews.create', $loan->book) }}" 
                                                           class="btn btn-outline-success"
                                                           title="Donner mon avis">
                                                            <i class="fas fa-star"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#loanDetailsModal{{ $loan->id }}"
                                                            title="Détails">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal des détails de l'emprunt -->
                                                <div class="modal fade" id="loanDetailsModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Détails de l'emprunt</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <img src="{{ $loan->book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
                                                                             alt="{{ $loan->book->title }}" 
                                                                             class="img-fluid rounded">
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <h4>{{ $loan->book->title }}</h4>
                                                                        <p class="text-muted">{{ $loan->book->author }}</p>
                                                                        
                                                                        <div class="row mt-4">
                                                                            <div class="col-6">
                                                                                <h6>Informations d'emprunt</h6>
                                                                                <ul class="list-unstyled">
                                                                                    <li><strong>Date d'emprunt:</strong> {{ $loan->borrow_date->format('d/m/Y') }}</li>
                                                                                    <li><strong>Date de retour prévue:</strong> {{ $loan->due_date->format('d/m/Y') }}</li>
                                                                                    @if($loan->status === 'returned' && $loan->returned_date)
                                                                                        <li><strong>Date de retour effective:</strong> {{ $loan->returned_date->format('d/m/Y') }}</li>
                                                                                    @endif
                                                                                    @if($loan->renewal_count > 0)
                                                                                        <li><strong>Renouvelé:</strong> {{ $loan->renewal_count }} fois</li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <h6>Statut</h6>
                                                                                <p>
                                                                                    @if($loan->status === 'returned')
                                                                                        <span class="badge bg-success">Retourné</span>
                                                                                        @if($loan->returned_date->gt($loan->due_date))
                                                                                            <span class="text-danger">
                                                                                                ({{ $loan->returned_date->diffInDays($loan->due_date) }} jours de retard)
                                                                                            </span>
                                                                                        @endif
                                                                                    @elseif($loan->status === 'overdue')
                                                                                        <span class="badge bg-danger">En retard</span>
                                                                                        <div class="text-danger mt-1">
                                                                                            <i class="fas fa-exclamation-triangle"></i> 
                                                                                            {{ now()->diffInDays($loan->due_date) }} jour(s) de retard
                                                                                        </div>
                                                                                    @elseif($loan->status === 'cancelled')
                                                                                        <span class="badge bg-secondary">Annulé</span>
                                                                                    @endif
                                                                                </p>
                                                                                
                                                                                @if($loan->fine_amount > 0)
                                                                                    <div class="alert alert-warning mt-3">
                                                                                        <h6><i class="fas fa-exclamation-triangle"></i> Frais de retard</h6>
                                                                                        <p class="mb-0">
                                                                                            Montant dû: <strong>{{ number_format($loan->fine_amount, 2, ',', ' ') }} €</strong>
                                                                                            @if($loan->fine_paid)
                                                                                                <span class="badge bg-success ms-2">Payé</span>
                                                                                            @else
                                                                                                <a href="#" class="btn btn-sm btn-outline-primary ms-2">Payer en ligne</a>
                                                                                            @endif
                                                                                        </p>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                @if($loan->status === 'returned' && !$loan->was_reviewed && $loan->book->reviews_enabled)
                                                                    <a href="{{ route('library.books.reviews.create', $loan->book) }}" class="btn btn-primary">
                                                                        <i class="fas fa-star"></i> Donner mon avis
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Statistiques -->
                        <div class="row mt-4">
                            <div class="col-md-3 col-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $stats['total'] }}</h5>
                                        <p class="card-text text-muted small mb-0">Emprunt(s) au total</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $stats['returned_on_time'] }}</h5>
                                        <p class="card-text text-muted small mb-0">Retourné(s) à temps</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $stats['returned_late'] }}</h5>
                                        <p class="card-text text-muted small mb-0">Retourné(s) en retard</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $stats['renewed'] }}</h5>
                                        <p class="card-text text-muted small mb-0">Renouvellement(s)</p>
                                    </div>
                                </div>
                            </div>
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
                                {{ $loans->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal d'export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-file-export"></i> Exporter l'historique
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('library.loans.export') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="format" class="form-label">Format d'export</label>
                        <select class="form-select" id="format" name="format" required>
                            <option value="pdf">PDF (Portable Document Format)</option>
                            <option value="csv">CSV (Excel, Numbers, etc.)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="period" class="form-label">Période</label>
                        <select class="form-select" id="period" name="period">
                            <option value="all">Tous les emprunts</option>
                            <option value="year">Cette année ({{ now()->year }})</option>
                            <option value="last_year">L'année dernière ({{ now()->subYear()->year }})</option>
                            <option value="custom">Période personnalisée</option>
                        </select>
                    </div>
                    <div class="row mb-3 d-none" id="customPeriodFields">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_details" name="include_details" checked>
                        <label class="form-check-label" for="include_details">
                            Inclure les détails des emprunts
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Exporter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Afficher/masquer les champs de période personnalisée
    document.getElementById('period').addEventListener('change', function() {
        const customFields = document.getElementById('customPeriodFields');
        if (this.value === 'custom') {
            customFields.classList.remove('d-none');
        } else {
            customFields.classList.add('d-none');
        }
    });
    
    // Initialiser les dates par défaut pour la période personnalisée
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), 0, 1);
        const lastDay = new Date(today.getFullYear(), 11, 31);
        
        document.getElementById('start_date').valueAsDate = firstDay;
        document.getElementById('end_date').valueAsDate = lastDay;
    });
</script>
@endpush
