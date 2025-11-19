
<?php $__env->startSection('page_title', 'Sections académiques'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Gérer les sections académiques</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form method="post" action="<?php echo e(route('academic_sections.store')); ?>" class="mb-3">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nom de la section académique <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-group mt-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="active" value="1" class="form-check-input" checked>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($sec->name); ?></td>
                            <td><?php echo e($sec->code); ?></td>
                            <td>
                                <?php if($sec->active): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form method="post" action="<?php echo e(route('academic_sections.update', $sec->id)); ?>" class="d-inline-block mr-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="active" value="<?php echo e($sec->active ? 1 : 0); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Basculer statut</button>
                                </form>
                                <form method="post" action="<?php echo e(route('academic_sections.destroy', $sec->id)); ?>" class="d-inline-block" onsubmit="return confirm('Supprimer cette section académique ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/academic_sections/index.blade.php ENDPATH**/ ?>