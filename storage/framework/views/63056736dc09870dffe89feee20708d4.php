

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="row no-gutters">
            <!-- Sidebar gauche - Menu de navigation -->
            <div class="col-lg-3 border-right" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                <div class="p-3">
                    <!-- Bouton Nouveau message -->
                    <a href="<?php echo e(route($routePrefix . '.messages.create')); ?>" class="btn btn-primary btn-block mb-4 py-2 shadow-sm">
                        <i class="icon-pencil7 mr-2"></i> Nouveau message
                    </a>

                    <!-- Menu de navigation -->
                    <div class="nav flex-column">
                        <a href="<?php echo e(route($routePrefix . '.messages.index', ['filter' => 'all'])); ?>" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 <?php echo e($filter == 'all' ? 'active bg-primary text-white' : 'text-dark'); ?>">
                            <i class="icon-stack mr-3"></i>
                            <span>Tous les messages</span>
                            <span class="badge <?php echo e($filter == 'all' ? 'badge-light' : 'badge-secondary'); ?> ml-auto"><?php echo e($inboxCount + $sentCount); ?></span>
                        </a>
                        
                        <a href="<?php echo e(route($routePrefix . '.messages.index', ['filter' => 'inbox'])); ?>" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 <?php echo e($filter == 'inbox' ? 'active bg-primary text-white' : 'text-dark'); ?>">
                            <i class="icon-inbox mr-3"></i>
                            <span>Boîte de réception</span>
                            <?php if($unreadCount > 0): ?>
                                <span class="badge badge-danger ml-auto"><?php echo e($unreadCount); ?></span>
                            <?php else: ?>
                                <span class="badge <?php echo e($filter == 'inbox' ? 'badge-light' : 'badge-secondary'); ?> ml-auto"><?php echo e($inboxCount); ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <a href="<?php echo e(route($routePrefix . '.messages.index', ['filter' => 'sent'])); ?>" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 <?php echo e($filter == 'sent' ? 'active bg-primary text-white' : 'text-dark'); ?>">
                            <i class="icon-paperplane mr-3"></i>
                            <span>Messages envoyés</span>
                            <span class="badge <?php echo e($filter == 'sent' ? 'badge-light' : 'badge-secondary'); ?> ml-auto"><?php echo e($sentCount); ?></span>
                        </a>
                    </div>

                    <!-- Statistiques -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-muted mb-3"><i class="icon-stats-dots mr-2"></i>Statistiques</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Non lus</span>
                            <span class="font-weight-bold text-primary"><?php echo e($unreadCount); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Reçus</span>
                            <span class="font-weight-bold"><?php echo e($inboxCount); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Envoyés</span>
                            <span class="font-weight-bold"><?php echo e($sentCount); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone principale - Liste des messages -->
            <div class="col-lg-9">
                <div class="p-3">
                    <!-- Alertes -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-checkmark-circle mr-2"></i><?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-cancel-circle2 mr-2"></i><?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- En-tête avec titre et actions -->
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="mb-0">
                            <?php if($filter == 'inbox'): ?>
                                <i class="icon-inbox text-primary mr-2"></i>Boîte de réception
                            <?php elseif($filter == 'sent'): ?>
                                <i class="icon-paperplane text-success mr-2"></i>Messages envoyés
                            <?php else: ?>
                                <i class="icon-stack text-secondary mr-2"></i>Tous les messages
                            <?php endif; ?>
                        </h5>
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm" onclick="location.reload()" title="Actualiser">
                                <i class="icon-reload-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Liste des messages -->
                    <div class="messages-list">
                        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $isSent = $message->sender_id == auth()->id();
                                $isUnread = !$message->isReadBy(auth()->id());
                            ?>
                            <div class="message-item d-flex align-items-start p-3 border-bottom <?php echo e($isUnread && !$isSent ? 'bg-light' : ''); ?>" 
                                 style="cursor: pointer; transition: all 0.2s;"
                                 onclick="window.location='<?php echo e(route($routePrefix . '.messages.show', $message->id)); ?>'">
                                
                                <!-- Avatar -->
                                <div class="mr-3">
                                    <?php if($isSent): ?>
                                        <div class="avatar-circle bg-success text-white">
                                            <i class="icon-paperplane"></i>
                                        </div>
                                    <?php else: ?>
                                        <?php if($message->sender && $message->sender->photo): ?>
                                            <img src="<?php echo e($message->sender->photo); ?>" class="rounded-circle" width="45" height="45" alt="">
                                        <?php else: ?>
                                            <div class="avatar-circle bg-primary text-white">
                                                <?php echo e(strtoupper(substr($message->sender->name ?? 'U', 0, 1))); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Contenu du message -->
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="font-weight-bold text-truncate <?php echo e($isUnread && !$isSent ? 'text-dark' : 'text-secondary'); ?>">
                                            <?php if($isSent): ?>
                                                <span class="text-muted">À: </span>
                                                <?php $__currentLoopData = $message->recipients->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e($recipient->recipient->name ?? 'Inconnu'); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($message->recipients->count() > 2): ?>
                                                    <span class="text-muted">+<?php echo e($message->recipients->count() - 2); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php echo e($message->sender->name ?? 'Inconnu'); ?>

                                                <?php if(isset($message->sender->user_type)): ?>
                                                    <?php if($message->sender->user_type == 'teacher'): ?>
                                                        <span class="badge badge-info badge-sm ml-1">Enseignant</span>
                                                    <?php elseif(in_array($message->sender->user_type, ['admin', 'super_admin'])): ?>
                                                        <span class="badge badge-success badge-sm ml-1">Admin</span>
                                                    <?php elseif($message->sender->user_type == 'student'): ?>
                                                        <span class="badge badge-warning badge-sm ml-1">Étudiant</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted text-nowrap ml-2">
                                            <?php echo e($message->created_at->diffForHumans()); ?>

                                        </small>
                                    </div>
                                    
                                    <div class="mb-1 <?php echo e($isUnread && !$isSent ? 'font-weight-bold' : ''); ?>">
                                        <?php echo e($message->subject); ?>

                                        <?php if($message->attachments && $message->attachments->count() > 0): ?>
                                            <i class="icon-attachment text-muted ml-1" title="<?php echo e($message->attachments->count()); ?> pièce(s) jointe(s)"></i>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-muted small text-truncate">
                                        <?php echo e(Str::limit($message->content, 80)); ?>

                                    </div>
                                </div>

                                <!-- Indicateurs -->
                                <div class="ml-3 text-right">
                                    <?php if($isUnread && !$isSent): ?>
                                        <span class="badge badge-primary badge-pill">Nouveau</span>
                                    <?php elseif($isSent): ?>
                                        <span class="badge badge-light"><i class="icon-checkmark"></i></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="icon-envelop text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">Aucun message</h5>
                                <p class="text-muted">
                                    <?php if($filter == 'inbox'): ?>
                                        Votre boîte de réception est vide
                                    <?php elseif($filter == 'sent'): ?>
                                        Vous n'avez pas encore envoyé de messages
                                    <?php else: ?>
                                        Commencez une conversation !
                                    <?php endif; ?>
                                </p>
                                <a href="<?php echo e(route($routePrefix . '.messages.create')); ?>" class="btn btn-primary mt-2">
                                    <i class="icon-plus2 mr-2"></i>Écrire un message
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if($messages->hasPages()): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($messages->appends(['filter' => $filter])->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .message-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .nav-link {
        transition: all 0.2s;
    }
    
    .nav-link:hover:not(.active) {
        background-color: #e9ecef;
    }
    
    .min-width-0 {
        min-width: 0;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.2em 0.5em;
    }
</style>
<?php /**PATH D:\laragon\www\eschool\resources\views/partials/messages/index.blade.php ENDPATH**/ ?>