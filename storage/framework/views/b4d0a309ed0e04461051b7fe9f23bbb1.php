
<?php $__env->startSection('page_title', 'Manage Exam Marks'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="alert alert-info border-0 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <div class="d-flex align-items-center">
            <i class="icon-info22 mr-3 icon-2x"></i>
            <div>
                <strong>Nouvelles Fonctionnalités Disponibles!</strong>
                <p class="mb-0">
                    Accédez au 
                    <a href="<?php echo e(route('exams.dashboard')); ?>" class="alert-link font-weight-bold">Tableau de Bord Examens</a>
                    pour gérer les calendriers, publications et analytics.
                </p>
            </div>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_schedules.index')); ?>" class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="icon-calendar icon-2x mb-2"></i>
                    <h6 class="mb-0">Calendrier d'Examens</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_analytics.index')); ?>" class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="icon-stats-dots icon-2x mb-2"></i>
                    <h6 class="mb-0">Analytics & Rapports</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('marks.tabulation')); ?>" class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="icon-table2 icon-2x mb-2"></i>
                    <h6 class="mb-0">Feuille de Tabulation</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('marks.batch_fix')); ?>" class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="icon-wrench icon-2x mb-2"></i>
                    <h6 class="mb-0">Correction Batch</h6>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-books mr-2"></i> Manage Exam Marks</h5>
            <div class="header-elements">
                <a href="<?php echo e(route('exams.dashboard')); ?>" class="btn btn-primary btn-sm">
                    <i class="icon-grid mr-2"></i>Dashboard Examens
                </a>
                <?php echo Qs::getPanelOptions(); ?>

            </div>
        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.marks.selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <style>
    .card a.card {
        text-decoration: none;
        transition: transform 0.2s;
    }
    .card a.card:hover {
        transform: translateY(-5px);
    }
    </style>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/index.blade.php ENDPATH**/ ?>