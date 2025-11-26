
<?php $__env->startSection('page_title', 'Liste des Étudiants - Bulletins'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h5 class="card-title text-white">
            <i class="icon-users mr-2"></i> 
            Bulletins - <?php echo e($type == 'period' ? 'Période ' . $period : 'Semestre ' . $semester); ?> (<?php echo e($year); ?>)
        </h5>
        <div class="header-elements">
            <a href="<?php echo e(route('bulletins.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <?php if($students->count() > 0): ?>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge badge-info badge-lg"><?php echo e($students->count()); ?> étudiants</span>
                </div>
                <div>
                    <form action="<?php echo e(route('bulletins.batch')); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="my_class_id" value="<?php echo e($class_id); ?>">
                        <input type="hidden" name="section_id" value="<?php echo e($section_id); ?>">
                        <input type="hidden" name="type" value="<?php echo e($type); ?>">
                        <input type="hidden" name="period" value="<?php echo e($period); ?>">
                        <input type="hidden" name="semester" value="<?php echo e($semester); ?>">
                        <button type="submit" class="btn btn-success">
                            <i class="icon-file-zip mr-2"></i> Télécharger tous les bulletins (ZIP)
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable-basic">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Photo</th>
                            <th>Nom Complet</th>
                            <th>Matricule</th>
                            <th>Classe</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td class="text-center">
                                    <img src="<?php echo e($student->user->photo ?? asset('global_assets/images/user.png')); ?>" 
                                         width="40" height="40" class="rounded-circle" 
                                         alt="<?php echo e($student->user->name); ?>"
                                         onerror="this.src='<?php echo e(asset('global_assets/images/user.png')); ?>'">
                                </td>
                                <td>
                                    <strong><?php echo e($student->user->name); ?></strong>
                                </td>
                                <td><?php echo e($student->adm_no); ?></td>
                                <td><?php echo e($student->my_class->full_name ?? $student->my_class->name); ?></td>
                                <td>
                                    
                                    <a href="<?php echo e(route('bulletins.preview', $student->user_id)); ?>?type=<?php echo e($type); ?>&period=<?php echo e($period); ?>&semester=<?php echo e($semester); ?>" 
                                       class="btn btn-info btn-sm" title="Prévisualiser" target="_blank">
                                        <i class="icon-eye"></i>
                                    </a>
                                    
                                    
                                    <a href="<?php echo e(route('bulletins.generate', $student->user_id)); ?>?type=<?php echo e($type); ?>&period=<?php echo e($period); ?>&semester=<?php echo e($semester); ?>" 
                                       class="btn btn-primary btn-sm" title="Télécharger PDF" target="_blank">
                                        <i class="icon-file-pdf"></i>
                                    </a>
                                    
                                    
                                    <a href="<?php echo e(route('bulletins.preview', $student->user_id)); ?>?type=<?php echo e($type); ?>&period=<?php echo e($period); ?>&semester=<?php echo e($semester); ?>&print=1" 
                                       class="btn btn-secondary btn-sm" title="Imprimer" target="_blank">
                                        <i class="icon-printer"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                Aucun étudiant trouvé dans cette classe.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/bulletins/students.blade.php ENDPATH**/ ?>