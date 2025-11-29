
<?php $__env->startSection('page_title', 'Gestion des Professeurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <h4 class="mb-0"><i class="icon-users4 mr-2"></i> Gestion des Professeurs</h4>
        <small class="opacity-75">Attribuez les classes et matières aux professeurs</small>
    </div>
</div>


<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-info text-white text-center py-3">
            <h3 class="mb-0"><?php echo e($stats['total_teachers']); ?></h3>
            <small>Total Professeurs</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white text-center py-3">
            <h3 class="mb-0"><?php echo e($stats['teachers_with_classes']); ?></h3>
            <small>Avec Classes</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white text-center py-3">
            <h3 class="mb-0"><?php echo e($stats['teachers_without_classes']); ?></h3>
            <small>Sans Classes</small>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Liste des Professeurs</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable-basic">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Classes</th>
                        <th>Matières</th>
                        <th>Titulaire de</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $teacherClasses = $teacher->subjects->pluck('my_class')->unique('id');
                            $titularClass = \App\Models\MyClass::where('teacher_id', $teacher->id)->first();
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo e(Qs::getUserPhoto($teacher->photo)); ?>" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <strong><?php echo e($teacher->name); ?></strong>
                                <br><small class="text-muted"><?php echo e($teacher->code); ?> | <span class="text-primary">ID: <?php echo e($teacher->id); ?></span></small>
                            </td>
                            <td><?php echo e($teacher->email); ?></td>
                            <td>
                                <?php if($teacherClasses->count() > 0): ?>
                                    <?php $__currentLoopData = $teacherClasses->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-info"><?php echo e($class->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($teacherClasses->count() > 3): ?>
                                        <span class="badge badge-secondary">+<?php echo e($teacherClasses->count() - 3); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Aucune</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?php echo e($teacher->subjects->count()); ?> matière(s)</span>
                            </td>
                            <td>
                                <?php if($titularClass): ?>
                                    <span class="badge badge-success"><?php echo e($titularClass->name); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('teachers.management.show', $teacher->id)); ?>" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="<?php echo e(route('teachers.management.edit', $teacher->id)); ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier attributions">
                                    <i class="icon-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/teachers/management.blade.php ENDPATH**/ ?>