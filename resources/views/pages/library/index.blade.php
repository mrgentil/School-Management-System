@extends('layouts.app')

@section('title', 'Bibliothèque')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-book"></i> Bibliothèque
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Barre de recherche -->
                    <div class="row mb-4">
                        <div class="col-md-8 offset-md-2">
                            <form action="{{ route('library.search') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" 
                                           name="q" 
                                           class="form-control form-control-lg" 
                                           placeholder="Rechercher un livre par titre, auteur ou ISBN..."
                                           value="{{ request('q') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i> Rechercher
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Catégories -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-tags"></i> Catégories
                            </h5>
                            <div class="d-flex flex-wrap">
                                @forelse($categories as $category)
                                    <a href="{{ route('library.search', ['category' => $category->id]) }}" 
                                       class="btn btn-outline-primary btn-sm m-1">
                                        {{ $category->name }} 
                                        <span class="badge bg-primary">{{ $category->books_count }}</span>
                                    </a>
                                @empty
                                    <p class="text-muted">Aucune catégorie disponible pour le moment.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Derniers ajouts -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>
                                    <i class="fas fa-clock"></i> Derniers ajouts
                                </h5>
                                <a href="{{ route('library.search') }}" class="btn btn-sm btn-outline-primary">
                                    Voir plus <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="row">
                                @forelse($recentBooks as $book)
                                    <div class="col-md-3 mb-4">
                                        @include('pages.library.books.partials.book-card', ['book' => $book])
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Aucun livre n'a été ajouté récemment.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Livres populaires -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>
                                    <i class="fas fa-fire"></i> Livres populaires
                                </h5>
                                <a href="{{ route('library.search', ['sort' => 'popular']) }}" class="btn btn-sm btn-outline-primary">
                                    Voir plus <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="row">
                                @forelse($popularBooks as $book)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="row no-gutters">
                                                <div class="col-md-4">
                                                    <img src="{{ $book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
                                                         class="card-img" 
                                                         alt="{{ $book->title }}"
                                                         style="height: 100%; object-fit: cover;">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h6 class="card-title">
                                                            <a href="{{ route('library.books.show', $book) }}">
                                                                {{ $book->title }}
                                                            </a>
                                                        </h6>
                                                        <p class="card-text text-muted small mb-1">
                                                            {{ $book->author }}
                                                        </p>
                                                        <div class="mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $book->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                            <small class="text-muted">({{ $book->reviews_count }} avis)</small>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-{{ $book->isAvailable() ? 'success' : 'secondary' }}">
                                                                {{ $book->available_copies }} disponible(s)
                                                            </span>
                                                            <a href="{{ route('library.books.show', $book) }}" class="btn btn-sm btn-outline-primary">
                                                                Voir plus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            Aucun livre populaire pour le moment.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('library.loans.current') }}" class="btn btn-outline-primary">
                                <i class="fas fa-book-reader"></i> Mes emprunts
                            </a>
                            <a href="{{ route('library.reservations.active') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-bookmark"></i> Mes réservations
                            </a>
                        </div>
                        <div>
                            <a href="#" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#libraryHelpModal">
                                <i class="fas fa-question-circle"></i> Aide
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aide -->
<div class="modal fade" id="libraryHelpModal" tabindex="-1" aria-labelledby="libraryHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libraryHelpModalLabel">
                    <i class="fas fa-question-circle"></i> Aide de la bibliothèque
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Comment emprunter un livre ?</h6>
                <p>Pour emprunter un livre, recherchez-le dans la barre de recherche ou parcourez les catégories. Cliquez sur le livre qui vous intéresse, puis sur le bouton "Emprunter".</p>
                
                <h6>Durée d'emprunt</h6>
                <p>La durée d'emprunt est de 14 jours. Vous pouvez renouveler votre emprunt une seule fois pour une durée supplémentaire de 14 jours, à condition que le livre ne soit pas réservé par un autre utilisateur.</p>
                
                <h6>Réserver un livre</h6>
                <p>Si un livre est déjà emprunté, vous pouvez le réserver. Vous serez notifié par email dès qu'il sera disponible. Vous aurez alors 3 jours pour venir le récupérer.</p>
                
                <h6>Retour d'un livre</h6>
                <p>Pour les livres numériques, le retour est automatique à la fin de la période d'emprunt. Pour les livres physiques, merci de les rapporter à l'accueil de la bibliothèque.</p>
                
                <h6>Problèmes ou questions ?</h6>
                <p>Si vous rencontrez des difficultés ou avez des questions, n'hésitez pas à contacter le service de la bibliothèque à l'adresse <a href="mailto:bibliotheque@ecole.fr">bibliotheque@ecole.fr</a>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .book-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .book-cover {
        height: 200px;
        object-fit: cover;
    }
    .book-badge {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>
@endpush
