<?php $__env->startSection('page_title', 'Mes Devoirs'); ?>

<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($total_assignments); ?></h3>
                    <span class="text-uppercase font-size-xs">Total Devoirs</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark3 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($submitted_count); ?></h3>
                    <span class="text-uppercase font-size-xs">Soumis</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-hour-glass2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($pending_count); ?></h3>
                    <span class="text-uppercase font-size-xs">En attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($overdue_count); ?></h3>
                    <span class="text-uppercase font-size-xs">En retard</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Devoirs</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        
        <form method="GET" action="<?php echo e(route('student.assignments.index')); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Toutes les matières</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($selected_subject == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Période</label>
                        <select name="period" class="form-control">
                            <option value="">Toutes</option>
                            <option value="1" <?php echo e($selected_period == 1 ? 'selected' : ''); ?>>Période 1 (S1)</option>
                            <option value="2" <?php echo e($selected_period == 2 ? 'selected' : ''); ?>>Période 2 (S1)</option>
                            <option value="3" <?php echo e($selected_period == 3 ? 'selected' : ''); ?>>Période 3 (S2)</option>
                            <option value="4" <?php echo e($selected_period == 4 ? 'selected' : ''); ?>>Période 4 (S2)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="pending" <?php echo e($selected_status == 'pending' ? 'selected' : ''); ?>>En attente</option>
                            <option value="submitted" <?php echo e($selected_status == 'submitted' ? 'selected' : ''); ?>>Soumis</option>
                            <option value="overdue" <?php echo e($selected_status == 'overdue' ? 'selected' : ''); ?>>En retard</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4 mr-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <?php if($assignments->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Période</th>
                            <th>Enseignant</th>
                            <th>Date Limite</th>
                            <th>Note Max</th>
                            <th>Statut</th>
                            <th>Ma Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $submission = $assignment->submissions->first();
                                $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                                
                                if ($submission) {
                                    if ($submission->score !== null) {
                                        $status = 'Noté';
                                        $statusClass = 'badge-success';
                                    } else {
                                        $status = $submission->status == 'late' ? 'Soumis en retard' : 'Soumis';
                                        $statusClass = $submission->status == 'late' ? 'badge-warning' : 'badge-info';
                                    }
                                } else {
                                    $status = $isOverdue ? 'En retard' : 'Non soumis';
                                    $statusClass = $isOverdue ? 'badge-danger' : 'badge-secondary';
                                }
                            ?>
                            <tr>
                                <td><strong><?php echo e($assignment->title); ?></strong></td>
                                <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php
                                        $periodLabels = [1 => 'P1', 2 => 'P2', 3 => 'P3', 4 => 'P4'];
                                        $periodBadges = [
                                            1 => 'badge-primary',
                                            2 => 'badge-info',
                                            3 => 'badge-success',
                                            4 => 'badge-warning'
                                        ];
                                    ?>
                                    <span class="badge <?php echo e($periodBadges[$assignment->period] ?? 'badge-secondary'); ?>">
                                        <?php echo e($periodLabels[$assignment->period] ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td><?php echo e($assignment->teacher->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo e($assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A'); ?>

                                    <?php if($isOverdue && !$submission): ?>
                                        <br><small class="text-danger">Expiré</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($assignment->max_score); ?></td>
                                <td><span class="badge <?php echo e($statusClass); ?>"><?php echo e($status); ?></span></td>
                                <td>
                                    <?php if($submission && $submission->score !== null): ?>
                                        <strong class="text-success"><?php echo e($submission->score); ?>/<?php echo e($assignment->max_score); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('student.assignments.show', $assignment->id)); ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="icon-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <?php echo e($assignments->appends(request()->query())->links()); ?>

            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun devoir trouvé. Modifiez les filtres pour voir plus de devoirs.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/assignments/index.blade.php ENDPATH**/ ?>