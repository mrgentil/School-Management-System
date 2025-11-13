<?php $__env->startSection('page_title', 'Mes Paiements'); ?>

<?php $__env->startSection('content'); ?>

<?php
    // Calculer les statistiques
    $totalAmount = 0;
    $totalPaid = 0;
    $totalBalance = 0;
    $paidCount = 0;
    $partialCount = 0;
    $unpaidCount = 0;
    
    $allPayments = \App\Models\PaymentRecord::where('student_id', auth()->id())->get();
    
    foreach($allPayments as $p) {
        $totalAmount += $p->payment->amount ?? 0;
        $totalPaid += $p->amt_paid;
        $totalBalance += $p->balance;
        
        if($p->balance == 0) {
            $paidCount++;
        } elseif($p->amt_paid > 0) {
            $partialCount++;
        } else {
            $unpaidCount++;
        }
    }
?>

<!-- Résumé Financier -->
<div class="row mb-3">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card card-body bg-blue-400 text-white has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-cash3 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e(number_format($totalAmount, 0, ',', ' ')); ?> $</h3>
                    <span class="text-uppercase font-size-xs">Total à Payer</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card card-body bg-success-400 text-white has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark-circle icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e(number_format($totalPaid, 0, ',', ' ')); ?> $</h3>
                    <span class="text-uppercase font-size-xs">Payés</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card card-body bg-danger-400 text-white has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-warning icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e(number_format($totalBalance, 0, ',', ' ')); ?> $</h3>
                    <span class="text-uppercase font-size-xs">Restants</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card card-body bg-indigo-400 text-white has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-calculator icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($allPayments->count()); ?></h3>
                    <span class="text-uppercase font-size-xs">Total Paiements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <h4 class="text-success mb-0"><?php echo e($paidCount); ?></h4>
                        <small class="text-muted">Payés</small>
                    </div>
                    <div class="border-left"></div>
                    <div>
                        <h4 class="text-warning mb-0"><?php echo e($partialCount); ?></h4>
                        <small class="text-muted">Partiels</small>
                    </div>
                    <div class="border-left"></div>
                    <div>
                        <h4 class="text-danger mb-0"><?php echo e($unpaidCount); ?></h4>
                        <small class="text-muted">Impayés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-3">
    <div class="card-header bg-light">
        <h6 class="card-title"><i class="icon-filter3 mr-2"></i>Filtres</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('student.finance.payments')); ?>" class="form-inline">
            <div class="form-group mr-3 mb-2">
                <label for="year" class="mr-2">Année:</label>
                <select name="year" id="year" class="form-control">
                    <option value="">Toutes les années</option>
                    <?php $__currentLoopData = $years ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="form-group mr-3 mb-2">
                <label for="status" class="mr-2">Statut:</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Tous les statuts</option>
                    <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Payé</option>
                    <option value="partial" <?php echo e(request('status') == 'partial' ? 'selected' : ''); ?>>Partiel</option>
                    <option value="unpaid" <?php echo e(request('status') == 'unpaid' ? 'selected' : ''); ?>>Impayé</option>
                </select>
            </div>
            
            <div class="form-group mb-2">
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="icon-search4 mr-1"></i> Filtrer
                </button>
                <a href="<?php echo e(route('student.finance.payments')); ?>" class="btn btn-light">
                    <i class="icon-reset mr-1"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Liste des Paiements -->
