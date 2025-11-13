<?php $__env->startSection('page_title', 'Reçu de Paiement #' . $receipt->id); ?>

<?php $__env->startSection('content'); ?>

<?php if(isset($autoPrint) && $autoPrint): ?>
<script>
    // Déclencher l'impression automatiquement
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>
<?php endif; ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reçu de Paiement</h1>
        <div class="d-none d-sm-inline-block">
            <a href="<?php echo e(route('student.finance.receipts')); ?>" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
            </a>
            <a href="<?php echo e(route('student.finance.receipt.download', $receipt->id)); ?>" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Télécharger
            </a>
            <a href="<?php echo e(route('student.finance.receipt.print', $receipt->id)); ?>" class="btn btn-sm btn-success shadow-sm" target="_blank">
                <i class="fas fa-print fa-sm text-white-50"></i> Imprimer
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt"></i> Reçu #<?php echo e($receipt->ref_no); ?>

                    </h6>
                    <span class="badge badge-<?php echo e($receipt->status == 'approved' ? 'success' : ($receipt->status == 'pending' ? 'warning' : 'danger')); ?>">
                        <?php echo e(ucfirst($receipt->status)); ?>

                    </span>
                </div>
                <div class="card-body">
                    <?php echo $__env->make('pages.student.finance.partials.receipt_details', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x mr-3"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Besoin d'aide ?</h6>
                                        <p class="mb-0">Si vous avez des questions concernant ce reçu, veuillez contacter le service financier de l'établissement.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>Reçu généré le <?php echo e($receipt->created_at->format('d/m/Y à H:i')); ?> par <?php echo e(config('app.name')); ?></small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    .receipt-details .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .receipt-details .table th {
        background-color: #f8f9fc;
    }
    .receipt-details .section-title {
        color: #4e73df;
        font-size: 1rem;
        border-left: 4px solid #4e73df;
        padding-left: 10px;
        margin: 20px 0 15px;
    }
    .receipt-details .alert {
        border-left: 4px solid #4e73df;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/finance/receipt_show.blade.php ENDPATH**/ ?>