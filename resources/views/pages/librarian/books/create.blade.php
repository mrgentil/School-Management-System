@extends('layouts.master')
@section('page_title', 'Ajouter un Livre')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Ajouter un Nouveau Livre</h6>
        <div class="header-elements">
            <a href="{{ route('librarian.books.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left13 mr-2"></i> Retour
            </a>
        </div>
    </div>

    <form action="{{ route('librarian.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="card-body">
            <div class="row">
                <!-- Titre -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Titre du Livre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required placeholder="Entrez le titre du livre">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Auteur -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Auteur</label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" 
                               value="{{ old('author') }}" placeholder="Nom de l'auteur">
                        @error('author')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Type de livre -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Type de Livre</label>
                        <select name="book_type" class="form-control @error('book_type') is-invalid @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="Manuel" {{ old('book_type') == 'Manuel' ? 'selected' : '' }}>Manuel</option>
                            <option value="Roman" {{ old('book_type') == 'Roman' ? 'selected' : '' }}>Roman</option>
                            <option value="Référence" {{ old('book_type') == 'Référence' ? 'selected' : '' }}>Référence</option>
                            <option value="Magazine" {{ old('book_type') == 'Magazine' ? 'selected' : '' }}>Magazine</option>
                            <option value="Autre" {{ old('book_type') == 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('book_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Classe associée -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Classe Associée</label>
                        <select name="my_class_id" class="form-control @error('my_class_id') is-invalid @enderror">
                            <option value="">Aucune classe spécifique</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('my_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('my_class_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Nombre total de copies -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nombre Total de Copies</label>
                        <input type="number" name="total_copies" class="form-control @error('total_copies') is-invalid @enderror" 
                               value="{{ old('total_copies', 1) }}" min="1" placeholder="1">
                        @error('total_copies')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Copies empruntées -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Copies Empruntées</label>
                        <input type="number" name="issued_copies" class="form-control @error('issued_copies') is-invalid @enderror" 
                               value="{{ old('issued_copies', 0) }}" min="0" placeholder="0">
                        @error('issued_copies')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Localisation -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Localisation</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                               value="{{ old('location') }}" placeholder="Ex: Étagère A3">
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- URL (pour les livres numériques) -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>URL (Livre Numérique)</label>
                        <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" 
                               value="{{ old('url') }}" placeholder="https://...">
                        @error('url')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Description -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Description du livre...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Disponible -->
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="available" value="1" class="form-check-input" 
                                   id="available" {{ old('available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="available">
                                Livre disponible pour emprunt
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('librarian.books.index') }}" class="btn btn-light">
                <i class="icon-cross2 mr-2"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

@endsection
