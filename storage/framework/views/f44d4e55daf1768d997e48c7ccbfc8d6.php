
<?php $__env->startSection('page_title', 'Analyses et Rapports'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title">
                <i class="icon-stats-dots mr-2"></i> Analyses et Rapports d'Examens
            </h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Sélectionner un Examen pour l'Analyse</h5>
                </div>
            </div>

            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-3 border-left-primary">
                            <div class="card-body">
                                <h6 class="font-weight-bold"><?php echo e($exam->name); ?></h6>
                                <p class="text-muted mb-2">
                                    <i class="icon-calendar mr-1"></i> <?php echo e($exam->year); ?> - Semestre <?php echo e($exam->semester); ?>

                                </p>
                                <div class="mt-3">
                                    <a href="<?php echo e(route('exam_analytics.overview', $exam->id)); ?>" class="btn btn-primary btn-sm">
                                        <i class="icon-stats-dots"></i> Voir l'Analyse
                                    </a>
                                    <a href="<?php echo e(route('exam_publication.show', $exam->id)); ?>" class="btn btn-info btn-sm ml-2">
                                        <i class="icon-eye"></i> Publication
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            Aucun examen disponible pour l'année en cours.
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="card mt-4">
                <div class="card-header bg-success-400">
                    <h6 class="card-title">
                        <i class="icon-library2 mr-2"></i> Analyses par Classe
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6><?php echo e($class->name); ?></h6>
                                        <select class="form-control select-search" onchange="if(this.value) window.location.href=this.value">
                                            <option value="">Sélectionner examen...</option>
                                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e(route('exam_analytics.class_analysis', [$exam->id, $class->id])); ?>">
                                                    <?php echo e($exam->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_analytics/index.blade.php ENDPATH**/ ?>