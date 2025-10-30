@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Bibliothèque</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Colonne de gauche - Image et actions -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="{{ $book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
                     class="card-img-top" 
                     alt="{{ $book->title }}"
                     style="max-height: 400px; object-fit: contain;">
                
                <div class="card-body">
                    <!-- Boutons d'action -->
                    @if($book->isAvailable() && !$hasBorrowed)
                        <form action="{{ route('library.loans.borrow', $book) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-book-reader"></i> Emprunter ce livre
                            </button>
                        </form>
                    @elseif(!$book->isAvailable())
                        <form action="{{ route('library.reservations.reserve', $book) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100" 
                                    {{ $book->reservations()->pending()->where('user_id', auth()->id())->exists() ? 'disabled' : '' }}>
                                <i class="fas fa-bookmark"></i> 
                                {{ $book->reservations()->pending()->where('user_id', auth()->id())->exists() 
                                    ? 'Déjà réservé' 
                                    : 'Réserver ce livre' }}
                            </button>
                        </form>
                    @endif

                    @if($book->type === 'numerique' && $hasBorrowed)
                        <a href="{{ route('library.books.download', $book) }}" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    @endif

                    @if(!$hasReviewed && $hasBorrowed)
                        <a href="{{ route('library.books.reviews.create', $book) }}" class="btn btn-outline-info w-100 mb-3">
                            <i class="fas fa-star"></i> Donner mon avis
                        </a>
                    @endif

                    <div class="d-grid gap-2">
                        @if($book->file_path && $book->type === 'numerique')
                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#previewModal">
                                <i class="fas fa-eye"></i> Aperçu
                            </button>
                        @endif
                        
                        @if($book->url)
                            <a href="{{ $book->url }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-external-link-alt"></i> Voir en ligne
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Disponibilité:</strong>
                            @if($book->isAvailable())
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> En stock
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle"></i> Indisponible
                                </span>
                            @endif
                        </div>
                        <span class="badge bg-{{ $book->type === 'numerique' ? 'info' : 'secondary' }}">
                            {{ $book->type === 'numerique' ? 'Numérique' : 'Papier' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne de droite - Détails du livre -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">{{ $book->title }}</h1>
                    <p class="text-muted mb-0">
                        Par {{ $book->author }}
                        @if($book->publication_year)
                            &middot; {{ $book->publication_year }}
                        @endif
                    </p>
                </div>
                
                <div class="card-body">
                    <!-- Métadonnées -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                @if($book->isbn)
                                    <li><strong>ISBN:</strong> {{ $book->isbn }}</li>
                                @endif
                                @if($book->publisher)
                                    <li><strong>Éditeur:</strong> {{ $book->publisher }}</li>
                                @endif
                                @if($book->edition)
                                    <li><strong>Édition:</strong> {{ $book->edition }}</li>
                                @endif
                                @if($book->pages)
                                    <li><strong>Pages:</strong> {{ $book->pages }}</li>
                                @endif
                                @if($book->language)
                                    <li><strong>Langue:</strong> {{ $book->language }}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                @if($book->category)
                                    <li>
                                        <strong>Catégorie:</strong> 
                                        <a href="{{ route('library.search', ['category' => $book->category_id]) }}">
                                            {{ $book->category->name }}
                                        </a>
                                    </li>
                                @endif
                                @if($book->subject)
                                    <li>
                                        <strong>Matière:</strong> 
                                        <a href="{{ route('library.search', ['subject' => $book->subject_id]) }}">
                                            {{ $book->subject->name }}
                                        </a>
                                    </li>
                                @endif
                                @if($book->shelf_location)
                                    <li><strong>Localisation:</strong> {{ $book->shelf_location }}</li>
                                @endif
                                <li>
                                    <strong>Exemplaires:</strong> 
                                    {{ $book->available_copies }} disponible(s) sur {{ $book->total_copies }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5>Description</h5>
                        <div class="border rounded p-3 bg-light">
                            {!! $book->description ? nl2br(e($book->description)) : '<em>Aucune description disponible.</em>' !!}
                        </div>
                    </div>

                    <!-- Mots-clés -->
                    @if($book->keywords)
                        <div class="mb-4">
                            <h5>Mots-clés</h5>
                            <div>
                                @foreach(explode(',', $book->keywords) as $keyword)
                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        {{ trim($keyword) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Note moyenne -->
                    <div class="mb-4">
                        <h5>Note moyenne</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="display-4 fw-bold">{{ number_format($book->rating, 1) }}</div>
                            </div>
                            <div>
                                <div class="mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= floor($book->rating) ? 'text-warning' : ($i - 0.5 <= $book->rating ? 'text-warning fas fa-star-half-alt' : 'text-muted') }}"></i>
                                    @endfor
                                    <span class="ms-2">({{ $book->reviews_count }} avis)</span>
                                </div>
                                <div class="progress" style="height: 10px; width: 200px;">
                                    @php
                                        $percentage = $book->rating * 20; // Convertir la note sur 5 en pourcentage
                                    @endphp
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: {{ $percentage }}%" 
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avis des utilisateurs -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Avis des utilisateurs</h5>
                    @if($hasBorrowed && !$hasReviewed)
                        <a href="{{ route('library.books.reviews.create', $book) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Donner mon avis
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($book->reviews->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucun avis pour le moment.</p>
                            @if($hasBorrowed && !$hasReviewed)
                                <p class="mt-2">
                                    <a href="{{ route('library.books.reviews.create', $book) }}">
                                        Soyez le premier à donner votre avis !
                                    </a>
                                </p>
                            @endif
                        </div>
                    @else
                        @foreach($book->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <span class="text-muted small ms-2">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                                
                                @if($review->user_id === auth()->id())
                                    <div class="mt-2">
                                        <a href="{{ route('library.reviews.edit', $review) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('library.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        
                        @if($book->reviews->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-outline-primary">
                                    Voir plus d'avis <i class="fas fa-arrow-down"></i>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aperçu -->
@if($book->file_path && $book->type === 'numerique')
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Aperçu de {{ $book->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 70vh;">
                <iframe src="{{ route('library.books.preview', $book) }}" 
                        style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <a href="{{ route('library.books.download', $book) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Télécharger
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .book-cover {
        max-height: 400px;
        object-fit: contain;
    }
    .progress {
        background-color: #e9ecef;
    }
    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endpush
