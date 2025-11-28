
<?php $__env->startSection('page_title', 'Statistiques de l\'École'); ?>

<?php $__env->startSection('content'); ?>

<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques de l'École</h4>
                <small class="opacity-75">Année scolaire <?php echo e($year); ?></small>
            </div>
            <a href="<?php echo e(route('statistics.export')); ?>" class="btn btn-light">
                <i class="icon-download mr-1"></i> Exporter CSV
            </a>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-users icon-2x text-primary mb-2"></i>
                <h3 class="mb-0"><?php echo e($generalStats['total_students']); ?></h3>
                <small class="text-muted">Élèves</small>
                <div class="mt-2">
                    <small class="text-primary">♂ <?php echo e($generalStats['boys']); ?></small>
                    <small class="text-danger ml-2">♀ <?php echo e($generalStats['girls']); ?></small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-user-tie icon-2x text-success mb-2"></i>
                <h3 class="mb-0"><?php echo e($generalStats['total_teachers']); ?></h3>
                <small class="text-muted">Enseignants</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-library icon-2x text-info mb-2"></i>
                <h3 class="mb-0"><?php echo e($generalStats['total_classes']); ?></h3>
                <small class="text-muted">Classes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-book icon-2x text-warning mb-2"></i>
                <h3 class="mb-0"><?php echo e($generalStats['total_subjects']); ?></h3>
                <small class="text-muted">Matières</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-pie-chart5 mr-2"></i> Répartition par Genre</h6>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="200"></canvas>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-checkmark-circle mr-2"></i> Assiduité Globale</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="200"></canvas>
                <div class="text-center mt-3">
                    <h4 class="text-success mb-0"><?php echo e($attendanceStats['rate']); ?>%</h4>
                    <small class="text-muted">Taux de présence</small>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-coins mr-2"></i> Situation Financière</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Recouvrement</span>
                        <strong><?php echo e($financeStats['collection_rate']); ?>%</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: <?php echo e($financeStats['collection_rate']); ?>%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success mb-0"><?php echo e(number_format($financeStats['total_paid'])); ?></h5>
                        <small class="text-muted">Payé (FC)</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-danger mb-0"><?php echo e(number_format($financeStats['total_balance'])); ?></h5>
                        <small class="text-muted">Reste (FC)</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <span class="badge badge-success"><?php echo e($financeStats['students_paid_full']); ?></span>
                        <br><small>Soldés</small>
                    </div>
                    <div class="col-6">
                        <span class="badge badge-warning"><?php echo e($financeStats['students_with_balance']); ?></span>
                        <br><small>Avec solde</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars2 mr-2"></i> Moyennes par Classe</h6>
            </div>
            <div class="card-body">
                <canvas id="classChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-line-chart mr-2"></i> Inscriptions Mensuelles</h6>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-trophy mr-2"></i> Top 10 Élèves</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <?php if($index == 0): ?>
                                            🥇
                                        <?php elseif($index == 1): ?>
                                            🥈
                                        <?php elseif($index == 2): ?>
                                            🥉
                                        <?php else: ?>
                                            <?php echo e($index + 1); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo e($item['student']->name ?? 'N/A'); ?></strong></td>
                                    <td><small><?php echo e($item['class']->name ?? ''); ?></small></td>
                                    <td><span class="badge badge-success"><?php echo e($item['average']); ?>/20</span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="text-center text-muted">Pas de données</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Élèves en Difficulté</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $strugglingStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><strong><?php echo e($item['student']->name ?? 'N/A'); ?></strong></td>
                                    <td><small><?php echo e($item['class']->name ?? ''); ?></small></td>
                                    <td><span class="badge badge-danger"><?php echo e($item['average']); ?>/20</span></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="text-center text-success py-3">✓ Tous les élèves réussissent!</td></tr>
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
        <h6 class="card-title mb-0"><i class="icon-book mr-2"></i> Performance par Matière</h6>
    </div>
    <div class="card-body">
        <canvas id="subjectChart" height="80"></canvas>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light d-flex justify-content-between">
        <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Récapitulatif par Classe</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped datatable-basic mb-0">
                <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Type</th>
                        <th>Titulaire</th>
                        <th>Élèves</th>
                        <th>Moyenne</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $classStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($stat['name']); ?></strong></td>
                            <td><?php echo e($stat['type']); ?></td>
                            <td><?php echo e($stat['teacher']); ?></td>
                            <td><?php echo e($stat['students']); ?></td>
                            <td><?php echo e($stat['average']); ?>/20</td>
                            <td>
                                <div class="progress" style="height: 20px; min-width: 100px;">
                                    <?php $pct = ($stat['average'] / 20) * 100; ?>
                                    <div class="progress-bar <?php echo e($pct >= 50 ? 'bg-success' : 'bg-danger'); ?>" 
                                         style="width: <?php echo e($pct); ?>%">
                                        <?php echo e(round($pct)); ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données
const generalStats = <?php echo json_encode($generalStats, 15, 512) ?>;
const classStats = <?php echo json_encode($classStats, 15, 512) ?>;
const attendanceStats = <?php echo json_encode($attendanceStats, 15, 512) ?>;
const enrollmentTrend = <?php echo json_encode($enrollmentTrend, 15, 512) ?>;
const subjectPerformance = <?php echo json_encode($subjectPerformance, 15, 512) ?>;

// Graphique Genre
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Garçons', 'Filles'],
        datasets: [{
            data: [generalStats.boys, generalStats.girls],
            backgroundColor: ['#2196F3', '#E91E63'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Graphique Présence
new Chart(document.getElementById('attendanceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Présent', 'Absent', 'Retard', 'Excusé'],
        datasets: [{
            data: [attendanceStats.present, attendanceStats.absent, attendanceStats.late, attendanceStats.excused],
            backgroundColor: ['#4CAF50', '#f44336', '#FF9800', '#9E9E9E'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Graphique Classes
new Chart(document.getElementById('classChart'), {
    type: 'bar',
    data: {
        labels: classStats.map(c => c.name),
        datasets: [{
            label: 'Moyenne',
            data: classStats.map(c => c.average),
            backgroundColor: classStats.map(c => c.average >= 10 ? '#4CAF50' : '#f44336'),
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 20 }
        }
    }
});

// Graphique Inscriptions
new Chart(document.getElementById('enrollmentChart'), {
    type: 'line',
    data: {
        labels: enrollmentTrend.map(e => e.month),
        datasets: [{
            label: 'Inscriptions',
            data: enrollmentTrend.map(e => e.count),
            borderColor: '#2196F3',
            backgroundColor: 'rgba(33, 150, 243, 0.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Graphique Matières
new Chart(document.getElementById('subjectChart'), {
    type: 'bar',
    data: {
        labels: subjectPerformance.map(s => s.name),
        datasets: [{
            label: 'Moyenne',
            data: subjectPerformance.map(s => s.average),
            backgroundColor: subjectPerformance.map(s => s.average >= 10 ? '#4CAF50' : '#f44336'),
            borderRadius: 5,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, max: 20 } }
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/statistics/index.blade.php ENDPATH**/ ?>