<?php $__env->startSection('page_title', 'Détails du Devoir'); ?>
<?php $__env->startSection('content'); ?>


<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            <?php echo e($assignment->title); ?>

        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('assignments.edit', $assignment->id)); ?>" class="btn btn-light btn-sm">
                <i class="icon-pencil mr-2"></i>Modifier
            </a>
            <a href="<?php echo e(route('assignments.export', $assignment->id)); ?>" class="btn btn-success btn-sm">
                <i class="icon-file-excel mr-2"></i>Exporter
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Classe:</th>
                        <td><?php echo e($assignment->myClass->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Section:</th>
                        <td><?php echo e($assignment->section->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Matière:</th>
                        <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Période:</th>
                        <td>
                            <?php
                                $periodLabels = [
                                    1 => 'Période 1',
                                    2 => 'Période 2',
                                    3 => 'Période 3',
                                    4 => 'Période 4'
                                ];
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
                            <small class="text-muted ml-2">
                                (<?php echo e($assignment->period <= 2 ? 'Semestre 1' : 'Semestre 2'); ?>)
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <th>Enseignant:</th>
                        <td><?php echo e($assignment->teacher->name ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Date limite:</th>
                        <td><?php echo e($assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Note maximale:</th>
                        <td><?php echo e($assignment->max_score); ?></td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            <?php if($assignment->status == 'active'): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php elseif($assignment->status == 'closed'): ?>
                                <span class="badge badge-danger">Fermé</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Brouillon</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Fichier:</th>
                        <td>
                            <?php if($assignment->file_path): ?>
                                <a href="<?php echo e(asset('storage/' . $assignment->file_path)); ?>" target="_blank" class="btn btn-sm btn-info">
                                    <i class="icon-download"></i> Télécharger
                                </a>
                            <?php else: ?>
                                Aucun
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <h6 class="font-weight-semibold">Description:</h6>
            <p><?php echo e($assignment->description); ?></p>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
                    <span class="text-uppercase font-size-xs">Total Étudiants</span>
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
                    <h3 class="mb-0"><?php echo e($stats['submitted']); ?></h3>
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
                    <h3 class="mb-0"><?php echo e($stats['pending']); ?></h3>
                    <span class="text-uppercase font-size-xs">En attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-teal-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-star-full2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0"><?php echo e($stats['graded']); ?></h3>
                    <span class="text-uppercase font-size-xs">Notés</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Soumissions (<?php echo e($submissions->count()); ?>)</h6>
    </div>

    <div class="card-body">
        <?php if($submissions->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Date Soumission</th>
                            <th>Statut</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($submission->student->name ?? 'N/A'); ?></strong></td>
                                <td><?php echo e($submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A'); ?></td>
                                <td>
                                    <?php if($submission->status == 'graded'): ?>
                                        <span class="badge badge-success">Noté</span>
                                    <?php elseif($submission->status == 'late'): ?>
                                        <span class="badge badge-warning">En retard</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Soumis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($submission->score !== null): ?>
                                        <strong><?php echo e($submission->score); ?>/<?php echo e($assignment->max_score); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">Non noté</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gradeModal<?php echo e($submission->id); ?>">
                                        <i class="icon-pencil"></i> Noter
                                    </button>
                                    <?php if($submission->file_path): ?>
                                        <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="icon-download"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            
                            <div class="modal fade" id="gradeModal<?php echo e($submission->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Noter - <?php echo e($submission->student->name); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('assignments.grade', $submission->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Texte soumis:</label>
                                                    <p class="border p-2"><?php echo e($submission->submission_text ?? 'Aucun texte'); ?></p>
                                                </div>

                                                <div class="form-group">
                                                    <label>Note (sur <?php echo e($assignment->max_score); ?>)</label>
                                                    <input type="number" name="score" class="form-control" min="0" max="<?php echo e($assignment->max_score); ?>" value="<?php echo e($submission->score); ?>" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feedback</label>
                                                    <textarea name="teacher_feedback" class="form-control" rows="3"><?php echo e($submission->teacher_feedback); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune soumission pour le moment.
            </div>
        <?php endif; ?>

        
        <?php if($notSubmitted->count() > 0): ?>
            <div class="mt-4">
                <h6 class="font-weight-semibold">Étudiants n'ayant pas soumis (<?php echo e($notSubmitted->count()); ?>)</h6>
                <div class="alert alert-warning">
                    <?php $__currentLoopData = $notSubmitted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge badge-warning mr-1"><?php echo e($student->user->name ?? 'N/A'); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/assignments/show.blade.php ENDPATH**/ ?>