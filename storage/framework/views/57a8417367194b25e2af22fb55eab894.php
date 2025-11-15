<?php $__env->startSection('page_title', 'Mes Présences'); ?>

<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark3 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['present']); ?></h3>
                    <span class="text-uppercase font-size-xs">Présent</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-cross2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['absent']); ?></h3>
                    <span class="text-uppercase font-size-xs">Absent</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['late']); ?></h3>
                    <span class="text-uppercase font-size-xs">En retard</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-info-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-file-text2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['excused']); ?></h3>
                    <span class="text-uppercase font-size-xs">Excusé</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Historique des Présences</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        
        <form method="GET" action="<?php echo e(route('student.attendance.index')); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Mois</label>
                        <select name="month" class="form-control">
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($num); ?>" <?php echo e($filters['month'] == $num ? 'selected' : ''); ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Année</label>
                        <select name="year" class="form-control">
                            <?php for($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($filters['year'] == $y ? 'selected' : ''); ?>>
                                    <?php echo e($y); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="present" <?php echo e($filters['status'] == 'present' ? 'selected' : ''); ?>>Présent</option>
                            <option value="absent" <?php echo e($filters['status'] == 'absent' ? 'selected' : ''); ?>>Absent</option>
                            <option value="late" <?php echo e($filters['status'] == 'late' ? 'selected' : ''); ?>>En retard</option>
                            <option value="excused" <?php echo e($filters['status'] == 'excused' ? 'selected' : ''); ?>>Excusé</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4 mr-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Remarques</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($attendance->date ? $attendance->date->format('d/m/Y') : 'N/A'); ?></td>
                            <td>
                                <?php if($attendance->status == 'present'): ?>
                                    <span class="badge badge-success">Présent</span>
                                <?php elseif($attendance->status == 'absent'): ?>
                                    <span class="badge badge-danger">Absent</span>
                                <?php elseif($attendance->status == 'late'): ?>
                                    <span class="badge badge-warning">En retard</span>
                                <?php elseif($attendance->status == 'excused'): ?>
                                    <span class="badge badge-info">Excusé</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e(ucfirst($attendance->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($attendance->notes ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucune présence enregistrée pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($attendances->hasPages()): ?>
            <div class="mt-3">
                <?php echo e($attendances->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/attendance/index.blade.php ENDPATH**/ ?>