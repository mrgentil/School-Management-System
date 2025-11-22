
<?php $__env->startSection('page_title', 'Barèmes de Notation'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Gestion des Barèmes de Notation</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-grades" class="nav-link active" data-toggle="tab">Barèmes Existants</a></li>
                <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Ajouter un Barème</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-grades">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Grade</th>
                                <th>Type de Classe</th>
                                <th>Intervalle</th>
                                <th>Mention</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($gr->name); ?></td>
                                    <td><?php echo e($gr->class_type_id ? $class_types->where('id', $gr->class_type_id)->first()->name : ''); ?></td>
                                    <td><?php echo e($gr->mark_from.' - '.$gr->mark_to); ?></td>
                                    <td><?php echo e($gr->remark); ?></td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <?php if(Qs::userIsTeamSA()): ?>
                                                    
                                                    <a href="<?php echo e(route('grades.edit', $gr->id)); ?>" class="dropdown-item"><i class="icon-pencil"></i> Modifier</a>
                                                   <?php endif; ?>
                                                    <?php if(Qs::userIsSuperAdmin()): ?>
                                                    
                                                    <a id="<?php echo e($gr->id); ?>" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Supprimer</a>
                                                    <form method="post" id="item-delete-<?php echo e($gr->id); ?>" action="<?php echo e(route('grades.destroy', $gr->id)); ?>" class="hidden"><?php echo csrf_field(); ?> <?php echo method_field('delete'); ?></form>
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

                <div class="tab-pane fade" id="new-grade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span><strong>Instructions :</strong> Si le barème s'applique à tous les types de classe, sélectionnez <strong>NON APPLICABLE</strong>. Sinon, choisissez le type de classe spécifique concerné par ce barème.</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <form method="post" action="<?php echo e(route('grades.store')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Grade <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="<?php echo e(old('name')); ?>" required type="text" class="form-control text-uppercase" placeholder="Ex. A1, B2, C3">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Type de Classe</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="class_type_id" id="class_type_id">
                                            <option value="">Non Applicable</option>
                                         <?php $__currentLoopData = $class_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('class_type_id') == $ct->id ? 'selected' : ''); ?> value="<?php echo e($ct->id); ?>"><?php echo e($ct->name); ?></option>
                                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Note Minimum <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input min="0" max="20" name="mark_from" value="<?php echo e(old('mark_from')); ?>" required type="number" step="0.01" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Note Maximum <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input min="0" max="20" name="mark_to" value="<?php echo e(old('mark_to')); ?>" required type="number" step="0.01" class="form-control" placeholder="20">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Mention</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="remark" id="remark">
                                            <option value="">Sélectionner une mention...</option>
                                            <?php $__currentLoopData = Mk::getRemarks(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('remark') == $rem ? 'selected' : ''); ?> value="<?php echo e($rem); ?>"><?php echo e($rem); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Enregistrer le Barème <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title"><i class="icon-info22 mr-2"></i>Barèmes Suggérés (Système RDC)</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-primary">Barème Standard sur 20 :</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Intervalle</th>
                                                    <th>Mention</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-success text-white">
                                                    <td><strong>A1</strong></td>
                                                    <td>18 - 20</td>
                                                    <td>Excellent</td>
                                                </tr>
                                                <tr class="bg-info text-white">
                                                    <td><strong>A2</strong></td>
                                                    <td>16 - 17.99</td>
                                                    <td>Très Bien</td>
                                                </tr>
                                                <tr class="bg-primary text-white">
                                                    <td><strong>B1</strong></td>
                                                    <td>14 - 15.99</td>
                                                    <td>Bien</td>
                                                </tr>
                                                <tr class="bg-secondary text-white">
                                                    <td><strong>B2</strong></td>
                                                    <td>12 - 13.99</td>
                                                    <td>Assez Bien</td>
                                                </tr>
                                                <tr class="bg-warning text-dark">
                                                    <td><strong>C</strong></td>
                                                    <td>10 - 11.99</td>
                                                    <td>Passable</td>
                                                </tr>
                                                <tr class="bg-danger text-white">
                                                    <td><strong>D</strong></td>
                                                    <td>8 - 9.99</td>
                                                    <td>Insuffisant</td>
                                                </tr>
                                                <tr class="bg-dark text-white">
                                                    <td><strong>E</strong></td>
                                                    <td>0 - 7.99</td>
                                                    <td>Très Insuffisant</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6 class="text-success">Conseils :</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Utilisez des intervalles sans chevauchement</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>La note de passage est généralement 10/20</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Adaptez selon votre établissement</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Créez des barèmes spécifiques par niveau si nécessaire</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/grades/index.blade.php ENDPATH**/ ?>