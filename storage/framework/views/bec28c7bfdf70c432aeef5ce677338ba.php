<?php $__env->startSection('page_title', 'Retards et Pénalités - Rapports'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-danger-400">
        <h6 class="card-title text-white">
            <i class="icon-alarm mr-2"></i>
            Rapport des Retards et Pénalités
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
        <form method="GET" action="<?php echo e(route('librarian.reports.penalties')); ?>" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Du :</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e($startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate); ?>">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Au :</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e($endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate); ?>">
            </div>
            <button type="submit" class="btn btn-danger">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques des retards -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-alarm icon-3x text-warning"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($penalties->count()); ?></h3>
                        <span class="text-muted font-size-sm">Retours en retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-calendar icon-3x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($totalDaysLate); ?></h3>
                        <span class="text-muted font-size-sm">Total jours de retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-stats-bars icon-3x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?php echo e($penalties->count() > 0 ? round($totalDaysLate / $penalties->count(), 1) : 0); ?></h3>
                        <span class="text-muted font-size-sm">Moyenne jours/retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des pénalités -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Détail des Retards et Pénalités</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="25%">Étudiant</th>
                    <th width="30%">Livre</th>
                    <th width="15%" class="text-center">Date retour prévue</th>
                    <th width="15%" class="text-center">Date retour réel</th>
                    <th width="15%" class="text-center">Jours de retard</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <?php if($penalty->student && $penalty->student->user): ?>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="bg-danger-400 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <span class="text-white font-weight-bold font-size-sm"><?php echo e(strtoupper(substr($penalty->student->user->name, 0, 1))); ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-semibold"><?php echo e($penalty->student->user->name); ?></div>
                                    <small class="text-muted"><?php echo e($penalty->student->user->email ?? ''); ?></small>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($penalty->book): ?>
                            <div class="font-weight-semibold"><?php echo e(Str::limit($penalty->book->name, 40)); ?></div>
                            <small class="text-muted"><?php echo e($penalty->book->author ?? ''); ?></small>
                        <?php else: ?>
                            <span class="text-muted">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($penalty->expected_return_date): ?>
                            <?php echo e(\Carbon\Carbon::parse($penalty->expected_return_date)->format('d/m/Y')); ?>

                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if($penalty->actual_return_date): ?>
                            <?php echo e(\Carbon\Carbon::parse($penalty->actual_return_date)->format('d/m/Y')); ?>

                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="badge 
                            <?php if($penalty->days_late > 30): ?> badge-danger
                            <?php elseif($penalty->days_late > 14): ?> badge-warning
                            <?php else: ?> badge-info
                            <?php endif; ?> badge-pill font-size-lg">
                            <?php echo e($penalty->days_late ?? 0); ?> jour<?php echo e(($penalty->days_late ?? 0) > 1 ? 's' : ''); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="icon-checkmark-circle icon-3x text-success d-block mb-3"></i>
                        <h5 class="text-muted">Aucun retard enregistré</h5>
                        <p class="text-muted">Tous les livres ont été retournés à temps durant cette période.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($penalties->count() > 0): ?>
    <div class="card-footer bg-light">
        <div class="row">
            <div class="col-md-4">
                <strong>Total retours en retard : </strong>
                <span class="text-danger font-size-lg"><?php echo e($penalties->count()); ?></span>
            </div>
            <div class="col-md-4 text-center">
                <strong>Total jours de retard : </strong>
                <span class="text-warning font-size-lg"><?php echo e($totalDaysLate); ?></span>
            </div>
            <div class="col-md-4 text-right">
                <strong>Moyenne jours/retard : </strong>
                <span class="text-info font-size-lg"><?php echo e(round($totalDaysLate / $penalties->count(), 1)); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Graphique de distribution -->
<?php if($penalties->count() > 0): ?>
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Analyse des Retards</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-info mb-2"><?php echo e($penalties->where('days_late', '<=', 7)->count()); ?></h4>
                    <p class="text-muted mb-0">Retards ≤ 7 jours</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-warning mb-2"><?php echo e($penalties->whereBetween('days_late', [8, 14])->count()); ?></h4>
                    <p class="text-muted mb-0">Retards 8-14 jours</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-danger mb-2"><?php echo e($penalties->where('days_late', '>', 14)->count()); ?></h4>
                    <p class="text-muted mb-0">Retards > 14 jours</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->startSection('styles'); ?>
<style>
    .bg-danger-400 {
        background-color: #ef5350 !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/reports/penalties.blade.php ENDPATH**/ ?>