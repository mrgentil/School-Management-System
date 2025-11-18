
<?php $__env->startSection('page_title', 'Mon Calendrier d\'Examens'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline bg-primary text-white">
                    <h6 class="card-title">
                        <i class="icon-calendar mr-2"></i> Mon Calendrier d'Examens
                    </h6>
                    <?php echo Qs::getPanelOptions(); ?>

                </div>

                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <strong>Classe:</strong> <?php echo e($my_class->name); ?> - <?php echo e($section->name); ?>

                    </div>

                    
                    <?php if($upcoming->count() > 0): ?>
                    <div class="card">
                        <div class="card-header bg-warning-400">
                            <h6 class="card-title">
                                <i class="icon-alarm-add mr-2"></i> Examens à Venir (30 prochains jours)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-3 border-left-warning">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($schedule->subject->name); ?></h6>
                                                    <p class="text-muted mb-1"><?php echo e($schedule->exam->name); ?></p>
                                                </div>
                                                <span class="badge badge-warning"><?php echo e(\Carbon\Carbon::parse($schedule->exam_date)->diffForHumans()); ?></span>
                                            </div>
                                            <div class="mt-2">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-calendar3 mr-2 text-primary"></i>
                                                    <span><strong>Date:</strong> <?php echo e($schedule->exam_date->format('d/m/Y')); ?></span>
                                                </div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-clock mr-2 text-success"></i>
                                                    <span><strong>Heure:</strong> <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?></span>
                                                </div>
                                                <?php if($schedule->room): ?>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-location4 mr-2 text-danger"></i>
                                                    <span><strong>Salle:</strong> <?php echo e($schedule->room); ?></span>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($schedule->instructions): ?>
                                                <div class="mt-2">
                                                    <div class="alert alert-light mb-0">
                                                        <strong>Instructions:</strong><br>
                                                        <?php echo e($schedule->instructions); ?>

                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="card mt-3">
                        <div class="card-header bg-info-400">
                            <h6 class="card-title">
                                <i class="icon-calendar2 mr-2"></i> Tous les Examens Planifiés
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if($schedules->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="bg-light">
                                        <th>Examen</th>
                                        <th>Matière</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Salle</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $schedules->sortBy('exam_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><strong><?php echo e($schedule->exam->name); ?></strong></td>
                                            <td><?php echo e($schedule->subject->name); ?></td>
                                            <td>
                                                <span class="badge badge-flat border-primary text-primary">
                                                    <?php echo e($schedule->exam_date->format('d/m/Y')); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?></td>
                                            <td><?php echo e($schedule->room ?: '-'); ?></td>
                                            <td>
                                                <?php
                                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                                    $end = \Carbon\Carbon::parse($schedule->end_time);
                                                    $duration = $start->diffInMinutes($end);
                                                ?>
                                                <?php echo e($duration); ?> min
                                            </td>
                                            <td>
                                                <?php if($schedule->status == 'scheduled'): ?>
                                                    <span class="badge badge-info">Planifié</span>
                                                <?php elseif($schedule->status == 'ongoing'): ?>
                                                    <span class="badge badge-warning">En cours</span>
                                                <?php elseif($schedule->status == 'completed'): ?>
                                                    <span class="badge badge-success">Terminé</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Annulé</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="icon-calendar-empty icon-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun examen planifié pour le moment</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/exam_schedule.blade.php ENDPATH**/ ?>