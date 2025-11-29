
<?php $__env->startSection('page_title', 'Professeur: ' . $teacher->name); ?>

<?php $__env->startSection('content'); ?>
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?php echo e(Qs::getUserPhoto($teacher->photo)); ?>" 
                     class="rounded-circle mr-3" 
                     style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h4 class="mb-0"><?php echo e($teacher->name); ?></h4>
                    <small class="opacity-75"><?php echo e($teacher->email); ?> | <?php echo e($teacher->code); ?></small>
                </div>
            </div>
            <div>
                <a href="<?php echo e(route('teachers.management.edit', $teacher->id)); ?>" class="btn btn-light">
                    <i class="icon-pencil mr-1"></i> Modifier Attributions
                </a>
                <a href="<?php echo e(route('teachers.management.index')); ?>" class="btn btn-outline-light">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-user mr-2"></i> Informations</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Téléphone:</strong></td>
                        <td><?php echo e($teacher->phone ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Adresse:</strong></td>
                        <td><?php echo e($teacher->address ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Titulaire de:</strong></td>
                        <td>
                            <?php if($titularClass): ?>
                                <span class="badge badge-success"><?php echo e($titularClass->name); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Aucune classe</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques</h6>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-6">
                        <h3 class="text-primary mb-0"><?php echo e($teachingClasses->count()); ?></h3>
                        <small class="text-muted">Classes</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success mb-0"><?php echo e($teacher->subjects->count()); ?></h3>
                        <small class="text-muted">Matières</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h6 class="card-title mb-0"><i class="icon-library mr-2"></i> Classes et Matières Attribuées</h6>
            </div>
            <div class="card-body">
                <?php if($teacher->subjects->count() > 0): ?>
                    <?php
                        $subjectsByClass = $teacher->subjects->groupBy('my_class_id');
                    ?>
                    
                    <?php $__currentLoopData = $subjectsByClass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $subjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $class = $subjects->first()->my_class; ?>
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">
                                <i class="icon-graduation mr-1"></i> 
                                <strong><?php echo e($class->name ?? 'Classe inconnue'); ?></strong>
                                <span class="badge badge-info ml-2"><?php echo e($subjects->count()); ?> matière(s)</span>
                            </h6>
                            <div class="row">
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                            <i class="icon-book text-primary mr-2"></i>
                                            <span><?php echo e($subject->name); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="icon-warning d-block mb-2" style="font-size: 48px;"></i>
                        <h5>Aucune matière attribuée</h5>
                        <p>Ce professeur n'a pas encore de classes/matières assignées.</p>
                        <a href="<?php echo e(route('teachers.management.edit', $teacher->id)); ?>" class="btn btn-primary">
                            <i class="icon-plus3 mr-1"></i> Attribuer des matières
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/teachers/show.blade.php ENDPATH**/ ?>