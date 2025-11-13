<?php $__env->startSection('page_title', 'Mes Demandes de Livres'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="icon-books"></i> Mes Demandes de Livres
                    </h3>
                    <a href="<?php echo e(route('student.library.index')); ?>" class="btn btn-primary">
                        <i class="icon-plus"></i> Nouvelle demande
                    </a>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="icon-checkmark-circle"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="icon-close-circle"></i> <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if($requests->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Livre</th>
                                        <th>Auteur</th>
                                        <th>Date de demande</th>
                                        <th>Date de retour prévue</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration + ($requests->currentPage() - 1) * $requests->perPage()); ?></td>
                                            <td>
                                                <strong><?php echo e($request->book->name ?? 'N/A'); ?></strong>
                                            </td>
                                            <td><?php echo e($request->book->author ?? 'N/A'); ?></td>
                                            <td>
                                                <i class="icon-calendar"></i>
                                                <?php echo e($request->created_at->format('d/m/Y')); ?>

                                                <br>
                                                <small class="text-muted"><?php echo e($request->created_at->diffForHumans()); ?></small>
                                            </td>
                                            <td>
                                                <?php if($request->expected_return_date): ?>
                                                    <i class="icon-calendar"></i>
                                                    <?php echo e(\Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y')); ?>

                                                    <br>
                                                    <?php if($request->status === 'borrowed' && \Carbon\Carbon::parse($request->expected_return_date)->isPast()): ?>
                                                        <small class="text-danger">
                                                            <i class="icon-warning"></i> En retard!
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted">
                                                            <?php echo e(\Carbon\Carbon::parse($request->expected_return_date)->diffForHumans()); ?>

                                                        </small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Non définie</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo e($request->badge_class); ?>">
                                                    <?php if($request->status === 'pending'): ?>
                                                        <i class="icon-clock"></i> En attente
                                                    <?php elseif($request->status === 'approved'): ?>
                                                        <i class="icon-checkmark"></i> Approuvé
                                                    <?php elseif($request->status === 'borrowed'): ?>
                                                        <i class="icon-book"></i> Emprunté
                                                    <?php elseif($request->status === 'returned'): ?>
                                                        <i class="icon-checkmark-circle"></i> Retourné
                                                    <?php else: ?>
                                                        <i class="icon-close"></i> Rejeté
                                                    <?php endif; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('student.library.requests.show', $request->id)); ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="Voir les détails">
                                                        <i class="icon-eye"></i>
                                                    </a>
                                                    
                                                    <?php if($request->canBeCancelled()): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger"
                                                                onclick="confirmCancel(<?php echo e($request->id); ?>)"
                                                                title="Annuler la demande">
                                                            <i class="icon-close"></i>
                                                        </button>
                                                        
                                                        <form id="cancel-form-<?php echo e($request->id); ?>" 
                                                              action="<?php echo e(route('student.library.requests.cancel', $request->id)); ?>" 
                                                              method="POST" 
                                                              style="display: none;">
                                                            <?php echo csrf_field(); ?>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <?php echo e($requests->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="icon-books text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">Aucune demande de livre</h4>
                            <p class="text-muted">Vous n'avez pas encore fait de demande d'emprunt de livre.</p>
                            <a href="<?php echo e(route('student.library.index')); ?>" class="btn btn-primary mt-3">
                                <i class="icon-plus"></i> Parcourir la bibliothèque
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCancel(requestId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette demande de livre?')) {
        document.getElementById('cancel-form-' + requestId).submit();
    }
}
</script>

<style>
.badge {
    padding: 0.5em 0.75em;
    font-size: 0.875rem;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/book_requests/index.blade.php ENDPATH**/ ?>