<?php $__env->startSection('page_title', 'Mes Notes par Période'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-certificate mr-2"></i>
            Mes Notes - <?php echo e($year); ?>

        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('student.grades.bulletin')); ?>" class="btn btn-light btn-sm">
                <i class="icon-file-text2 mr-2"></i>Voir le Bulletin Complet
            </a>
        </div>
    </div>

    <div class="card-body">
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="btn-group btn-group-lg w-100" role="group">
                    <?php for($i = 1; $i <= 4; $i++): ?>
                        <?php
                            $badgeColors = [1 => 'primary', 2 => 'info', 3 => 'success', 4 => 'warning'];
                            $isActive = $selectedPeriod == $i;
                        ?>
                        <a href="<?php echo e(route('student.grades.index', ['period' => $i])); ?>" 
                           class="btn btn-<?php echo e($isActive ? $badgeColors[$i] : 'light'); ?> <?php echo e($isActive ? 'active' : ''); ?>">
                            <i class="icon-calendar mr-2"></i>
                            Période <?php echo e($i); ?>

                            <small class="d-block"><?php echo e($i <= 2 ? 'Semestre 1' : 'Semestre 2'); ?></small>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0">
            <i class="icon-info22 mr-2"></i>
            <strong>Période <?php echo e($selectedPeriod); ?></strong> - 
            <?php echo e($selectedPeriod <= 2 ? 'Semestre 1' : 'Semestre 2'); ?>

        </div>

        <?php if(count($gradesData) > 0): ?>
            <?php $__currentLoopData = $gradesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <i class="icon-book mr-2"></i>
                                    <strong><?php echo e($data['subject']->name); ?></strong>
                                </h6>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if($data['graded_count'] > 0): ?>
                                    <span class="badge badge-primary badge-pill">
                                        Total: <?php echo e($data['total_score']); ?>/<?php echo e($data['total_max_score']); ?>

                                        (<?php echo e($data['total_on_twenty']); ?>/20)
                                    </span>
                                    <?php if($data['period_average']): ?>
                                        <span class="badge badge-success badge-pill ml-2">
                                            Moyenne P<?php echo e($selectedPeriod); ?>: <?php echo e($data['period_average']); ?>/20
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-pill">Aucune note</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php if(count($data['submissions']) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 35%">Devoir</th>
                                            <th style="width: 15%">Date Limite</th>
                                            <th style="width: 15%">Ma Note</th>
                                            <th style="width: 15%">Sur 20</th>
                                            <th style="width: 15%">Pourcentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $data['submissions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($index + 1); ?></td>
                                                <td>
                                                    <strong><?php echo e($sub['assignment']->title); ?></strong>
                                                    <?php if($sub['submission']): ?>
                                                        <br><small class="text-success">
                                                            <i class="icon-checkmark-circle"></i> Soumis
                                                        </small>
                                                    <?php else: ?>
                                                        <br><small class="text-muted">
                                                            <i class="icon-cross-circle2"></i> Non noté
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo e($sub['assignment']->due_date ? $sub['assignment']->due_date->format('d/m/Y') : 'N/A'); ?></small>
                                                </td>
                                                <td>
                                                    <?php if($sub['submission']): ?>
                                                        <strong class="text-success">
                                                            <?php echo e($sub['score']); ?>/<?php echo e($sub['max_score']); ?>

                                                        </strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">-/-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($sub['submission']): ?>
                                                        <strong class="text-primary">
                                                            <?php echo e($sub['on_twenty']); ?>/20
                                                        </strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($sub['submission']): ?>
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-<?php echo e($sub['percentage'] >= 60 ? 'success' : ($sub['percentage'] >= 50 ? 'warning' : 'danger')); ?>" 
                                                                 role="progressbar" 
                                                                 style="width: <?php echo e($sub['percentage']); ?>%"
                                                                 aria-valuenow="<?php echo e($sub['percentage']); ?>" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                                <?php echo e($sub['percentage']); ?>%
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <?php if($data['graded_count'] > 0): ?>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="3" class="text-right">TOTAL</td>
                                                <td>
                                                    <strong class="text-success">
                                                        <?php echo e($data['total_score']); ?>/<?php echo e($data['total_max_score']); ?>

                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-primary">
                                                        <?php echo e($data['total_on_twenty']); ?>/20
                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-info">
                                                        <?php echo e($data['total_percentage']); ?>%
                                                    </strong>
                                                </td>
                                            </tr>
                                            <?php if($data['period_average']): ?>
                                                <tr class="table-success">
                                                    <td colspan="3" class="text-right">MOYENNE PÉRIODE <?php echo e($selectedPeriod); ?></td>
                                                    <td colspan="3">
                                                        <strong class="text-success h6">
                                                            <?php echo e($data['period_average']); ?>/20
                                                        </strong>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="icon-warning mr-2"></i>
                                Aucun devoir pour cette période.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune note disponible pour la Période <?php echo e($selectedPeriod); ?>.
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-secondary text-white">
        <h6 class="mb-0">
            <i class="icon-info22 mr-2"></i>
            Légende
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Total :</strong> Somme de vos notes brutes (ex: 10/35)</p>
                <p><strong>Sur 20 :</strong> Notes ramenées sur 20 pour comparaison</p>
            </div>
            <div class="col-md-6">
                <p><strong>Pourcentage :</strong> Votre taux de réussite</p>
                <p><strong>Moyenne Période :</strong> Calculée automatiquement à partir de toutes vos notes</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/grades/index.blade.php ENDPATH**/ ?>