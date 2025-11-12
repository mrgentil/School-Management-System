@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Bibliothèque</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recherche</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Filtres -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> Filtres
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('library.search') }}" method="GET">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        
                        <!-- Catégorie -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Catégorie</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="category" 
                                       id="all_categories" 
                                       value=""
                                       {{ !request('category') ? 'checked' : '' }}>
                                <label class="form-check-label" for="all_categories">
                                    Toutes catégories
                                </label>
                            </div>
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="category" 
                                           id="category_{{ $category->id }}" 
                                           value="{{ $category->id }}"
                                           {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex justify-content-between" for="category_{{ $category->id }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge bg-secondary">{{ $category->books_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Type de livre -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Type</label>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="type" 
                                       id="all_types" 
                                       value=""
                                       {{ !request('type') ? 'checked' : '' }}>
                                <label class="form-check-label" for="all_types">
                                    Tous les types
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="type" 
                                       id="type_physique" 
                                       value="physique"
                                       {{ request('type') === 'physique' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_physique">
                                    Livres papier
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="type" 
                                       id="type_numerique" 
                                       value="numerique"
                                       {{ request('type') === 'numerique' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_numerique">
                                    Livres numériques
                                </label>
                            </div>
                        </div>
                        
                        <!-- Disponibilité -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="available_only" 
                                       id="available_only" 
                                       value="1"
                                       {{ request('available_only') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="available_only">
                                    Afficher uniquement les livres disponibles
                                </label>
                            </div>
                        </div>
                        
                        <!-- Note minimale -->
                        <div class="mb-4">
                            <label for="min_rating" class="form-label fw-bold">Note minimale</label>
                            <div class="d-flex align-items-center">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="min_rating" 
                                               id="rating_{{ $i }}" 
                                               value="{{ $i }}"
                                               {{ (int)request('min_rating', 0) === $i ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rating_{{ $i }}">
                                            {{ $i }}
                                        </label>
                                    </div>
                                @endfor
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="min_rating" 
                                           id="rating_0" 
                                           value=""
                                           {{ !request('min_rating') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rating_0">
                                        Tous
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Appliquer les filtres
                            </button>
                            <a href="{{ route('library.search') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Suggestions de recherche -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Suggestions
                    </h5>
                </div>
                <div class="card-body">
                    <p>Essayez ces recherches populaires :</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('library.search', ['q' => 'roman']) }}" class="text-decoration-none">
                                <i class="fas fa-search text-muted me-1"></i> Romans
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('library.search', ['q' => 'programmation']) }}" class="text-decoration-none">
                                <i class="fas fa-search text-muted me-1"></i> Programmation
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('library.search', ['available_only' => 1]) }}" class="text-decoration-none">
                                <i class="fas fa-search text-muted me-1"></i> Livres disponibles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('library.search', ['type' => 'numerique']) }}" class="text-decoration-none">
                                <i class="fas fa-search text-muted me-1"></i> Livres numériques
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Résultats -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-search"></i> 
                            @if(request('q'))
                                Résultats pour "{{ request('q') }}"
                            @else
                                Tous les livres
                            @endif
                            
                            @if(request('category') || request('type') || request('available_only') || request('min_rating'))
                                <small class="text-muted">({{ $books->total() }} résultat(s))</small>
                            @endif
                        </h5>
                        
                        <!-- Tri -->
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                    type="button" 
                                    id="sortDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                <i class="fas fa-sort"></i> 
                                @switch(request('sort', 'recent'))
                                    @case('title')
                                        Titre (A-Z)
                                        @break
                                    @case('title_desc')
                                        Titre (Z-A)
                                        @break
                                    @case('author')
                                        Auteur (A-Z)
                                        @break
                                    @case('author_desc')
                                        Auteur (Z-A)
                                        @break
                                    @case('popular')
                                        Les plus populaires
                                        @break
                                    @case('rating')
                                        Meilleures notes
                                        @break
                                    @default
                                        Les plus récents
                                @endswitch
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item {{ !request('sort') || request('sort') === 'recent' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'recent']) }}">
                                        Les plus récents
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'title' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}">
                                        Titre (A-Z)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'title_desc' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'title_desc']) }}">
                                        Titre (Z-A)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'author' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'author']) }}">
                                        Auteur (A-Z)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'author_desc' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'author_desc']) }}">
                                        Auteur (Z-A)
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'popular' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">
                                        Les plus populaires
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request('sort') === 'rating' ? 'active' : '' }}" 
                                       href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}">
                                        Meilleures notes
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($books->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-book-open fa-4x text-muted mb-4"></i>
                            <h4>Aucun livre trouvé</h4>
                            <p class="text-muted">
                                Aucun livre ne correspond à votre recherche. Essayez d'autres termes ou élargissez vos critères.
                            </p>
                            <a href="{{ route('library.search') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-undo"></i> Réinitialiser la recherche
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach($books as $book)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    @include('pages.library.books.partials.book-card', ['book' => $book])
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $books->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de {{ $books->firstItem() }} à {{ $books->lastItem() }} sur {{ $books->total() }} résultat(s)
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#searchHelpModal">
                                <i class="fas fa-question-circle"></i> Aide à la recherche
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'aide à la recherche -->
<div class="modal fade" id="searchHelpModal" tabindex="-1" aria-labelledby="searchHelpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchHelpModalLabel">
                    <i class="fas fa-question-circle"></i> Aide à la recherche
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Conseils de recherche</h6>
                <ul>
                    <li>Utilisez des mots-clés simples et précis (ex: "programmation python" au lieu de "livre sur la programmation en python")</li>
                    <li>Utilisez des guillemets pour une recherche exacte (ex: "Le Petit Prince")</li>
                    <li>Utilisez des opérateurs booléens comme AND, OR, NOT (ex: "programmation AND java NOT spring")</li>
                    <li>Utilisez l'astérisque comme joker (ex: "programm*" pour trouver programmation, programmeur, etc.)</li>
                </ul>
                
                <h6 class="mt-4">Filtres disponibles</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Catégorie</strong><br>
                        Filtrez les résultats par catégorie de livre (romans, sciences, histoire, etc.)</p>
                        
                        <p><strong>Type de livre</strong><br>
                        Affichez uniquement les livres papier ou numériques</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Disponibilité</strong><br>
                        Affichez uniquement les livres disponibles à l'emprunt</p>
                        
                        <p><strong>Note minimale</strong><br>
                        Filtrez les livres par note moyenne (sur 5 étoiles)</p>
                    </div>
                </div>
                
                <h6 class="mt-4">Tri des résultats</h6>
                <p>Vous pouvez trier les résultats par :</p>
                <ul>
                    <li><strong>Les plus récents</strong> : affiche les livres les plus récemment ajoutés</li>
                    <li><strong>Titre (A-Z ou Z-A)</strong> : trie les livres par ordre alphabétique du titre</li>
                    <li><strong>Auteur (A-Z ou Z-A)</strong> : trie les livres par ordre alphabétique de l'auteur</li>
                    <li><strong>Les plus populaires</strong> : affiche les livres les plus empruntés</li>
                    <li><strong>Meilleures notes</strong> : affiche les livres avec les meilleures notes moyennes</li>
                </ul>
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
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .dropdown-item.active, .dropdown-item:active {
        background-color: #0d6efd;
    }
    .pagination .page-link {
        color: #0d6efd;
    }
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush
