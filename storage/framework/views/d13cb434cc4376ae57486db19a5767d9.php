<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Paiements</h1>
        <div class="d-none d-sm-inline-block">
            <a href="<?php echo e(route('student.finance.dashboard')); ?>" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour au tableau de bord
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtrer les paiements</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('student.finance.payments')); ?>" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="year" class="mr-2">Année scolaire:</label>
                    <select name="year" id="year" class="form-control form-control-sm">
                        <option value="">Toutes les années</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e($selected_year == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="status" class="mr-2">Statut:</label>
                    <select name="status" id="status" class="form-control form-control-sm">
                        <option value="">Tous les statuts</option>
                        <option value="paid" <?php echo e($selected_status == 'paid' ? 'selected' : ''); ?>>Payé</option>
                        <option value="partial" <?php echo e($selected_status == 'partial' ? 'selected' : ''); ?>>Partiel</option>
                        <option value="unpaid" <?php echo e($selected_status == 'unpaid' ? 'selected' : ''); ?>>Impayé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mb-2">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <?php if(request()->has('year') || request()->has('status')): ?>
                    <a href="<?php echo e(route('student.finance.payments')); ?>" class="btn btn-secondary btn-sm mb-2 ml-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Paiements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Paiements</h6>
            <div>
                <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#printModal">
                    <i class="fas fa-print"></i> Imprimer
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Libellé</th>
                            <th>Année Scolaire</th>
                            <th>Montant</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($payment->created_at->format('d/m/Y')); ?></td>
                            <td><?php echo e($payment->ref_no); ?></td>
                            <td><?php echo e($payment->payment->title); ?></td>
                            <td><?php echo e($payment->year); ?></td>
                            <td class="text-right"><?php echo e(number_format($payment->payment->amount, 0, ',', ' ')); ?> FCFA</td>
                            <td class="text-right"><?php echo e(number_format($payment->amt_paid, 0, ',', ' ')); ?> FCFA</td>
                            <td class="text-right"><?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> FCFA</td>
                            <td>
                                <?php if($payment->balance == 0): ?>
                                    <span class="badge badge-success">Payé</span>
                                <?php elseif($payment->amt_paid > 0): ?>
                                    <span class="badge badge-warning">Partiel</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Impayé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#paymentDetailModal<?php echo e($payment->id); ?>">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($payment->balance > 0): ?>
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#makePaymentModal<?php echo e($payment->id); ?>">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Détails du Paiement Modal -->
                        <div class="modal fade" id="paymentDetailModal<?php echo e($payment->id); ?>" tabindex="-1" role="dialog" aria-labelledby="paymentDetailModalLabel<?php echo e($payment->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentDetailModalLabel<?php echo e($payment->id); ?>">Détails du Paiement</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Informations sur le Paiement</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th>Référence:</th>
                                                        <td><?php echo e($payment->ref_no); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Libellé:</th>
                                                        <td><?php echo e($payment->payment->title); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Description:</th>
                                                        <td><?php echo e($payment->payment->description ?? 'Aucune description'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Année Scolaire:</th>
                                                        <td><?php echo e($payment->year); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date d'échéance:</th>
                                                        <td><?php echo e($payment->payment->deadline ? $payment->payment->deadline->format('d/m/Y') : 'Non définie'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Détails Financiers</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th>Montant Total:</th>
                                                        <td class="text-right"><?php echo e(number_format($payment->payment->amount, 0, ',', ' ')); ?> FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Montant Payé:</th>
                                                        <td class="text-right"><?php echo e(number_format($payment->amt_paid, 0, ',', ' ')); ?> FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Reste à Payer:</th>
                                                        <td class="text-right"><strong><?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> FCFA</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Statut:</th>
                                                        <td>
                                                            <?php if($payment->balance == 0): ?>
                                                                <span class="badge badge-success">Payé</span>
                                                            <?php elseif($payment->amt_paid > 0): ?>
                                                                <span class="badge badge-warning">Partiel</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger">Impayé</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>

                                        <?php if($payment->receipt): ?>
                                        <hr>
                                        <h6>Reçu de Paiement</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date de Paiement</th>
                                                        <th>Montant</th>
                                                        <th>Méthode</th>
                                                        <th>Référence</th>
                                                        <th>Reçu</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?php echo e($payment->receipt->created_at->format('d/m/Y H:i')); ?></td>
                                                        <td><?php echo e(number_format($payment->receipt->amt_paid, 0, ',', ' ')); ?> FCFA</td>
                                                        <td><?php echo e(ucfirst($payment->receipt->payment_method)); ?></td>
                                                        <td><?php echo e($payment->receipt->transaction_ref ?? 'N/A'); ?></td>
                                                        <td>
                                                            <a href="<?php echo e(route('student.finance.receipt', $payment->receipt->id)); ?>" class="btn btn-sm btn-primary" target="_blank">
                                                                <i class="fas fa-file-invoice"></i> Voir le reçu
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        <?php if($payment->balance > 0): ?>
                                            <a href="#" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#makePaymentModal<?php echo e($payment->id); ?>">
                                                <i class="fas fa-money-bill-wave"></i> Effectuer un Paiement
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Effectuer un Paiement Modal -->
                        <div class="modal fade" id="makePaymentModal<?php echo e($payment->id); ?>" tabindex="-1" role="dialog" aria-labelledby="makePaymentModalLabel<?php echo e($payment->id); ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="<?php echo e(route('student.finance.pay', $payment->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="makePaymentModalLabel<?php echo e($payment->id); ?>">Effectuer un Paiement</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="amount<?php echo e($payment->id); ?>">Montant à Payer (FCFA)</label>
                                                <input type="number" class="form-control" id="amount<?php echo e($payment->id); ?>" name="amount" 
                                                       min="1" max="<?php echo e($payment->balance); ?>" value="<?php echo e($payment->balance); ?>" required>
                                                <small class="form-text text-muted">Solde restant: <?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> FCFA</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="payment_method<?php echo e($payment->id); ?>">Méthode de Paiement</label>
                                                <select class="form-control" id="payment_method<?php echo e($payment->id); ?>" name="payment_method" required>
                                                    <option value="">Sélectionnez une méthode</option>
                                                    <option value="cash">Espèces</option>
                                                    <option value="check">Chèque</option>
                                                    <option value="bank_transfer">Virement Bancaire</option>
                                                    <option value="mobile_money">Mobile Money</option>
                                                    <option value="card">Carte Bancaire</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="transaction_ref<?php echo e($payment->id); ?>">Référence de Transaction (Optionnel)</label>
                                                <input type="text" class="form-control" id="transaction_ref<?php echo e($payment->id); ?>" name="transaction_ref" placeholder="N° de transaction, référence, etc.">
                                            </div>
                                            <div class="form-group">
                                                <label for="notes<?php echo e($payment->id); ?>">Notes (Optionnel)</label>
                                                <textarea class="form-control" id="notes<?php echo e($payment->id); ?>" name="notes" rows="2" placeholder="Ajoutez des notes si nécessaire"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check"></i> Confirmer le Paiement
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3"></i>
                                    <p>Aucun paiement trouvé avec les critères sélectionnés.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($payments->hasPages()): ?>
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($payments->appends(request()->query())->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Options d'Impression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="printRange">Période</label>
                    <select class="form-control" id="printRange">
                        <option value="all">Tous les paiements</option>
                        <option value="current_year">Année en cours</option>
                        <option value="last_year">Année dernière</option>
                        <option value="custom">Période personnalisée</option>
                    </select>
                </div>
                <div class="form-group" id="customDateRange" style="display: none;">
                    <label>Période personnalisée</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="startDate" placeholder="Date de début">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="endDate" placeholder="Date de fin">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="printStatus">Statut</label>
                    <select class="form-control" id="printStatus">
                        <option value="all">Tous les statuts</option>
                        <option value="paid">Payé</option>
                        <option value="partial">Partiel</option>
                        <option value="unpaid">Impayé</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="printButton">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<!-- Custom styles for this page -->
<link href="<?php echo e(asset('vendor/datatables/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.35em 0.65em;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('js'); ?>
<!-- Page level plugins -->
<script src="<?php echo e(asset('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            },
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": [8] } // Disable sorting on actions column
            ]
        });

        // Show/hide custom date range
        $('#printRange').change(function() {
            if ($(this).val() === 'custom') {
                $('#customDateRange').show();
            } else {
                $('#customDateRange').hide();
            }
        });

        // Print button handler
        $('#printButton').click(function() {
            let range = $('#printRange').val();
            let status = $('#printStatus').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            
            let url = "<?php echo e(route('student.finance.payments.print')); ?>?print=1";
            
            if (range === 'custom' && startDate && endDate) {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            } else if (range !== 'all') {
                url += `&range=${range}`;
            }
            
            if (status !== 'all') {
                url += `&status=${status}`;
            }
            
            window.open(url, '_blank');
            $('#printModal').modal('hide');
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/finance/payments.blade.php ENDPATH**/ ?>