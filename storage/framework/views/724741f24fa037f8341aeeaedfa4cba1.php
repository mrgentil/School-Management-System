
<?php $__env->startSection('page_title', 'Admettre un Étudiant'); ?>
<?php $__env->startSection('content'); ?>
        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 class="card-title">Veuillez remplir le formulaire ci-dessous pour admettre un nouvel étudiant</h6>

                <?php echo Qs::getPanelOptions(); ?>

            </div>

            <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation" action="<?php echo e(route('students.store')); ?>" data-fouc>
               <?php echo csrf_field(); ?>
                <h6>Données personnelles</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet : <span class="text-danger">*</span></label>
                                <input value="<?php echo e(old('name')); ?>" required type="text" name="name" placeholder="Nom complet" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Adresse : <span class="text-danger">*</span></label>
                                <input value="<?php echo e(old('address')); ?>" class="form-control" placeholder="Adresse" name="address" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Adresse email :</label>
                                <input type="email" value="<?php echo e(old('email')); ?>" name="email" class="form-control" placeholder="Adresse email">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Genre : <span class="text-danger">*</span></label>
                                <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choisir..">
                                    <option value=""></option>
                                    <option <?php echo e((old('gender') == 'Male') ? 'selected' : ''); ?> value="Male">Masculin</option>
                                    <option <?php echo e((old('gender') == 'Female') ? 'selected' : ''); ?> value="Female">Féminin</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Téléphone :</label>
                                <input value="<?php echo e(old('phone')); ?>" type="text" name="phone" class="form-control" placeholder="" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Téléphone 2 :</label>
                                <input value="<?php echo e(old('phone2')); ?>" type="text" name="phone2" class="form-control" placeholder="" >
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de naissance :</label>
                                <input name="dob" value="<?php echo e(old('dob')); ?>" type="text" class="form-control date-pick" placeholder="Sélectionner une date...">

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nal_id">Nationalité : <span class="text-danger">*</span></label>
                                <select data-placeholder="Choisir..." required name="nal_id" id="nal_id" class="select-search form-control">
                                    <option value=""></option>
                                    <?php $__currentLoopData = $nationals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e((old('nal_id') == $nal->id ? 'selected' : '')); ?> value="<?php echo e($nal->id); ?>"><?php echo e($nal->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="state_id">État : <span class="text-danger">*</span></label>
                            <select onchange="getLGA(this.value)" required data-placeholder="Choisir.." class="select-search form-control" name="state_id" id="state_id">
                                <option value=""></option>
                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e((old('state_id') == $st->id ? 'selected' : '')); ?> value="<?php echo e($st->id); ?>"><?php echo e($st->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="lga_id">Zone administrative : <span class="text-danger">*</span></label>
                            <select required data-placeholder="Sélectionner l'état d'abord" class="select-search form-control" name="lga_id" id="lga_id">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bg_id">Groupe sanguin :</label>
                                <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    <?php $__currentLoopData = App\Models\BloodGroup::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e((old('bg_id') == $bg->id ? 'selected' : '')); ?> value="<?php echo e($bg->id); ?>"><?php echo e($bg->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Télécharger la photo d'identité :</label>
                                <input value="<?php echo e(old('photo')); ?>" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Formats acceptés : jpeg, png. Taille max 2Mb</span>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <h6>Données scolaires</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_class_id">Classe : <span class="text-danger">*</span></label>
                                <select onchange="getClassSections(this.value)" data-placeholder="Choose..." required name="my_class_id" id="my_class_id" class="select-search form-control">
                                    <option value=""></option>
                                    <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e((old('my_class_id') == $c->id ? 'selected' : '')); ?> value="<?php echo e($c->id); ?>"><?php echo e($c->full_name ?: $c->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                        </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id">Division (A, B, C, ...): <span class="text-danger">*</span></label>
                                <select data-placeholder="Select Class First" required name="section_id" id="section_id" class="select-search form-control">
                                    <option <?php echo e((old('section_id')) ? 'selected' : ''); ?> value="<?php echo e(old('section_id')); ?>"><?php echo e((old('section_id')) ? 'Selected' : ''); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="academic_section_display">Section académique :</label>
                                <input type="hidden" name="academic_section_id" id="academic_section_value" value="<?php echo e(old('academic_section_id')); ?>">
                                <select data-placeholder="Choisir..." id="academic_section_display" class="select-search form-control">
                                    <option value=""></option>
                                    <?php $__currentLoopData = $academic_sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $as): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e(old('academic_section_id') == $as->id ? 'selected' : ''); ?> value="<?php echo e($as->id); ?>"><?php echo e($as->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small id="academic_section_lock_notice" class="form-text text-muted d-none">La section est définie automatiquement par l'option choisie.</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_parent_id">Parent :</label>
                                <select data-placeholder="Choose..."  name="my_parent_id" id="my_parent_id" class="select-search form-control">
                                    <option  value=""></option>
                                    <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e((old('my_parent_id') == Qs::hash($p->id)) ? 'selected' : ''); ?> value="<?php echo e(Qs::hash($p->id)); ?>"><?php echo e($p->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year_admitted">Année d'admission : <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="year_admitted" id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    <?php for($y=date('Y', strtotime('- 10 years')); $y<=date('Y'); $y++): ?>
                                        <option <?php echo e((old('year_admitted') == $y) ? 'selected' : ''); ?> value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label for="dorm_id">Internat :</label>
                            <select data-placeholder="Choose..."  name="dorm_id" id="dorm_id" class="select-search form-control">
                                <option value=""></option>
                                <?php $__currentLoopData = $dorms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e((old('dorm_id') == $d->id) ? 'selected' : ''); ?> value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chambre d'internat :</label>
                                <input type="text" name="dorm_room_no" placeholder="Chambre d'internat" class="form-control" value="<?php echo e(old('dorm_room_no')); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Maison (sport) :</label>
                                <input type="text" name="house" placeholder="Maison (sport)" class="form-control" value="<?php echo e(old('house')); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Numéro d'admission :</label>
                                <input type="text" name="adm_no" placeholder="Numéro d'admission" class="form-control" value="<?php echo e(old('adm_no')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="option_id">Option :</label>
                                <select data-placeholder="Choose..." name="option_id" id="option_id" class="select-search form-control">
                                    <option value=""></option>
                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option data-section-id="<?php echo e(optional($opt->academic_section)->id); ?>" <?php echo e(old('option_id') == $opt->id ? 'selected' : ''); ?> value="<?php echo e($opt->id); ?>"><?php echo e($opt->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </form>
        </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/students/add.blade.php ENDPATH**/ ?>