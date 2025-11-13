<?php $__env->startSection('page_title', 'Messagerie - Boîte de réception'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Boîte de réception</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i class="icon-envelope mr-2"></i> Boîte de réception
                        <span class="badge badge-primary badge-pill ml-auto">0</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="icon-paperplane mr-2"></i> Envoyés
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="icon-star-full2 mr-2"></i> Favoris
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="icon-trash mr-2"></i> Corbeille
                    </a>
                </div>

                <div class="mt-3">
                    <a href="#" class="btn btn-primary btn-block">
                        <i class="icon-pencil7 mr-2"></i> Nouveau message
                    </a>
                </div>
            </div>

            <!-- Message list -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title">Messages</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="reload"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="icon-envelop5 icon-2x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun message</h5>
                            <p class="text-muted">Votre boîte de réception est vide pour le moment.</p>
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
    $(function() {
        $('.select').select2();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/messages/inbox.blade.php ENDPATH**/ ?>