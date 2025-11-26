
<?php $__env->startSection('page_title', 'Progression de l\'étudiant'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-light">
        <h5 class="card-title">
            <i class="icon-stats-growth mr-2"></i> Progression de l'étudiant
        </h5>
        <div class="header-elements">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <?php if($student && $sr): ?>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="media">
                        <div class="mr-3">
                            <img src="<?php echo e($student->photo ?: asset('global_assets/images/user.png')); ?>" 
                                 width="80" height="80" class="rounded-circle" alt="<?php echo e($student->name); ?>">
                        </div>
                        <div class="media-body">
                            <h4 class="mb-1"><?php echo e($student->name); ?></h4>
                            <p class="mb-1">
                                <span class="badge badge-primary"><?php echo e($sr->my_class->name ?? 'N/A'); ?></span>
                                <span class="badge badge-secondary"><?php echo e($sr->section->name ?? ''); ?></span>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="icon-calendar3 mr-1"></i> Session: <?php echo e($sr->session); ?>

                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="d-inline-block text-left">
                        <p class="mb-1"><strong>N° Matricule:</strong> <?php echo e($sr->adm_no); ?></p>
                        <p class="mb-0"><strong>Année d'admission:</strong> <?php echo e($sr->year_admitted); ?></p>
                    </div>
                </div>
            </div>

            <hr>

            
            <?php if(count($progress_data) > 0): ?>
                <div class="row">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">
                            <i class="icon-chart mr-2"></i> Évolution des résultats
                        </h6>
                        <div style="height: 300px;">
                            <canvas id="progressChart"></canvas>
                        </div>
                    </div>
                </div>

                <hr>

                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">
                            <i class="icon-list mr-2"></i> Détail des examens
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Année</th>
                                        <th>Examen</th>
                                        <th>Total</th>
                                        <th>Moyenne</th>
                                        <th>Position</th>
                                        <th>Appréciation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $progress_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($data['year']); ?></td>
                                            <td><?php echo e($data['exam_name']); ?></td>
                                            <td><?php echo e($data['total']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo e($data['average'] >= 50 ? 'success' : 'danger'); ?>">
                                                    <?php echo e(number_format($data['average'], 2)); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <?php if($data['position'] <= 3): ?>
                                                    <span class="badge badge-warning"><?php echo e($data['position']); ?><?php echo e($data['position'] == 1 ? 'er' : 'ème'); ?></span>
                                                <?php else: ?>
                                                    <?php echo e($data['position']); ?>ème
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($data['average'] >= 80): ?>
                                                    <span class="text-success">Excellent</span>
                                                <?php elseif($data['average'] >= 70): ?>
                                                    <span class="text-info">Très Bien</span>
                                                <?php elseif($data['average'] >= 60): ?>
                                                    <span class="text-primary">Bien</span>
                                                <?php elseif($data['average'] >= 50): ?>
                                                    <span class="text-warning">Passable</span>
                                                <?php else: ?>
                                                    <span class="text-danger">Insuffisant</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-0"><?php echo e(count($progress_data)); ?></h3>
                                <small>Examens passés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <?php
                                    $avgAll = count($progress_data) > 0 ? collect($progress_data)->avg('average') : 0;
                                ?>
                                <h3 class="mb-0"><?php echo e(number_format($avgAll, 1)); ?>%</h3>
                                <small>Moyenne générale</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <?php
                                    $bestPos = count($progress_data) > 0 ? collect($progress_data)->min('position') : 0;
                                ?>
                                <h3 class="mb-0"><?php echo e($bestPos); ?><?php echo e($bestPos == 1 ? 'er' : 'ème'); ?></h3>
                                <small>Meilleure position</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <?php
                                    $passed = collect($progress_data)->where('average', '>=', 50)->count();
                                ?>
                                <h3 class="mb-0"><?php echo e($passed); ?>/<?php echo e(count($progress_data)); ?></h3>
                                <small>Examens réussis</small>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-info">
                    <i class="icon-info22 mr-2"></i>
                    Aucun résultat d'examen trouvé pour cet étudiant.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="icon-warning mr-2"></i>
                Étudiant non trouvé.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php if(isset($progress_data) && count($progress_data) > 0): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('progressChart').getContext('2d');
    var progressData = <?php echo json_encode($progress_data, 15, 512) ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: progressData.map(item => item.exam_name + ' (' + item.year + ')'),
            datasets: [{
                label: 'Moyenne (%)',
                data: progressData.map(item => item.average),
                borderColor: 'rgba(102, 126, 234, 1)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 6,
                pointBackgroundColor: progressData.map(item => item.average >= 50 ? '#28a745' : '#dc3545'),
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Moyenne: ' + context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/exam_analytics/student_progress.blade.php ENDPATH**/ ?>