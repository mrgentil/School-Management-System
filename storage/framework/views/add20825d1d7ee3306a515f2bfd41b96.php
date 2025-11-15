<?php $__env->startSection('page_title', 'Ressources Pédagogiques'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Ressources Pédagogiques</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher une ressource..." value="<?php echo e($search); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="subject_id" class="form-control select">
                            <option value="">Toutes les matières</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($subject_id == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="file_type" class="form-control select">
                            <option value="">Tous les types</option>
                            <option value="pdf" <?php echo e($file_type == 'pdf' ? 'selected' : ''); ?>>PDF</option>
                            <option value="doc" <?php echo e($file_type == 'doc' ? 'selected' : ''); ?>>Document Word</option>
                            <option value="ppt" <?php echo e($file_type == 'ppt' ? 'selected' : ''); ?>>Présentation</option>
                            <option value="video" <?php echo e($file_type == 'video' ? 'selected' : ''); ?>>Vidéo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="icon-search4"></i> Rechercher</button>
                </div>
            </div>
        </form>

        <!-- Materials Grid -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="mr-3">
                                <i class="<?php echo e($material->file_icon); ?> icon-2x text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">
                                    <a href="<?php echo e(route('student.materials.show', $material->id)); ?>"><?php echo e($material->title); ?></a>
                                </h6>
                                <p class="mb-1 text-muted"><?php echo e($material->subject->name ?? 'Général'); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><?php echo e($material->file_size_formatted); ?></small>
                                    <a href="<?php echo e(route('student.materials.download', $material->id)); ?>" 
                                       class="btn btn-sm btn-success">
                                        <i class="icon-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php if($material->description): ?>
                        <p class="mt-2 text-muted small"><?php echo e(Str::limit($material->description, 100)); ?></p>
                        <?php endif; ?>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="icon-user"></i> <?php echo e($material->uploader->name ?? 'Système'); ?> - 
                                <i class="icon-calendar"></i> <?php echo e($material->created_at->format('d/m/Y')); ?> - 
                                <i class="icon-download"></i> <?php echo e($material->download_count); ?> téléchargements
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-file-text2 icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune ressource trouvée</h5>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($materials->hasPages()): ?>
        <div class="d-flex justify-content-center">
            <?php echo e($materials->appends(request()->query())->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/student/materials/index.blade.php ENDPATH**/ ?>