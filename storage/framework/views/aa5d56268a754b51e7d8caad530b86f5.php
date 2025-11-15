<?php $__env->startSection('page_title', 'Détails du Devoir'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            <?php echo e($assignment->title); ?>

        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('student.assignments.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left7 mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Matière:</th>
                        <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Enseignant:</th>
                        <td><?php echo e($assignment->teacher->name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Date limite:</th>
                        <td>
                            <?php echo e($assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A'); ?>

                            <?php if($assignment->due_date && $assignment->due_date->isPast()): ?>
                                <span class="badge badge-danger ml-2">Expiré</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Note maximale:</th>
                        <td><?php echo e($assignment->max_score); ?></td>
                    </tr>
                    <tr>
                        <th>Fichier joint:</th>
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
                    <tr>
                        <th>Statut:</th>
                        <td>
                            <?php if($submission): ?>
                                <?php if($submission->score !== null): ?>
                                    <span class="badge badge-success">Noté</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Soumis</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-warning">Non soumis</span>
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


<?php if($submission): ?>
    <div class="card">
        <div class="card-header bg-success">
            <h6 class="card-title text-white">Ma Soumission</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date de soumission:</strong> <?php echo e($submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A'); ?></p>
                    <p><strong>Statut:</strong> 
                        <?php if($submission->status == 'late'): ?>
                            <span class="badge badge-warning">Soumis en retard</span>
                        <?php elseif($submission->status == 'graded'): ?>
                            <span class="badge badge-success">Noté</span>
                        <?php else: ?>
                            <span class="badge badge-info">Soumis</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <?php if($submission->score !== null): ?>
                        <div class="alert alert-success">
                            <h4 class="mb-0">Note: <?php echo e($submission->score); ?>/<?php echo e($assignment->max_score); ?></h4>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <p class="mb-0">En attente de notation</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($submission->submission_text): ?>
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Mon texte:</h6>
                    <p class="border p-3"><?php echo e($submission->submission_text); ?></p>
                </div>
            <?php endif; ?>

            <?php if($submission->file_path): ?>
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Mon fichier:</h6>
                    <a href="<?php echo e(asset('storage/' . $submission->file_path)); ?>" target="_blank" class="btn btn-info">
                        <i class="icon-download mr-2"></i>Télécharger mon fichier
                    </a>
                </div>
            <?php endif; ?>

            <?php if($submission->teacher_feedback): ?>
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Feedback de l'enseignant:</h6>
                    <div class="alert alert-primary">
                        <?php echo e($submission->teacher_feedback); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    
    <?php if($assignment->status == 'active'): ?>
        <div class="card">
            <div class="card-header bg-info">
                <h6 class="card-title text-white">Soumettre mon Devoir</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('student.assignments.submit', $assignment->id)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label class="font-weight-semibold">Votre réponse (texte)</label>
                        <textarea name="submission_text" class="form-control" rows="6" placeholder="Écrivez votre réponse ici..."></textarea>
                        <small class="text-muted">Vous pouvez écrire votre réponse ou joindre un fichier (ou les deux)</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold">Fichier (optionnel)</label>
                        <input type="file" name="submission_file" class="form-control-file">
                        <small class="text-muted">PDF, DOC, DOCX, images (Max: 10MB)</small>
                    </div>

                    <?php if($assignment->due_date && $assignment->due_date->isPast()): ?>
                        <div class="alert alert-warning">
                            <i class="icon-warning mr-2"></i>
                            Attention: La date limite est dépassée. Votre soumission sera marquée comme "en retard".
                        </div>
                    <?php endif; ?>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-checkmark3 mr-2"></i>
                            Soumettre mon Devoir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <i class="icon-blocked mr-2"></i>
            Ce devoir n'est plus actif. Vous ne pouvez plus le soumettre.
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/assignments/show.blade.php ENDPATH**/ ?>