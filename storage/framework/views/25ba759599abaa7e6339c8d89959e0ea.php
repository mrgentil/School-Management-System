
<?php $__env->startSection('page_title', 'Calendrier des Examens'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Calendrier des Examens</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="<?php echo e(route('exam_schedules.calendar')); ?>" class="btn btn-primary">
                        <i class="icon-calendar"></i> Vue Calendrier
                    </a>
                </div>
            </div>

            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Examen</th>
                    <th>Année</th>
                    <th>Semestre</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($ex->name); ?></td>
                        <td><?php echo e($ex->year); ?></td>
                        <td>Semestre <?php echo e($ex->semester); ?></td>
                        <td>
                            <?php if($ex->status == 'published'): ?>
                                <span class="badge badge-success">Publié</span>
                            <?php elseif($ex->status == 'active'): ?>
                                <span class="badge badge-primary">Actif</span>
                            <?php elseif($ex->status == 'grading'): ?>
                                <span class="badge badge-warning">Notation</span>
                            <?php elseif($ex->status == 'archived'): ?>
                                <span class="badge badge-secondary">Archivé</span>
                            <?php else: ?>
                                <span class="badge badge-info">Brouillon</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="<?php echo e(route('exam_schedules.show', $ex->id)); ?>" class="dropdown-item">
                                            <i class="icon-calendar"></i> Gérer Horaires
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_schedules/index.blade.php ENDPATH**/ ?>