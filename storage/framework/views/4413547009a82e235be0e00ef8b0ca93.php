
<?php $__env->startSection('page_title', 'Progression - ' . $student->user->name); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="<?php echo e($student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg')); ?>" 
                     class="rounded-circle" width="80" height="80">
            </div>
            <div class="col">
                <h4 class="mb-1"><?php echo e($student->user->name); ?></h4>
                <p class="text-muted mb-0">
                    <span class="badge badge-primary"><?php echo e($student->my_class->full_name ?? $student->my_class->name ?? 'N/A'); ?></span>
                    <span class="badge badge-secondary"><?php echo e($student->section->name ?? ''); ?></span>
                    <span class="ml-2">N° <?php echo e($student->adm_no); ?></span>
                </p>
            </div>
            <div class="col-auto">
                
                <div class="text-center">
                    <i class="<?php echo e($trend['icon'] ?? 'icon-minus3'); ?> text-<?php echo e($trend['color'] ?? 'info'); ?>" style="font-size: 32px;"></i>
                    <br><span class="badge badge-<?php echo e($trend['color'] ?? 'info'); ?>"><?php echo e($trend['message'] ?? 'N/A'); ?></span>
                </div>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('student_progress.pdf', $student->user_id)); ?>" class="btn btn-danger" target="_blank">
                    <i class="icon-file-pdf mr-1"></i> Exporter PDF
                </a>
                <a href="<?php echo e(route('student_progress.index')); ?>" class="btn btn-secondary ml-1">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h2 class="mb-0"><?php echo e($stats['general_average']); ?>/20</h2>
                <small>Moyenne Générale</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h2 class="mb-0"><?php echo e($stats['class_rank']); ?><sup>e</sup>/<?php echo e($stats['class_total']); ?></h2>
                <small>Rang dans la classe</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h2 class="mb-0"><?php echo e($stats['total_subjects']); ?></h2>
                <small>Matières évaluées</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-<?php echo e(count($stats['strengths']) > count($stats['weaknesses']) ? 'success' : 'warning'); ?> text-white">
            <div class="card-body text-center">
                <h2 class="mb-0"><?php echo e(count($stats['strengths'])); ?> / <?php echo e(count($stats['weaknesses'])); ?></h2>
                <small>Forces / Faiblesses</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="icon-stats-growth mr-2"></i> Évolution par Période
                </h6>
            </div>
            <div class="card-body">
                <canvas id="progressChart" height="120"></canvas>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-thumbs-up2 mr-2"></i> Points Forts</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $stats['strengths']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-2 border-bottom">
                        <i class="icon-checkmark text-success mr-1"></i> <?php echo e($subject); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-muted text-center">Aucun point fort identifié (>= 14/20)</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> À Améliorer</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $stats['weaknesses']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-2 border-bottom">
                        <i class="icon-cross text-danger mr-1"></i> <?php echo e($subject); ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-muted text-center">Aucune faiblesse identifiée (< 10/20)</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0">
            <i class="icon-book mr-2"></i> Détail par Matière
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th class="text-center">P1</th>
                        <th class="text-center">P2</th>
                        <th class="text-center">P3</th>
                        <th class="text-center">P4</th>
                        <th class="text-center">Moyenne</th>
                        <th>Évolution</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $subjectData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $evolution = 0;
                            $periods = [$subject['p1'], $subject['p2'], $subject['p3'], $subject['p4']];
                            $validPeriods = array_filter($periods, fn($v) => $v !== null);
                            if (count($validPeriods) >= 2) {
                                $values = array_values($validPeriods);
                                $evolution = end($values) - $values[0];
                            }
                        ?>
                        <tr>
                            <td><strong><?php echo e($subject['subject']); ?></strong></td>
                            <td class="text-center">
                                <?php if($subject['p1'] !== null): ?>
                                    <span class="badge badge-<?php echo e($subject['p1'] >= 10 ? 'success' : 'danger'); ?>"><?php echo e($subject['p1']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($subject['p2'] !== null): ?>
                                    <span class="badge badge-<?php echo e($subject['p2'] >= 10 ? 'success' : 'danger'); ?>"><?php echo e($subject['p2']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($subject['p3'] !== null): ?>
                                    <span class="badge badge-<?php echo e($subject['p3'] >= 10 ? 'success' : 'danger'); ?>"><?php echo e($subject['p3']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($subject['p4'] !== null): ?>
                                    <span class="badge badge-<?php echo e($subject['p4'] >= 10 ? 'success' : 'danger'); ?>"><?php echo e($subject['p4']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <strong class="text-<?php echo e($subject['average'] >= 10 ? 'success' : 'danger'); ?>">
                                    <?php echo e($subject['average']); ?>/20
                                </strong>
                            </td>
                            <td>
                                <?php if($evolution > 0.5): ?>
                                    <span class="text-success"><i class="icon-arrow-up7"></i> +<?php echo e(round($evolution, 1)); ?></span>
                                <?php elseif($evolution < -0.5): ?>
                                    <span class="text-danger"><i class="icon-arrow-down7"></i> <?php echo e(round($evolution, 1)); ?></span>
                                <?php else: ?>
                                    <span class="text-muted"><i class="icon-minus3"></i> Stable</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0">
            <i class="icon-chart mr-2"></i> Profil des Compétences
        </h6>
    </div>
    <div class="card-body">
        <canvas id="radarChart" height="100"></canvas>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const progressData = <?php echo json_encode($progressData, 15, 512) ?>;
    const subjectData = <?php echo json_encode($subjectData, 15, 512) ?>;

    // Graphique d'évolution
    new Chart(document.getElementById('progressChart'), {
        type: 'line',
        data: {
            labels: progressData.periods,
            datasets: [
                {
                    label: 'Élève',
                    data: progressData.averages,
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(33, 150, 243, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 6,
                    pointBackgroundColor: '#2196F3',
                },
                {
                    label: 'Moyenne Classe',
                    data: progressData.class_averages,
                    borderColor: '#9E9E9E',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.3,
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 20,
                    title: { display: true, text: 'Note /20' }
                }
            },
            plugins: {
                annotation: {
                    annotations: {
                        line1: {
                            type: 'line',
                            yMin: 10,
                            yMax: 10,
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1,
                            borderDash: [3, 3],
                        }
                    }
                }
            }
        }
    });

    // Graphique radar
    if (subjectData.length > 0) {
        new Chart(document.getElementById('radarChart'), {
            type: 'radar',
            data: {
                labels: subjectData.slice(0, 8).map(s => s.subject),
                datasets: [{
                    label: 'Moyenne',
                    data: subjectData.slice(0, 8).map(s => s.average),
                    backgroundColor: 'rgba(33, 150, 243, 0.2)',
                    borderColor: '#2196F3',
                    pointBackgroundColor: '#2196F3',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 20,
                        ticks: { stepSize: 5 }
                    }
                }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/student_progress/show.blade.php ENDPATH**/ ?>