@extends('layouts.app')

@section('title', 'Mes réservations en cours')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Bibliothèque</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mes réservations</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-bookmark"></i> Mes réservations en cours
                    </h4>
                    <div>
                        <a href="{{ route('library.reservations.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-history"></i> Historique
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($reservations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-bookmark fa-4x text-muted mb-4"></i>
                            <h4>Vous n'avez aucune réservation en cours</h4>
                            <p class="text-muted">
                                Parcourez notre catalogue pour réserver votre prochaine lecture !
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
                                        <th>Date de réservation</th>
                                        <th>Date d'expiration</th>
                                        <th>Statut</th>
                                        <th>Disponibilité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        @php
                                            $isExpired = $reservation->status === 'expired' || 
                                                        ($reservation->expiration_date && $reservation->expiration_date->isPast());
                                            $isAvailable = $reservation->book->isAvailable();
                                            $isReady = $reservation->status === 'ready_for_pickup';
                                            $isPending = $reservation->status === 'pending';
                                        @endphp
                                        
                                        <tr class="{{ $isExpired ? 'table-secondary' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $reservation->book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
                                                         alt="{{ $reservation->book->title }}" 
                                                         style="width: 40px; height: 60px; object-fit: cover;"
                                                         class="me-3 rounded">
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="{{ route('library.books.show', $reservation->book) }}" class="text-decoration-none">
                                                                {{ $reservation->book->title }}
                                                            </a>
                                                        </div>
                                                        <div class="text-muted small">
                                                            {{ $reservation->book->author }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $reservation->reservation_date->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($reservation->expiration_date)
                                                    {{ $reservation->expiration_date->format('d/m/Y H:i') }}
                                                    @if($isExpired)
                                                        <div class="text-danger small">
                                                            <i class="fas fa-exclamation-triangle"></i> Expirée
                                                        </div>
                                                    @else
                                                        <div class="text-muted small">
                                                            (dans {{ $reservation->expiration_date->diffForHumans(now(), ['parts' => 2, 'join' => true]) }})
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isReady)
                                                    <span class="badge bg-success">Prêt à être récupéré</span>
                                                @elseif($isPending)
                                                    <span class="badge bg-warning text-dark">En attente</span>
                                                    @if($reservation->position_in_queue > 0)
                                                        <div class="text-muted small">
                                                            Position #{{ $reservation->position_in_queue }} dans la file d'attente
                                                        </div>
                                                    @endif
                                                @elseif($isExpired)
                                                    <span class="badge bg-secondary">Expirée</span>
                                                @else
                                                    <span class="badge bg-info">{{ ucfirst($reservation->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isAvailable)
                                                    <span class="badge bg-success">Disponible</span>
                                                @else
                                                    <span class="badge bg-secondary">Indisponible</span>
                                                    @if($reservation->book->available_copies_soon > 0)
                                                        <div class="text-muted small">
                                                            {{ $reservation->book->available_copies_soon }} copie(s) bientôt disponible(s)
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('library.books.show', $reservation->book) }}" 
                                                       class="btn btn-outline-primary"
                                                       title="Voir le livre">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($isReady)
                                                        <a href="{{ route('library.reservations.pickup', $reservation) }}" 
                                                           class="btn btn-success"
                                                           title="Confirmer la récupération">
                                                            <i class="fas fa-check"></i> Récupérer
                                                        </a>
                                                    @endif
                                                    
                                                    @if($isPending || $isReady)
                                                        <form action="{{ route('library.reservations.cancel', $reservation) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" 
                                                                    title="Annuler la réservation">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($isExpired)
                                                        <form action="{{ route('library.reservations.renew', $reservation) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-primary" 
                                                                    title="Renouveler la réservation">
                                                                <i class="fas fa-redo"></i> Renouveler
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
                        
                        <!-- Informations importantes -->
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-info-circle"></i> Informations importantes</h5>
                            <ul class="mb-0">
                                <li>Une fois le livre disponible, vous aurez 3 jours pour venir le récupérer.</li>
                                <li>Vous pouvez annuler une réservation à tout moment avant sa récupération.</li>
                                <li>Si vous ne récupérez pas un livre réservé à temps, la réservation sera automatiquement annulée.</li>
                                <li>Vous pouvez avoir jusqu'à 3 réservations en même temps.</li>
                            </ul>
                        </div>
                    @endif
                </div>
                
                @if(!$reservations->isEmpty())
                    <div class="card-footer text-muted">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                {{ $reservations->total() > 1 ? $reservations->count() . ' réservations trouvées' : '1 réservation trouvée' }}
                            </div>
                            <div>
                                {{ $reservations->links() }}
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
                    <i class="fas fa-question-circle"></i> Aide - Gestion des réservations
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Comment réserver un livre ?</h6>
                <p>Pour réserver un livre, recherchez-le dans le catalogue et cliquez sur le bouton "Réserver" sur la page du livre. 
                Vous serez notifié par email dès que le livre sera disponible.</p>
                
                <h6 class="mt-4">Combien de temps dure une réservation ?</h6>
                <p>Une fois le livre disponible, vous avez 3 jours pour venir le récupérer. Passé ce délai, la réservation est annulée automatiquement.</p>
                
                <h6 class="mt-4">Puis-je annuler une réservation ?</h6>
                <p>Oui, vous pouvez annuler une réservation à tout moment avant de venir récupérer le livre en cliquant sur le bouton d'annulation.</p>
                
                <h6 class="mt-4">Combien de réservations puis-je avoir en même temps ?</h6>
                <p>Vous pouvez avoir jusqu'à 3 réservations actives en même temps.</p>
                
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
    // Afficher une notification si l'utilisateur a des réservations prêtes à être récupérées
    document.addEventListener('DOMContentLoaded', function() {
        const readyReservations = {!! $reservations->where('status', 'ready_for_pickup')->count() !!};
        
        if (readyReservations > 0) {
            Swal.fire({
                icon: 'info',
                title: 'Livre(s) disponible(s)',
                html: `Vous avez ${readyReservations} livre(s) prêt(s) à être récupéré(s) à la bibliothèque.`,
                confirmButtonText: 'Voir mes réservations',
                confirmButtonColor: '#0d6efd',
                showCancelButton: true,
                cancelButtonText: 'Plus tard',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('library.reservations.active') }}#ready-for-pickup';
                }
            });
        }
    });
    
    // Confirmation de suppression
    document.querySelectorAll('.cancel-reservation').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Confirmer l\'annulation',
                text: 'Êtes-vous sûr de vouloir annuler cette réservation ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, annuler',
                cancelButtonText: 'Non, garder',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
