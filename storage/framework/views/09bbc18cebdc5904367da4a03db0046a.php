<?php $__env->startSection('page_title', 'Gestion des Devoirs'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            Liste des Devoirs
        </h6>
        <div class="header-elements">
            <a href="<?php echo e(route('assignments.create')); ?>" class="btn btn-light btn-sm">
                <i class="icon-plus2 mr-2"></i>
                Créer un Devoir
            </a>
        </div>
    </div>

    <div class="card-body">
        
        <form method="GET" action="<?php echo e(route('assignments.index')); ?>" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control">
                            <option value="">Toutes les classes</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e($filters['my_class_id'] == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->full_name ?: $class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section</label>
                        <select name="section_id" id="section_id" class="form-control">
                            <option value="">Toutes</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($filters['subject_id'] == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Période</label>
                        <select name="period" class="form-control">
                            <option value="">Toutes</option>
                            <option value="1" <?php echo e($filters['period'] == 1 ? 'selected' : ''); ?>>Période 1 (S1)</option>
                            <option value="2" <?php echo e($filters['period'] == 2 ? 'selected' : ''); ?>>Période 2 (S1)</option>
                            <option value="3" <?php echo e($filters['period'] == 3 ? 'selected' : ''); ?>>Période 3 (S2)</option>
                            <option value="4" <?php echo e($filters['period'] == 4 ? 'selected' : ''); ?>>Période 4 (S2)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="active" <?php echo e($filters['status'] == 'active' ? 'selected' : ''); ?>>Actif</option>
                            <option value="closed" <?php echo e($filters['status'] == 'closed' ? 'selected' : ''); ?>>Fermé</option>
                            <option value="draft" <?php echo e($filters['status'] == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
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
                            <th>Classe</th>
                            <th>Section</th>
                            <th>Matière</th>
                            <th>Période</th>
                            <th>Date Limite</th>
                            <th>Note Max</th>
                            <th>Statut</th>
                            <th>Soumissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($assignment->title); ?></strong></td>
                                <td><?php echo e($assignment->myClass ? ($assignment->myClass->full_name ?: $assignment->myClass->name) : 'N/A'); ?></td>
                                <td><?php echo e($assignment->section->name ?? 'N/A'); ?></td>
                                <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
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
                                    <small class="d-block text-muted mt-1">
                                        <?php echo e($assignment->period <= 2 ? 'Semestre 1' : 'Semestre 2'); ?>

                                    </small>
                                </td>
                                <td>
                                    <?php echo e($assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A'); ?>

                                    <?php if($assignment->due_date && $assignment->due_date->isPast()): ?>
                                        <span class="badge badge-danger ml-2">Expiré</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($assignment->max_score); ?></td>
                                <td>
                                    <?php if($assignment->status == 'active'): ?>
                                        <span class="badge badge-success">Actif</span>
                                    <?php elseif($assignment->status == 'closed'): ?>
                                        <span class="badge badge-danger">Fermé</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Brouillon</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo e($assignment->submissions->count()); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('assignments.show', $assignment->id)); ?>" class="btn btn-sm btn-primary" title="Voir">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('assignments.edit', $assignment->id)); ?>" class="btn btn-sm btn-info" title="Modifier">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <?php if(Qs::userIsTeamSA()): ?>
                                            <form method="POST" action="<?php echo e(route('assignments.destroy', $assignment->id)); ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')" title="Supprimer">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de <?php echo e($assignments->firstItem() ?? 0); ?> à <?php echo e($assignments->lastItem() ?? 0); ?> sur <?php echo e($assignments->total()); ?> résultats
                </div>
                <div>
                    <?php echo e($assignments->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun devoir trouvé. Cliquez sur "Créer un Devoir" pour commencer ou modifiez les filtres.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Load sections when class is selected
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        $('#section_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            $.ajax({
                url: '/assignments/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                },
                error: function() {
                    $('#section_id').html('<option value="">Erreur</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Toutes</option>');
        }
    });
    
    // Trigger on page load if class is selected
    <?php if($filters['my_class_id']): ?>
        $('#my_class_id').trigger('change');
        <?php if($filters['section_id']): ?>
            setTimeout(function() {
                $('#section_id').val('<?php echo e($filters["section_id"]); ?>');
            }, 500);
        <?php endif; ?>
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/assignments/index.blade.php ENDPATH**/ ?>