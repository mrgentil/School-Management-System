
<?php $__env->startSection('page_title', 'Placements - ' . $schedule->exam->name); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h5 class="card-title">
            <i class="icon-users4 mr-2"></i>
            Placements - <?php echo e($schedule->exam->name); ?>

        </h5>
        <div class="header-elements">
            <a href="<?php echo e(route('exam_schedules.show', $schedule->exam_id)); ?>" class="btn btn-sm btn-light">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux horaires
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info border-0">
            <div class="row">
                <div class="col-md-3">
                    <strong>Classe:</strong> <?php echo e($schedule->my_class->name); ?>

                </div>
                <div class="col-md-3">
                    <strong>Matière:</strong> <?php echo e($schedule->subject->name); ?>

                </div>
                <div class="col-md-3">
                    <strong>Date:</strong> <?php echo e($schedule->exam_date->format('d/m/Y')); ?>

                </div>
                <div class="col-md-3">
                    <strong>Heure:</strong> <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?>

                </div>
            </div>
        </div>

        <?php if($placementsByRoom->count() > 0): ?>
            <div class="row">
                <div class="col-md-12">
                    <h6 class="mb-3">
                        <i class="icon-home9 mr-2"></i>
                        Placements par Salle (<?php echo e($placementsByRoom->sum->count()); ?> étudiants)
                    </h6>
                </div>
            </div>

            <div class="row">
                <?php $__currentLoopData = $placementsByRoom; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomId => $placements): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $room = $rooms->find($roomId);
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header bg-<?php echo e($room->level == 'excellence' ? 'success' : ($room->level == 'moyen' ? 'warning' : 'danger')); ?>-400">
                                <h6 class="card-title">
                                    <i class="icon-home9 mr-2"></i>
                                    Salle <?php echo e($room->name); ?>

                                    <span class="badge badge-light badge-pill ml-2"><?php echo e($placements->count()); ?> / <?php echo e($room->capacity); ?></span>
                                </h6>
                                <div class="header-elements">
                                    <span class="badge badge-light">
                                        <?php echo e(strtoupper($room->level)); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Place</th>
                                                <th>Étudiant</th>
                                                <th>Classe</th>
                                                <th class="text-right">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $__currentLoopData = $placements->sortBy('seat_number'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $placement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><strong>#<?php echo e($placement->seat_number); ?></strong></td>
                                                <td><?php echo e($placement->student->name); ?></td>
                                                <td>
                                                    <?php if($placement->studentRecord): ?>
                                                        <?php echo e($placement->studentRecord->my_class->name); ?>

                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge badge-flat border-primary text-primary">
                                                        <?php echo e(number_format($placement->ranking_score, 2)); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="text-center mt-3">
                <a href="#" onclick="window.print(); return false;" class="btn btn-primary">
                    <i class="icon-printer mr-2"></i> Imprimer les placements
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="icon-user-cancel icon-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun placement n'a encore été généré pour cet examen.</p>
                <a href="<?php echo e(route('exam_schedules.show', $schedule->exam_id)); ?>" class="btn btn-primary">
                    <i class="icon-arrow-left8 mr-2"></i> Retour aux horaires
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_placements/show.blade.php ENDPATH**/ ?>