<?php $__env->startSection('page_title', 'Livres Populaires - Rapports'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-success-400">
        <h6 class="card-title text-white">
            <i class="icon-stars mr-2"></i>
            Livres les Plus Populaires
        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('librarian.reports.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux rapports
            </a>
        </div>
    </div>
</div>

<!-- Filtres de période -->
<div class="card mt-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('librarian.reports.popular-books')); ?>" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Du :</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e($startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate); ?>">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Au :</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e($endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate); ?>">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Tableau des livres populaires -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Top 20 des Livres les Plus Empruntés</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="5%">Rang</th>
                    <th width="10%">Couverture</th>
                    <th width="30%">Titre</th>
                    <th width="20%">Auteur</th>
                    <th width="15%">Catégorie</th>
                    <th width="10%" class="text-center">Emprunts</th>
                    <th width="10%" class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $popularBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <span class="badge badge-flat 
                            <?php if($index == 0): ?> badge-success 
                            <?php elseif($index == 1): ?> badge-primary 
                            <?php elseif($index == 2): ?> badge-warning 
                            <?php else: ?> badge-secondary <?php endif; ?> 
                            badge-pill" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <strong><?php echo e($index + 1); ?></strong>
                        </span>
                    </td>
                    <td>
                        <?php if($book->cover_image): ?>
                            <img src="<?php echo e(Storage::url($book->cover_image)); ?>" 
                                 alt="<?php echo e($book->name); ?>" 
                                 class="rounded shadow-sm" 
                                 style="width: 50px; height: 70px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-success-400 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                <i class="icon-book3 text-white icon-2x"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="font-weight-semibold"><?php echo e($book->name); ?></div>
                        <small class="text-muted">ISBN: <?php echo e($book->isbn ?? 'N/A'); ?></small>
                    </td>
                    <td><?php echo e($book->author); ?></td>
                    <td>
                        <span class="badge badge-info"><?php echo e($book->category ?? 'Non classifié'); ?></span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success badge-pill font-size-lg">
                            <i class="icon-reading mr-1"></i>
                            <?php echo e($book->requests_count); ?>

                        </span>
                    </td>
                    <td class="text-center">
                        <?php if($book->available_copies > 0): ?>
                            <span class="badge badge-success"><?php echo e($book->available_copies); ?> / <?php echo e($book->total_copies); ?></span>
                        <?php else: ?>
                            <span class="badge badge-danger">Épuisé</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="icon-info22 icon-3x text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Aucune donnée disponible</h5>
                        <p class="text-muted">Aucun livre n'a été emprunté durant cette période.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
    .bg-success-400 {
        background-color: #66bb6a !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/reports/popular_books.blade.php ENDPATH**/ ?>