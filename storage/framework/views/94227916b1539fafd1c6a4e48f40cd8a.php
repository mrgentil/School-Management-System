<?php $__env->startSection('page_title', 'Bibliothèque'); ?>
<?php
    use Illuminate\Support\Str;
    use App\Models\BookRequest;
?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<?php if(session('warning')): ?>
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <?php echo e(session('warning')); ?>

</div>
<?php endif; ?>

<div class="card">
    <div class="card-header header-elements-inline bg-teal-400">
        <h6 class="card-title text-white">
            <i class="icon-books mr-2"></i>
            Bibliothèque Numérique
        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('student.library.requests.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-list mr-2"></i> Mes Demandes
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text bg-teal-400 border-teal-400">
                                    <i class="icon-search4 text-white"></i>
                                </span>
                            </span>
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Rechercher par titre, auteur, ISBN..." value="<?php echo e($search); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <select name="book_type" class="form-control form-control-lg select">
                            <option value="">📚 Tous les types</option>
                            <option value="textbook" <?php echo e($book_type == 'textbook' ? 'selected' : ''); ?>>📖 Manuel scolaire</option>
                            <option value="reference" <?php echo e($book_type == 'reference' ? 'selected' : ''); ?>>📕 Référence</option>
                            <option value="fiction" <?php echo e($book_type == 'fiction' ? 'selected' : ''); ?>>📗 Fiction</option>
                            <option value="non-fiction" <?php echo e($book_type == 'non-fiction' ? 'selected' : ''); ?>>📘 Non-fiction</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-teal btn-lg btn-block">
                        <i class="icon-search4 mr-2"></i> Rechercher
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="<?php echo e(route('student.library.index')); ?>" class="btn btn-light btn-lg btn-block">
                        <i class="icon-reset mr-2"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </form>

        <!-- Book List -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 book-card">
                    <?php
                        $bookStatus = $bookStatuses->get($book->id, [
                            'status' => 'unavailable',
                            'text' => 'Non disponible',
                            'badge_class' => 'badge-danger',
                            'can_request' => false,
                            'action_text' => 'Indisponible'
                        ]);
                    ?>
                    
                    <!-- Badge de statut en haut -->
                    <div class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                        <span class="badge <?php echo e($bookStatus['badge_class']); ?> badge-pill px-3 py-2 shadow-sm">
                            <?php if($bookStatus['status'] === 'available'): ?>
                                <i class="icon-checkmark-circle mr-1"></i>
                            <?php elseif($bookStatus['status'] === 'pending'): ?>
                                <i class="icon-hour-glass2 mr-1"></i>
                            <?php elseif($bookStatus['status'] === 'borrowed'): ?>
                                <i class="icon-book mr-1"></i>
                            <?php else: ?>
                                <i class="icon-cross-circle mr-1"></i>
                            <?php endif; ?>
                            <?php echo e($bookStatus['text']); ?>

                        </span>
                    </div>

                    <!-- Image de couverture -->
                    <div class="book-cover-container" style="position: relative; overflow: hidden;">
                        <?php if($book->cover_image): ?>
                            <img src="<?php echo e(Storage::url($book->cover_image)); ?>" class="card-img-top" alt="<?php echo e($book->name); ?>" style="height: 280px; object-fit: cover; transition: transform 0.3s;">
                        <?php else: ?>
                            <div class="text-center py-5 bg-gradient-to-br from-teal-400 to-teal-600" style="height: 280px; display: flex; align-items: center; justify-content: center;">
                                <div>
                                    <i class="icon-book3 icon-4x text-white opacity-75"></i>
                                    <div class="text-white mt-2 font-weight-semibold"><?php echo e(Str::limit($book->name, 20)); ?></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body pb-2">
                        <h6 class="card-title font-weight-bold text-dark mb-2" style="min-height: 45px; line-height: 1.3;">
                            <?php echo e(Str::limit($book->name, 60)); ?>

                        </h6>
                        
                        <div class="mb-3">
                            <div class="text-muted mb-1">
                                <i class="icon-user text-teal mr-1"></i>
                                <small class="font-weight-semibold"><?php echo e(Str::limit($book->author, 30)); ?></small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-flat border-teal text-teal-600">
                                    <i class="icon-tag mr-1"></i>
                                    <?php echo e(ucfirst($book->book_type)); ?>

                                </span>
                                <?php if($book->category): ?>
                                <span class="badge badge-flat border-grey text-grey-600">
                                    <i class="icon-folder mr-1"></i>
                                    <?php echo e(Str::limit($book->category, 15)); ?>

                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <p class="card-text text-muted small mb-3" style="min-height: 60px; font-size: 0.85rem; line-height: 1.5;">
                            <?php echo e(Str::limit($book->description, 100)); ?>

                        </p>
                    </div>
                    
                    <div class="card-footer bg-white border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="btn btn-outline-teal btn-sm" data-toggle="modal" data-target="#bookDetails<?php echo e($book->id); ?>">
                                <i class="icon-info22 mr-1"></i> Détails
                            </a>
                            
                            <?php if($bookStatus['can_request']): ?>
                            <form id="request-form-<?php echo e($book->id); ?>" action="<?php echo e(route('student.library.books.request', $book)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm"
                                    onclick="return confirm('Êtes-vous sûr de vouloir demander ce livre ?')">
                                    <i class="icon-plus-circle2 mr-1"></i> Demander
                                </button>
                            </form>
                            <div id="debug-<?php echo e($book->id); ?>" class="small text-muted mt-1"></div>
                            <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="icon-<?php echo e($bookStatus['status'] === 'pending' ? 'clock' : ($bookStatus['status'] === 'borrowed' ? 'book' : 'cross2')); ?> mr-1"></i>
                                <?php echo e($bookStatus['status'] === 'pending' ? 'En attente' : ($bookStatus['status'] === 'borrowed' ? 'Emprunté' : 'Indispo')); ?>

                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Book Details Modal -->
                <div class="modal fade" id="bookDetails<?php echo e($book->id); ?>" tabindex="-1" role="dialog" aria-labelledby="bookDetailsLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookDetailsLabel"><?php echo e($book->name); ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?php if($book->cover_image): ?>
                                            <img src="<?php echo e(Storage::url($book->cover_image)); ?>" class="img-fluid rounded" alt="<?php echo e($book->name); ?>">
                                        <?php else: ?>
                                            <div class="text-center py-5 bg-light rounded">
                                                <i class="fas fa-book fa-5x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-8">
                                        <p><strong>Auteur :</strong> <?php echo e($book->author); ?></p>
                                        <p><strong>Type :</strong> <?php echo e(ucfirst($book->book_type)); ?></p>
                                        <p><strong>Catégorie :</strong> <?php echo e($book->category ?? 'Non spécifiée'); ?></p>
                                        <p><strong>ISBN :</strong> <?php echo e($book->isbn ?? 'Non spécifié'); ?></p>
                                        <p><strong>Éditeur :</strong> <?php echo e($book->publisher ?? 'Non spécifié'); ?></p>
                                        <p><strong>Année de publication :</strong> <?php echo e($book->publication_year ?? 'Non spécifiée'); ?></p>
                                        <p><strong>Pages :</strong> <?php echo e($book->pages ?? 'Non spécifié'); ?></p>
                                        <p><strong>Langue :</strong> <?php echo e($book->language ?? 'Non spécifiée'); ?></p>
                                        <p><strong>Description :</strong></p>
                                        <p><?php echo e($book->description ?? 'Aucune description disponible.'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <?php if($book->available): ?>
                                    <form action="<?php echo e(route('student.library.books.request', $book)); ?>" method="POST" class="mr-2">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-hand-holding"></i> Demander ce livre
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Non disponible pour le moment</span>
                                <?php endif; ?>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="empty-state">
                    <div class="mb-4">
                        <i class="icon-books icon-3x text-muted d-block mb-3"></i>
                        <div style="width: 80px; height: 4px; background: linear-gradient(to right, #26a69a, #00897b); margin: 0 auto; border-radius: 2px;"></div>
                    </div>
                    <h4 class="font-weight-semibold text-dark mb-2">Aucun livre trouvé</h4>
                    <p class="text-muted mb-4">Essayez de modifier vos critères de recherche ou parcourez tous les livres disponibles.</p>
                    <a href="<?php echo e(route('student.library.index')); ?>" class="btn btn-teal">
                        <i class="icon-reset mr-2"></i> Voir tous les livres
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($books->hasPages()): ?>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Pagination">
                <ul class="pagination">
                    
                    <?php if($books->onFirstPage()): ?>
                        <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($books->previousPageUrl()); ?>" rel="prev" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">&lsaquo;</a>
                        </li>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = $books->getUrlRange(1, $books->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $books->currentPage()): ?>
                            <li class="page-item active" aria-current="page">
                                <span class="page-link"><?php echo e($page); ?></span>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($books->hasMorePages()): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($books->nextPageUrl()); ?>" rel="next" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">&rsaquo;</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled" aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- My Recent Requests -->
<?php if(count($userRequests) > 0): ?>
    <div class="card mt-4">
        <div class="card-header header-elements-inline bg-teal-400">
            <h6 class="card-title text-white">
                <i class="icon-history mr-2"></i>
                Mes Demandes Récentes
            </h6>
            <div class="header-elements">
                <span class="badge badge-light badge-pill px-3 py-2">
                    <?php echo e(count($userRequests)); ?> demande<?php echo e(count($userRequests) > 1 ? 's' : ''); ?>

                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="25%">Livre</th>
                        <th width="15%">Auteur</th>
                        <th width="12%" class="text-center">Date demande</th>
                        <th width="12%" class="text-center">Statut</th>
                        <th width="15%" class="text-center">Retour prévu</th>
                        <th width="21%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $userRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="icon-book3 text-teal mr-2"></i>
                                <div>
                                    <div class="font-weight-semibold text-dark"><?php echo e(Str::limit($request->book->name ?? 'Livre inconnu', 40)); ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted"><?php echo e(Str::limit($request->book->author ?? '-', 25)); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="text-muted">
                                <i class="icon-calendar3 mr-1"></i>
                                <?php echo e($request->request_date->format('d/m/Y')); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?php echo e($request->badge_class); ?> badge-pill px-3 py-2">
                                <?php echo e(\App\Models\BookRequest::getStatuses()[$request->status] ?? ucfirst($request->status)); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($request->expected_return_date): ?>
                                <div class="badge badge-flat border-teal text-teal-600 px-2 py-1">
                                    <i class="icon-calendar mr-1"></i>
                                    <?php echo e($request->expected_return_date->format('d/m/Y')); ?>

                                </div>
                                <?php if($request->isOverdue()): ?>
                                    <div class="badge badge-danger badge-pill mt-1">
                                        <i class="icon-alarm mr-1"></i>
                                        En retard
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($request->canBeMarkedAsReturned()): ?>
                                <form action="<?php echo e(route('student.library.books.return', $request)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Êtes-vous sûr de vouloir marquer ce livre comme retourné ?')">
                                        <i class="icon-checkmark mr-1"></i> Retourner
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if($request->canBeCancelled()): ?>
                                <form action="<?php echo e(route('student.library.requests.cancel', $request)); ?>" method="POST" class="d-inline ml-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                        <i class="icon-cross mr-1"></i> Annuler
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right bg-light">
            <a href="<?php echo e(route('student.library.requests.index')); ?>" class="btn btn-teal">
                <i class="icon-list mr-2"></i> Voir toutes mes demandes
            </a>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Cartes de livres */
    .book-card {
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .book-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border-color: #26a69a;
    }
    
    .book-card:hover .book-cover-container img {
        transform: scale(1.05);
    }
    
    /* Badges de statut */
    .badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    .badge-pill {
        border-radius: 50px;
    }
    
    /* Boutons */
    .btn-teal {
        background-color: #26a69a;
        border-color: #26a69a;
        color: white;
    }
    
    .btn-teal:hover {
        background-color: #00897b;
        border-color: #00897b;
        color: white;
    }
    
    .btn-outline-teal {
        color: #26a69a;
        border-color: #26a69a;
    }
    
    .btn-outline-teal:hover {
        background-color: #26a69a;
        border-color: #26a69a;
        color: white;
    }
    
    /* Couleurs teal personnalisées */
    .bg-teal-400 {
        background-color: #26a69a !important;
    }
    
    .text-teal {
        color: #26a69a !important;
    }
    
    .text-teal-600 {
        color: #00897b !important;
    }
    
    .border-teal {
        border-color: #26a69a !important;
    }
    
    .border-teal-400 {
        border-color: #26a69a !important;
    }
    
    /* Pagination */
    .pagination .page-item.active .page-link {
        background-color: #26a69a;
        border-color: #26a69a;
    }
    
    .pagination .page-link {
        color: #26a69a;
    }
    
    .pagination .page-link:hover {
        color: #00897b;
        background-color: #e0f2f1;
    }
    
    /* Message vide */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #b0bec5;
        margin-bottom: 1rem;
    }
    
    /* Input de recherche */
    .input-group-text {
        border: none;
    }
    
    .form-control:focus {
        border-color: #26a69a;
        box-shadow: 0 0 0 0.2rem rgba(38, 166, 154, 0.25);
    }
    
    /* Animation de chargement */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .book-card {
        animation: fadeIn 0.5s ease-out;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .book-card {
            margin-bottom: 1.5rem;
        }
        
        .book-cover-container img,
        .book-cover-container > div {
            height: 220px !important;
        }
    }
    
    /* Gradient pour les couvertures sans image */
    .bg-gradient-to-br {
        background: linear-gradient(to bottom right, #26a69a, #00796b);
    }
    
    /* Styles pour les badges flat */
    .badge-flat {
        background-color: transparent;
        border: 1px solid;
        font-weight: 500;
        font-size: 0.7rem;
    }
    
    /* Amélioration des titres de cartes */
    .card-title {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    /* Footer de carte */
    .card-footer {
        background-color: #fafafa;
        border-top: 1px solid #e0e0e0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php if(session('success')): ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        Swal.fire({
            title: 'Succès !',
            text: '<?php echo e(session('success')); ?>',
            icon: 'success',
            confirmButtonColor: '#4f46e5'
        });
    </script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(session('error')): ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        Swal.fire({
            title: 'Erreur !',
            text: '<?php echo e(session('error')); ?>',
            icon: 'error',
            confirmButtonColor: '#4f46e5'
        });
    </script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php if(session('warning')): ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        Swal.fire({
            title: 'Attention !',
            text: '<?php echo e(session('warning')); ?>',
            icon: 'warning',
            confirmButtonColor: '#4f46e5'
        });
    </script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/library/index.blade.php ENDPATH**/ ?>