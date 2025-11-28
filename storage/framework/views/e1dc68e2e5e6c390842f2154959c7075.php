
<?php $__env->startSection('page_title', 'Détails ' . $academicSession->name); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar mr-2"></i> <?php echo e($academicSession->name); ?>

                    <?php echo $academicSession->current_badge; ?>

                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Libellé</strong></td>
                        <td><?php echo e($academicSession->label); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Statut</strong></td>
                        <td><?php echo $academicSession->status_badge; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Période</strong></td>
                        <td>
                            <?php if($academicSession->start_date && $academicSession->end_date): ?>
                                <?php echo e($academicSession->start_date->format('d/m/Y')); ?> - <?php echo e($academicSession->end_date->format('d/m/Y')); ?>

                            <?php else: ?>
                                <span class="text-muted">Non défini</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($academicSession->registration_start): ?>
                    <tr>
                        <td><strong>Inscriptions</strong></td>
                        <td><?php echo e($academicSession->registration_start->format('d/m/Y')); ?> - <?php echo e($academicSession->registration_end?->format('d/m/Y')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($academicSession->exam_start): ?>
                    <tr>
                        <td><strong>Examens</strong></td>
                        <td><?php echo e($academicSession->exam_start->format('d/m/Y')); ?> - <?php echo e($academicSession->exam_end?->format('d/m/Y')); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>

                <?php if($academicSession->description): ?>
                    <div class="alert alert-light">
                        <?php echo e($academicSession->description); ?>

                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?php echo e(route('academic_sessions.edit', $academicSession)); ?>" class="btn btn-primary btn-sm">
                        <i class="icon-pencil mr-1"></i> Modifier
                    </a>
                    <a href="<?php echo e(route('academic_sessions.copy_structure', $academicSession)); ?>" class="btn btn-warning btn-sm">
                        <i class="icon-copy mr-1"></i> Copier
                    </a>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h3 text-primary mb-0"><?php echo e($academicSession->total_students); ?></div>
                        <small class="text-muted">Élèves</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-success mb-0"><?php echo e($academicSession->total_classes); ?></div>
                        <small class="text-muted">Classes</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-info mb-0"><?php echo e($academicSession->average_score ? number_format($academicSession->average_score, 1) : '-'); ?></div>
                        <small class="text-muted">Moyenne</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-users mr-2"></i> Répartition par Classe</h6>
            </div>
            <div class="card-body">
                <?php if($stats['students_by_class']->count() > 0): ?>
                    <canvas id="classChart" height="150"></canvas>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="icon-info22 d-block mb-2" style="font-size: 32px;"></i>
                        Aucun élève inscrit pour cette année
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="icon-user mr-2"></i> Par Genre</h6>
                    </div>
                    <div class="card-body">
                        <?php if(array_sum($stats['students_by_gender']->toArray()) > 0): ?>
                            <canvas id="genderChart" height="200"></canvas>
                        <?php else: ?>
                            <div class="text-center text-muted">Pas de données</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="icon-chart mr-2"></i> Moyennes par Période</h6>
                    </div>
                    <div class="card-body">
                        <?php if(array_sum($stats['performance_by_period']) > 0): ?>
                            <canvas id="periodChart" height="200"></canvas>
                        <?php else: ?>
                            <div class="text-center text-muted">Pas de données</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3">
    <a href="<?php echo e(route('academic_sessions.index')); ?>" class="btn btn-secondary">
        <i class="icon-arrow-left7 mr-1"></i> Retour à la liste
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const classData = <?php echo json_encode($stats['students_by_class'], 15, 512) ?>;
    const genderData = <?php echo json_encode($stats['students_by_gender'], 15, 512) ?>;
    const periodData = <?php echo json_encode($stats['performance_by_period'], 15, 512) ?>;

    // Graphique par classe
    if (classData.length > 0) {
        new Chart(document.getElementById('classChart'), {
            type: 'bar',
            data: {
                labels: classData.map(c => c.my_class?.name || 'N/A'),
                datasets: [{
                    label: 'Élèves',
                    data: classData.map(c => c.total),
                    backgroundColor: '#2196F3',
                    borderRadius: 5,
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    // Graphique par genre
    if (Object.keys(genderData).length > 0) {
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: ['Garçons', 'Filles'],
                datasets: [{
                    data: [genderData.Male || 0, genderData.Female || 0],
                    backgroundColor: ['#2196F3', '#E91E63'],
                }]
            },
            options: { responsive: true }
        });
    }

    // Graphique par période
    if (Object.values(periodData).some(v => v > 0)) {
        new Chart(document.getElementById('periodChart'), {
            type: 'line',
            data: {
                labels: Object.keys(periodData),
                datasets: [{
                    label: 'Moyenne',
                    data: Object.values(periodData),
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: 20 } } }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/academic_sessions/show.blade.php ENDPATH**/ ?>