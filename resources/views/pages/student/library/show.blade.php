@extends('layouts.master')
@section('page_title', 'Détails du Livre')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{ $book->name }}</h5>
        <div class="header-elements">
            <a href="{{ route('student.library.index') }}" class="btn btn-primary">
                <i class="icon-arrow-left8"></i> Retour à la bibliothèque
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="icon-book icon-5x text-primary"></i>
                        <div class="mt-3">
                            <div class="mb-3">
                                <span class="badge badge-{{ $book->isAvailable() ? 'success' : 'danger' }} p-2">
                                    {{ $book->isAvailable() ? 'Disponible' : 'Non disponible' }}
                                </span>
                            </div>
                            @if($book->isAvailable())
                                <form method="POST" action="{{ route('student.library.request', $book->id) }}" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="icon-plus2"></i> Demander ce livre
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Détails du livre</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Auteur</th>
                                <td>{{ $book->author ?? 'Non spécifié' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>
                                    @switch($book->book_type)
                                        @case('textbook')
                                            Manuel scolaire
                                            @break
                                        @case('reference')
                                            Ouvrage de référence
                                            @break
                                        @case('fiction')
                                            Fiction
                                            @break
                                        @case('non-fiction')
                                            Non-fiction
                                            @break
                                        @default
                                            {{ $book->book_type ?? 'Non spécifié' }}
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>Localisation</th>
                                <td>{{ $book->location ?? 'Non spécifiée' }}</td>
                            </tr>
                            <tr>
                                <th>Exemplaires</th>
                                <td>
                                    {{ $book->available_copies }} disponible(s) sur {{ $book->total_copies }}
                                </td>
                            </tr>
                        </table>

                        @if($book->description)
                            <div class="mt-4">
                                <h6>Description</h6>
                                <p class="text-muted">{{ $book->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($relatedBooks->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Livres similaires</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($relatedBooks as $relatedBook)
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="icon-book icon-3x text-primary"></i>
                                        <h6 class="mt-2">
                                            <a href="{{ route('student.library.show', $relatedBook->id) }}">
                                                {{ $relatedBook->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small">{{ $relatedBook->author }}</p>
                                        <span class="badge badge-{{ $relatedBook->isAvailable() ? 'success' : 'secondary' }} mb-2">
                                            {{ $relatedBook->available_copies }} disponible(s)
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
