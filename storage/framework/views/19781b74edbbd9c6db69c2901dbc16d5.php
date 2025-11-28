

<div class="card border-0 shadow-sm">
    <!-- En-tête du message -->
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="<?php echo e(route($routePrefix . '.messages.index')); ?>" class="btn btn-light btn-sm mr-3">
                    <i class="icon-arrow-left8"></i>
                </a>
                <div>
                    <h5 class="mb-0"><?php echo e($message->subject); ?></h5>
                    <small class="text-muted">
                        <?php echo e($conversation->count()); ?> message(s) dans cette conversation
                    </small>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#reply-form">
                    <i class="icon-reply mr-1"></i> Répondre
                </button>
                <?php if($message->sender_id == auth()->id()): ?>
                    <form action="<?php echo e(route($routePrefix . '.messages.destroy', $message->id)); ?>" method="POST" class="d-inline ml-2" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="icon-trash"></i>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Alertes -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-checkmark-circle mr-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-cancel-circle2 mr-2"></i><?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Fil de discussion -->
        <div class="conversation-thread">
            <?php $__currentLoopData = $conversation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isOwn = $msg->sender_id == auth()->id();
                ?>
                <div class="message-bubble mb-4 <?php echo e($isOwn ? 'own-message' : ''); ?>">
                    <div class="d-flex <?php echo e($isOwn ? 'flex-row-reverse' : ''); ?>">
                        <!-- Avatar -->
                        <div class="<?php echo e($isOwn ? 'ml-3' : 'mr-3'); ?>">
                            <?php if($msg->sender && $msg->sender->photo): ?>
                                <img src="<?php echo e($msg->sender->photo); ?>" class="rounded-circle shadow-sm" width="45" height="45" alt="">
                            <?php else: ?>
                                <div class="avatar-circle <?php echo e($isOwn ? 'bg-primary' : 'bg-secondary'); ?> text-white shadow-sm">
                                    <?php echo e(strtoupper(substr($msg->sender->name ?? 'U', 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Contenu -->
                        <div class="flex-grow-1" style="max-width: 80%;">
                            <div class="d-flex align-items-center mb-2 <?php echo e($isOwn ? 'justify-content-end' : ''); ?>">
                                <span class="font-weight-bold <?php echo e($isOwn ? 'order-2 ml-2' : 'mr-2'); ?>">
                                    <?php echo e($msg->sender->name ?? 'Inconnu'); ?>

                                </span>
                                <?php if(isset($msg->sender->user_type) && !$isOwn): ?>
                                    <?php if($msg->sender->user_type == 'teacher'): ?>
                                        <span class="badge badge-info badge-sm <?php echo e($isOwn ? 'order-1' : ''); ?>">Enseignant</span>
                                    <?php elseif(in_array($msg->sender->user_type, ['admin', 'super_admin'])): ?>
                                        <span class="badge badge-success badge-sm <?php echo e($isOwn ? 'order-1' : ''); ?>">Admin</span>
                                    <?php elseif($msg->sender->user_type == 'student'): ?>
                                        <span class="badge badge-warning badge-sm <?php echo e($isOwn ? 'order-1' : ''); ?>">Étudiant</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <small class="text-muted <?php echo e($isOwn ? 'order-0 mr-2' : 'ml-auto'); ?>">
                                    <?php echo e($msg->created_at->format('d/m/Y à H:i')); ?>

                                </small>
                            </div>
                            
                            <div class="message-content p-3 rounded <?php echo e($isOwn ? 'bg-primary text-white' : 'bg-light'); ?>">
                                <?php echo nl2br(e($msg->content)); ?>

                            </div>
                            
                            <!-- Pièces jointes -->
                            <?php if($msg->attachments && $msg->attachments->count() > 0): ?>
                                <div class="attachments mt-2">
                                    <small class="text-muted d-block mb-1">
                                        <i class="icon-attachment mr-1"></i><?php echo e($msg->attachments->count()); ?> pièce(s) jointe(s)
                                    </small>
                                    <div class="d-flex flex-wrap">
                                        <?php $__currentLoopData = $msg->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(Storage::url($attachment->path)); ?>" 
                                               class="btn btn-sm btn-outline-secondary mr-2 mb-1" 
                                               target="_blank" download>
                                                <i class="icon-download mr-1"></i>
                                                <?php echo e(Str::limit($attachment->filename, 20)); ?>

                                                <span class="text-muted">(<?php echo e($attachment->formatted_size); ?>)</span>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Formulaire de réponse -->
        <div class="collapse mt-4" id="reply-form">
            <div class="card border">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="icon-reply mr-2"></i>Répondre à cette conversation
                    </h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route($routePrefix . '.messages.reply', $message->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label class="font-weight-semibold">Votre réponse</label>
                            <textarea name="content" rows="5" class="form-control" 
                                      placeholder="Écrivez votre réponse ici..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="icon-attachment mr-1"></i>Pièces jointes (optionnel)
                            </label>
                            <div class="custom-file">
                                <input type="file" name="attachments[]" class="custom-file-input" id="attachments" multiple>
                                <label class="custom-file-label" for="attachments">Choisir des fichiers...</label>
                            </div>
                            <small class="form-text text-muted">Max 10 Mo par fichier</small>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light mr-2" data-toggle="collapse" data-target="#reply-form">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-paperplane mr-2"></i>Envoyer la réponse
                            </button>
                        </div>
                    </form>
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
    
    .message-content {
        word-wrap: break-word;
    }
    
    .own-message .message-content {
        border-radius: 18px 18px 4px 18px;
    }
    
    .message-bubble:not(.own-message) .message-content {
        border-radius: 18px 18px 18px 4px;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.2em 0.5em;
    }
    
    .custom-file-label::after {
        content: "Parcourir";
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher le nom des fichiers sélectionnés
    document.getElementById('attachments')?.addEventListener('change', function() {
        var files = this.files;
        var label = this.nextElementSibling;
        if (files.length > 1) {
            label.textContent = files.length + ' fichiers sélectionnés';
        } else if (files.length === 1) {
            label.textContent = files[0].name;
        }
    });
});
</script>
<?php /**PATH D:\laragon\www\eschool\resources\views/partials/messages/show.blade.php ENDPATH**/ ?>