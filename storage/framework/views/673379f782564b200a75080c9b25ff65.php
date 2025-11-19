
<?php $__env->startSection('page_title', 'Calendrier des Examens'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title">
                <i class="icon-calendar mr-2"></i> Vue Calendrier - Examens à Venir
            </h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <div class="mb-3">
                <a href="<?php echo e(route('exam_schedules.index')); ?>" class="btn btn-light">
                    <i class="icon-list"></i> Vue Liste
                </a>
            </div>

            <?php if($upcoming->count() > 0): ?>
                <div class="timeline timeline-left">
                    <?php $currentDate = null; ?>
                    <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($currentDate != $schedule->exam_date->format('Y-m-d')): ?>
                            <?php $currentDate = $schedule->exam_date->format('Y-m-d'); ?>
                            <div class="timeline-container">
                                <div class="timeline-icon bg-primary">
                                    <i class="icon-calendar3"></i>
                                </div>
                                <div class="timeline-card card">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <?php echo e($schedule->exam_date->format('l d F Y')); ?>

                                        </h6>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="timeline-container">
                            <div class="timeline-icon bg-<?php echo e($schedule->status == 'scheduled' ? 'info' : ($schedule->status == 'completed' ? 'success' : 'warning')); ?>">
                                <i class="icon-clock"></i>
                            </div>
                            <div class="timeline-card card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="font-weight-bold mb-1">
                                                <?php echo e($schedule->subject->name); ?>

                                            </h6>
                                            <p class="text-muted mb-1">
                                                <strong>Examen:</strong> <?php echo e($schedule->exam->name); ?> | 
                                                <strong>Classe:</strong> <?php echo e($schedule->my_class->name); ?>

                                                <?php if($schedule->section): ?>
                                                    - <?php echo e($schedule->section->name); ?>

                                                <?php endif; ?>
                                            </p>
                                            <p class="mb-1">
                                                <i class="icon-clock mr-1"></i>
                                                <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?>

                                            </p>
                                            <?php if($schedule->room): ?>
                                                <p class="mb-1">
                                                    <i class="icon-location4 mr-1"></i>
                                                    Salle: <strong><?php echo e($schedule->room); ?></strong>
                                                </p>
                                            <?php endif; ?>
                                            <?php if($schedule->supervisors->count() > 0): ?>
                                                <p class="mb-0">
                                                    <i class="icon-user mr-1"></i>
                                                    Surveillants: 
                                                    <?php $__currentLoopData = $schedule->supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-secondary"><?php echo e($sup->teacher->name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="badge badge-<?php echo e($schedule->status == 'scheduled' ? 'info' : ($schedule->status == 'completed' ? 'success' : 'warning')); ?>">
                                                <?php if($schedule->status == 'scheduled'): ?>
                                                    Planifié
                                                <?php elseif($schedule->status == 'ongoing'): ?>
                                                    En cours
                                                <?php elseif($schedule->status == 'completed'): ?>
                                                    Terminé
                                                <?php else: ?>
                                                    Annulé
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="icon-calendar-empty icon-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun examen planifié dans les 30 prochains jours</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_schedules/calendar.blade.php ENDPATH**/ ?>