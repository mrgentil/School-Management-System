
<li class="nav-item">
    <a href="<?php echo e(route('teacher.messages.index')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['teacher.messages.index', 'teacher.messages.create', 'teacher.messages.show']) ? 'active' : ''); ?>">
        <i class="icon-envelop"></i> <span>💬 Messagerie</span>
        <?php
            $unreadMsgCount = \App\Models\MessageRecipient::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();
        ?>
        <?php if($unreadMsgCount > 0): ?>
            <span class="badge badge-danger badge-pill ml-auto"><?php echo e($unreadMsgCount); ?></span>
        <?php endif; ?>
    </a>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('calendar.public')); ?>" class="nav-link <?php echo e(Route::is('calendar.public') ? 'active' : ''); ?>">
        <i class="icon-calendar3"></i> <span>📅 Calendrier</span>
    </a>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('print.index')); ?>" class="nav-link <?php echo e(Route::is('print.*') ? 'active' : ''); ?>">
        <i class="icon-printer"></i> <span>🖨️ Impression</span>
    </a>
</li>
<?php /**PATH D:\laragon\www\eschool\resources\views/pages/teacher/menu.blade.php ENDPATH**/ ?>