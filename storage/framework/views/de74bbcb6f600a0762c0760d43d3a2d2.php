
<?php $__env->startSection('page_title', 'Gestion des Classes'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Gestion des Classes</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Liste des Classes</a></li>
                <li class="nav-item"><a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Nouvelle Classe</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-classes">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom</th>
                                <th>Titulaire</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <strong><?php echo e($c->full_name ?: $c->name); ?></strong>
                                        <?php if($c->academic_level || $c->division || $c->academic_option): ?>
                                            <br><small class="text-muted"><?php echo e($c->name); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($c->teacher): ?>
                                            <span class="badge badge-primary"><?php echo e($c->teacher->name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Non assigné</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($c->class_type->name); ?></td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <?php if(Qs::userIsTeamSA()): ?>
                                                    
                                                    <a href="<?php echo e(route('classes.edit', $c->id)); ?>" class="dropdown-item"><i class="icon-pencil"></i> Modifier</a>
                                                   <?php endif; ?>
                                                        <?php if(Qs::userIsSuperAdmin()): ?>
                                                    
                                                    <a id="<?php echo e($c->id); ?>" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Supprimer</a>
                                                    <form method="post" id="item-delete-<?php echo e($c->id); ?>" action="<?php echo e(route('classes.destroy', $c->id)); ?>" class="hidden"><?php echo csrf_field(); ?> <?php echo method_field('delete'); ?></form>
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

                <div class="tab-pane fade" id="new-class">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span><strong>Système RDC :</strong> Créez une classe en sélectionnant le niveau (1ère, 2ème...), la division (A, B, C, D) et l'option si applicable. Le nom complet sera généré automatiquement (ex: "1ère A Biochimie").</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form class="ajax-store" method="post" action="<?php echo e(route('classes.store')); ?>">
                                <?php echo csrf_field(); ?>
                                
                                <div class="form-group row">
                                    <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Type de classe <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Sélectionner le type" class="form-control select" name="class_type_id" id="class_type_id">
                                            <option value="">-- Sélectionner --</option>
                                            <?php $__currentLoopData = $class_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(old('class_type_id') == $ct->id ? 'selected' : ''); ?> value="<?php echo e($ct->id); ?>"><?php echo e($ct->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Niveau <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Sélectionner le niveau" class="form-control select" name="academic_level" id="academic_level">
                                            <option value="">-- Sélectionner --</option>
                                            <optgroup label="Crèche & Pré-Maternelle">
                                                <option value="Crèche PS">Crèche Petite Section</option>
                                                <option value="Crèche GS">Crèche Grande Section</option>
                                                <option value="Pré-Maternelle">Pré-Maternelle</option>
                                            </optgroup>
                                            <optgroup label="Maternelle">
                                                <option value="1ère Maternelle">1ère Année Maternelle</option>
                                                <option value="2ème Maternelle">2ème Année Maternelle</option>
                                                <option value="3ème Maternelle">3ème Année Maternelle</option>
                                            </optgroup>
                                            <optgroup label="Primaire">
                                                <option value="1ère">1ère Année Primaire</option>
                                                <option value="2ème">2ème Année Primaire</option>
                                                <option value="3ème">3ème Année Primaire</option>
                                                <option value="4ème">4ème Année Primaire</option>
                                                <option value="5ème">5ème Année Primaire</option>
                                                <option value="6ème">6ème Année Primaire</option>
                                            </optgroup>
                                            <optgroup label="Secondaire 1er Cycle">
                                                <option value="7ème">7ème Année (1ère Secondaire)</option>
                                                <option value="8ème">8ème Année (2ème Secondaire)</option>
                                            </optgroup>
                                            <optgroup label="Secondaire 2ème Cycle">
                                                <option value="3ème Sec">3ème Secondaire</option>
                                                <option value="4ème Sec">4ème Secondaire</option>
                                                <option value="5ème Sec">5ème Secondaire</option>
                                                <option value="6ème Sec">6ème Secondaire</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Division <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Sélectionner la division" class="form-control select" name="division" id="division">
                                            <option value="">-- Sélectionner --</option>
                                            <option value="A">Division A</option>
                                            <option value="B">Division B</option>
                                            <option value="C">Division C</option>
                                            <option value="D">Division D</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" id="option-group">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Option/Spécialisation</label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Sélectionner l'option" class="form-control select" name="option_id" id="option_id">
                                            <option value="">-- Choisir une option --</option>
                                            <?php $__currentLoopData = $academic_sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <optgroup label="<?php echo e($section->name); ?>">
                                                    <?php $__currentLoopData = $options->where('academic_section_id', $section->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($option->id); ?>"><?php echo e($option->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="form-text text-muted">Choisissez l'option/spécialisation de la classe</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Titulaire</label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Sélectionner le titulaire" class="form-control select-search" name="teacher_id" id="teacher_id">
                                            <option value="">-- Choisir un titulaire --</option>
                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="form-text text-muted">Le professeur responsable/garant de cette classe</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Nom généré</label>
                                    <div class="col-lg-9">
                                        <input type="text" id="generated-name" name="name" class="form-control" placeholder="Le nom sera généré automatiquement">
                                        <small class="form-text text-muted">Ce nom sera généré automatiquement selon vos sélections. Vous pouvez le modifier si nécessaire.</small>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button id="ajax-btn" type="submit" class="btn btn-primary">Créer la classe <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title">🇨🇩 Exemples de Classes RDC</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2"><strong>1ère A</strong> <small class="text-muted">- Primaire</small></li>
                                        <li class="mb-2"><strong>6ème B</strong> <small class="text-muted">- Fin primaire</small></li>
                                        <li class="mb-2"><strong>3ème Sec A Biochimie</strong> <small class="text-muted">- Sciences</small></li>
                                        <li class="mb-2"><strong>4ème Sec C Électronique</strong> <small class="text-muted">- Technique</small></li>
                                        <li><strong>5ème Sec B Comptabilité</strong> <small class="text-muted">- Commercial</small></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="alert alert-success mt-3">
                                <h6 class="alert-heading"><i class="icon-info22 mr-2"></i>Titulaire</h6>
                                <p class="mb-0">Le titulaire est le professeur responsable de la classe. Il assure le suivi des élèves et la communication avec les parents.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const academicLevel = document.getElementById('academic_level');
                    const division = document.getElementById('division');
                    const academicOption = document.getElementById('academic_option');
                    const generatedName = document.getElementById('generated-name');

                    function updateGeneratedName() {
                        const level = academicLevel.value;
                        const div = division.value;
                        const option = academicOption.value;

                        let name = '';
                        if (level) name += level;
                        if (div) name += (name ? ' ' : '') + div;
                        if (option) name += (name ? ' ' : '') + option;

                        generatedName.value = name;
                    }

                    // Mettre à jour le nom généré quand les champs changent
                    academicLevel.addEventListener('change', updateGeneratedName);
                    division.addEventListener('change', updateGeneratedName);
                    academicOption.addEventListener('change', updateGeneratedName);
                    
                    // Mise à jour initiale
                    updateGeneratedName();
                });
                </script>
            </div>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/classes/index.blade.php ENDPATH**/ ?>