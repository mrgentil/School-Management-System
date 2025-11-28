<?php $__env->startSection('page_title', 'Nouveau message'); ?>

<?php $__env->startSection('content'); ?>
    <?php $routePrefix = 'super_admin'; ?>
    <?php echo $__env->make('partials.messages.create', [
        'recipients' => $recipients,
        'routePrefix' => $routePrefix,
        'showBulkOptions' => true
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/super_admin/messages/create.blade.php ENDPATH**/ ?>