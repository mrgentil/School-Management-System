
<?php $__env->startSection('page_title', 'Mon Calendrier d\'Examens'); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="card-title text-white font-weight-bold">
                        <i class="icon-calendar3 mr-2"></i> 📅 Mes Horaires d'Examens
                    </h5>
                    <div class="header-elements">
                        <span class="badge badge-light badge-pill">Session <?php echo e(Qs::getSetting('current_session')); ?></span>
                    </div>
                </div>

                <div class="card-body">
                    
                    <div class="alert" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <div class="d-flex align-items-center text-white">
                            <i class="icon-user icon-2x mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-white"><?php echo e(Auth::user()->name); ?></h6>
                                <small class="text-white font-weight-bold"><?php echo e($sr->my_class ? ($sr->my_class->full_name ?: $sr->my_class->name) : 'N/A'); ?></small>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($upcoming->count() > 0): ?>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                            <h6 class="card-title text-white mb-0">
                                <i class="icon-alarm-add mr-2"></i> ⏰ Examens à Venir (30 prochains jours)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="row no-gutters">
                                <?php $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="card m-3 shadow-sm" style="border-left: 4px solid <?php echo e($schedule->exam_type == 'session' ? '#f5576c' : '#4fc3f7'); ?>;">
                                        <div class="card-body">
                                            
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <?php if($schedule->exam_type == 'session'): ?>
                                                    <span class="badge badge-danger badge-pill">🔄 SESSION</span>
                                                <?php else: ?>
                                                    <span class="badge badge-info badge-pill">🏠 HORS SESSION</span>
                                                <?php endif; ?>
                                                <span class="badge badge-warning"><?php echo e(\Carbon\Carbon::parse($schedule->exam_date)->diffForHumans()); ?></span>
                                            </div>

                                            <h6 class="mb-1 font-weight-bold"><?php echo e($schedule->subject->name); ?></h6>
                                            <p class="text-muted mb-3" style="font-size: 0.85rem;"><?php echo e($schedule->exam->name); ?></p>

                                            <div class="mb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="icon-calendar3 mr-2" style="color: #667eea;"></i>
                                                    <span><strong>Date:</strong> <?php echo e($schedule->exam_date->format('d/m/Y')); ?></span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="icon-clock mr-2" style="color: #f5576c;"></i>
                                                    <span><strong>Heure:</strong> <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?></span>
                                                </div>

                                                
                                                <?php if($schedule->exam_type == 'session'): ?>
                                                    <?php
                                                        // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    ?>
                                                    <?php if($myPlacement): ?>
                                                        <div class="alert alert-success mb-2 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="icon-home9 mr-2"></i>
                                                                <div>
                                                                    <strong>Salle d'examen:</strong> <?php echo e($myPlacement->room->name ?? 'N/A'); ?><br>
                                                                    <strong>Place N°:</strong> <span class="badge badge-success">#<?php echo e($myPlacement->seat_number); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-warning mb-2 py-2">
                                                            <i class="icon-info22 mr-2"></i>
                                                            <small>Placement non encore disponible</small>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="icon-home mr-2" style="color: #4fc3f7;"></i>
                                                        <span><strong>Salle:</strong> Votre salle habituelle</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if($schedule->instructions): ?>
                                            <div class="alert alert-light mb-0 py-2">
                                                <small><strong>Instructions:</strong> <?php echo e($schedule->instructions); ?></small>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php
                        $sessionSchedules = $schedules->where('exam_type', 'session');
                        $horsSessionSchedules = $schedules->where('exam_type', 'hors_session');
                    ?>

                    
                    <ul class="nav nav-tabs nav-tabs-highlight mb-0">
                        <li class="nav-item">
                            <a href="#hors-session-tab" class="nav-link active" data-toggle="tab">
                                <i class="icon-home mr-2"></i> 🏠 Examens HORS SESSION
                                <span class="badge badge-info badge-pill ml-2"><?php echo e($horsSessionSchedules->count()); ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#session-tab" class="nav-link" data-toggle="tab">
                                <i class="icon-shuffle mr-2"></i> 🔄 Examens SESSION
                                <span class="badge badge-danger badge-pill ml-2"><?php echo e($sessionSchedules->count()); ?></span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content card shadow-sm">
                        
                        <div class="tab-pane fade show active" id="hors-session-tab">
                            <div class="card-body">
                                <?php if($horsSessionSchedules->count() > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-info text-white">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Durée</th>
                                                <th>Salle</th>
                                                <th>Statut</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__currentLoopData = $horsSessionSchedules->sortBy('exam_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo e($schedule->subject->name); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($schedule->exam->name); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-flat border-primary text-primary-600">
                                                            <?php echo e($schedule->exam_date->format('d/m/Y')); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-clock mr-1"></i>
                                                        <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?>

                                                    </td>
                                                    <td>
                                                        <?php
                                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                                            $duration = $start->diffInMinutes($end);
                                                        ?>
                                                        <span class="badge badge-secondary"><?php echo e($duration); ?> min</span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-home mr-1 text-info"></i>
                                                        Votre salle habituelle
                                                    </td>
                                                    <td>
                                                        <?php if($schedule->status == 'scheduled'): ?>
                                                            <span class="badge badge-info">Planifié</span>
                                                        <?php elseif($schedule->status == 'ongoing'): ?>
                                                            <span class="badge badge-warning">En cours</span>
                                                        <?php elseif($schedule->status == 'completed'): ?>
                                                            <span class="badge badge-success">Terminé</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">Annulé</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="icon-home icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun examen HORS SESSION planifié</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="tab-pane fade" id="session-tab">
                            <div class="card-body">
                                <?php if($sessionSchedules->count() > 0): ?>
                                    <div class="alert alert-info border-0 mb-3">
                                        <i class="icon-info22 mr-2"></i>
                                        <strong>Examens SESSION :</strong> Vous serez placé automatiquement dans une salle d'examen selon vos performances.
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-danger text-white">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Durée</th>
                                                <th>Salle d'Examen</th>
                                                <th>Place N°</th>
                                                <th>Statut</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $__currentLoopData = $sessionSchedules->sortBy('exam_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                    $myPlacement = null;
                                                    if ($schedule->exam_id) {
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo e($schedule->subject->name); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($schedule->exam ? $schedule->exam->name : 'N/A'); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-flat border-danger text-danger-600">
                                                            <?php echo e($schedule->exam_date->format('d/m/Y')); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-clock mr-1"></i>
                                                        <?php echo e(date('H:i', strtotime($schedule->start_time))); ?> - <?php echo e(date('H:i', strtotime($schedule->end_time))); ?>

                                                    </td>
                                                    <td>
                                                        <?php
                                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                                            $duration = $start->diffInMinutes($end);
                                                        ?>
                                                        <span class="badge badge-secondary"><?php echo e($duration); ?> min</span>
                                                    </td>
                                                    <?php
                                                        // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    ?>
                                                    <td>
                                                        <?php if($myPlacement): ?>
                                                            <span class="badge badge-success">
                                                                <i class="icon-home9 mr-1"></i>
                                                                <?php echo e($myPlacement->room->name ?? 'N/A'); ?>

                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted"><i class="icon-hour-glass2 mr-1"></i> En attente</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($myPlacement): ?>
                                                            <span class="badge badge-pill badge-primary" style="font-size: 1rem;">
                                                                #<?php echo e($myPlacement->seat_number); ?>

                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($schedule->status == 'scheduled'): ?>
                                                            <span class="badge badge-info">Planifié</span>
                                                        <?php elseif($schedule->status == 'ongoing'): ?>
                                                            <span class="badge badge-warning">En cours</span>
                                                        <?php elseif($schedule->status == 'completed'): ?>
                                                            <span class="badge badge-success">Terminé</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">Annulé</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="icon-shuffle icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun examen SESSION planifié</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/exam_schedule.blade.php ENDPATH**/ ?>