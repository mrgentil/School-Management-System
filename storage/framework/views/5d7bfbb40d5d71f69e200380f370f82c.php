
<?php $__env->startSection('page_title', 'Générateur de Données de Test'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="icon-database mr-2"></i>Générateur de Données de Test</h5>
    </div>
    <div class="card-body">
        
        <?php if(session('flash_success')): ?>
            <div class="alert alert-success">
                <pre style="white-space: pre-wrap; margin: 0;"><?php echo e(session('flash_success')); ?></pre>
            </div>
        <?php endif; ?>
        
        <?php if(session('flash_danger')): ?>
            <div class="alert alert-danger"><?php echo e(session('flash_danger')); ?></div>
        <?php endif; ?>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($classes->count()); ?></h2>
                        <small>Classes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($students); ?></h2>
                        <small>Étudiants</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($assignments); ?></h2>
                        <small>Devoirs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($marks); ?></h2>
                        <small>Notes</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info">
            <h6><i class="icon-info22 mr-2"></i>Ce générateur va créer pour chaque classe :</h6>
            <ul class="mb-0">
                <li><strong>12 étudiants</strong> avec noms congolais réalistes</li>
                <li><strong>8 matières</strong> : Math, Français, Anglais, Physique, Chimie, Info, Histoire, Géo</li>
                <li><strong>Configurations de notes</strong> avec cotes max par matière</li>
                <li><strong>64 devoirs</strong> (2 par matière × 4 périodes)</li>
                <li><strong>Soumissions</strong> avec notes pour 85% des élèves</li>
                <li><strong>Notes d'interrogations</strong> (t1, t2, t3, t4) et TCA</li>
                <li><strong>2 examens semestriels</strong> avec notes</li>
            </ul>
        </div>
        
        <h6 class="font-weight-bold mb-3">Classes disponibles :</h6>
        <table class="table table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>ID</th>
                    <th>Nom de la Classe</th>
                    <th>Sections</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($class->id); ?></td>
                    <td><strong><?php echo e($class->name); ?></strong></td>
                    <td>
                        <?php if($class->section->count()): ?>
                            <?php echo e($class->section->pluck('name')->implode(', ')); ?>

                        <?php else: ?>
                            <span class="text-muted">Aucune (sera créée: A)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" class="text-center text-danger">
                        <i class="icon-warning22 mr-2"></i>Aucune classe trouvée. Veuillez d'abord créer des classes.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <?php if($classes->count() > 0): ?>
        <div class="text-center mt-4">
            <form action="<?php echo e(route('seeder.seed')); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir générer les données de test? Cette opération peut prendre quelques secondes.');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-lg btn-success">
                    <i class="icon-play3 mr-2"></i>Générer les Données de Test
                </button>
            </form>
        </div>
        <?php endif; ?>
        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/seeder/index.blade.php ENDPATH**/ ?>