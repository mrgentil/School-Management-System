@extends('layouts.master')
@section('page_title', 'Gestion des Livres')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Liste des Livres</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a href="{{ route('librarian.books.create') }}" class="btn btn-primary">
                    <i class="icon-plus2 mr-2"></i> Ajouter un Livre
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtres -->
        <form method="GET" action="{{ route('librarian.books.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher (titre, auteur, ISBN...)" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="book_type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="Manuel" {{ request('book_type') == 'Manuel' ? 'selected' : '' }}>Manuel</option>
                        <option value="Roman" {{ request('book_type') == 'Roman' ? 'selected' : '' }}>Roman</option>
                        <option value="Référence" {{ request('book_type') == 'Référence' ? 'selected' : '' }}>Référence</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="availability" class="form-control">
                        <option value="">Tous</option>
                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Disponibles</option>
                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Non disponibles</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="icon-search4"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Tableau des livres -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Type</th>
                        <th>Copies Totales</th>
                        <th>Copies Empruntées</th>
                        <th>Disponible</th>
                        <th>Localisation</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>
                            <strong>{{ $book->name }}</strong>
                            @if($book->description)
                                <br><small class="text-muted">{{ Str::limit($book->description, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ $book->author ?? 'N/A' }}</td>
                        <td>{{ $book->book_type ?? 'N/A' }}</td>
                        <td>{{ $book->total_copies ?? 0 }}</td>
                        <td>{{ $book->issued_copies ?? 0 }}</td>
                        <td>
                            @if($book->available)
                                <span class="badge badge-success">Oui</span>
                            @else
                                <span class="badge badge-danger">Non</span>
                            @endif
                        </td>
                        <td>{{ $book->location ?? 'N/A' }}</td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{ route('librarian.books.show', $book->id) }}" class="dropdown-item">
                                            <i class="icon-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('librarian.books.edit', $book->id) }}" class="dropdown-item">
                                            <i class="icon-pencil"></i> Modifier
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) document.getElementById('delete-form-{{ $book->id }}').submit();" class="dropdown-item text-danger">
                                            <i class="icon-trash"></i> Supprimer
                                        </a>
                                        <form id="delete-form-{{ $book->id }}" action="{{ route('librarian.books.destroy', $book->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="icon-info22 mr-2"></i> Aucun livre trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    </div>
</div>

@endsection
