@extends('layouts.master')
@section('page_title', 'Détails du Livre - '.$book->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Détails du Livre : {{ $book->name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td class="font-weight-bold">Nom du Livre</td>
                            <td>{{ $book->name }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Auteur</td>
                            <td>{{ $book->author ?? 'Non spécifié' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Type de Livre</td>
                            <td>{{ $book->book_type ?? 'Non spécifié' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Classe Associée</td>
                            <td>{{ $book->myClass->name ?? 'Aucune classe spécifique' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Nombre Total de Copies</td>
                            <td>{{ $book->total_copies ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Copies Empruntées</td>
                            <td>{{ $book->issued_copies ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Copies Disponibles</td>
                            <td>
                                <span class="badge badge-{{ $book->available_copies > 0 ? 'success' : 'danger' }} badge-lg">
                                    {{ $book->available_copies }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Emplacement</td>
                            <td>{{ $book->location ?? 'Non spécifié' }}</td>
                        </tr>
                        @if($book->url)
                        <tr>
                            <td class="font-weight-bold">Lien Numérique</td>
                            <td><a href="{{ $book->url }}" target="_blank" class="btn btn-sm btn-info">Accéder au livre numérique</a></td>
                        </tr>
                        @endif
                        <tr>
                            <td class="font-weight-bold">Description</td>
                            <td>{{ $book->description ?? 'Aucune description disponible' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Ajouté le</td>
                            <td>{{ $book->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Dernière modification</td>
                            <td>{{ $book->updated_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Actions</h6>
                        </div>
                        <div class="card-body">
                            @if(Qs::userIsTeamSA())
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-block">
                                    <i class="icon-pencil"></i> Modifier ce Livre
                                </a>
                            @endif
                            
                            <a href="{{ route('books.index') }}" class="btn btn-secondary btn-block">
                                <i class="icon-arrow-left7"></i> Retour à la Liste
                            </a>

                            @if($book->available_copies > 0)
                                <div class="alert alert-success mt-3">
                                    <i class="icon-checkmark3"></i> Ce livre est disponible pour emprunt
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    <i class="icon-warning"></i> Toutes les copies sont actuellement empruntées
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($book->bookRequests && $book->bookRequests->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Historique des Emprunts</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @foreach($book->bookRequests->take(5) as $request)
                                    <div class="list-group-item">
                                        <div class="font-weight-semibold">{{ $request->user->name }}</div>
                                        <div class="text-muted small">
                                            {{ $request->created_at->format('d/m/Y') }}
                                            - <span class="badge badge-{{ $request->status == 'approved' ? 'success' : ($request->status == 'returned' ? 'info' : 'warning') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($book->bookRequests->count() > 5)
                                <a href="{{ route('book-requests.index', ['book_id' => $book->id]) }}" class="btn btn-sm btn-link">
                                    Voir tous les emprunts ({{ $book->bookRequests->count() }})
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
