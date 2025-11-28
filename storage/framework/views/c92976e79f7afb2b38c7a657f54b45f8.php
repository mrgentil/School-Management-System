
<?php $__env->startSection('page_title', 'Mes Notifications'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-bell2 mr-2"></i>Mes Notifications
            <?php if($unreadCount > 0): ?>
                <span class="badge badge-light ml-2"><?php echo e($unreadCount); ?> non lue(s)</span>
            <?php endif; ?>
        </h6>
        <div class="header-elements">
            <?php if($unreadCount > 0): ?>
                <form action="<?php echo e(route('student.notifications.mark_all_read')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="icon-checkmark3 mr-1"></i>Tout marquer comme lu
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body p-0">
        <?php if($notifications->count() > 0): ?>
            <div class="list-group list-group-flush">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item list-group-item-action <?php echo e(!$notification->is_read ? 'bg-light' : ''); ?>">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <span class="btn btn-<?php echo e($notification->color); ?> btn-icon rounded-circle">
                                        <i class="<?php echo e($notification->icon); ?>"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1 <?php echo e(!$notification->is_read ? 'font-weight-bold' : ''); ?>">
                                        <?php echo e($notification->title); ?>

                                        <small class="text-muted">(#<?php echo e($notification->id); ?> - user:<?php echo e($notification->user_id); ?>)</small>
                                        <?php if(!$notification->is_read): ?>
                                            <span class="badge badge-primary badge-pill ml-1">Nouveau</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-1 text-muted"><?php echo e($notification->message); ?></p>
                                    <small class="text-muted">
                                        <i class="icon-clock mr-1"></i><?php echo e($notification->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <?php if($notification->data && isset($notification->data['url'])): ?>
                                    <a href="<?php echo e(route('student.notifications.read', $notification->id)); ?>" class="btn btn-sm btn-outline-primary mr-2">
                                        <i class="icon-arrow-right7"></i> Voir
                                    </a>
                                <?php elseif(!$notification->is_read): ?>
                                    <form action="<?php echo e(route('student.notifications.read', $notification->id)); ?>" method="POST" class="d-inline mr-2">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="icon-checkmark"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('student.notifications.destroy', $notification->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette notification ?')">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="card-footer">
                <?php echo e($notifications->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="icon-bell2 text-muted" style="font-size: 48px;"></i>
                <p class="text-muted mt-3">Aucune notification pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if($notifications->where('is_read', true)->count() > 0): ?>
    <div class="text-center mt-3">
        <form action="<?php echo e(route('student.notifications.clear_read')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer toutes les notifications lues ?')">
                <i class="icon-trash mr-1"></i>Supprimer les notifications lues
            </button>
        </form>
    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/notifications/index.blade.php ENDPATH**/ ?>