@extends('layouts.master')
@section('page_title', 'Mes Demandes d\'Emprunt')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Demandes d'Emprunt</h6>
        <div class="header-elements">
            <a href="{{ route('student.library.index') }}" class="btn btn-primary">
                <i class="icon-arrow-left8"></i> Retour à la bibliothèque
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Date de demande</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                    <tr>
                        <td>
                            <a href="{{ route('student.library.show', $request->book_id) }}">
                                {{ $request->book->name }}
                            </a>
                        </td>
                        <td>{{ $request->book->author ?? 'Non spécifié' }}</td>
                        <td>{{ $request->request_date->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($request->status)
                                @case('pending')
                                    <span class="badge badge-warning">En attente</span>
                                    @break
                                @case('approved')
                                    <span class="badge badge-success">Approuvé</span>
                                    @if($request->expected_return_date && $request->expected_return_date->isPast())
                                        <span class="badge badge-danger">En retard</span>
                                    @endif
                                    @break
                                @case('rejected')
                                    <span class="badge badge-danger">Rejeté</span>
                                    @break
                                @case('returned')
                                    <span class="badge badge-info">Retourné</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $request->status }}</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('student.library.show', $request->book_id) }}" class="btn btn-sm btn-primary">
                                <i class="icon-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="p-3">
                                <i class="icon-book text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucune demande d'emprunt trouvée</p>
                                <a href="{{ route('student.library.index') }}" class="btn btn-primary mt-2">
                                    <i class="icon-book"></i> Parcourir la bibliothèque
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $requests->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