<div class="card">
    <div class="card-header header-elements-inline bg-light">
        <h5 class="card-title font-weight-bold"><i class="icon-cash3 mr-2"></i>Mes Paiements - <?php echo e(auth()->user()->name); ?></h5>
        <div class="header-elements">
            <?php
                $totalPayments = \App\Models\PaymentRecord::where('student_id', auth()->id())->count();
                $unpaidCount = \App\Models\PaymentRecord::where('student_id', auth()->id())->where('balance', '>', 0)->count();
            ?>
            <span class="badge badge-primary badge-pill mr-2"><?php echo e($totalPayments); ?> paiement(s)</span>
            <?php if($unpaidCount > 0): ?>
                <span class="badge badge-warning badge-pill"><?php echo e($unpaidCount); ?> impayé(s)</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Référence</th>
                        <th>Libellé</th>
                        <th>Année</th>
                        <th class="text-right">Montant Total</th>
                        <th class="text-right">Payé</th>
                        <th class="text-right">Reste</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Détails</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $progressPercentage = $payment->payment->amount > 0 ? ($payment->amt_paid / $payment->payment->amount) * 100 : 0;
                            $statusColor = $payment->balance == 0 ? 'success' : ($payment->amt_paid > 0 ? 'warning' : 'danger');
                        ?>
                        <tr class="payment-row <?php echo e($payment->balance == 0 ? 'bg-light-success' : ''); ?>">
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="icon-calendar3 text-muted mr-2"></i>
                                    <span class="font-weight-semibold"><?php echo e($payment->created_at->format('d/m/Y')); ?></span>
                                </div>
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded"><?php echo e($payment->ref_no); ?></code>
                            </td>
                            <td>
                                <div>
                                    <div class="font-weight-semibold text-dark"><?php echo e($payment->payment->title); ?></div>
                                    <?php if($payment->payment->description): ?>
                                        <small class="text-muted"><?php echo e(Str::limit($payment->payment->description, 40)); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary badge-pill"><?php echo e($payment->year); ?></span>
                            </td>
                            <td class="text-right">
                                <div class="font-weight-bold text-primary"><?php echo e(number_format($payment->payment->amount, 0, ',', ' ')); ?> $</div>
                                <small class="text-muted">USD</small>
                            </td>
                            <td class="text-right">
                                <div class="font-weight-bold text-success"><?php echo e(number_format($payment->amt_paid, 0, ',', ' ')); ?> $</div>
                                <small class="text-muted">USD</small>
                            </td>
                            <td class="text-right">
                                <div class="font-weight-bold text-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>">
                                    <?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> $
                                </div>
                                <small class="text-muted">USD</small>
                            </td>
                            <td class="text-center" style="min-width: 150px;">
                                <div class="mb-1">
                                    <?php if($payment->balance == 0): ?>
                                        <span class="badge badge-success badge-pill px-3 py-1">
                                            <i class="icon-checkmark-circle mr-1"></i>Payé
                                        </span>
                                    <?php elseif($payment->amt_paid > 0): ?>
                                        <span class="badge badge-warning badge-pill px-3 py-1">
                                            <i class="icon-hourglass mr-1"></i>Partiel
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger badge-pill px-3 py-1">
                                            <i class="icon-cross-circle mr-1"></i>Impayé
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <!-- Mini barre de progression -->
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-<?php echo e($statusColor); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo e($progressPercentage); ?>%;" 
                                         aria-valuenow="<?php echo e($progressPercentage); ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100"
                                         title="<?php echo e(number_format($progressPercentage, 1)); ?>% payé">
                                    </div>
                                </div>
                                <small class="text-muted"><?php echo e(number_format($progressPercentage, 0)); ?>%</small>
                            </td>
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm btn-primary shadow-sm" 
                                        data-toggle="modal" 
                                        data-target="#paymentDetailModal<?php echo e($payment->id); ?>"
                                        title="Voir les détails">
                                    <i class="icon-eye mr-1"></i> Détails
                                </button>
                            </td>
                        </tr>

                        <!-- Détails du Paiement Modal -->
                        <div class="modal fade" id="paymentDetailModal<?php echo e($payment->id); ?>" tabindex="-1" role="dialog" aria-labelledby="paymentDetailModalLabel<?php echo e($payment->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="paymentDetailModalLabel<?php echo e($payment->id); ?>">
                                            <i class="icon-file-text2 mr-2"></i>Détails du Paiement - <?php echo e($payment->payment->title); ?>

                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Résumé du Paiement -->
                                        <div class="alert alert-<?php echo e($payment->balance == 0 ? 'success' : ($payment->amt_paid > 0 ? 'warning' : 'danger')); ?> border-0">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="icon-<?php echo e($payment->balance == 0 ? 'checkmark-circle' : ($payment->amt_paid > 0 ? 'warning' : 'cross-circle')); ?> icon-3x"></i>
                                                </div>
                                                <div class="flex-fill">
                                                    <h5 class="mb-1">
                                                        Statut: 
                                                        <?php if($payment->balance == 0): ?>
                                                            <span class="badge badge-success badge-pill">Payé Intégralement</span>
                                                        <?php elseif($payment->amt_paid > 0): ?>
                                                            <span class="badge badge-warning badge-pill">Paiement Partiel</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger badge-pill">Impayé</span>
                                                        <?php endif; ?>
                                                    </h5>
                                                    <p class="mb-0">Référence: <strong><?php echo e($payment->ref_no); ?></strong></p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Détails Financiers (Visuel) -->
                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <div class="card card-body bg-blue-100 border-blue-300">
                                                    <h6 class="text-blue-800 mb-2">
                                                        <i class="icon-cash3 mr-1"></i>Montant Total
                                                    </h6>
                                                    <h3 class="mb-0 text-blue-900"><?php echo e(number_format($payment->payment->amount, 0, ',', ' ')); ?> $</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card card-body bg-success-100 border-success-300">
                                                    <h6 class="text-success-800 mb-2">
                                                        <i class="icon-checkmark-circle mr-1"></i>Montant Payé
                                                    </h6>
                                                    <h3 class="mb-0 text-success-900"><?php echo e(number_format($payment->amt_paid, 0, ',', ' ')); ?> $</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card card-body bg-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>-100 border-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>-300">
                                                    <h6 class="text-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>-800 mb-2">
                                                        <i class="icon-calculator mr-1"></i>Reste à Payer
                                                    </h6>
                                                    <h3 class="mb-0 text-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>-900"><?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> $</h3>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Informations Détaillées -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="card-title mb-0"><i class="icon-info3 mr-2"></i>Informations du Paiement</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <table class="table table-borderless table-sm mb-0">
                                                            <tr>
                                                                <td class="font-weight-semibold" width="40%">Référence:</td>
                                                                <td><?php echo e($payment->ref_no); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-semibold">Libellé:</td>
                                                                <td><?php echo e($payment->payment->title); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-semibold">Description:</td>
                                                                <td><?php echo e($payment->payment->description ?? 'Aucune description'); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-semibold">Année Scolaire:</td>
                                                                <td><span class="badge badge-secondary"><?php echo e($payment->year); ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-semibold">Date de création:</td>
                                                                <td><?php echo e($payment->created_at->format('d/m/Y')); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-semibold">Date d'échéance:</td>
                                                                <td>
                                                                    <?php if($payment->payment->deadline): ?>
                                                                        <span class="badge badge-<?php echo e(\Carbon\Carbon::parse($payment->payment->deadline)->isPast() && $payment->balance > 0 ? 'danger' : 'info'); ?>">
                                                                            <?php echo e(\Carbon\Carbon::parse($payment->payment->deadline)->format('d/m/Y')); ?>

                                                                        </span>
                                                                        <?php if(\Carbon\Carbon::parse($payment->payment->deadline)->isPast() && $payment->balance > 0): ?>
                                                                            <small class="text-danger d-block">Échéance dépassée</small>
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <span class="text-muted">Non définie</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <h6 class="card-title mb-0"><i class="icon-chart-bars mr-2"></i>Progression du Paiement</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <?php
                                                            $percentage = $payment->payment->amount > 0 ? ($payment->amt_paid / $payment->payment->amount) * 100 : 0;
                                                        ?>
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <span class="font-weight-semibold">Progression:</span>
                                                                <span class="font-weight-semibold text-<?php echo e($percentage == 100 ? 'success' : 'primary'); ?>"><?php echo e(number_format($percentage, 1)); ?>%</span>
                                                            </div>
                                                            <div class="progress" style="height: 25px;">
                                                                <div class="progress-bar progress-bar-striped bg-<?php echo e($percentage == 100 ? 'success' : ($percentage > 0 ? 'warning' : 'danger')); ?>" 
                                                                     role="progressbar" 
                                                                     style="width: <?php echo e($percentage); ?>%;" 
                                                                     aria-valuenow="<?php echo e($percentage); ?>" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                    <?php echo e(number_format($percentage, 1)); ?>%
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="alert alert-light border mb-0">
                                                            <div class="d-flex justify-content-between">
                                                                <span>Montant payé:</span>
                                                                <strong class="text-success"><?php echo e(number_format($payment->amt_paid, 0, ',', ' ')); ?> $</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span>Reste à payer:</span>
                                                                <strong class="text-<?php echo e($payment->balance > 0 ? 'danger' : 'success'); ?>"><?php echo e(number_format($payment->balance, 0, ',', ' ')); ?> $</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if($payment->receipt && $payment->receipt->count() > 0): ?>
                                        <hr class="mt-4">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="card-title mb-0">
                                                    <i class="icon-history mr-2"></i>Historique des Versements 
                                                    <span class="badge badge-primary badge-pill ml-2"><?php echo e($payment->receipt->count()); ?></span>
                                                </h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-hover mb-0">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th><i class="icon-calendar mr-1"></i>Date</th>
                                                                <th><i class="icon-cash mr-1"></i>Montant Versé</th>
                                                                <th><i class="icon-calculator mr-1"></i>Solde Après</th>
                                                                <th class="text-center"><i class="icon-printer mr-1"></i>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__currentLoopData = $payment->receipt->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td>
                                                                    <i class="icon-calendar3 text-muted mr-1"></i>
                                                                    <?php echo e($receipt->created_at->format('d/m/Y H:i')); ?>

                                                                </td>
                                                                <td>
                                                                    <strong class="text-success"><?php echo e(number_format($receipt->amt_paid, 0, ',', ' ')); ?> $</strong>
                                                                </td>
                                                                <td>
                                                                    <span class="text-<?php echo e($receipt->balance > 0 ? 'warning' : 'success'); ?>">
                                                                        <?php echo e(number_format($receipt->balance, 0, ',', ' ')); ?> $
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="<?php echo e(route('student.finance.receipt', $receipt->id)); ?>" 
                                                                       class="btn btn-sm btn-primary" 
                                                                       target="_blank"
                                                                       title="Voir le reçu">
                                                                        <i class="icon-file-text2 mr-1"></i> Reçu
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <?php else: ?>
                                        <hr class="mt-4">
                                        <div class="alert alert-warning">
                                            <div class="d-flex align-items-center">
                                                <i class="icon-info22 icon-2x mr-3"></i>
                                                <div>
                                                    <h6 class="mb-1">Aucun versement effectué</h6>
                                                    <p class="mb-0">Aucun paiement n'a encore été enregistré pour ce frais.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-light" data-dismiss="modal">
                                            <i class="icon-cross2 mr-1"></i> Fermer
                                        </button>
                                        <?php if($payment->balance > 0): ?>
                                            <button type="button" class="btn btn-info" disabled>
                                                <i class="icon-info22 mr-1"></i> Paiement à la caisse uniquement
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="icon-search4 icon-3x opacity-50 mb-3 d-block"></i>
                                    <h5 class="mb-2">Aucun paiement trouvé</h5>
                                    <p class="mb-0">Aucun paiement ne correspond aux critères de recherche sélectionnés.</p>
                                    <?php if(request()->has('year') || request()->has('status')): ?>
                                        <a href="<?php echo e(route('student.finance.payments')); ?>" class="btn btn-light btn-sm mt-3">
                                            <i class="icon-reset mr-1"></i> Réinitialiser les filtres
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="4" class="text-right font-weight-bold">
                                <i class="icon-calculator mr-2"></i>TOTAUX DE LA PAGE:
                            </td>
                            <td class="text-right">
                                <strong class="text-primary">
                                    <?php echo e(number_format($payments->sum(function($p) { return $p->payment->amount; }), 0, ',', ' ')); ?> $
                                </strong>
                            </td>
                            <td class="text-right">
                                <strong class="text-success">
                                    <?php echo e(number_format($payments->sum('amt_paid'), 0, ',', ' ')); ?> $
                                </strong>
                            </td>
                            <td class="text-right">
                                <strong class="text-danger">
                                    <?php echo e(number_format($payments->sum('balance'), 0, ',', ' ')); ?> $
                                </strong>
                            </td>
                            <td colspan="2" class="text-center">
                                <small class="text-muted">
                                    <?php echo e($payments->count()); ?> ligne(s) affichée(s)
                                </small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Résumé Visuel -->
            <div class="row mt-4 mb-3">
                <div class="col-md-12">
                    <div class="alert alert-info border-left-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-2">
                                    <i class="icon-info3 mr-2"></i>Informations Importantes
                                </h6>
                                <ul class="mb-0 pl-3">
                                    <li>Pour effectuer un paiement, veuillez vous rendre à la caisse de l'établissement</li>
                                    <li>Conservez vos reçus de paiement pour toute réclamation ultérieure</li>
                                    <?php if($unpaidCount > 0): ?>
                                        <li class="text-danger font-weight-bold">
                                            <i class="icon-warning mr-1"></i>
                                            Vous avez <?php echo e($unpaidCount); ?> paiement(s) en attente pour un total de <?php echo e(number_format($totalBalance, 0, ',', ' ')); ?> $
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <?php if($payments->hasPages()): ?>
                            <div class="ml-3">
                                <a href="<?php echo e(route('student.finance.receipts')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="icon-file-text2 mr-1"></i> Voir tous les reçus
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if($payments->hasPages()): ?>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de <?php echo e($payments->firstItem()); ?> à <?php echo e($payments->lastItem()); ?> sur <?php echo e($payments->total()); ?> paiement(s)
                </div>
                <div>
                    <?php echo e($payments->appends(request()->query())->links()); ?>

                </div>
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
<style>
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
    
    .payment-row {
        transition: all 0.3s ease;
    }
    
    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.05) !important;
    }
    
    .table tbody tr td:first-child {
        border-left: 3px solid transparent;
    }
    
    .table tbody tr:hover td:first-child {
        border-left-color: #007bff;
    }
    
    .table code {
        font-size: 0.85em;
        font-weight: 600;
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
    
    /* Card Styles */
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card.has-bg-image {
        background-size: cover;
        background-position: center;
    }
    
    .card-header.bg-light {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
    }
    
    /* Icon Styles */
    .icon-3x {
        font-size: 3rem;
    }
    
    .icon-2x {
        font-size: 2rem;
    }
    
    /* Statistics Cards */
    .bg-blue-100 { background-color: #cfe2ff !important; }
    .bg-blue-400 { background-color: #0d6efd !important; }
    .border-blue-300 { border-color: #9ec5fe !important; }
    .text-blue-800 { color: #084298 !important; }
    .text-blue-900 { color: #052c65 !important; }
    
    .bg-success-100 { background-color: #d1e7dd !important; }
    .bg-success-400 { background-color: #28a745 !important; }
    .border-success-300 { border-color: #75b798 !important; }
    .text-success-800 { color: #0a3622 !important; }
    .text-success-900 { color: #052c16 !important; }
    
    .bg-danger-100 { background-color: #f8d7da !important; }
    .bg-danger-400 { background-color: #dc3545 !important; }
    .border-danger-300 { border-color: #ea868f !important; }
    .text-danger-800 { color: #58151c !important; }
    .text-danger-900 { color: #2c0b0e !important; }
    
    .bg-indigo-400 { background-color: #6610f2 !important; }
    
    /* Progress Bar */
    .progress {
        border-radius: 10px;
        overflow: hidden;
        background-color: #e9ecef;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .progress-bar {
        font-weight: 600;
        font-size: 0.875rem;
        transition: width 0.6s ease;
    }
    
    /* Mini progress bars in table */
    .table .progress {
        height: 6px;
        margin: 5px 0;
        border-radius: 3px;
    }
    
    /* Icon Enhancements */
    .icon-calendar3 {
        font-size: 1.1em;
    }
    
    /* Button Enhancements */
    .btn-sm.shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    
    .btn-sm.shadow-sm:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
        transform: translateY(-1px);
    }
    
    /* Amount Display */
    .table td .font-weight-bold {
        font-size: 1em;
        line-height: 1.2;
    }
    
    .table td small.text-muted {
        font-size: 0.75rem;
        display: block;
        margin-top: 2px;
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    
    .modal-footer {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }
    
    /* Alert Styles */
    .alert {
        border-radius: 0.5rem;
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #28a745;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
    }
    
    .alert-danger {
        border-left-color: #dc3545;
    }
    
    .alert-info {
        border-left-color: #17a2b8;
    }
    
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    
    /* Table Footer */
    .table tfoot td {
        font-weight: 600;
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
        padding: 1rem 0.75rem;
    }
    
    .table tfoot strong {
        font-size: 1.1em;
    }
    
    /* Button Styles */
    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        h3 {
            font-size: 1.5rem;
        }
    }
    
    /* Prevent overflow */
    body, html {
        overflow-x: hidden;
    }
    
    .content-wrapper {
        overflow-x: hidden;
    }
    
    /* Animation for cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card {
        animation: fadeInUp 0.5s ease;
    }
    
    /* Opacity utilities */
    .opacity-50 {
        opacity: 0.5;
    }
    
    .opacity-75 {
        opacity: 0.75;
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