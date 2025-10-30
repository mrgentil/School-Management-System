@props(['book'])

<div class="card h-100 book-card">
    <!-- Badge de disponibilité -->
    @if($book->isAvailable())
        <span class="badge bg-success book-badge">
            <i class="fas fa-check-circle"></i> Disponible
        </span>
    @else
        <span class="badge bg-secondary book-badge">
            <i class="fas fa-times-circle"></i> Emprunté
        </span>
    @endif
    
    <!-- Couverture du livre -->
    <img src="{{ $book->cover_image_url ?? asset('images/default-book-cover.jpg') }}" 
         class="card-img-top book-cover" 
         alt="{{ $book->title }}">
    
    <div class="card-body d-flex flex-column">
        <!-- Titre du livre -->
        <h5 class="card-title">
            <a href="{{ route('library.books.show', $book) }}" class="text-decoration-none">
                {{ Str::limit($book->title, 50) }}
            </a>
        </h5>
        
        <!-- Auteur -->
        <p class="card-text text-muted small mb-2">
            <i class="fas fa-user-edit"></i> {{ $book->author }}
        </p>
        
        <!-- Note moyenne -->
        <div class="mb-2">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $book->rating ? 'text-warning' : 'text-muted' }}"></i>
            @endfor
            <small class="text-muted">({{ $book->reviews_count }} avis)</small>
        </div>
        
        <!-- Disponibilité -->
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-{{ $book->isAvailable() ? 'success' : 'secondary' }} text-white">
                    {{ $book->available_copies }} / {{ $book->total_copies }} disponible(s)
                </span>
                
                <a href="{{ route('library.books.show', $book) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Voir
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-white border-top-0">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Catégorie -->
            @if($book->category)
                <a href="{{ route('library.search', ['category' => $book->category_id]) }}" 
                   class="badge bg-light text-dark text-decoration-none">
                    {{ $book->category->name }}
                </a>
            @endif
            
            <!-- Type de livre -->
            <span class="badge bg-info text-white">
                {{ $book->type === 'numerique' ? 'Numérique' : 'Papier' }}
            </span>
        </div>
    </div>
</div>
