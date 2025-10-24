@extends('layouts.master')
@section('page_title', 'Modifier le Livre - '.$book->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Modifier le Livre : {{ $book->name }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('books.update', $book->id) }}">
                        @csrf @method('PUT')
                        
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Nom du Livre <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $book->name }}" required type="text" class="form-control" placeholder="Nom du livre">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Auteur</label>
                            <div class="col-lg-9">
                                <input name="author" value="{{ $book->author }}" type="text" class="form-control" placeholder="Nom de l'auteur">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Type de Livre</label>
                            <div class="col-lg-9">
                                <select name="book_type" class="form-control select">
                                    <option value="">Sélectionner le type</option>
                                    <option value="Manuel scolaire" {{ $book->book_type == 'Manuel scolaire' ? 'selected' : '' }}>Manuel scolaire</option>
                                    <option value="Roman" {{ $book->book_type == 'Roman' ? 'selected' : '' }}>Roman</option>
                                    <option value="Documentaire" {{ $book->book_type == 'Documentaire' ? 'selected' : '' }}>Documentaire</option>
                                    <option value="Référence" {{ $book->book_type == 'Référence' ? 'selected' : '' }}>Référence</option>
                                    <option value="Fiction" {{ $book->book_type == 'Fiction' ? 'selected' : '' }}>Fiction</option>
                                    <option value="Non-fiction" {{ $book->book_type == 'Non-fiction' ? 'selected' : '' }}>Non-fiction</option>
                                    <option value="Autre" {{ $book->book_type == 'Autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Classe Associée</label>
                            <div class="col-lg-9">
                                <select name="my_class_id" class="form-control select">
                                    <option value="">Aucune classe spécifique</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $book->my_class_id == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Nombre Total de Copies</label>
                            <div class="col-lg-9">
                                <input name="total_copies" value="{{ $book->total_copies }}" type="number" min="0" class="form-control" placeholder="Nombre total de copies">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Copies Empruntées</label>
                            <div class="col-lg-9">
                                <input name="issued_copies" value="{{ $book->issued_copies }}" type="number" min="0" class="form-control" placeholder="Nombre de copies actuellement empruntées">
                                <small class="form-text text-muted">
                                    Copies disponibles : {{ $book->available_copies }}
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Emplacement</label>
                            <div class="col-lg-9">
                                <input name="location" value="{{ $book->location }}" type="text" class="form-control" placeholder="Emplacement dans la bibliothèque">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">URL (si livre numérique)</label>
                            <div class="col-lg-9">
                                <input name="url" value="{{ $book->url }}" type="url" class="form-control" placeholder="Lien vers le livre numérique">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Description</label>
                            <div class="col-lg-9">
                                <textarea name="description" class="form-control" rows="4" placeholder="Description du livre">{{ $book->description }}</textarea>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Informations</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="icon-info22"></i>
                                <strong>Attention :</strong> Assurez-vous que le nombre de copies empruntées ne dépasse pas le nombre total de copies.
                            </div>

                            <div class="list-group">
                                <div class="list-group-item">
                                    <strong>Créé le :</strong><br>
                                    {{ $book->created_at->format('d/m/Y à H:i') }}
                                </div>
                                <div class="list-group-item">
                                    <strong>Dernière modification :</strong><br>
                                    {{ $book->updated_at->format('d/m/Y à H:i') }}
                                </div>
                                <div class="list-group-item">
                                    <strong>Statut actuel :</strong><br>
                                    @if($book->available_copies > 0)
                                        <span class="badge badge-success">{{ $book->available_copies }} disponible(s)</span>
                                    @else
                                        <span class="badge badge-danger">Indisponible</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-info btn-block">
                                    <i class="icon-eye"></i> Voir les Détails
                                </a>
                                <a href="{{ route('books.index') }}" class="btn btn-secondary btn-block">
                                    <i class="icon-arrow-left7"></i> Retour à la Liste
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
