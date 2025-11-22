
<?php $__env->startSection('page_title', 'Gestion des Notes'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_schedules.index')); ?>" class="btn btn-primary btn-block">
                <i class="icon-calendar mr-2"></i>Calendrier d'Examens
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_analytics.index')); ?>" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analytics & Rapports
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('marks.tabulation')); ?>" class="btn btn-warning btn-block">
                <i class="icon-table2 mr-2"></i>Feuille de Tabulation
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('marks.batch_fix')); ?>" class="btn btn-info btn-block">
                <i class="icon-wrench mr-2"></i>Correction Batch
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary">
            <h6 class="card-title text-white font-weight-bold"><i class="icon-pencil5 mr-2"></i>Sélectionner l'Examen et la Classe</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.marks.selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <div class="card">

        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-book mr-2 text-primary"></i>
                        <strong>Matière:</strong> <?php echo e($m->subject->name); ?>

                    </h6>
                </div>
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-users mr-2 text-success"></i>
                        <strong>Classe:</strong> <?php echo e(($m->my_class ? ($m->my_class->full_name ?: $m->my_class->name) : 'N/A').' '.($m->section ? $m->section->name : 'N/A')); ?>

                    </h6>
                </div>
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-file-text2 mr-2 text-warning"></i>
                        <strong>Examen:</strong> <?php echo e($m->exam->name.' - '.$m->year); ?>

                    </h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.marks.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/manage.blade.php ENDPATH**/ ?>