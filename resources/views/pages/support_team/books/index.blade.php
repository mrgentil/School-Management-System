@extends('layouts.master')
@section('page_title', 'Gestion des Livres')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Gestion des Livres de la Bibliothèque</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-books" class="nav-link active" data-toggle="tab">Tous les Livres</a></li>
                <li class="nav-item"><a href="#new-book" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Ajouter un Livre</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-books">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nom du Livre</th>
                            <th>Auteur</th>
                            <th>Type</th>
                            <th>Copies Totales</th>
                            <th>Copies Empruntées</th>
                            <th>Disponibles</th>
                            <th>Emplacement</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($books as $book)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->author ?? 'N/A' }}</td>
                                <td>{{ $book->book_type ?? 'N/A' }}</td>
                                <td>{{ $book->total_copies ?? 0 }}</td>
                                <td>{{ $book->issued_copies ?? 0 }}</td>
                                <td>
                                    <span class="badge badge-{{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                        {{ $book->available_copies }}
                                    </span>
                                </td>
                                <td>{{ $book->location ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                <a href="{{ route('books.show', $book->id) }}" class="dropdown-item"><i class="icon-eye"></i> Voir</a>
                                                @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('books.edit', $book->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Modifier</a>
                                                @endif
                                                @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $book->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Supprimer</a>
                                                    <form method="post" id="item-delete-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" class="hidden">@csrf @method('delete')</form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if(Qs::userIsTeamSA())
                <div class="tab-pane fade" id="new-book">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ajax-store" method="post" action="{{ route('books.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Nom du Livre <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Nom du livre">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Auteur</label>
                                    <div class="col-lg-9">
                                        <input name="author" value="{{ old('author') }}" type="text" class="form-control" placeholder="Nom de l'auteur">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Type de Livre</label>
                                    <div class="col-lg-9">
                                        <select name="book_type" class="form-control select">
                                            <option value="">Sélectionner le type</option>
                                            <option value="Manuel scolaire">Manuel scolaire</option>
                                            <option value="Roman">Roman</option>
                                            <option value="Documentaire">Documentaire</option>
                                            <option value="Référence">Référence</option>
                                            <option value="Fiction">Fiction</option>
                                            <option value="Non-fiction">Non-fiction</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Classe Associée</label>
                                    <div class="col-lg-9">
                                        <select name="my_class_id" class="form-control select">
                                            <option value="">Aucune classe spécifique</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Nombre Total de Copies</label>
                                    <div class="col-lg-9">
                                        <input name="total_copies" value="{{ old('total_copies', 1) }}" type="number" min="0" class="form-control" placeholder="Nombre total de copies">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Copies Empruntées</label>
                                    <div class="col-lg-9">
                                        <input name="issued_copies" value="{{ old('issued_copies', 0) }}" type="number" min="0" class="form-control" placeholder="Nombre de copies actuellement empruntées">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Emplacement</label>
                                    <div class="col-lg-9">
                                        <input name="location" value="{{ old('location') }}" type="text" class="form-control" placeholder="Emplacement dans la bibliothèque">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">URL (si livre numérique)</label>
                                    <div class="col-lg-9">
                                        <input name="url" value="{{ old('url') }}" type="url" class="form-control" placeholder="Lien vers le livre numérique">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                                    <div class="col-lg-9">
                                        <textarea name="description" class="form-control" rows="3" placeholder="Description du livre">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Ajouter le Livre <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{--Book List Ends--}}

@endsection
