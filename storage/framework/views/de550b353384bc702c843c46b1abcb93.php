<?php $__env->startSection('page_title', 'Demandes d\'Emprunt'); ?>
<?php $__env->startSection('content'); ?>

<!-- Statistiques -->
<div class="row mb-3">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-hour-glass2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['pending']); ?></h3>
                    <span class="text-uppercase font-size-xs">En Attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark-circle icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['approved']); ?></h3>
                    <span class="text-uppercase font-size-xs">Approuvées</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-info-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['borrowed']); ?></h3>
                    <span class="text-uppercase font-size-xs">Empruntés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['overdue']); ?></h3>
                    <span class="text-uppercase font-size-xs">En Retard</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des demandes -->
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Liste des Demandes d'Emprunt</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a href="<?php echo e(route('librarian.book-requests.overdue')); ?>" class="btn btn-danger btn-sm">
                    <i class="icon-alarm mr-2"></i> Livres en Retard (<?php echo e($stats['overdue']); ?>)
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtres -->
        <form method="GET" action="<?php echo e(route('librarian.book-requests.index')); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En Attente</option>
                        <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approuvées</option>
                        <option value="borrowed" <?php echo e(request('status') == 'borrowed' ? 'selected' : ''); ?>>Empruntés</option>
                        <option value="returned" <?php echo e(request('status') == 'returned' ? 'selected' : ''); ?>>Retournés</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejetées</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" placeholder="Date début" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="icon-search4"></i> Filtrer
                    </button>
                </div>
            </div>
        </form>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Date Demande</th>
                        <th>Date Retour Prévue</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($request->id); ?></td>
                        <td>
                            <?php if($request->student): ?>
                                <?php echo e($request->student->user->name ?? 'N/A'); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($request->book): ?>
                                <strong><?php echo e($request->book->name); ?></strong>
                                <?php if($request->book->author): ?>
                                    <br><small class="text-muted"><?php echo e($request->book->author); ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') : 'N/A'); ?></td>
                        <td>
                            <?php if($request->expected_return_date): ?>
                                <?php echo e(\Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y')); ?>

                                <?php if($request->status == 'borrowed' && \Carbon\Carbon::parse($request->expected_return_date)->isPast()): ?>
                                    <span class="badge badge-danger ml-2">En retard</span>
                                <?php endif; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($request->status == 'pending'): ?>
                                <span class="badge badge-warning">En Attente</span>
                            <?php elseif($request->status == 'approved'): ?>
                                <span class="badge badge-success">Approuvée</span>
                            <?php elseif($request->status == 'borrowed'): ?>
                                <span class="badge badge-info">Emprunté</span>
                            <?php elseif($request->status == 'returned'): ?>
                                <span class="badge badge-primary">Retourné</span>
                            <?php elseif($request->status == 'rejected'): ?>
                                <span class="badge badge-danger">Rejetée</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="<?php echo e(route('librarian.book-requests.show', $request->id)); ?>" class="btn btn-sm btn-primary">
                                <i class="icon-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="icon-info22 mr-2"></i> Aucune demande trouvée
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($requests->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/book_requests/index.blade.php ENDPATH**/ ?>