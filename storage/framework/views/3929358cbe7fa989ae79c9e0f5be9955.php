
<?php $__env->startSection('page_title', 'Modifier les Notes'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-warning">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">
                <i class="icon-pencil mr-2"></i>
                Modifier les Notes - <strong><?php echo e($subject->name); ?></strong> | 
                <?php echo e($class->full_name ?: $class->name); ?>

                <?php if($class->section && $class->section->count() > 0): ?>
                    (<?php echo e($class->section->first()->name); ?>)
                <?php endif; ?>
                <?php if($period != 'all'): ?>
                    | <span class="badge badge-dark">Période <?php echo e($period); ?></span>
                <?php else: ?>
                    | <span class="badge badge-info">Toutes les périodes</span>
                <?php endif; ?>
            </h5>
            <a href="<?php echo e(route('marks.index')); ?>" class="btn btn-dark btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <?php if(session('flash_success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-checkmark3 mr-2"></i><?php echo e(session('flash_success')); ?>

            </div>
        <?php endif; ?>
        
        <?php if($marks->isEmpty()): ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun étudiant trouvé dans cette classe pour l'année en cours.
            </div>
        <?php else: ?>
            <div class="alert alert-success border-0 mb-3">
                <i class="icon-users mr-2"></i>
                <strong><?php echo e($marks->count()); ?> étudiant(s)</strong> dans cette classe
            </div>
            <form method="POST" action="<?php echo e(route('marks.modify_update')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="class_id" value="<?php echo e($class->id); ?>">
                <input type="hidden" name="subject_id" value="<?php echo e($subject->id); ?>">
                <input type="hidden" name="period" value="<?php echo e($period); ?>">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="25%">Nom de l'Étudiant</th>
                                <?php if($period == 'all' || $period == '1'): ?>
                                    <th width="10%" class="text-center bg-primary">P1</th>
                                <?php endif; ?>
                                <?php if($period == 'all' || $period == '2'): ?>
                                    <th width="10%" class="text-center bg-primary">P2</th>
                                <?php endif; ?>
                                <?php if($period == 'all' || $period == '3'): ?>
                                    <th width="10%" class="text-center bg-primary">P3</th>
                                <?php endif; ?>
                                <?php if($period == 'all' || $period == '4'): ?>
                                    <th width="10%" class="text-center bg-primary">P4</th>
                                <?php endif; ?>
                                <?php if($period == 'all'): ?>
                                    <th width="10%" class="text-center bg-success">TCA</th>
                                    <th width="10%" class="text-center bg-info">Exam S1</th>
                                    <th width="10%" class="text-center bg-info">Exam S2</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $marks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <strong><?php echo e($mark->user->name ?? 'N/A'); ?></strong>
                                    <br><small class="text-muted"><?php echo e($mark->user->student_record->adm_no ?? ''); ?></small>
                                </td>
                                
                                <?php if($period == 'all' || $period == '1'): ?>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][t1]" 
                                           value="<?php echo e($mark->t1); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <?php endif; ?>
                                
                                <?php if($period == 'all' || $period == '2'): ?>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][t2]" 
                                           value="<?php echo e($mark->t2); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <?php endif; ?>
                                
                                <?php if($period == 'all' || $period == '3'): ?>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][t3]" 
                                           value="<?php echo e($mark->t3); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <?php endif; ?>
                                
                                <?php if($period == 'all' || $period == '4'): ?>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][t4]" 
                                           value="<?php echo e($mark->t4); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <?php endif; ?>
                                
                                <?php if($period == 'all'): ?>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][tca]" 
                                           value="<?php echo e($mark->tca); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][s1_exam]" 
                                           value="<?php echo e($mark->s1_exam); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[<?php echo e($mark->id); ?>][s2_exam]" 
                                           value="<?php echo e($mark->s2_exam); ?>" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="icon-checkmark3 mr-2"></i>Enregistrer les Modifications
                    </button>
                    <a href="<?php echo e(route('marks.index')); ?>" class="btn btn-secondary btn-lg ml-2">
                        <i class="icon-cross2 mr-2"></i>Annuler
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/modify.blade.php ENDPATH**/ ?>