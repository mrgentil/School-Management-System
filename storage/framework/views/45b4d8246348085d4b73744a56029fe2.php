<?php $__env->startSection('page_title', 'Message'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><?php echo e($message->subject); ?></h5>
        <div class="header-elements">
            <a href="<?php echo e(route('student.messages.index')); ?>" class="btn btn-light">
                <i class="icon-arrow-left8"></i> Retour aux messages
            </a>
            <a href="#reply-form" class="btn btn-primary ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="reply-form">
                <i class="icon-reply"></i> Répondre
            </a>
        </div>
    </div>

    <div class="card-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Fil de discussion -->
        <div class="timeline">
            <?php $__currentLoopData = $conversation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="timeline-item">
                <div class="timeline-item-date"><?php echo e($msg->created_at->format('d/m/Y H:i')); ?></div>
                
                <div class="timeline-item-content">
                    <div class="d-flex align-items-center mb-2">
                        <div class="mr-3">
                            <img src="<?php echo e($msg->sender->photo); ?>" width="40" height="40" class="rounded-circle" alt="">
                        </div>
                        <div>
                            <h6 class="mb-0">
                                <?php echo e($msg->sender->name); ?>

                                <?php if($msg->sender->user_type == 'teacher'): ?>
                                    <span class="badge badge-info ml-1">Professeur</span>
                                <?php elseif(in_array($msg->sender->user_type, ['admin', 'super_admin'])): ?>
                                    <span class="badge badge-success ml-1">Administration</span>
                                <?php endif; ?>
                            </h6>
                            <span class="text-muted"><?php echo e($msg->created_at->diffForHumans()); ?></span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <?php echo nl2br(e($msg->content)); ?>

                    </div>
                    
                    <?php if($msg->attachments->count() > 0): ?>
                    <div class="mb-3">
                        <h6>Pièces jointes :</h6>
                        <div class="list-group">
                            <?php $__currentLoopData = $msg->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <i class="icon-file-<?php echo e($attachment->getFileTypeIcon()); ?> mr-2"></i>
                                    <div class="mr-auto"><?php echo e($attachment->filename); ?></div>
                                    <a href="<?php echo e(route('student.messages.download', $attachment->id)); ?>" class="btn btn-sm btn-light ml-2">
                                        <i class="icon-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <!-- /fil de discussion -->

        <!-- Formulaire de réponse -->
        <div class="collapse mt-4" id="reply-form">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Répondre</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('student.messages.reply', $message->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="content" rows="5" class="form-control" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="attachments">Pièces jointes (optionnel)</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            <span class="form-text text-muted">Vous pouvez sélectionner plusieurs fichiers (max 10 Mo par fichier)</span>
                        </div>
                        
                        <div class="text-right">
                            <button type="reset" class="btn btn-light" data-toggle="collapse" data-target="#reply-form">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-paperplane mr-2"></i> Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /formulaire de réponse -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Faire défiler jusqu'au dernier message
    $(document).ready(function() {
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 'slow');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/messages/show.blade.php ENDPATH**/ ?>