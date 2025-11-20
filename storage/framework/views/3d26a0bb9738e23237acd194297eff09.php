<div class="receipt-details">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="icon-file-text2 mr-2"></i>Reçu de Paiement #<?php echo e($receipt->id); ?></h5>
                <div>
                    <a href="<?php echo e(route('student.finance.receipt.download', $receipt->id)); ?>" class="btn btn-light btn-sm">
                        <i class="icon-download mr-1"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-success btn-sm">
                        <i class="icon-printer mr-1"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="icon-cash3 mr-2"></i>Informations sur le Paiement
                    </h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">ID Reçu:</th>
                            <td><strong>#<?php echo e($receipt->id); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td><?php echo e($receipt->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Libellé:</th>
                            <td><?php echo e($receipt->paymentRecord->payment->title ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Année:</th>
                            <td><span class="badge badge-secondary"><?php echo e($receipt->year); ?></span></td>
                        </tr>
                        <tr>
                            <th>Montant Versé:</th>
                            <td class="font-weight-bold text-success"><?php echo e(number_format($receipt->amt_paid, 0, ',', ' ')); ?> $</td>
                        </tr>
                        <tr>
                            <th>Solde Après:</th>
                            <td class="font-weight-bold text-<?php echo e($receipt->balance > 0 ? 'warning' : 'success'); ?>">
                                <?php echo e(number_format($receipt->balance, 0, ',', ' ')); ?> $
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="icon-user mr-2"></i>Informations sur l'Étudiant
                    </h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Nom Complet:</th>
                            <td><?php echo e(auth()->user()->name); ?></td>
                        </tr>
                        <tr>
                            <th width="40%">Matricule:</th>
                            <td><?php echo e(auth()->user()->username ?? auth()->user()->code ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <th>Classe:</th>
                            <td>
                                <?php
                                    $studentRecord = auth()->user()->student_record ?? null;
                                    $className = $studentRecord && $studentRecord->my_class ? ($studentRecord->my_class->full_name ?: $studentRecord->my_class->name) : 'N/A';
                                ?>
                                <span class="badge badge-info"><?php echo e($className); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Année Scolaire:</th>
                            <td><?php echo e($receipt->year); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success border-left-success">
                        <div class="d-flex align-items-center">
                            <i class="icon-checkmark-circle icon-2x mr-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Paiement Reçu</h6>
                                <p class="mb-0">Ce reçu certifie que le paiement de <strong><?php echo e(number_format($receipt->amt_paid, 0, ',', ' ')); ?> $</strong> a été reçu le <?php echo e($receipt->created_at->format('d/m/Y')); ?>.</p>
                                <?php if($receipt->balance > 0): ?>
                                    <p class="mb-0 mt-2 text-warning">
                                        <i class="icon-info22 mr-1"></i>Solde restant: <strong><?php echo e(number_format($receipt->balance, 0, ',', ' ')); ?> $</strong>
                                    </p>
                                <?php else: ?>
                                    <p class="mb-0 mt-2 text-success">
                                        <i class="icon-checkmark-circle mr-1"></i>Paiement intégralement soldé
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted text-center bg-light">
            <small>
                <i class="icon-calendar22 mr-1"></i>
                Reçu généré le <?php echo e(now()->format('d/m/Y à H:i')); ?> | <?php echo e(config('app.name', 'E-School')); ?>

            </small>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .navbar, .sidebar, .no-print {
        display: none !important;
    }
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
<?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/finance/partials/receipt_details.blade.php ENDPATH**/ ?>