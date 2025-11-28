<?php $__env->startSection('page_title', 'Mon Emploi du Temps'); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="card mb-3">
        <div class="card-body bg-primary-400 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1"><i class="icon-calendar mr-2"></i>Mon Emploi du Temps</h4>
                    <p class="mb-0 opacity-75">
                        <?php if(isset($className)): ?>
                            Classe: <strong><?php echo e($className); ?></strong>
                        <?php endif; ?>
                    </p>
                </div>
                <?php if(isset($timetableRecord)): ?>
                <div>
                    <a href="<?php echo e(route('student.timetable.calendar')); ?>" class="btn btn-light">
                        <i class="icon-calendar3 mr-2"></i> Vue Calendrier
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(isset($message)): ?>
        <!-- Message d'information -->
        <div class="alert alert-info border-left-info">
            <div class="d-flex align-items-center">
                <i class="icon-info22 icon-2x mr-3"></i>
                <div>
                    <h6 class="alert-heading mb-1">Information</h6>
                    <p class="mb-0"><?php echo e($message); ?></p>
                </div>
            </div>
        </div>
    <?php elseif(isset($schedule)): ?>
        <!-- Emploi du temps -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar22 mr-2"></i>Emploi du Temps Hebdomadaire
                    <?php if(isset($timetableRecord) && $timetableRecord->exam): ?>
                        <span class="badge badge-warning ml-2">Examens: <?php echo e($timetableRecord->exam->name); ?></span>
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="15%" class="text-center">
                                    <i class="icon-calendar3 mr-1"></i>Jour
                                </th>
                                <th width="15%" class="text-center">
                                    <i class="icon-clock mr-1"></i>Horaire
                                </th>
                                <th>
                                    <i class="icon-book mr-1"></i>Matière
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $courses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($courses->count() > 0): ?>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php if($index === 0): ?>
                                                <td rowspan="<?php echo e($courses->count()); ?>" class="align-middle bg-light font-weight-bold text-center">
                                                    <i class="icon-calendar22 mr-1"></i><?php echo e($day); ?>

                                                </td>
                                            <?php endif; ?>
                                            <td class="text-center">
                                                <?php if($course->time_slot): ?>
                                                    <div class="font-weight-semibold text-primary">
                                                        <?php echo e(date('H:i', $course->time_slot->timestamp_from)); ?>

                                                    </div>
                                                    <div class="text-muted small">
                                                        <?php echo e(date('H:i', $course->time_slot->timestamp_to)); ?>

                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <span class="badge badge-primary badge-pill">
                                                            <i class="icon-book"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">
                                                            <?php echo e($course->subject->name ?? 'N/A'); ?>

                                                        </div>
                                                        <?php if($course->time_slot): ?>
                                                            <div class="text-muted small">
                                                                Durée: <?php echo e($course->time_slot->full ?? 'N/A'); ?>

                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="align-middle bg-light font-weight-bold text-center">
                                            <i class="icon-calendar22 mr-1"></i><?php echo e($day); ?>

                                        </td>
                                        <td colspan="2" class="text-center text-muted">
                                            <i class="icon-blocked mr-1"></i>Aucun cours prévu
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="icon-info22 mr-2"></i>Informations</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="icon-checkmark-circle text-success mr-2"></i>
                                Consultez votre emploi du temps pour planifier votre semaine
                            </li>
                            <li class="mb-2">
                                <i class="icon-calendar3 text-primary mr-2"></i>
                                Utilisez la vue calendrier pour une visualisation différente
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="icon-printer text-info mr-2"></i>
                                Vous pouvez imprimer cet emploi du temps (Ctrl+P)
                            </li>
                            <li class="mb-2">
                                <i class="icon-bell2 text-warning mr-2"></i>
                                Vérifiez régulièrement les mises à jour
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('css'); ?>
<style>
    @media print {
        .btn, .navbar, .sidebar, .no-print, .card-footer {
            display: none !important;
        }
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .badge-pill {
        padding: 0.5rem 0.75rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/timetable/index.blade.php ENDPATH**/ ?>