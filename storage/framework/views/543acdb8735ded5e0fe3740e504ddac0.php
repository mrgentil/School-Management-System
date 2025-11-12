<?php $__env->startSection('page_title', 'Rapport Mensuel - Rapports'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-warning-400">
        <h6 class="card-title text-white">
            <i class="icon-calendar mr-2"></i>
            Rapport Mensuel de la Bibliothèque
        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('librarian.reports.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux rapports
            </a>
        </div>
    </div>
</div>

<!-- Sélection de période -->
<div class="card mt-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('librarian.reports.monthly')); ?>" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Mois :</label>
                <select name="month" class="form-control">
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e($month == $m ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::create()->month($m)->locale('fr')->translatedFormat('F')); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Année :</label>
                <select name="year" class="form-control">
                    <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques mensuelles -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-stack2 icon-2x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['total_requests']); ?></h3>
                        <span class="text-muted font-size-sm">Total demandes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-checkmark-circle icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['approved']); ?></h3>
                        <span class="text-muted font-size-sm">Approuvées</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-book icon-2x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['borrowed']); ?></h3>
                        <span class="text-muted font-size-sm">Empruntés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-undo2 icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['returned']); ?></h3>
                        <span class="text-muted font-size-sm">Retournés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-cross-circle icon-2x text-danger"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['rejected']); ?></h3>
                        <span class="text-muted font-size-sm">Rejetées</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-alarm icon-2x text-danger"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['overdue']); ?></h3>
                        <span class="text-muted font-size-sm">En retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-plus-circle2 icon-2x text-teal"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['new_books']); ?></h3>
                        <span class="text-muted font-size-sm">Nouveaux livres ajoutés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-checkmark3 icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['total_requests'] > 0 ? round(($stats['returned'] / $stats['total_requests']) * 100, 1) : 0); ?>%</h3>
                        <span class="text-muted font-size-sm">Taux de retour</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphique des demandes journalières -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Évolution des Demandes par Jour</h6>
    </div>
    <div class="card-body">
        <?php if($dailyStats->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Demandes</th>
                        <th>Graphique</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $maxCount = $dailyStats->max('count');
                    ?>
                    <?php $__currentLoopData = $dailyStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($stat->date)->locale('fr')->translatedFormat('d F Y')); ?></td>
                        <td><strong><?php echo e($stat->count); ?></strong></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-warning" 
                                     role="progressbar" 
                                     style="width: <?php echo e($maxCount > 0 ? ($stat->count / $maxCount) * 100 : 0); ?>%"
                                     aria-valuenow="<?php echo e($stat->count); ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="<?php echo e($maxCount); ?>">
                                    <?php echo e($stat->count); ?>

                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="icon-info22 icon-3x text-muted d-block mb-3"></i>
            <p class="text-muted">Aucune donnée disponible pour ce mois.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
    .bg-warning-400 {
        background-color: #ffa726 !important;
    }
    
    .text-teal {
        color: #26a69a !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/reports/monthly.blade.php ENDPATH**/ ?>