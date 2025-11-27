
<?php $__env->startSection('page_title', 'Bulletin Non Disponible'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header bg-warning">
        <h6 class="card-title">
            <i class="icon-lock mr-2"></i>Bulletin Non Disponible
        </h6>
    </div>

    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="icon-file-locked text-warning" style="font-size: 80px;"></i>
        </div>
        
        <h4 class="text-warning mb-3">Ce bulletin n'est pas encore publié</h4>
        
        <p class="text-muted mb-4">
            Le bulletin de 
            <strong>
                <?php if($type == 'period'): ?>
                    Période <?php echo e($period); ?>

                <?php else: ?>
                    Semestre <?php echo e($semester); ?>

                <?php endif; ?>
            </strong>
            n'est pas encore disponible pour consultation.<br>
            Veuillez patienter jusqu'à ce que l'administration le publie.
        </p>

        
        <?php if(count($publishedBulletins['periods']) > 0 || count($publishedBulletins['semesters']) > 0): ?>
            <div class="card border-success mx-auto" style="max-width: 500px;">
                <div class="card-header bg-success text-white">
                    <i class="icon-checkmark mr-2"></i>Bulletins Disponibles
                </div>
                <div class="card-body">
                    <?php if(count($publishedBulletins['periods']) > 0): ?>
                        <h6 class="text-muted mb-2">Périodes :</h6>
                        <div class="mb-3">
                            <?php $__currentLoopData = $publishedBulletins['periods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('student.grades.bulletin', ['type' => 'period', 'period' => $p])); ?>" 
                                   class="btn btn-outline-primary mr-2 mb-2">
                                    <i class="icon-file-text2 mr-1"></i>Période <?php echo e($p); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(count($publishedBulletins['semesters']) > 0): ?>
                        <h6 class="text-muted mb-2">Semestres :</h6>
                        <div>
                            <?php $__currentLoopData = $publishedBulletins['semesters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('student.grades.bulletin', ['type' => 'semester', 'semester' => $s])); ?>" 
                                   class="btn btn-outline-success mr-2 mb-2">
                                    <i class="icon-file-text2 mr-1"></i>Semestre <?php echo e($s); ?>

                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info mx-auto" style="max-width: 500px;">
                <i class="icon-info22 mr-2"></i>
                Aucun bulletin n'est encore publié pour cette année scolaire.
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="<?php echo e(route('student.grades.index')); ?>" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i>Retour à Mes Notes
            </a>
        </div>
    </div>
</div>


<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Nom :</strong> <?php echo e($student->name); ?></p>
                <p class="mb-0"><strong>Matricule :</strong> <?php echo e($student->code ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Classe :</strong> <?php echo e($studentRecord->my_class->name ?? 'N/A'); ?></p>
                <p class="mb-0"><strong>Année :</strong> <?php echo e($year); ?></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/grades/bulletin_not_published.blade.php ENDPATH**/ ?>