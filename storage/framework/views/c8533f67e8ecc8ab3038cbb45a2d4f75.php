
<?php $__env->startSection('page_title', 'Messagerie'); ?>

<?php $__env->startSection('content'); ?>
    <?php $routePrefix = 'teacher'; ?>
    <?php echo $__env->make('partials.messages.index', [
        'messages' => $messages,
        'filter' => $filter,
        'unreadCount' => $unreadCount,
        'sentCount' => $sentCount,
        'inboxCount' => $inboxCount,
        'routePrefix' => $routePrefix
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/teacher/messages/index.blade.php ENDPATH**/ ?>