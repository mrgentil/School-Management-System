@extends('layouts.student')

@section('title') {{ __('Nouvelle Demande de Livre') }} @endsection

@section('content')
    <div class="page-title">
        <h4 class="mb-3">
            {{ __('Nouvelle Demande de Livre') }}
            <a href="{{ route('student.book-requests.index') }}" class="btn btn-secondary btn-sm float-right">
                <i class="fas fa-arrow-left"></i> {{ __('Retour') }}
            </a>
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('student.book-requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="titre">{{ __('Titre du livre') }} <span class="text-danger">*</span></label>
                            <input type="text" name="titre" id="titre" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   value="{{ old('titre') }}" required>
                            @error('titre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="auteur">{{ __('Auteur') }} <span class="text-danger">*</span></label>
                            <input type="text" name="auteur" id="auteur" 
                                   class="form-control @error('auteur') is-invalid @enderror" 
                                   value="{{ old('auteur') }}" required>
                            @error('auteur')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="isbn">{{ __('ISBN (optionnel)') }}</label>
                            <input type="text" name="isbn" id="isbn" 
                                   class="form-control @error('isbn') is-invalid @enderror" 
                                   value="{{ old('isbn') }}">
                            @error('isbn')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">{{ __('Description (optionnel)') }}</label>
                            <textarea name="description" id="description" 
                                      class="form-control @error('description') is-invalid @enderror" 
                                      rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> {{ __('Soumettre la demande') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
