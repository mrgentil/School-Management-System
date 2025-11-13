<?php $__env->startSection('page_title', 'Mes Présences'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Présences</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
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
                            <td><?php echo e($attendance->attendance_date->format('d/m/Y')); ?></td>
                            <td>
                                <?php if($attendance->status == 'present'): ?>
                                    <span class="badge badge-success">Présent</span>
                                <?php elseif($attendance->status == 'absent'): ?>
                                    <span class="badge badge-danger">Absent</span>
                                <?php elseif($attendance->status == 'late'): ?>
                                    <span class="badge badge-warning">En retard</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Autre</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($attendance->remarks ?? 'Aucune remarque'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucune présence enregistrée pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/attendance/index.blade.php ENDPATH**/ ?>