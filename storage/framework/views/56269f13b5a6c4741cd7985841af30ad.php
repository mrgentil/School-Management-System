
<?php $__env->startSection('page_title', 'Options'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Gérer les options</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form method="post" action="<?php echo e(route('options.store')); ?>" class="mb-3">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Section académique <span class="text-danger">*</span></label>
                            <select name="academic_section_id" class="form-control select-search" required>
                                <option value=""></option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sec->id); ?>"><?php echo e($sec->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nom de l'option <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                </div>
                <div class="form-row">
                    <div class="col-md-2 offset-md-10 text-right">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Option</th>
                        <th>Section académique</th>
                        <th>Code</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($opt->name); ?></td>
                            <td><?php echo e(optional($opt->academic_section)->name); ?></td>
                            <td><?php echo e($opt->code); ?></td>
                            <td>
                                <?php if($opt->active): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form method="post" action="<?php echo e(route('options.update', $opt->id)); ?>" class="d-inline-block mr-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="academic_section_id" value="<?php echo e($opt->academic_section_id); ?>">
                                    <input type="hidden" name="name" value="<?php echo e($opt->name); ?>">
                                    <input type="hidden" name="code" value="<?php echo e($opt->code); ?>">
                                    <input type="hidden" name="active" value="<?php echo e($opt->active ? 1 : 0); ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Basculer statut</button>
                                </form>
                                <form method="post" action="<?php echo e(route('options.destroy', $opt->id)); ?>" class="d-inline-block" onsubmit="return confirm('Supprimer cette option ?');">
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

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/options/index.blade.php ENDPATH**/ ?>