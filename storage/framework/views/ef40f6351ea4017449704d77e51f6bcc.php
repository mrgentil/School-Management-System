
<?php $__env->startSection('page_title', 'Promotion des Étudiants'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">
                Promotion des Étudiants de la session
                <span class="text-danger"><?php echo e($old_year); ?></span>
                vers
                <span class="text-success"><?php echo e($new_year); ?></span>
            </h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.students.promotion.selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php if($selected): ?>
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">
                Promouvoir les étudiants de
                <span class="text-teal">
                    <?php echo e(optional($my_classes->where('id', $fc)->first())->full_name ?: optional($my_classes->where('id', $fc)->first())->name); ?>

                    <?php echo e($sections->where('id', $fs)->first()->name ?? ''); ?>

                </span>
                vers
                <span class="text-purple">
                    <?php echo e(optional($my_classes->where('id', $tc)->first())->full_name ?: optional($my_classes->where('id', $tc)->first())->name); ?>

                    <?php echo e($sections->where('id', $ts)->first()->name ?? ''); ?>

                </span>
            </h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.students.promotion.promote', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <?php endif; ?>


    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/students/promotion/index.blade.php ENDPATH**/ ?>