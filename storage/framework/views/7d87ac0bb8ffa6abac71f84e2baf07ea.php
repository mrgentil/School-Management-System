
<?php $__env->startSection('page_title', 'Gestion des Emplois du Temps'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="alert alert-info alert-styled-left alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <span class="font-weight-semibold">📖 Guide Rapide:</span>
        <ol class="mb-0 mt-2">
            <li><strong>Créer</strong> un emploi du temps en donnant un nom et en sélectionnant une classe</li>
            <li><strong>Gérer</strong> l'emploi du temps pour ajouter des créneaux horaires (ex: 08:00 AM - 09:00 AM)</li>
            <li><strong>Assigner</strong> les matières à chaque créneau pour chaque jour de la semaine</li>
            <li><strong>Voir</strong> l'emploi du temps complet pour vérifier</li>
        </ol>
        <a href="<?php echo e(asset('GUIDE_EMPLOI_DU_TEMPS.md')); ?>" class="alert-link" target="_blank">📚 Voir le guide complet</a>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">📅 Gestion des Emplois du Temps</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <?php if(Qs::userIsTeamSA()): ?>
                <li class="nav-item"><a href="#add-tt" class="nav-link active" data-toggle="tab">➕ Créer un Emploi du Temps</a></li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">📋 Voir les Emplois du Temps</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="#ttr<?php echo e($mc->id); ?>" class="dropdown-item" data-toggle="tab"><?php echo e($mc->name); ?></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </li>
            </ul>


            <div class="tab-content">

                <?php if(Qs::userIsTeamSA()): ?>
                <div class="tab-pane fade show active" id="add-tt">
                   <div class="col-md-8">
                       <form class="ajax-store" method="post" action="<?php echo e(route('ttr.store')); ?>">
                           <?php echo csrf_field(); ?>
                           <div class="form-group row">
                               <label class="col-lg-3 col-form-label font-weight-semibold">Nom <span class="text-danger">*</span></label>
                               <div class="col-lg-9">
                                   <input name="name" value="<?php echo e(old('name')); ?>" required type="text" class="form-control" placeholder="Ex: Emploi du temps Classe 1A - Trimestre 1">
                                   <span class="form-text text-muted">Donnez un nom descriptif pour identifier facilement cet emploi du temps</span>
                               </div>
                           </div>

                           <div class="form-group row">
                               <label for="my_class_id" class="col-lg-3 col-form-label font-weight-semibold">Classe <span class="text-danger">*</span></label>
                               <div class="col-lg-9">
                                   <select required data-placeholder="Sélectionner une classe" class="form-control select" name="my_class_id" id="my_class_id">
                                       <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <option <?php echo e(old('my_class_id') == $mc->id ? 'selected' : ''); ?> value="<?php echo e($mc->id); ?>"><?php echo e($mc->name); ?></option>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </select>
                               </div>
                           </div>

                           <div class="form-group row">
                               <label for="exam_id" class="col-lg-3 col-form-label font-weight-semibold">Type</label>
                               <div class="col-lg-9">
                                   <select class="select form-control" name="exam_id" id="exam_id">
                                       <option value="">📚 Emploi du temps de classe (normal)</option>
                                       <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <option <?php echo e(old('exam_id') == $ex->id ? 'selected' : ''); ?> value="<?php echo e($ex->id); ?>"><?php echo e($ex->name); ?></option>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </select>
                               </div>
                           </div>


                           <div class="text-right">
                               <button id="ajax-btn" type="submit" class="btn btn-primary btn-lg">✅ Créer l'Emploi du Temps <i class="icon-paperplane ml-2"></i></button>
                           </div>
                       </form>
                   </div>

                </div>
                <?php endif; ?>

                <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="tab-pane fade" id="ttr<?php echo e($mc->id); ?>">                         <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom</th>
                                <th>Classe</th>
                                <th>Type</th>
                                <th>Année</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $tt_records->where('my_class_id', $mc->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ttr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($ttr->name); ?></td>
                                    <td><?php echo e($ttr->my_class ? ($ttr->my_class->full_name ?: $ttr->my_class->name) : 'N/A'); ?></td>
                                    <td><?php echo e(($ttr->exam_id) ? '📝 '.$ttr->exam->name : '📚 Emploi du temps de classe'); ?>

                                    <td><?php echo e($ttr->year); ?></td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    
                                                    <a href="<?php echo e(route('ttr.show', $ttr->id)); ?>" class="dropdown-item"><i class="icon-eye"></i> 👁️ Voir</a>

                                                    <?php if(Qs::userIsTeamSA()): ?>
                                                    
                                                    <a href="<?php echo e(route('ttr.manage', $ttr->id)); ?>" class="dropdown-item"><i class="icon-plus-circle2"></i> ⚙️ Gérer</a>
                                                    
                                                    <a href="<?php echo e(route('ttr.edit', $ttr->id)); ?>" class="dropdown-item"><i class="icon-pencil"></i> ✏️ Modifier</a>
                                                    <?php endif; ?>

                                                    
                                                    <?php if(Qs::userIsSuperAdmin()): ?>
                                                        <a id="<?php echo e($ttr->id); ?>" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> 🗑️ Supprimer</a>
                                                        <form method="post" id="item-delete-<?php echo e($ttr->id); ?>" action="<?php echo e(route('ttr.destroy', $ttr->id)); ?>" class="hidden"><?php echo csrf_field(); ?> <?php echo method_field('delete'); ?></form>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/timetables/index.blade.php ENDPATH**/ ?>