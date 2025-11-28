<?php $__env->startSection('page_title', 'Message - ' . $message->subject); ?>

<?php $__env->startSection('content'); ?>
    <?php $routePrefix = 'student'; ?>
    <?php echo $__env->make('partials.messages.show', [
        'message' => $message,
        'conversation' => $conversation,
        'routePrefix' => $routePrefix
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/messages/show.blade.php ENDPATH**/ ?>