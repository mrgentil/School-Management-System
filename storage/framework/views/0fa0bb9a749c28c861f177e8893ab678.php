
<?php $__env->startSection('page_title', 'Modification Rapide des Notes'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="icon-pencil5 mr-2"></i>Modification Rapide des Notes</h5>
    </div>
    <div class="card-body">
        
        <?php if(session('flash_success')): ?>
            <div class="alert alert-success"><?php echo e(session('flash_success')); ?></div>
        <?php endif; ?>
        
        <?php if(session('flash_danger')): ?>
            <div class="alert alert-danger"><?php echo e(session('flash_danger')); ?></div>
        <?php endif; ?>

        <div class="alert alert-info">
            <i class="icon-info22 mr-2"></i>
            <strong>Instructions :</strong> Sélectionnez la classe et la matière pour afficher et modifier les notes de tous les étudiants.
        </div>

        <form method="GET" action="<?php echo e(route('marks.quick_edit')); ?>" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Classe</label>
                        <select name="class_id" id="class_id" class="form-control select" required onchange="this.form.submit()">
                            <option value="">-- Sélectionner une classe --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <?php if(request('class_id') && isset($subjects)): ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Matière</label>
                        <select name="subject_id" id="subject_id" class="form-control select" required onchange="this.form.submit()">
                            <option value="">-- Sélectionner une matière --</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search4 mr-2"></i>Afficher les Notes
                    </button>
                </div>
            </div>
        </form>

        <?php if(isset($marks) && $marks->count() > 0): ?>
        <div class="card border-primary">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="icon-book mr-2"></i>
                    <strong><?php echo e($selectedSubject->name); ?></strong> - 
                    <?php echo e($selectedClass->name); ?>

                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('marks.quick_update')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>">
                    <input type="hidden" name="subject_id" value="<?php echo e(request('subject_id')); ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Étudiant</th>
                                    <th width="10%">P1 (/20)</th>
                                    <th width="10%">P2 (/20)</th>
                                    <th width="10%">P3 (/20)</th>
                                    <th width="10%">P4 (/20)</th>
                                    <th width="10%">TCA (/20)</th>
                                    <th width="10%">Examen S1</th>
                                    <th width="10%">Examen S2</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $marks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td><strong><?php echo e($mark->user->name ?? 'N/A'); ?></strong></td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[<?php echo e($mark->id); ?>][t1]" 
                                               value="<?php echo e($mark->t1); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[<?php echo e($mark->id); ?>][t2]" 
                                               value="<?php echo e($mark->t2); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[<?php echo e($mark->id); ?>][t3]" 
                                               value="<?php echo e($mark->t3); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[<?php echo e($mark->id); ?>][t4]" 
                                               value="<?php echo e($mark->t4); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[<?php echo e($mark->id); ?>][tca]" 
                                               value="<?php echo e($mark->tca); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="100" 
                                               name="marks[<?php echo e($mark->id); ?>][s1_exam]" 
                                               value="<?php echo e($mark->s1_exam); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="100" 
                                               name="marks[<?php echo e($mark->id); ?>][s2_exam]" 
                                               value="<?php echo e($mark->s2_exam); ?>" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="icon-checkmark3 mr-2"></i>Enregistrer les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php elseif(request('class_id') && request('subject_id')): ?>
        <div class="alert alert-warning">
            <i class="icon-warning22 mr-2"></i>
            Aucune note trouvée pour cette classe et cette matière. Les notes seront créées automatiquement quand vous les saisirez.
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/quick_edit.blade.php ENDPATH**/ ?>