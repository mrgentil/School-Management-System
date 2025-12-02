
<?php $__env->startSection('page_title', 'Tableau de Bord Parent'); ?>

<?php $__env->startSection('content'); ?>

<div class="card bg-primary text-white mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">👋 Bienvenue, <?php echo e(Auth::user()->name); ?></h4>
                <p class="mb-0 opacity-75">Année scolaire <?php echo e($year); ?></p>
            </div>
            <div class="text-right">
                <span class="badge badge-light text-primary"><?php echo e($stats['total_children']); ?> enfant(s)</span>
            </div>
        </div>
    </div>
</div>


<?php if($stats['total_balance'] > 0): ?>
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <span><i class="icon-warning mr-2"></i> Vous avez un solde de <strong><?php echo e(number_format($stats['total_balance'], 0, ',', ' ')); ?> FC</strong> à régler.</span>
    <a href="<?php echo e(route('parent.progress.index')); ?>" class="btn btn-sm btn-warning">Voir détails</a>
</div>
<?php endif; ?>

<?php if($stats['unread_notifications'] > 0): ?>
<div class="alert alert-info d-flex justify-content-between align-items-center">
    <span><i class="icon-bell2 mr-2"></i> Vous avez <strong><?php echo e($stats['unread_notifications']); ?></strong> notification(s) non lue(s).</span>
</div>
<?php endif; ?>


<div class="row">
    <?php $__currentLoopData = $childrenData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="<?php echo e($data['info']->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg')); ?>" 
                         class="rounded-circle mr-3" width="50" height="50">
                    <div>
                        <h5 class="mb-0"><?php echo e($data['info']->user->name); ?></h5>
                        <small class="text-muted">
                            <?php echo e($data['info']->my_class->full_name ?? $data['info']->my_class->name ?? 'N/A'); ?>

                            <?php if($data['info']->section): ?>
                                - <?php echo e($data['info']->section->name); ?>

                            <?php endif; ?>
                        </small>
                    </div>
                </div>
                <a href="<?php echo e(route('parent.progress.show', $data['info']->user_id)); ?>" class="btn btn-sm btn-primary">
                    <i class="icon-stats-growth"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2"><i class="icon-book mr-1"></i> Notes récentes</h6>
                        <?php if(count($data['grades']) > 0): ?>
                            <?php $__currentLoopData = $data['grades']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between mb-1">
                                    <small><?php echo e(Str::limit($grade['subject'], 15)); ?></small>
                                    <span class="badge badge-<?php echo e($grade['status']); ?>"><?php echo e($grade['grade']); ?>/20</span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <small class="text-muted">Aucune note</small>
                        <?php endif; ?>
                    </div>

                    
                    <div class="col-md-6">
                        
                        <h6 class="text-muted mb-2"><i class="icon-checkmark-circle mr-1"></i> Présence (ce mois)</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Taux de présence</small>
                                <strong class="text-<?php echo e($data['attendance']['rate'] >= 80 ? 'success' : ($data['attendance']['rate'] >= 60 ? 'warning' : 'danger')); ?>">
                                    <?php echo e($data['attendance']['rate']); ?>%
                                </strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: <?php echo e($data['attendance']['rate']); ?>%"></div>
                            </div>
                            <small class="text-muted">
                                <?php echo e($data['attendance']['present']); ?> présent(s), 
                                <?php echo e($data['attendance']['absent']); ?> absent(s),
                                <?php echo e($data['attendance']['late']); ?> retard(s)
                            </small>
                        </div>

                        
                        <h6 class="text-muted mb-2"><i class="icon-cash3 mr-1"></i> Finance</h6>
                        <?php if($data['finance']['is_up_to_date']): ?>
                            <span class="badge badge-success">✅ À jour</span>
                        <?php else: ?>
                            <span class="badge badge-danger">
                                Solde: <?php echo e(number_format($data['finance']['total_balance'], 0, ',', ' ')); ?> FC
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row">
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-calendar3 mr-2"></i> Événements à Venir</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo e($event->title); ?></strong>
                            <br><small class="text-muted"><?php echo e($event->event_date?->format('d/m/Y')); ?></small>
                        </div>
                        <span class="badge" style="background-color: <?php echo e($event->color ?? '#2196F3'); ?>; color: white;">
                            <?php echo e($event->event_type); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                        Aucun événement prévu
                    </div>
                <?php endif; ?>
            </div>
            <?php if($upcomingEvents->count() > 0): ?>
                <div class="card-footer">
                    <a href="<?php echo e(route('calendar.public')); ?>" class="btn btn-sm btn-info">
                        Voir le calendrier complet
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="card-title mb-0"><i class="icon-bell2 mr-2"></i> Notifications Récentes</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom <?php echo e(!$notif->is_read ? 'bg-light' : ''); ?>">
                        <div class="d-flex justify-content-between">
                            <strong><?php echo e($notif->title); ?></strong>
                            <small class="text-muted"><?php echo e($notif->created_at->diffForHumans()); ?></small>
                        </div>
                        <small class="text-muted"><?php echo e(Str::limit($notif->message, 80)); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-center text-muted">
                        <i class="icon-bell2 d-block mb-2" style="font-size: 24px;"></i>
                        Aucune notification
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-grid6 mr-2"></i> Actions Rapides</h6>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo e(route('parent.progress.index')); ?>" class="btn btn-lg btn-outline-primary w-100">
                    <i class="icon-stats-growth d-block mb-2" style="font-size: 24px;"></i>
                    Progression
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo e(route('parent.bulletins.index')); ?>" class="btn btn-lg btn-outline-success w-100">
                    <i class="icon-file-text2 d-block mb-2" style="font-size: 24px;"></i>
                    Bulletins
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo e(route('calendar.public')); ?>" class="btn btn-lg btn-outline-info w-100">
                    <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                    Calendrier
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="<?php echo e(route('my_children')); ?>" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="icon-users4 d-block mb-2" style="font-size: 24px;"></i>
                    Mes Enfants
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/parent/dashboard.blade.php ENDPATH**/ ?>