@extends('layouts.student')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Réservations Actives</h5>
                    <a href="{{ route('student.library.books.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-book"></i> Voir la bibliothèque
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($reservations->isEmpty())
                        <div class="alert alert-info">
                            Vous n'avez aucune réservation active pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Livre</th>
                                        <th>Date de réservation</th>
                                        <th>Date d'expiration</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($reservation->book->cover_image)
                                                        <img src="{{ Storage::url($reservation->book->cover_image) }}" 
                                                             alt="Couverture de {{ $reservation->book->title }}" 
                                                             class="img-thumbnail me-3" style="width: 50px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 50px; height: 70px;">
                                                            <i class="fas fa-book text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $reservation->book->title }}</h6>
                                                        <small class="text-muted">
                                                            {{ $reservation->book->author }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $reservation->reservation_date->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="{{ $reservation->is_expired ? 'text-danger' : '' }}">
                                                    {{ $reservation->expiration_date->format('d/m/Y H:i') }}
                                                    @if($reservation->is_expired)
                                                        <span class="badge bg-danger">Expiré</span>
                                                    @else
                                                        <div class="text-muted small">
                                                            ({{ $reservation->expiration_date->diffForHumans() }})
                                                        </div>
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @switch($reservation->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Approuvée</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejetée</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-secondary">Annulée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-info">{{ $reservation->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('student.library.books.show', $reservation->book_id) }}" 
                                                       class="btn btn-outline-primary" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($reservation->status === 'pending' || $reservation->status === 'approved')
                                                        <button type="button" 
                                                                class="btn btn-outline-danger" 
                                                                onclick="confirmCancel('{{ $reservation->id }}')"
                                                                title="Annuler la réservation">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <form id="cancel-form-{{ $reservation->id }}" 
                                                              action="{{ route('student.library.reservations.cancel', $reservation) }}" 
                                                              method="POST" 
                                                              style="display: none;">
                                                            @csrf
                                                            @method('PUT')
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reservations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmCancel(reservationId) {
        Swal.fire({
            title: 'Confirmer l\'annulation',
            text: 'Êtes-vous sûr de vouloir annuler cette réservation ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder la réservation'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-form-' + reservationId).submit();
            }
        });
    }
</script>
@endpush

@endsection
