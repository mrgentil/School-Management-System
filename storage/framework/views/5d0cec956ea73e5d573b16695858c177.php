
<?php $__env->startSection('page_title', 'Analyse - ' . $exam->name); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline bg-info text-white">
            <h6 class="card-title">
                <i class="icon-stats-dots mr-2"></i> Analyse Détaillée - <?php echo e($exam->name); ?> (<?php echo e($exam->year); ?>)
            </h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="icon-users4 icon-3x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($total_students); ?></h3>
                            <p class="mb-0">Étudiants</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="icon-books icon-3x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($total_subjects); ?></h3>
                            <p class="mb-0">Matières</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="icon-calculator icon-3x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($avg_class_average); ?>%</h3>
                            <p class="mb-0">Moyenne Générale</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="icon-trophy icon-3x mb-2"></i>
                            <h3 class="mb-0"><?php echo e($top_students->first()->ave ?? 'N/A'); ?>%</h3>
                            <p class="mb-0">Meilleure Moyenne</p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title">Distribution des Grades</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $grade_distribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md">
                            <div class="text-center p-3 border rounded">
                                <h2 class="mb-0 font-weight-bold text-<?php echo e($grade == 'A' ? 'success' : ($grade == 'F' ? 'danger' : 'primary')); ?>">
                                    <?php echo e($count); ?>

                                </h2>
                                <p class="mb-0">Grade <?php echo e($grade); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="mt-3">
                        <canvas id="gradeChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success-400">
                            <h6 class="card-title">
                                <i class="icon-trophy mr-2"></i> Top 10 Étudiants
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Nom</th>
                                        <th>Classe</th>
                                        <th>Moyenne</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $top_students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php if($index == 0): ?>
                                                    <span class="badge badge-warning">🥇</span>
                                                <?php elseif($index == 1): ?>
                                                    <span class="badge badge-secondary">🥈</span>
                                                <?php elseif($index == 2): ?>
                                                    <span class="badge badge-danger">🥉</span>
                                                <?php else: ?>
                                                    <?php echo e($index + 1); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($record->user->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($record->my_class->name ?? 'N/A'); ?></td>
                                            <td><strong><?php echo e($record->ave); ?>%</strong></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary-400">
                            <h6 class="card-title">
                                <i class="icon-library2 mr-2"></i> Performance par Classe
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>Classe</th>
                                        <th>Étudiants</th>
                                        <th>Moyenne</th>
                                        <th>Max/Min</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $class_stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><strong><?php echo e($stat['class_name']); ?></strong></td>
                                            <td><?php echo e($stat['students']); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo e($stat['average'] >= 70 ? 'success' : ($stat['average'] >= 50 ? 'warning' : 'danger')); ?>">
                                                    <?php echo e($stat['average']); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-success"><?php echo e($stat['highest']); ?></small> / 
                                                <small class="text-danger"><?php echo e($stat['lowest']); ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mt-4">
                <div class="card-header bg-warning-400">
                    <h6 class="card-title">
                        <i class="icon-book mr-2"></i> Performance par Matière
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatable-basic">
                            <thead>
                            <tr class="bg-light">
                                <th>Matière</th>
                                <th>Étudiants</th>
                                <th>Moyenne</th>
                                <th>Note Max</th>
                                <th>Note Min</th>
                                <th>Graphique</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $subject_stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($stat['subject_name']); ?></strong></td>
                                    <td><?php echo e($stat['students']); ?></td>
                                    <td><?php echo e($stat['average']); ?>%</td>
                                    <td><span class="text-success"><?php echo e($stat['highest']); ?></span></td>
                                    <td><span class="text-danger"><?php echo e($stat['lowest']); ?></span></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-<?php echo e($stat['average'] >= 70 ? 'success' : ($stat['average'] >= 50 ? 'warning' : 'danger')); ?>" 
                                                 style="width: <?php echo e($stat['average']); ?>%">
                                                <?php echo e($stat['average']); ?>%
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
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Grade Distribution Chart
    const ctx = document.getElementById('gradeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['A', 'B', 'C', 'D', 'F'],
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: [
                    <?php echo e($grade_distribution['A']); ?>,
                    <?php echo e($grade_distribution['B']); ?>,
                    <?php echo e($grade_distribution['C']); ?>,
                    <?php echo e($grade_distribution['D']); ?>,
                    <?php echo e($grade_distribution['F']); ?>

                ],
                backgroundColor: [
                    'rgba(34, 139, 34, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(34, 139, 34, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_analytics/overview.blade.php ENDPATH**/ ?>