<?php $__env->startSection('page_title', '📧 Messagerie'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="row no-gutters">
            <!-- Sidebar gauche -->
            <div class="col-md-3 border-right bg-light">
                <div class="p-3">
                    <!-- Bouton Nouveau message -->
                    <a href="<?php echo e(route('student.messages.create')); ?>" class="btn btn-primary btn-block mb-3">
                        <i class="icon-pencil7 mr-2"></i> ✉️ Nouveau message
                    </a>

                    <!-- Menu de navigation -->
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('student.messages.index')); ?>" class="list-group-item list-group-item-action active">
                            <i class="icon-inbox mr-2"></i> 📥 Boîte de réception
                            <?php
                                $unreadCount = \App\Models\MessageRecipient::where('recipient_id', Auth::id())
                                    ->where('is_read', false)
                                    ->count();
                            ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="badge badge-primary badge-pill float-right"><?php echo e($unreadCount); ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="filterMessages('sent'); return false;">
                            <i class="icon-paperplane mr-2"></i> 📤 Envoyés
                            <?php
                                $sentCount = \App\Models\Message::where('sender_id', Auth::id())->count();
                            ?>
                            <span class="badge badge-secondary badge-pill float-right"><?php echo e($sentCount); ?></span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action disabled">
                            <i class="icon-star-full2 mr-2"></i> ⭐ Favoris
                            <small class="text-muted ml-2">(Bientôt)</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action disabled">
                            <i class="icon-trash mr-2"></i> 🗑️ Corbeille
                            <small class="text-muted ml-2">(Bientôt)</small>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Zone des messages -->
            <div class="col-md-9">
                <div class="p-3">
                    <!-- Alertes -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show border-0">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="icon-checkmark-circle mr-2"></i><?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show border-0">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="icon-cancel-circle2 mr-2"></i><?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <!-- En-tête -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">📬 Messages</h5>
                        <div>
                            <button class="btn btn-light btn-sm" onclick="location.reload()">
                                <i class="icon-reload-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Liste des messages -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="messagesTable">
                <thead>
                    <tr>
                        <th width="5%">Type</th>
                        <th>De / À</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isSent = $message->sender_id == auth()->id();
                        $isUnread = !$message->isReadBy(auth()->id());
                    ?>
                    <tr class="<?php echo e(!$isSent && $isUnread ? 'font-weight-bold bg-light' : ''); ?>">
                        <td class="text-center">
                            <?php if($isSent): ?>
                                <i class="icon-paperplane text-success" data-popup="tooltip" title="Envoyé"></i>
                            <?php else: ?>
                                <i class="icon-envelope text-primary" data-popup="tooltip" title="Reçu"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($isSent): ?>
                                <div class="text-muted small">À:</div>
                                <?php $__currentLoopData = $message->recipients->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($recipient->recipient->name); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($message->recipients->count() > 2): ?>
                                    <span class="text-muted">+<?php echo e($message->recipients->count() - 2); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e($message->sender->name); ?>

                                <?php if($message->sender->user_type == 'teacher'): ?>
                                    <span class="badge badge-info badge-sm ml-1">👨‍🏫</span>
                                <?php elseif(in_array($message->sender->user_type, ['admin', 'super_admin'])): ?>
                                    <span class="badge badge-success badge-sm ml-1">👔</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('student.messages.show', $message->id)); ?>" class="text-body">
                                <?php if(!$isSent && $isUnread): ?>
                                    <strong><?php echo e($message->subject); ?></strong>
                                <?php else: ?>
                                    <?php echo e($message->subject); ?>

                                <?php endif; ?>
                                <?php if($message->attachments->count() > 0): ?>
                                    <i class="icon-attachment text-muted ml-1"></i>
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <span class="text-muted"><?php echo e($message->created_at->format('d/m/Y')); ?></span>
                            <br>
                            <small class="text-muted"><?php echo e($message->created_at->format('H:i')); ?></small>
                        </td>
                        <td>
                            <?php if($isSent): ?>
                                <span class="badge badge-success">✓ Envoyé</span>
                            <?php else: ?>
                                <?php if($isUnread): ?>
                                    <span class="badge badge-primary">✉️ Nouveau</span>
                                <?php else: ?>
                                    <span class="badge badge-light">✓ Lu</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <a href="<?php echo e(route('student.messages.show', $message->id)); ?>" class="list-icons-item" data-popup="tooltip" title="Voir le message">
                                    <i class="icon-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="p-4">
                                <i class="icon-envelop text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucun message trouvé</p>
                                <a href="<?php echo e(route('student.messages.create')); ?>" class="btn btn-primary mt-2">
                                    <i class="icon-plus2"></i> Écrire un message
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

                            <?php if($conversations->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($conversations->links()); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {
    // Initialisation des tooltips
    $('[data-popup="tooltip"]').tooltip();
    
    // Fonction de filtrage des messages
    window.filterMessages = function(type) {
        var table = $('#messagesTable tbody tr');
        
        // Mettre à jour l'état actif du menu
        $('.list-group-item').removeClass('active');
        
        if (type === 'sent') {
            // Afficher uniquement les messages envoyés
            table.each(function() {
                var icon = $(this).find('td:first i');
                if (icon.hasClass('icon-paperplane')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('.list-group-item:contains("Envoyés")').addClass('active');
        } else if (type === 'received') {
            // Afficher uniquement les messages reçus
            table.each(function() {
                var icon = $(this).find('td:first i');
                if (icon.hasClass('icon-envelope')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            $('.list-group-item:contains("Boîte de réception")').addClass('active');
        } else {
            // Afficher tous les messages
            table.show();
            $('.list-group-item:first').addClass('active');
        }
    };
});
</script>

<style>
    .list-group-item {
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .list-group-item.active {
        border-left-color: #2196F3;
        background-color: #E3F2FD !important;
        color: #1976D2 !important;
    }
    
    .list-group-item:hover:not(.disabled) {
        background-color: #F5F5F5;
        cursor: pointer;
    }
    
    .table-hover tbody tr:hover {
        background-color: #F8F9FA;
        cursor: pointer;
    }
    
    .badge-pill {
        padding: 0.35em 0.65em;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/messages/index.blade.php ENDPATH**/ ?>