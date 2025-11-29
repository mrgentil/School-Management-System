
<?php $__env->startSection('page_title', 'Tableau de Bord Enseignant'); ?>

<?php $__env->startSection('content'); ?>

<div class="card bg-primary text-white mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">👋 Bonjour, <?php echo e($teacher->name); ?></h4>
                <p class="mb-0 opacity-75">Année scolaire <?php echo e($year); ?></p>
            </div>
            <div class="text-right">
                <div class="d-flex">
                    <div class="mr-4 text-center">
                        <h3 class="mb-0"><?php echo e($stats['total_classes']); ?></h3>
                        <small>Classes</small>
                    </div>
                    <div class="mr-4 text-center">
                        <h3 class="mb-0"><?php echo e($stats['total_students']); ?></h3>
                        <small>Élèves</small>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-0"><?php echo e($stats['total_subjects']); ?></h3>
                        <small>Matières</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($stats['pending_grades'] > 0): ?>
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <span><i class="icon-warning mr-2"></i> Vous avez <strong><?php echo e($stats['pending_grades']); ?></strong> devoir(s) en attente de notation.</span>
    <a href="<?php echo e(route('assignments.index')); ?>" class="btn btn-sm btn-warning">Voir les devoirs</a>
</div>
<?php endif; ?>


<div class="row">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="icon-calendar3 icon-2x mb-2"></i>
                <h3 class="mb-0"><?php echo e($stats['today_courses']); ?></h3>
                <small>Cours aujourd'hui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="icon-users icon-2x mb-2"></i>
                <h3 class="mb-0"><?php echo e($stats['total_students']); ?></h3>
                <small>Élèves total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="icon-star-full2 icon-2x mb-2"></i>
                <h3 class="mb-0"><?php echo e($stats['titular_classes']); ?></h3>
                <small>Classes titulaires</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="icon-warning icon-2x mb-2"></i>
                <h3 class="mb-0"><?php echo e(count($strugglingStudents)); ?></h3>
                <small>Élèves en difficulté</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="icon-calendar3 mr-2"></i> Cours Aujourd'hui
                    <span class="badge badge-light ml-2"><?php echo e(now()->format('l d M')); ?></span>
                </h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $todayCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo e($course->subject->name ?? 'N/A'); ?></strong>
                            <br><small class="text-muted"><?php echo e($course->tt_record->my_class->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-primary"><?php echo e($course->time_slot->time_start ?? ''); ?> - <?php echo e($course->time_slot->time_end ?? ''); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 32px;"></i>
                        Pas de cours aujourd'hui
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-library mr-2"></i> Mes Classes</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $teachingClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo e($class->full_name ?: $class->name); ?></strong>
                            <?php if($class->teacher_id == $teacher->id): ?>
                                <span class="badge badge-warning ml-2">Titulaire</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted"><?php echo e($class->class_type->name ?? ''); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-4 text-center text-muted">Aucune classe</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Élèves en Difficulté</h6>
            </div>
            <div class="card-body p-0">
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $strugglingStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo e($item['student']->name ?? 'N/A'); ?></strong>
                                <br><small class="text-muted"><?php echo e($item['class']->name ?? ''); ?></small>
                            </div>
                            <span class="badge badge-danger"><?php echo e($item['average']); ?>/20</span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-success">
                            <i class="icon-checkmark-circle d-block mb-2" style="font-size: 32px;"></i>
                            Tous les élèves vont bien !
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Moyennes par Classe</h6>
            </div>
            <div class="card-body">
                <?php if(count($classStats) > 0): ?>
                    <canvas id="classChart" height="120"></canvas>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="icon-stats-bars d-block mb-2" style="font-size: 32px;"></i>
                        Pas encore de données
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-history mr-2"></i> Dernières Notes Saisies</h6>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $recentMarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-2 border-bottom">
                        <small>
                            <strong><?php echo e($mark->user->name ?? 'N/A'); ?></strong>
                            - <?php echo e($mark->subject->name ?? ''); ?>

                            <br>
                            <span class="text-muted"><?php echo e($mark->my_class->name ?? ''); ?></span>
                        </small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-3 text-center text-muted">Aucune note récente</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-calendar mr-2"></i> Emploi du Temps de la Semaine</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                        <th>Samedi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php $__currentLoopData = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td style="vertical-align: top; min-width: 150px;">
                                <?php if(isset($weekCourses[$day])): ?>
                                    <?php $__currentLoopData = $weekCourses[$day]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="mb-2 p-2 bg-primary text-white rounded" style="font-size: 11px;">
                                            <strong><?php echo e($course->time_slot->time_start ?? ''); ?></strong><br>
                                            <?php echo e($course->subject->name ?? 'N/A'); ?><br>
                                            <small><?php echo e($course->tt_record->my_class->name ?? ''); ?></small>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-grid6 mr-2"></i> Actions Rapides</h6>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('marks.index')); ?>" class="btn btn-lg btn-outline-primary w-100">
                    <i class="icon-pencil d-block mb-2" style="font-size: 24px;"></i>
                    <small>Saisir Notes</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('attendance.index')); ?>" class="btn btn-lg btn-outline-success w-100">
                    <i class="icon-checkmark-circle d-block mb-2" style="font-size: 24px;"></i>
                    <small>Présences</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('assignments.index')); ?>" class="btn btn-lg btn-outline-info w-100">
                    <i class="icon-clipboard3 d-block mb-2" style="font-size: 24px;"></i>
                    <small>Devoirs</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('teacher.messages.index')); ?>" class="btn btn-lg btn-outline-warning w-100">
                    <i class="icon-envelop d-block mb-2" style="font-size: 24px;"></i>
                    <small>Messages</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('calendar.public')); ?>" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                    <small>Calendrier</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="<?php echo e(route('my_account')); ?>" class="btn btn-lg btn-outline-dark w-100">
                    <i class="icon-user d-block mb-2" style="font-size: 24px;"></i>
                    <small>Mon Compte</small>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if(count($classStats) > 0): ?>
    const classStats = <?php echo json_encode($classStats, 15, 512) ?>;
    
    new Chart(document.getElementById('classChart'), {
        type: 'bar',
        data: {
            labels: classStats.map(c => c.class),
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
                y: { 
                    beginAtZero: true, 
                    max: 20,
                    title: { display: true, text: 'Moyenne /20' }
                }
            }
        }
    });
    <?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/teacher/dashboard.blade.php ENDPATH**/ ?>