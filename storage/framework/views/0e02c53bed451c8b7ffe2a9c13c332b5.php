<?php $__env->startSection('page_title', 'Livres en Retard'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Livres en Retard</h6>
        <div class="header-elements">
            <span class="badge badge-danger badge-pill"><?php echo e($overdueRequests->total()); ?> en retard</span>
        </div>
    </div>

    <div class="card-body">
        <?php if($overdueRequests->isEmpty()): ?>
            <div class="alert alert-success">
                <i class="icon-checkmark-circle mr-2"></i>
                Aucun livre en retard actuellement !
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                <strong><?php echo e($overdueRequests->total()); ?></strong> livre(s) sont actuellement en retard.
            </div>
        <?php endif; ?>
    </div>

    <?php if($overdueRequests->isNotEmpty()): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour prévue</th>
                        <th>Jours de retard</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $overdueRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $daysOverdue = \Carbon\Carbon::parse($request->expected_return_date)->diffInDays(now());
                        ?>
                        <tr>
                            <td><?php echo e($request->id); ?></td>
                            <td>
                                <?php if($request->student && $request->student->user): ?>
                                    <div class="font-weight-semibold"><?php echo e($request->student->user->name); ?></div>
                                    <div class="text-muted">
                                        <small><?php echo e($request->student->user->email ?? ''); ?></small>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($request->book): ?>
                                    <div class="font-weight-semibold"><?php echo e($request->book->name); ?></div>
                                    <?php if($request->book->author): ?>
                                        <div class="text-muted">
                                            <small><?php echo e($request->book->author); ?></small>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?php echo e(\Carbon\Carbon::parse($request->request_date)->format('d/m/Y')); ?>

                                </span>
                            </td>
                            <td>
                                <span class="text-danger font-weight-semibold">
                                    <?php echo e(\Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y')); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($daysOverdue > 7): ?>
                                    <span class="badge badge-danger"><?php echo e($daysOverdue); ?> jours</span>
                                <?php elseif($daysOverdue > 3): ?>
                                    <span class="badge badge-warning"><?php echo e($daysOverdue); ?> jours</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($daysOverdue); ?> jours</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="list-icons">
                                    <a href="<?php echo e(route('librarian.book-requests.show', $request->id)); ?>" 
                                       class="list-icons-item text-primary" 
                                       data-toggle="tooltip" 
                                       title="Voir les détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                    
                                    <button type="button"
                                            class="list-icons-item text-success border-0 bg-transparent" 
                                            data-toggle="modal"
                                            data-target="#returnModal<?php echo e($request->id); ?>"
                                            title="Marquer comme retourné">
                                        <i class="icon-checkmark-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <?php echo e($overdueRequests->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Modals pour marquer comme retourné -->
<?php $__currentLoopData = $overdueRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="returnModal<?php echo e($request->id); ?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('librarian.book-requests.mark-returned', $request->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Marquer comme Retourné</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Livre :</strong> <?php echo e($request->book->name ?? 'N/A'); ?><br>
                        <strong>Étudiant :</strong> <?php echo e($request->student->user->name ?? 'N/A'); ?><br>
                        <strong>Retard :</strong> <?php echo e(\Carbon\Carbon::parse($request->expected_return_date)->diffInDays(now())); ?> jours
                    </div>
                    <div class="form-group">
                        <label>État du livre <span class="text-danger">*</span></label>
                        <select name="condition" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Bon</option>
                            <option value="fair">Moyen</option>
                            <option value="damaged">Endommagé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Remarques sur l'état du livre..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-undo2 mr-2"></i> Confirmer le Retour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/librarian/book_requests/overdue.blade.php ENDPATH**/ ?>