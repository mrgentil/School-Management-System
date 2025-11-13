<?php $__env->startSection('page_title', 'Mes Devoirs'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Devoirs</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <?php if($assignments->isNotEmpty()): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Date de remise</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $submission = $assignment->submissions->first();
                                $status = $submission 
                                    ? ($submission->marks !== null ? 'Noté' : 'Soumis')
                                    : 'Non soumis';
                                $statusClass = $submission 
                                    ? ($submission->marks !== null ? 'badge-success' : 'badge-info')
                                    : 'badge-warning';
                            ?>
                            <tr>
                                <td><?php echo e($key + 1); ?></td>
                                <td><?php echo e($assignment->title); ?></td>
                                <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($assignment->due_date->format('d/m/Y H:i')); ?></td>
                                <td><span class="badge <?php echo e($statusClass); ?>"><?php echo e($status); ?></span></td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('student.assignments.show', $assignment->id)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="icon-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <?php echo e($assignments->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="icon-book3 icon-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun devoir trouvé</h5>
                <p class="text-muted">Aucun devoir n'a été assigné pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialisation des tooltips
        $('[data-popup="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/assignments/index.blade.php ENDPATH**/ ?>