@extends('layouts.master')

@section('page_title', 'Mes Demandes de Livres')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="icon-books"></i> Mes Demandes de Livres
                    </h3>
                    <a href="{{ route('student.library.index') }}" class="btn btn-primary">
                        <i class="icon-plus"></i> Nouvelle demande
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="icon-checkmark-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="icon-close-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Livre</th>
                                        <th>Auteur</th>
                                        <th>Date de demande</th>
                                        <th>Date de retour prévue</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{ $loop->iteration + ($requests->currentPage() - 1) * $requests->perPage() }}</td>
                                            <td>
                                                <strong>{{ $request->book->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>{{ $request->book->author ?? 'N/A' }}</td>
                                            <td>
                                                <i class="icon-calendar"></i>
                                                {{ $request->created_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if($request->expected_return_date)
                                                    <i class="icon-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}
                                                    <br>
                                                    @if($request->status === 'borrowed' && \Carbon\Carbon::parse($request->expected_return_date)->isPast())
                                                        <small class="text-danger">
                                                            <i class="icon-warning"></i> En retard!
                                                        </small>
                                                    @else
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($request->expected_return_date)->diffForHumans() }}
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Non définie</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $request->badge_class }}">
                                                    @if($request->status === 'pending')
                                                        <i class="icon-clock"></i> En attente
                                                    @elseif($request->status === 'approved')
                                                        <i class="icon-checkmark"></i> Approuvé
                                                    @elseif($request->status === 'borrowed')
                                                        <i class="icon-book"></i> Emprunté
                                                    @elseif($request->status === 'returned')
                                                        <i class="icon-checkmark-circle"></i> Retourné
                                                    @else
                                                        <i class="icon-close"></i> Rejeté
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('student.library.requests.show', $request->id) }}" 
                                                       class="btn btn-sm btn-info"
                                                       title="Voir les détails">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                    
                                                    @if($request->canBeCancelled())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="confirmCancel({{ $request->id }})"
                                                                title="Annuler la demande">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                        
                                                        <form id="cancel-form-{{ $request->id }}" 
                                                              action="{{ route('student.library.requests.cancel', $request->id) }}" 
                                                              method="POST" 
                                                              style="display: none;">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-books text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">Aucune demande de livre</h4>
                            <p class="text-muted">Vous n'avez pas encore fait de demande d'emprunt de livre.</p>
                            <a href="{{ route('student.library.index') }}" class="btn btn-primary mt-3">
                                <i class="icon-plus"></i> Parcourir la bibliothèque
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCancel(requestId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette demande de livre?')) {
        document.getElementById('cancel-form-' + requestId).submit();
    }
}
</script>

<style>
.badge {
    padding: 0.5em 0.75em;
    font-size: 0.875rem;
}
</style>
@endsection
