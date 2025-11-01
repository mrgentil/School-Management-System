@extends('layouts.master')
@section('page_title', 'Bibliothèque')
@php
    use Illuminate\Support\Str;
    use App\Models\BookRequest;
@endphp

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    {{ session('error') }}
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    {{ session('warning') }}
</div>
@endif

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Bibliothèque Numérique</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un livre..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="book_type" class="form-control select">
                            <option value="">Tous les types</option>
                            <option value="textbook" {{ $book_type == 'textbook' ? 'selected' : '' }}>Manuel scolaire</option>
                            <option value="reference" {{ $book_type == 'reference' ? 'selected' : '' }}>Référence</option>
                            <option value="fiction" {{ $book_type == 'fiction' ? 'selected' : '' }}>Fiction</option>
                            <option value="non-fiction" {{ $book_type == 'non-fiction' ? 'selected' : '' }}>Non-fiction</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="icon-search4"></i> Rechercher</button>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('student.library.requests.index') }}" class="btn btn-info">
                        <i class="icon-list"></i> Mes Demandes
                    </a>
                </div>
            </div>
        </form>

        <!-- Book List -->
        <div class="row">
            @forelse($books as $book)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($book->cover_image)
                        <img src="{{ Storage::url($book->cover_image) }}" class="card-img-top" alt="{{ $book->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="text-center py-5 bg-light">
                            <i class="fas fa-book fa-4x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $book->name }}</h5>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-user-edit"></i> {{ $book->author }}<br>
                                <i class="fas fa-tag"></i> {{ ucfirst($book->book_type) }}<br>
                                <i class="fas fa-layer-group"></i> {{ $book->category ?? 'Non spécifié' }}
                            </small>
                        </p>
                        <p class="card-text">
                            {{ Str::limit($book->description, 100) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        @php
                            $bookStatus = $bookStatuses->get($book->id, [
                                'status' => 'unavailable',
                                'text' => 'Non disponible',
                                'badge_class' => 'badge-danger',
                                'can_request' => false,
                                'action_text' => 'Indisponible'
                            ]);
                        @endphp
                        <span class="badge {{ $bookStatus['badge_class'] }}">
                            {{ $bookStatus['text'] }}
                        </span>
                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#bookDetails{{ $book->id }}">
                                <i class="icon-info22 mr-2"></i> Détails
                            </a>
                            @if($bookStatus['can_request'])
                            <form id="request-form-{{ $book->id }}" action="{{ route('student.library.books.request', $book) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success ml-2"
                                    onclick="return confirm('Êtes-vous sûr de vouloir demander ce livre ?')">
                                    <i class="icon-plus-circle2 mr-2"></i> {{ $bookStatus['action_text'] }}
                                </button>
                            </form>
                            <div id="debug-{{ $book->id }}" class="small text-muted mt-1"></div>
                            @else
                            <button class="btn btn-secondary ml-2" disabled>
                                <i class="icon-{{ $bookStatus['status'] === 'pending' ? 'clock' : ($bookStatus['status'] === 'borrowed' ? 'check' : 'cross2') }} mr-2"></i>
                                {{ $bookStatus['action_text'] }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Book Details Modal -->
                <div class="modal fade" id="bookDetails{{ $book->id }}" tabindex="-1" role="dialog" aria-labelledby="bookDetailsLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookDetailsLabel">{{ $book->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if($book->cover_image)
                                            <img src="{{ Storage::url($book->cover_image) }}" class="img-fluid rounded" alt="{{ $book->name }}">
                                        @else
                                            <div class="text-center py-5 bg-light rounded">
                                                <i class="fas fa-book fa-5x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <p><strong>Auteur :</strong> {{ $book->author }}</p>
                                        <p><strong>Type :</strong> {{ ucfirst($book->book_type) }}</p>
                                        <p><strong>Catégorie :</strong> {{ $book->category ?? 'Non spécifiée' }}</p>
                                        <p><strong>ISBN :</strong> {{ $book->isbn ?? 'Non spécifié' }}</p>
                                        <p><strong>Éditeur :</strong> {{ $book->publisher ?? 'Non spécifié' }}</p>
                                        <p><strong>Année de publication :</strong> {{ $book->publication_year ?? 'Non spécifiée' }}</p>
                                        <p><strong>Pages :</strong> {{ $book->pages ?? 'Non spécifié' }}</p>
                                        <p><strong>Langue :</strong> {{ $book->language ?? 'Non spécifiée' }}</p>
                                        <p><strong>Description :</strong></p>
                                        <p>{{ $book->description ?? 'Aucune description disponible.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if($book->available)
                                    <form action="{{ route('student.library.books.request', $book) }}" method="POST" class="mr-2">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-hand-holding"></i> Demander ce livre
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Non disponible pour le moment</span>
                                @endif
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-book icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun livre trouvé</h5>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Pagination">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($books->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $books->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
                        @if ($page == $books->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($books->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $books->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<!-- My Recent Requests -->
@if(count($userRequests) > 0)
    <div class="card mt-4">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Mes demandes récentes</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Livre</th>
                        <th>Auteur</th>
                        <th>Date de demande</th>
                        <th>Statut</th>
                        <th>Date de retour prévue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userRequests as $request)
                    <tr>
                        <td>{{ $request->book->name ?? 'Livre inconnu' }}</td>
                        <td>{{ $request->book->author ?? '-' }}</td>
                        <td>{{ $request->request_date->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge {{ $request->badge_class }}">
                                {{ \App\Models\BookRequest::getStatuses()[$request->status] ?? ucfirst($request->status) }}
                            </span>
                        </td>
                        <td>
                            @if($request->expected_return_date)
                                {{ $request->expected_return_date->format('d/m/Y') }}
                                @if($request->isOverdue())
                                    <span class="badge badge-danger ml-1">En retard</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($request->canBeMarkedAsReturned())
                                <form action="{{ route('student.library.books.return', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Êtes-vous sûr de vouloir marquer ce livre comme retourné ?')">
                                        <i class="icon-checkmark"></i> Marquer comme retourné
                                    </button>
                                </form>
                            @endif

                            @if($request->canBeCancelled())
                                <form action="{{ route('student.library.requests.cancel', $request) }}" method="POST" class="d-inline ml-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                        <i class="icon-cross"></i> Annuler
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('student.library.requests.index') }}" class="btn btn-primary">
                <i class="icon-list"></i> Voir toutes mes demandes
            </a>
        </div>
    </div>
@endif

@section('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .pagination .page-item.active .page-link {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .pagination .page-link {
        color: #4f46e5;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.6em;
    }
</style>
@endsection

@push('scripts')
<script>
    // Script pour gérer les demandes de livres
    document.addEventListener('DOMContentLoaded', function() {
        // Afficher un message de confirmation lors de la demande d'un livre
        const requestForms = document.querySelectorAll('form[action*="/library/request"]');
        requestForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Confirmer la demande',
                    text: 'Êtes-vous sûr de vouloir demander ce livre ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Oui, demander',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Gestion de la soumission du formulaire de demande de livre
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM entièrement chargé');

        // Vérifier si les boutons sont bien sélectionnés
        const buttons = document.querySelectorAll('.request-book-btn');
        console.log('Boutons trouvés:', buttons.length);

        // Afficher les informations de débogage pour chaque bouton
        buttons.forEach((btn, index) => {
            console.log(`Bouton ${index + 1}:`, {
                id: btn.dataset.bookId,
                title: btn.dataset.bookTitle,
                form: btn.closest('form').action
            });

            // Afficher les informations de débogage à côté de chaque bouton
            const debugDiv = document.getElementById(`debug-${btn.dataset.bookId}`);
            if (debugDiv) {
                debugDiv.textContent = `ID: ${btn.dataset.bookId} - Action: ${btn.closest('form').action}`;
            }
        });
        const requestForms = document.querySelectorAll('form[action*="/library/books/"]');

        requestForms.forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                // Désactiver le bouton pendant la soumission
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="icon-spinner4 spinner mr-2"></i> Traitement...';

                try {
                    // Logs de débogage détaillés
                    console.log('=== DÉBUT DE LA REQUÊTE ===');
                    console.log('URL de la requête:', this.action);
                    console.log('Méthode: POST');
                    const formData = Object.fromEntries(new FormData(this));
                    console.log('Données du formulaire:', formData);
                    console.log('En-têtes:', {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    });

                const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(this)
                    });

                    const data = await response.json().catch(e => {
                        console.error('Erreur lors du parsing de la réponse JSON:', e);
                        return { success: false, message: 'Erreur de traitement de la réponse du serveur' };
                    });

                    console.log('=== RÉPONSE DU SERVEUR ===');
                    console.log('Status:', response.status, response.statusText);
                    console.log('Headers:');
                    response.headers.forEach((value, key) => {
                        console.log(`  ${key}: ${value}`);
                    });
                    console.log('Données de la réponse:', data);

                    // Afficher les erreurs de validation si elles existent
                    if (data.errors) {
                        console.error('Erreurs de validation:', data.errors);
                    }

                    if (response.ok) {
                        // Afficher un message de succès
                        Swal.fire({
                            title: 'Succès !',
                            text: data.message || 'Votre demande a été enregistrée avec succès.',
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        });

                        // Mettre à jour l'interface utilisateur avec les données de la réponse
                        const card = this.closest('.card');
                        if (card) {
                            // Mettre à jour le badge de statut
                            const badge = card.querySelector('.badge');
                            if (badge) {
                                // Réinitialiser les classes
                                badge.className = 'badge';
                                // Ajouter la classe de style appropriée
                                badge.classList.add(data.badge_class || 'badge-info');
                                // Mettre à jour le texte du statut
                                badge.textContent = data.status_text || 'En attente';
                            }

                            // Remplacer le formulaire par un message de statut
                            const buttonContainer = this.closest('.text-center');
                            if (buttonContainer) {
                                buttonContainer.innerHTML = `
                                    <div class="alert alert-${data.status === 'rejected' ? 'danger' : 'info'} p-2 mb-0">
                                        <i class="icon-${data.status === 'rejected' ? 'cross' : 'checkmark-circle'} mr-2"></i>
                                        ${data.message || 'Demande traitée'}
                                        ${data.expected_return_date ? `<br><small>Date de retour prévue: ${new Date(data.expected_return_date).toLocaleDateString()}</small>` : ''}
                                    </div>
                                `;
                            }

                            // Si le livre est maintenant emprunté, mettre à jour le statut de disponibilité
                            if (data.status === 'borrowed' || data.status === 'approved') {
                                const availabilityBadge = card.querySelector('.availability-badge');
                                if (availabilityBadge) {
                                    availabilityBadge.className = 'badge badge-warning';
                                    availabilityBadge.textContent = 'Emprunté';
                                }
                            }
                        }
                    } else {
                        // Afficher un message d'erreur
                        Swal.fire({
                            title: 'Erreur !',
                            text: data.message || 'Une erreur est survenue lors de l\'envoi de votre demande.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5'
                        });

                        // Réactiver le bouton
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    }
                } catch (error) {
                    console.error('=== ERREUR ===');
                    console.error('Message:', error.message);
                    console.error('Stack:', error.stack);
                    console.error('Nom:', error.name);
                    console.error('Données complètes:', error);
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Une erreur est survenue. Veuillez réessayer.',
                        icon: 'error',
                        confirmButtonColor: '#4f46e5'
                    });

                    // Réactiver le bouton en cas d'erreur
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            });
        });
    });
</script>
@endpush

@if(session('success'))
@push('scripts')
    <script>
        Swal.fire({
            title: 'Succès !',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#4f46e5'
        });
    </script>
@endpush
@endif

@if(session('error'))
@push('scripts')
    <script>
        Swal.fire({
            title: 'Erreur !',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#4f46e5'
        });
    </script>
@endpush
@endif

@if(session('warning'))
@push('scripts')
    <script>
        Swal.fire({
            title: 'Attention !',
            text: '{{ session('warning') }}',
            icon: 'warning',
            confirmButtonColor: '#4f46e5'
        });
    </script>
@endpush
@endif

@push('scripts')
<script>
    // Script de débogage
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DÉBOGAGE BIBLIOTHÈQUE ===');

        // Vérifier jQuery
        console.log('jQuery chargé:', typeof jQuery !== 'undefined' ? 'Oui' : 'Non');

        // Vérifier SweetAlert2
        console.log('SweetAlert2 chargé:', typeof Swal !== 'undefined' ? 'Oui' : 'Non');

        // Vérifier les formulaires
        const forms = document.querySelectorAll('form[action*="/library/books/"]');
        console.log('Formulaires trouvés:', forms.length);

        forms.forEach((form, index) => {
            console.log(`Formulaire ${index + 1}:`, {
                action: form.action,
                method: form.method,
                elements: form.elements.length
            });

            // Ajouter un ID unique si non présent
            if (!form.id) {
                form.id = `book-form-${index}`;
            }

            // Vérifier le bouton de soumission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                console.log(`Bouton de soumission pour le formulaire ${index + 1}:`, {
                    id: submitBtn.id,
                    class: submitBtn.className,
                    disabled: submitBtn.disabled,
                    text: submitBtn.textContent.trim()
                });
            } else {
                console.warn(`Aucun bouton de soumission trouvé pour le formulaire ${index + 1}`);
            }
        });

        // Vérifier les boutons de demande
        const requestBtns = document.querySelectorAll('.request-book-btn');
        console.log('Boutons de demande trouvés:', requestBtns.length);

        requestBtns.forEach((btn, index) => {
            console.log(`Bouton ${index + 1}:`, {
                id: btn.id,
                class: btn.className,
                'data-book-id': btn.dataset.bookId,
                'data-book-title': btn.dataset.bookTitle,
                disabled: btn.disabled,
                parentForm: btn.closest('form') ? 'Trouvé' : 'Non trouvé'
            });
        });
    });
</script>
@endpush

@endsection
