
<?php $__env->startSection('page_title', 'Progression des Élèves'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-stats-growth mr-2"></i> Rapport de Progression
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('student_progress.index')); ?>">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Classe</strong></label>
                        <select name="class_id" class="form-control select" id="class_select" required>
                            <option value="">-- Sélectionner une classe --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->full_name ?? $class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Section (optionnel)</strong></label>
                        <select name="section_id" class="form-control select" id="section_select">
                            <option value="">-- Toutes les sections --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4 mr-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if($students->count() > 0): ?>
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0">
            <i class="icon-users mr-2"></i> Élèves trouvés (<?php echo e($students->count()); ?>)
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Classe</th>
                    <th>Section</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <img src="<?php echo e($student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg')); ?>" 
                                 class="rounded-circle" width="40" height="40">
                        </td>
                        <td>
                            <strong><?php echo e($student->user->name); ?></strong>
                            <br><small class="text-muted"><?php echo e($student->adm_no); ?></small>
                        </td>
                        <td><?php echo e($student->my_class->full_name ?? $student->my_class->name ?? 'N/A'); ?></td>
                        <td><?php echo e($student->section->name ?? 'N/A'); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('student_progress.show', $student->user_id)); ?>" 
                               class="btn btn-info btn-sm">
                                <i class="icon-stats-growth mr-1"></i> Voir Progression
                            </a>
                            <a href="<?php echo e(route('student_progress.pdf', $student->user_id)); ?>" 
                               class="btn btn-danger btn-sm" target="_blank">
                                <i class="icon-file-pdf mr-1"></i> PDF
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php elseif(request('class_id')): ?>
<div class="alert alert-warning">
    <i class="icon-warning mr-2"></i>
    Aucun élève trouvé pour cette classe.
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    const classes = <?php echo json_encode($classes, 15, 512) ?>;
    
    document.getElementById('class_select').addEventListener('change', function() {
        const classId = this.value;
        const sectionSelect = document.getElementById('section_select');
        sectionSelect.innerHTML = '<option value="">-- Toutes les sections --</option>';
        
        if (classId) {
            const selectedClass = classes.find(c => c.id == classId);
            if (selectedClass && selectedClass.section) {
                selectedClass.section.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            }
        }
    });

    // Trigger on page load
    document.getElementById('class_select').dispatchEvent(new Event('change'));
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/student_progress/index.blade.php ENDPATH**/ ?>