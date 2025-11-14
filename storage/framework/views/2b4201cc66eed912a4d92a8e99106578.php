<?php $__env->startSection('page_title', 'Mes Reçus'); ?>

<?php $__env->startSection('content'); ?>

<!-- En-tête -->
<div class="card mb-3">
    <div class="card-body bg-primary-400 text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1"><i class="icon-file-text2 mr-2"></i>Mes Reçus de Paiement</h4>
                <p class="mb-0 opacity-75">Consultez et imprimez tous vos reçus de paiement</p>
            </div>
            <div>
                <a href="<?php echo e(route('student.finance.payments')); ?>" class="btn btn-light">
                    <i class="icon-arrow-left13 mr-2"></i> Retour aux paiements
                </a>
            </div>
        </div>
    </div>
</div>

    <!-- Filtres -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0"><i class="icon-filter3 mr-2"></i>Filtrer les reçus</h6>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('student.finance.receipts')); ?>" method="GET" class="form-inline">
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
                    <label for="month" class="mr-2">Mois:</label>
                    <select name="month" id="month" class="form-control form-control-sm">
                        <option value="">Tous les mois</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($selected_month == $i ? 'selected' : ''); ?>>
                                <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mb-2">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <?php if(request()->has('year') || request()->has('month')): ?>
                    <a href="<?php echo e(route('student.finance.receipts')); ?>" class="btn btn-secondary btn-sm mb-2 ml-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Reçus -->
    <div class="card">
        <div class="card-header header-elements-inline bg-white">
            <h5 class="card-title"><i class="icon-list mr-2"></i>Historique des Reçus (<?php echo e($receipts->total()); ?>)</h5>
            <div class="header-elements">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#printModal">
                    <i class="icon-printer mr-1"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if($receipts->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th><i class="icon-calendar3 mr-1"></i>Date</th>
                                <th><i class="icon-barcode mr-1"></i>Référence</th>
                                <th><i class="icon-file-text mr-1"></i>Libellé</th>
                                <th class="text-right"><i class="icon-cash mr-1"></i>Montant</th>
                                <th><i class="icon-credit-card mr-1"></i>Solde Après</th>
                                <th class="text-center"><i class="icon-checkmark-circle mr-1"></i>Statut</th>
                                <th class="text-center"><i class="icon-cog3 mr-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="icon-calendar22 text-muted mr-2"></i>
                                        <div>
                                            <div class="font-weight-semibold"><?php echo e($receipt->created_at->format('d/m/Y')); ?></div>
                                            <small class="text-muted"><?php echo e($receipt->created_at->format('H:i')); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded"><?php echo e($receipt->ref_no); ?></code>
                                </td>
                                <td>
                                    <div class="font-weight-semibold text-dark"><?php echo e($receipt->paymentRecord->payment->title ?? 'N/A'); ?></div>
                                    <small class="text-muted">Année: <?php echo e($receipt->paymentRecord->year ?? 'N/A'); ?></small>
                                </td>
                                <td class="text-right">
                                    <div class="font-weight-bold text-success"><?php echo e(number_format($receipt->amt_paid, 0, ',', ' ')); ?> $</div>
                                    <small class="text-muted">Versé</small>
                                </td>
                                <td class="text-right">
                                    <div class="font-weight-bold text-<?php echo e($receipt->balance > 0 ? 'warning' : 'success'); ?>">
                                        <?php echo e(number_format($receipt->balance, 0, ',', ' ')); ?> $
                                    </div>
                                    <small class="text-muted"><?php echo e($receipt->balance > 0 ? 'Reste dû' : 'Soldé'); ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if($receipt->balance == 0): ?>
                                        <span class="badge badge-success badge-pill">
                                            <i class="icon-checkmark-circle mr-1"></i>Soldé
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-warning badge-pill">
                                            <i class="icon-hourglass mr-1"></i>Partiel
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('student.finance.receipt', $receipt->id)); ?>" 
                                           class="btn btn-sm btn-info" 
                                           target="_blank"
                                           title="Voir le reçu">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary print-receipt"
                                                data-id="<?php echo e($receipt->id); ?>"
                                                title="Imprimer">
                                            <i class="icon-printer"></i>
                                        </button>
                                        <a href="<?php echo e(route('student.finance.receipt.download', $receipt->id)); ?>" 
                                           class="btn btn-sm btn-success"
                                           title="Télécharger PDF">
                                            <i class="icon-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($receipts->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-gray-300 mb-4"></i>
                    <h5 class="text-gray-500">Aucun reçu trouvé</h5>
                    <p class="text-muted">Vous n'avez pas encore de reçus de paiement.</p>
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
                        <option value="all">Tous les reçus</option>
                        <option value="current_month">Ce mois-ci</option>
                        <option value="last_month">Le mois dernier</option>
                        <option value="current_year">Cette année</option>
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
                        <option value="approved">Approuvé</option>
                        <option value="pending">En attente</option>
                        <option value="rejected">Rejeté</option>
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

<!-- Receipt Detail Modal -->
<div class="modal fade" id="receiptDetailModal" tabindex="-1" role="dialog" aria-labelledby="receiptDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptDetailModalLabel">Détails du Reçu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="receiptDetailContent">
                <!-- Dynamic content will be loaded here -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="printReceiptBtn">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    /* Header Styles */
    .bg-primary-400 {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .opacity-75 {
        opacity: 0.75;
    }
    
    /* Table Styles */
    .table td, .table th {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transition: all 0.3s ease;
        transform: translateX(2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Badge Styles */
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.8em;
        font-weight: 600;
    }
    
    .badge-pill {
        border-radius: 10rem;
    }
    
    /* Button Group */
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    
    .btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 1;
    }
    
    /* Card Enhancements */
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.3s ease;
    }
    
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
    }
    
    /* Code Styles */
    code {
        font-size: 0.85em;
        font-weight: 600;
    }
    
    /* Icon Enhancements */
    .icon-calendar22 {
        font-size: 1.1em;
    }
    
    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
        }
        
        .btn-group .btn {
            border-radius: 0.25rem !important;
            margin-bottom: 0.25rem;
        }
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
        // Show/hide custom date range
        $('#printRange').change(function() {
            if ($(this).val() === 'custom') {
                $('#customDateRange').show();
            } else {
                $('#customDateRange').hide();
            }
        });

        // Print all receipts with filters
        $('#printButton').click(function() {
            let range = $('#printRange').val();
            let status = $('#printStatus').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            
            let url = "<?php echo e(route('student.finance.receipts.print')); ?>?print=1";
            
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

        // Print individual receipt
        $('.print-receipt').on('click', function() {
            let receiptId = $(this).data('id');
            let url = `<?php echo e(url('student/finance/receipt')); ?>/${receiptId}?print=1`;
            
            // Open in new window for printing
            let printWindow = window.open(url, '_blank');
            
            // Optionally trigger print when loaded
            if (printWindow) {
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.print();
                    }, 500);
                };
            }
        });

        // Success message on print
        <?php if(session('print_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '<?php echo e(session('print_success')); ?>',
                timer: 3000
            });
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/finance/receipts.blade.php ENDPATH**/ ?>