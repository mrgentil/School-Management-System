
<?php $__env->startSection('page_title', 'Ma Progression'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline bg-primary text-white">
                    <h6 class="card-title">
                        <i class="icon-graph mr-2"></i> Ma Progression Académique
                    </h6>
                    <?php echo Qs::getPanelOptions(); ?>

                </div>

                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <strong>Classe:</strong> <?php echo e($sr->my_class->full_name ?: $sr->my_class->name); ?> - <?php echo e($sr->section->name); ?> | 
                        <strong>Année:</strong> <?php echo e($current_year); ?>

                    </div>

                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success-400">
                                    <h6 class="card-title">
                                        <i class="icon-calendar mr-2"></i> Moyennes par Période
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php for($i = 1; $i <= 4; $i++): ?>
                                        <div class="col-md-3">
                                            <div class="card border-left-3 border-left-<?php echo e($period_averages[$i] >= 60 ? 'success' : ($period_averages[$i] >= 50 ? 'warning' : 'danger')); ?>">
                                                <div class="card-body text-center">
                                                    <h5 class="mb-0">Période <?php echo e($i); ?></h5>
                                                    <h2 class="font-weight-bold text-<?php echo e($period_averages[$i] >= 60 ? 'success' : ($period_averages[$i] >= 50 ? 'warning' : 'danger')); ?>">
                                                        <?php echo e($period_averages[$i] ?? 'N/A'); ?>

                                                    </h2>
                                                    <p class="text-muted mb-0">
                                                        <?php if($period_averages[$i]): ?>
                                                            / 20
                                                        <?php else: ?>
                                                            Pas de notes
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row mb-4">
                        <?php for($i = 1; $i <= 2; $i++): ?>
                        <div class="col-md-6">
                            <div class="card bg-<?php echo e($i == 1 ? 'primary' : 'info'); ?>-400 text-white">
                                <div class="card-body text-center">
                                    <h5 class="mb-2">Semestre <?php echo e($i); ?></h5>
                                    <h1 class="font-weight-bold"><?php echo e($semester_averages[$i] ?? 'N/A'); ?></h1>
                                    <p class="mb-0">Périodes <?php echo e($i == 1 ? '1 & 2' : '3 & 4'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    
                    <div class="card mb-4">
                        <div class="card-header bg-warning-400">
                            <h6 class="card-title">
                                <i class="icon-graph mr-2"></i> Évolution de mes Performances
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="progressChart" height="80"></canvas>
                        </div>
                    </div>

                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="card-title">Détails par Examen</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th>Examen</th>
                                        <th>Semestre</th>
                                        <th>Ma Moyenne</th>
                                        <th>Moyenne Classe</th>
                                        <th>Ma Position</th>
                                        <th>Performance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $progression_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><strong><?php echo e($data['exam']); ?></strong></td>
                                            <td>S<?php echo e($data['semester']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo e($data['average'] >= 60 ? 'success' : ($data['average'] >= 50 ? 'warning' : 'danger')); ?>">
                                                    <?php echo e($data['average']); ?>%
                                                </span>
                                            </td>
                                            <td><?php echo e($data['class_avg']); ?>%</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    <?php echo e($data['position']); ?><?php echo e($data['position'] == 1 ? 'er' : 'ème'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($data['average'] > $data['class_avg']): ?>
                                                    <i class="icon-arrow-up12 text-success"></i> Au-dessus
                                                <?php elseif($data['average'] < $data['class_avg']): ?>
                                                    <i class="icon-arrow-down12 text-danger"></i> En-dessous
                                                <?php else: ?>
                                                    <i class="icon-minus3 text-warning"></i> Dans la moyenne
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune donnée d'examen disponible</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-left-3 border-left-success">
                                <div class="card-header bg-success-400">
                                    <h6 class="card-title">
                                        <i class="icon-trophy mr-2"></i> Mes Meilleures Matières
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php $__empty_1 = true; $__currentLoopData = $best_subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong><?php echo e($subject['subject']->name); ?></strong></span>
                                                <span class="badge badge-success"><?php echo e($subject['average']); ?>%</span>
                                            </div>
                                            <div class="progress mt-1" style="height: 10px;">
                                                <div class="progress-bar bg-success" style="width: <?php echo e($subject['average']); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-muted">Aucune donnée disponible</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-left-3 border-left-danger">
                                <div class="card-header bg-danger-400">
                                    <h6 class="card-title">
                                        <i class="icon-alert mr-2"></i> Matières à Améliorer
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php $__empty_1 = true; $__currentLoopData = $worst_subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong><?php echo e($subject['subject']->name); ?></strong></span>
                                                <span class="badge badge-danger"><?php echo e($subject['average']); ?>%</span>
                                            </div>
                                            <div class="progress mt-1" style="height: 10px;">
                                                <div class="progress-bar bg-danger" style="width: <?php echo e($subject['average']); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-muted">Aucune donnée disponible</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <?php if(count($recommendations) > 0): ?>
                    <div class="card mt-4">
                        <div class="card-header bg-info-400">
                            <h6 class="card-title">
                                <i class="icon-light-bulb mr-2"></i> Recommandations
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $recommendations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="alert alert-<?php echo e($rec['type']); ?> border-0">
                                    <?php echo e($rec['message']); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php $__currentLoopData = $progression_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    '<?php echo e($data['exam']); ?>',
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            ],
            datasets: [{
                label: 'Ma Moyenne',
                data: [
                    <?php $__currentLoopData = $progression_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($data['average']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4
            },
            {
                label: 'Moyenne de Classe',
                data: [
                    <?php $__currentLoopData = $progression_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($data['class_avg']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/progress/index.blade.php ENDPATH**/ ?>