@extends('layouts.master')
@section('page_title', 'Demandes d\'Emprunt')
@section('content')

<!-- Statistiques -->
<div class="row mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-hour-glass2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                    <span class="text-uppercase font-size-xs">En Attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark-circle icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                    <span class="text-uppercase font-size-xs">Approuvées</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-info-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['borrowed'] }}</h3>
                    <span class="text-uppercase font-size-xs">Empruntés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['overdue'] }}</h3>
                    <span class="text-uppercase font-size-xs">En Retard</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des demandes -->
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Liste des Demandes d'Emprunt</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a href="{{ route('librarian.book-requests.overdue') }}" class="btn btn-danger btn-sm">
                    <i class="icon-alarm mr-2"></i> Livres en Retard ({{ $stats['overdue'] }})
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtres -->
        <form method="GET" action="{{ route('librarian.book-requests.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En Attente</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvées</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Empruntés</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Retournés</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" placeholder="Date début" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="icon-search4"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Date Demande</th>
                        <th>Date Retour Prévue</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>
                            @if($request->student)
                                {{ $request->student->user->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($request->book)
                                <strong>{{ $request->book->name }}</strong>
                                @if($request->book->author)
                                    <br><small class="text-muted">{{ $request->book->author }}</small>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') : 'N/A' }}</td>
                        <td>
                            @if($request->expected_return_date)
                                {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}
                                @if($request->status == 'borrowed' && \Carbon\Carbon::parse($request->expected_return_date)->isPast())
                                    <span class="badge badge-danger ml-2">En retard</span>
                                @endif
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($request->status == 'pending')
                                <span class="badge badge-warning">En Attente</span>
                            @elseif($request->status == 'approved')
                                <span class="badge badge-success">Approuvée</span>
                            @elseif($request->status == 'borrowed')
                                <span class="badge badge-info">Emprunté</span>
                            @elseif($request->status == 'returned')
                                <span class="badge badge-primary">Retourné</span>
                            @elseif($request->status == 'rejected')
                                <span class="badge badge-danger">Rejetée</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('librarian.book-requests.show', $request->id) }}" class="btn btn-sm btn-primary">
                                <i class="icon-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="icon-info22 mr-2"></i> Aucune demande trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $requests->links() }}
        </div>
    </div>
</div>

@endsection
