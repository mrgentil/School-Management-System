
<?php $__env->startSection('page_title', 'Statistiques des étudiants'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Statistiques des étudiants par classe, division, section et option</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form action="<?php echo e(route('students.statistics')); ?>" method="get" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="my_class_id">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e(($filters['my_class_id'] ?? '') == $class->id ? 'selected' : ''); ?>><?php echo e($class ? ($class->full_name ?: $class->name) : 'N/A'); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id">Division</label>
                        <select name="section_id" id="section_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>" <?php echo e(($filters['section_id'] ?? '') == $section->id ? 'selected' : ''); ?>>
                                    <?php echo e($section->my_class ? ($section->my_class->full_name ?: $section->my_class->name) : 'N/A'); ?> - <?php echo e($section->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="academic_section_id">Section académique</label>
                        <select name="academic_section_id" id="academic_section_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $academic_sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>" <?php echo e(($filters['academic_section_id'] ?? '') == $section->id ? 'selected' : ''); ?>><?php echo e($section->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="option_id">Option</label>
                        <select name="option_id" id="option_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($option->id); ?>" <?php echo e(($filters['option_id'] ?? '') == $option->id ? 'selected' : ''); ?>>
                                    <?php echo e($option->name); ?> (<?php echo e(optional($option->academic_section)->name); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-md-3">
                        <label for="session">Année scolaire</label>
                        <select name="session" id="session" class="form-control select-search">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($session); ?>" <?php echo e(($filters['session'] ?? '') == $session ? 'selected' : ''); ?>><?php echo e($session); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-9 text-right align-self-end">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="<?php echo e(route('students.statistics')); ?>" class="btn btn-light">Réinitialiser</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Division</th>
                        <th>Section académique</th>
                        <th>Option</th>
                        <th class="text-center">Nombre d'étudiants</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($row->class_name ?? '-'); ?></td>
                            <td><?php echo e($row->division_name ?? '-'); ?></td>
                            <td><?php echo e($row->academic_section_name ?? '-'); ?></td>
                            <td><?php echo e($row->option_name ?? '-'); ?></td>
                            <td class="text-center"><strong><?php echo e($row->total_students); ?></strong></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune donnée pour les filtres sélectionnés.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/students/statistics.blade.php ENDPATH**/ ?>