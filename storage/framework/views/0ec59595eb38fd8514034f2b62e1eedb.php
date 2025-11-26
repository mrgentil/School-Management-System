
<?php $__env->startSection('page_title', 'Relevé de Notes'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="card bg-primary text-white">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">📄 Relevé de Notes - <?php echo e($sr->user->name); ?></h4>
                    <p class="mb-0"><?php echo e($my_class->full_name ?: $my_class->name); ?> | Année Scolaire : <?php echo e($year); ?></p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="<?php echo e(route('marks.bulk')); ?>" class="btn btn-light btn-sm mr-2">
                        <i class="icon-arrow-left5 mr-1"></i> Retour
                    </a>
                    <a href="<?php echo e(route('exam_analytics.student_progress', $student_id)); ?>" class="btn btn-light btn-sm">
                        <i class="icon-graph mr-1"></i> Progression
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card border-left-3 border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Moyenne Générale</h6>
                            <h3 class="mb-0 text-success"><?php echo e($exam_records->avg('ave') ? round($exam_records->avg('ave'), 1) : 'N/A'); ?>%</h3>
                        </div>
                        <i class="icon-trophy icon-3x text-success-400"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-3 border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Meilleur Rang</h6>
                            <h3 class="mb-0 text-primary"><?php echo e($exam_records->min('pos') ?? 'N/A'); ?><?php echo e($exam_records->min('pos') == 1 ? 'er' : 'ème'); ?></h3>
                        </div>
                        <i class="icon-medal icon-3x text-primary-400"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-3 border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Évaluations</h6>
                            <h3 class="mb-0 text-warning"><?php echo e($exam_records->count()); ?></h3>
                        </div>
                        <i class="icon-file-text2 icon-3x text-warning-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $exam_records->where('exam_id', $ex->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="font-weight-bold"><?php echo e($ex->name.' - '.$ex->year); ?></h6>
                        <?php echo Qs::getPanelOptions(); ?>

                    </div>

                    <div class="card-body collapse">

                        
                        <?php echo $__env->make('pages.support_team.marks.show.sheet', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        
                        <div class="text-center mt-3">
                            <a target="_blank" href="<?php echo e(route('marks.print', [Qs::hash($student_id), $ex->id, $year])); ?>" class="btn btn-secondary btn-lg">
                                <i class="icon-printer mr-2"></i> Imprimer le Relevé
                            </a>
                        </div>

                    </div>

                </div>

            
            <?php echo $__env->make('pages.support_team.marks.show.comments', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <?php echo $__env->make('pages.support_team.marks.show.skills', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/show/index.blade.php ENDPATH**/ ?>