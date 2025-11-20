<?php $__env->startSection('page_title', 'Supports Pédagogiques'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Bibliothèque de Supports Pédagogiques</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <!-- Filtres et recherche -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
                           value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="class_id" class="form-control select">
                        <option value="">Toutes les classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class ? ($class->full_name ?: $class->name) : 'N/A'); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="subject_id" class="form-control select">
                        <option value="">Toutes les matières</option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                <?php echo e($subject->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search4 mr-2"></i>Filtrer
                    </button>
                    <?php if(Qs::userIsTeamSAT()): ?>
                    <a href="<?php echo e(route('study-materials.create')); ?>" class="btn btn-success ml-2">
                        <i class="icon-plus2 mr-2"></i>Ajouter
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <!-- Liste des supports -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <i class="<?php echo e($material->file_icon); ?> icon-2x text-primary"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="mb-1"><?php echo e($material->title); ?></h6>
                                <p class="text-muted mb-2 small"><?php echo e(\Illuminate\Support\Str::limit($material->description, 80)); ?></p>
                                
                                <div class="mb-2">
                                    <?php if($material->myClass): ?>
                                    <span class="badge badge-light mr-1"><?php echo e($material->myClass->name); ?></span>
                                    <?php endif; ?>
                                    <?php if($material->subject): ?>
                                    <span class="badge badge-primary mr-1"><?php echo e($material->subject->name); ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span><?php echo e($material->file_size_formatted); ?></span>
                                    <span><?php echo e($material->download_count); ?> téléchargements</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Par <?php echo e($material->uploader->name); ?>

                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('study-materials.download', $material->id)); ?>" 
                                   class="btn btn-outline-success" title="Télécharger">
                                    <i class="icon-download4"></i>
                                </a>
                                <a href="<?php echo e(route('study-materials.show', $material->id)); ?>" 
                                   class="btn btn-outline-primary" title="Voir détails">
                                    <i class="icon-eye"></i>
                                </a>
                                <?php if(Qs::userIsTeamSAT()): ?>
                                <a href="<?php echo e(route('study-materials.edit', $material->id)); ?>" 
                                   class="btn btn-outline-warning" title="Modifier">
                                    <i class="icon-pencil"></i>
                                </a>
                                <form method="post" action="<?php echo e(route('study-materials.destroy', $material->id)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Êtes-vous sûr ?')" title="Supprimer">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-file-empty icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun support pédagogique</h5>
                    <p class="text-muted">Il n'y a actuellement aucun support pédagogique disponible.</p>
                    <?php if(Qs::userIsTeamSAT()): ?>
                    <a href="<?php echo e(route('study-materials.create')); ?>" class="btn btn-primary">
                        <i class="icon-plus2 mr-2"></i>Ajouter le premier support
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php echo e($materials->appends(request()->query())->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/study_materials/index.blade.php ENDPATH**/ ?>