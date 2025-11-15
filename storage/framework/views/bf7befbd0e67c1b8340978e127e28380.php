
<?php $__env->startSection('page_title', 'Student Promotion'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">Student Promotion From <span class="text-danger"><?php echo e($old_year); ?></span> TO <span class="text-success"><?php echo e($new_year); ?></span> Session</h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.students.promotion.selector', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php if($selected): ?>
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">Promote Students From <span class="text-teal"><?php echo e($my_classes->where('id', $fc)->first()->name.' '.$sections->where('id', $fs)->first()->name); ?></span> TO <span class="text-purple"><?php echo e($my_classes->where('id', $tc)->first()->name.' '.$sections->where('id', $ts)->first()->name); ?></span> </h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <?php echo $__env->make('pages.support_team.students.promotion.promote', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <?php endif; ?>


    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/students/promotion/index.blade.php ENDPATH**/ ?>