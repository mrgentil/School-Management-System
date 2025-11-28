
<?php $__env->startSection('page_title', 'Tableau de Bord Financier'); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?php echo e(number_format($stats['total_paid'], 0, ',', ' ')); ?> FC</h3>
                        <span>Total Encaissé</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-cash3 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?php echo e(number_format($stats['total_balance'], 0, ',', ' ')); ?> FC</h3>
                        <span>Solde Restant</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-warning icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?php echo e($stats['collection_rate']); ?>%</h3>
                        <span>Taux de Recouvrement</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-percent icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0"><?php echo e(number_format($stats['this_month'], 0, ',', ' ')); ?> FC</h3>
                        <span>Ce Mois 
                            <?php if($stats['monthly_growth'] > 0): ?>
                                <span class="badge badge-light text-success">+<?php echo e($stats['monthly_growth']); ?>%</span>
                            <?php elseif($stats['monthly_growth'] < 0): ?>
                                <span class="badge badge-light text-danger"><?php echo e($stats['monthly_growth']); ?>%</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-calendar icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-md-6">
        <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
            <span><i class="icon-checkmark-circle mr-2"></i> Élèves à jour</span>
            <strong><?php echo e($stats['students_fully_paid']); ?></strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0">
            <span><i class="icon-warning mr-2"></i> Élèves avec solde</span>
            <strong><?php echo e($stats['students_with_balance']); ?></strong>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Paiements par Mois</h6>
                <div>
                    <a href="<?php echo e(route('finance.export')); ?>" class="btn btn-sm btn-outline-success">
                        <i class="icon-file-excel mr-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="120"></canvas>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-pie-chart5 mr-2"></i> Répartition</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Par Classe</h6>
                <a href="<?php echo e(route('finance.by_class')); ?>" class="btn btn-sm btn-primary">Détails</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Classe</th>
                                <th class="text-right">Payé</th>
                                <th class="text-right">Solde</th>
                                <th class="text-center">Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $classData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($class['class']); ?></td>
                                    <td class="text-right text-success"><?php echo e(number_format($class['paid'], 0, ',', ' ')); ?></td>
                                    <td class="text-right text-danger"><?php echo e(number_format($class['balance'], 0, ',', ' ')); ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo e($class['rate'] >= 80 ? 'success' : ($class['rate'] >= 50 ? 'warning' : 'danger')); ?>">
                                            <?php echo e($class['rate']); ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-danger text-white d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Retards de Paiement</h6>
                <a href="<?php echo e(route('finance.export', ['type' => 'overdue'])); ?>" class="btn btn-sm btn-light">
                    <i class="icon-download mr-1"></i> Export
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Élève</th>
                                <th>Type</th>
                                <th class="text-right">Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $overdueStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item['student']->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($item['title']); ?></td>
                                    <td class="text-right text-danger font-weight-bold">
                                        <?php echo e(number_format($item['balance'], 0, ',', ' ')); ?> FC
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-success py-3">
                                        <i class="icon-checkmark-circle mr-2"></i> Aucun retard !
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-history mr-2"></i> Derniers Paiements</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Élève</th>
                    <th>Type</th>
                    <th class="text-right">Montant</th>
                    <th>Référence</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($record->created_at->format('d/m/Y H:i')); ?></td>
                        <td><?php echo e($record->student->name ?? 'N/A'); ?></td>
                        <td><?php echo e($record->payment->title ?? 'Frais'); ?></td>
                        <td class="text-right text-success font-weight-bold">
                            <?php echo e(number_format($record->amt_paid, 0, ',', ' ')); ?> FC
                        </td>
                        <td><code><?php echo e($record->ref_no ?? '-'); ?></code></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;
    const stats = <?php echo json_encode($stats, 15, 512) ?>;

    // Graphique mensuel
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Montant (FC)',
                data: monthlyData.map(d => d.amount),
                backgroundColor: '#4CAF50',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FC';
                        }
                    }
                }
            }
        }
    });

    // Graphique circulaire
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Payé', 'Restant'],
            datasets: [{
                data: [stats.total_paid, stats.total_balance],
                backgroundColor: ['#4CAF50', '#f44336'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/finance/dashboard.blade.php ENDPATH**/ ?>