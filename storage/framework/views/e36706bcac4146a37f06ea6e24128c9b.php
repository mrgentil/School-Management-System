<?php $__env->startSection('page_title', 'Message'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h6 class="card-title"><?php echo e($message->subject); ?></h6>
        <div class="header-elements">
            <a href="<?php echo e(route('super_admin.messages.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Informations du message -->
        <div class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>De:</strong> <?php echo e($message->sender->name); ?>

                    </p>
                    <p class="mb-1">
                        <strong>Date:</strong> <?php echo e($message->created_at->format('d/m/Y à H:i')); ?>

                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Destinataires:</strong> 
                        <span class="badge badge-primary"><?php echo e($message->recipients->count()); ?> personne(s)</span>
                    </p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Contenu du message -->
        <div class="message-content p-3 bg-light rounded">
            <?php echo nl2br(e($message->content)); ?>

        </div>

        <hr>

        <!-- Liste des destinataires -->
        <div class="mt-4">
            <h6 class="font-weight-semibold mb-3">📋 Liste des destinataires (<?php echo e($message->recipients->count()); ?>)</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $message->recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($recipient->recipient->name); ?></td>
                            <td><?php echo e($recipient->recipient->email); ?></td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo e(ucwords(str_replace('_', ' ', $recipient->recipient->user_type))); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($recipient->is_read): ?>
                                    <span class="badge badge-success">✓ Lu</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Non lu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/super_admin/messages/show.blade.php ENDPATH**/ ?>