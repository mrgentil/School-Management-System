<?php $__env->startSection('page_title', 'Tableau de Bord - Bibliothécaire'); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <!-- Statistiques de la bibliothèque -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0"><?php echo e($stats['total_books']); ?></h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Total Livres</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-books icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0"><?php echo e($stats['available_books']); ?></h3>
                    <span class="text-uppercase font-size-xs">Livres Disponibles</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-checkmark3 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0"><?php echo e($stats['borrowed_books']); ?></h3>
                    <span class="text-uppercase font-size-xs">Livres Empruntés</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-reading icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0"><?php echo e($stats['overdue_books']); ?></h3>
                    <span class="text-uppercase font-size-xs">Retards</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
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
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Livre</th>
                                <th>Date Demande</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($request->student->name ?? 'N/A'); ?></td>
                                <td><?php echo e($request->book->title ?? $request->titre ?? 'N/A'); ?></td>
                                <td><?php echo e($request->created_at->format('d/m/Y')); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger')); ?>">
                                        <?php echo e(ucfirst($request->status)); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($request->status == 'pending'): ?>
                                    <a href="<?php echo e(route('librarian.book-requests.show', $request->id)); ?>" class="btn btn-sm btn-outline-primary">
                                        Traiter
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucune demande récente</td>
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
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $popular_books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <img src="<?php echo e($book->cover_image ?? asset('global_assets/images/placeholders/placeholder.jpg')); ?>" 
                             alt="Cover" class="rounded" style="width: 40px; height: 50px; object-fit: cover;">
                    </div>
                    <div class="flex-1">
                        <h6 class="mb-0"><?php echo e(Str::limit($book->title, 30)); ?></h6>
                        <small class="text-muted"><?php echo e($book->author); ?></small>
                        <div>
                            <small class="text-success"><?php echo e($book->requests_count); ?> demandes</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/dashboard.blade.php ENDPATH**/ ?>