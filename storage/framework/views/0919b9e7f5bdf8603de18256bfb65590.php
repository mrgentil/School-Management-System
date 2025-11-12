<?php $__env->startSection('page_title', 'Tableau de Bord - Bibliothécaire'); ?>
<?php $__env->startSection('content'); ?>

<!-- Statistiques principales -->
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-books icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h2 class="mb-0 font-weight-semibold"><?php echo e($stats['total_books']); ?></h2>
                    <span class="text-uppercase font-size-sm opacity-75">Total Livres</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark-circle icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h2 class="mb-0 font-weight-semibold"><?php echo e($stats['available_books']); ?></h2>
                    <span class="text-uppercase font-size-sm opacity-75">Disponibles</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-orange-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h2 class="mb-0 font-weight-semibold"><?php echo e($stats['borrowed_books']); ?></h2>
                    <span class="text-uppercase font-size-sm opacity-75">Empruntés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h2 class="mb-0 font-weight-semibold"><?php echo e($stats['overdue_books']); ?></h2>
                    <span class="text-uppercase font-size-sm opacity-75">En Retard</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques secondaires -->
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-hour-glass2 icon-2x text-warning-400"></i>
                    </div>
                    <div>
                        <div class="font-weight-semibold"><?php echo e($stats['pending_requests'] ?? 0); ?></div>
                        <span class="text-muted font-size-sm">En attente</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-users icon-2x text-info-400"></i>
                    </div>
                    <div>
                        <div class="font-weight-semibold"><?php echo e($stats['active_borrowers'] ?? 0); ?></div>
                        <span class="text-muted font-size-sm">Emprunteurs actifs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-calendar icon-2x text-teal-400"></i>
                    </div>
                    <div>
                        <div class="font-weight-semibold"><?php echo e($stats['requests_today'] ?? 0); ?></div>
                        <span class="text-muted font-size-sm">Demandes aujourd'hui</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-checkmark3 icon-2x text-success-400"></i>
                    </div>
                    <div>
                        <div class="font-weight-semibold"><?php echo e($stats['returned_this_week'] ?? 0); ?></div>
                        <span class="text-muted font-size-sm">Retours cette semaine</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Demandes récentes -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Demandes d'Emprunt Récentes</h6>
                <div class="header-elements">
                    <a href="<?php echo e(route('librarian.book-requests.index')); ?>" class="btn btn-link btn-sm">Voir tout</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Étudiant</th>
                                <th width="30%">Livre</th>
                                <th width="15%" class="text-center">Date</th>
                                <th width="15%" class="text-center">Statut</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-muted">#<?php echo e($request->id); ?></td>
                                <td>
                                    <?php if($request->student && $request->student->user): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <div class="bg-teal-400 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    <span class="text-white font-weight-bold font-size-sm"><?php echo e(strtoupper(substr($request->student->user->name, 0, 1))); ?></span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-weight-semibold"><?php echo e($request->student->user->name); ?></div>
                                                <small class="text-muted"><?php echo e($request->student->user->email ?? ''); ?></small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($request->book): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="icon-book3 text-teal mr-2"></i>
                                            <div>
                                                <div class="font-weight-semibold"><?php echo e(Str::limit($request->book->name, 40)); ?></div>
                                                <small class="text-muted"><?php echo e($request->book->author ?? ''); ?></small>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="text-muted">
                                        <i class="icon-calendar3 mr-1"></i>
                                        <?php echo e($request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') : $request->created_at->format('d/m/Y')); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($request->status == 'pending'): ?>
                                        <span class="badge badge-warning badge-pill">
                                            <i class="icon-hour-glass2 mr-1"></i>
                                            En attente
                                        </span>
                                    <?php elseif($request->status == 'approved'): ?>
                                        <span class="badge badge-success badge-pill">
                                            <i class="icon-checkmark-circle mr-1"></i>
                                            Approuvée
                                        </span>
                                    <?php elseif($request->status == 'borrowed'): ?>
                                        <span class="badge badge-info badge-pill">
                                            <i class="icon-book mr-1"></i>
                                            Emprunté
                                        </span>
                                    <?php elseif($request->status == 'rejected'): ?>
                                        <span class="badge badge-danger badge-pill">
                                            <i class="icon-cross-circle mr-1"></i>
                                            Rejetée
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary badge-pill">
                                            <?php echo e(ucfirst($request->status)); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('librarian.book-requests.show', $request->id)); ?>" class="btn btn-sm btn-outline-teal" data-toggle="tooltip" title="Voir les détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="icon-info22 icon-2x text-muted d-block mb-2"></i>
                                    <span class="text-muted">Aucune demande récente</span>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="<?php echo e(route('librarian.books.create')); ?>" class="list-group-item list-group-item-action">
                        <i class="icon-plus2 mr-3"></i>Ajouter un Livre
                    </a>
                    <a href="<?php echo e(route('librarian.books.index')); ?>" class="list-group-item list-group-item-action">
                        <i class="icon-books mr-3"></i>Gérer les Livres
                    </a>
                    <a href="<?php echo e(route('librarian.book-requests.index')); ?>" class="list-group-item list-group-item-action">
                        <i class="icon-reading mr-3"></i>Demandes d'Emprunt
                        <?php if($stats['pending_requests'] > 0): ?>
                            <span class="badge badge-warning badge-pill ml-auto"><?php echo e($stats['pending_requests']); ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo e(route('librarian.reports.index')); ?>" class="list-group-item list-group-item-action">
                        <i class="icon-stats-dots mr-3"></i>Rapports
                    </a>
                </div>
            </div>
        </div>

        <!-- Livres populaires -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Livres Populaires</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php $__empty_1 = true; $__currentLoopData = $popular_books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <span class="badge badge-flat border-teal text-teal badge-pill" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                    <strong><?php echo e($index + 1); ?></strong>
                                </span>
                            </div>
                            <div class="mr-3">
                                <?php if($book->cover_image): ?>
                                    <img src="<?php echo e(Storage::url($book->cover_image)); ?>" 
                                         alt="Cover" class="rounded" style="width: 45px; height: 60px; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                <?php else: ?>
                                    <div class="bg-teal-400 rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 60px;">
                                        <i class="icon-book3 text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <h6 class="mb-1 font-weight-semibold"><?php echo e(Str::limit($book->name ?? $book->title, 35)); ?></h6>
                                <div class="text-muted font-size-sm mb-1">
                                    <i class="icon-user mr-1"></i><?php echo e(Str::limit($book->author, 30)); ?>

                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-success badge-pill font-size-xs">
                                        <i class="icon-reading mr-1"></i>
                                        <?php echo e($book->requests_count ?? 0); ?> demande<?php echo e(($book->requests_count ?? 0) > 1 ? 's' : ''); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item text-center py-4">
                        <i class="icon-book icon-2x text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">Aucune donnée disponible</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
    /* Styles pour le dashboard bibliothécaire */
    .bg-teal-400 {
        background-color: #26a69a !important;
        color: white !important;
    }
    
    .text-teal-400 {
        color: #26a69a !important;
    }
    
    .text-teal {
        color: #26a69a !important;
    }
    
    .border-teal {
        border-color: #26a69a !important;
    }
    
    .btn-outline-teal {
        color: #26a69a;
        border-color: #26a69a;
        background-color: transparent;
    }
    
    .btn-outline-teal:hover {
        color: white;
        background-color: #26a69a;
        border-color: #26a69a;
    }
    
    /* Cartes statistiques avec hover */
    .card.card-body {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card.card-body:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Amélioration du tableau */
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    /* Badge pill amélioré */
    .badge-pill {
        padding: 0.4em 0.8em;
        font-weight: 500;
    }
    
    /* Liste de livres populaires */
    .list-group-item {
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        border-left-color: #26a69a;
        background-color: #f8f9fa;
    }
    
    /* Animation pour les statistiques */
    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-body h2 {
        animation: countUp 0.5s ease-out;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Animation au chargement des cartes statistiques
        $('.card-body').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/dashboard.blade.php ENDPATH**/ ?>