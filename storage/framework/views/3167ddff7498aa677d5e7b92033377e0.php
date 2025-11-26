
<?php $__env->startSection('page_title', 'Relevés de Notes'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header header-elements-inline bg-primary">
            <h5 class="card-title text-white"><i class="icon-books mr-2"></i> Consulter les Relevés de Notes</h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
                <form method="post" action="<?php echo e(route('marks.bulk_select')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-10">
                            <fieldset>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="my_class_id" class="col-form-label font-weight-bold">Classe :</label>
                                            <select required onchange="getClassSections(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                                <option value="">-- Sélectionner une classe --</option>
                                                <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e(($selected && $my_class_id == $c->id) ? 'selected' : ''); ?> value="<?php echo e($c->id); ?>"><?php echo e($c->full_name ?: $c->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="section_id" class="col-form-label font-weight-bold">Section :</label>
                                            <select required id="section_id" name="section_id" data-placeholder="Sélectionner d'abord la classe" class="form-control select">
                                        <?php if($selected): ?>
                                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e(($section_id == $s->id ? 'selected' : '')); ?> value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                        </div>

                        <div class="col-md-2 mt-4">
                            <div class="text-right mt-1">
                                <button type="submit" class="btn btn-primary">Afficher <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>
    <?php if($selected): ?>
    <div class="card">
        <div class="card-header bg-success">
            <h6 class="card-title text-white mb-0">
                <i class="icon-users mr-2"></i>
                Liste des Étudiants (<?php echo e($students->count()); ?>)
            </h6>
        </div>
        <div class="card-body">
            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>N°</th>
                    <th>Photo</th>
                    <th>Nom de l'Étudiant</th>
                    <th>Matricule</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="<?php echo e($s->user->photo); ?>" alt="photo"></td>
                        <td><?php echo e($s->user->name); ?></td>
                        <td><?php echo e($s->adm_no); ?></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="<?php echo e(route('marks.year_select', Qs::hash($s->user_id))); ?>">
                                <i class="icon-file-text2 mr-1"></i> Voir Relevé
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/bulk.blade.php ENDPATH**/ ?>