
<?php $__env->startSection('page_title', 'Gérer les Paramètres Système'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-semibold">Mettre à jour les Paramètres Système</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form enctype="multipart/form-data" method="post" action="<?php echo e(route('settings.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-md-6 border-right-2 border-right-blue-400">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name of School <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="system_name" value="<?php echo e($s['system_name']); ?>" required type="text" class="form-control" placeholder="Name of School">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current_session" class="col-lg-3 col-form-label font-weight-semibold">Current Session <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Choose..." required name="current_session" id="current_session" class="select-search form-control">
                                    <option value=""></option>
                                    <?php
                                        // Déterminer l'année de début de la session actuelle (ex. 2025 pour 2025-2026)
                                        [$startYear] = explode('-', $s['current_session'] ?? date('Y').'-'.(date('Y')+1));
                                        $startYear = (int) $startYear;
                                        $fromYear  = $startYear - 3; // 3 ans avant
                                        $toYear    = $startYear + 3; // 3 ans après
                                    ?>
                                    <?php for($y = $fromYear; $y <= $toYear; $y++): ?>
                                        <?php $session = $y.'-'.($y + 1); ?>
                                        <option value="<?php echo e($session); ?>" <?php echo e(($s['current_session'] == $session) ? 'selected' : ''); ?>><?php echo e($session); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">School Acronym</label>
                            <div class="col-lg-9">
                                <input name="system_title" value="<?php echo e($s['system_title']); ?>" type="text" class="form-control" placeholder="School Acronym">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Phone</label>
                            <div class="col-lg-9">
                                <input name="phone" value="<?php echo e($s['phone']); ?>" type="text" class="form-control" placeholder="Phone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">School Email</label>
                            <div class="col-lg-9">
                                <input name="system_email" value="<?php echo e($s['system_email']); ?>" type="email" class="form-control" placeholder="School Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">School Address <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input required name="address" value="<?php echo e($s['address']); ?>" type="text" class="form-control" placeholder="School Address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">This Term Ends</label>
                            <div class="col-lg-6">
                                <input name="term_ends" value="<?php echo e($s['term_ends']); ?>" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                            </div>
                            <div class="col-lg-3 mt-2">
                                <span class="font-weight-bold font-italic">M-D-Y or M/D/Y </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Next Term Begins</label>
                            <div class="col-lg-6">
                                <input name="term_begins" value="<?php echo e($s['term_begins']); ?>" type="text" class="form-control date-pick" placeholder="Date Term Ends">
                            </div>
                            <div class="col-lg-3 mt-2">
                                <span class="font-weight-bold font-italic">M-D-Y or M/D/Y </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lock_exam" class="col-lg-3 col-form-label font-weight-semibold">Lock Exam</label>
                            <div class="col-lg-3">
                                <select class="form-control select" name="lock_exam" id="lock_exam">
                                    <option <?php echo e($s['lock_exam'] ? 'selected' : ''); ?> value="1">Yes</option>
                                    <option <?php echo e($s['lock_exam'] ?: 'selected'); ?> value="0">No</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                    <span class="font-weight-bold font-italic text-info-800"><?php echo e(__('msg.lock_exam')); ?></span>
                            </div>
                        </div>
                    
                    <fieldset class="mb-4">
                        <legend><strong><i class="icon-bell mr-2"></i>Notifications Parents</strong></legend>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label font-weight-semibold">Notifications Email</label>
                            <div class="col-lg-6">
                                <select class="form-control select" name="email_notifications">
                                    <option <?php echo e(($s['email_notifications'] ?? 'no') == 'yes' ? 'selected' : ''); ?> value="yes">Oui</option>
                                    <option <?php echo e(($s['email_notifications'] ?? 'no') == 'no' ? 'selected' : ''); ?> value="no">Non</option>
                                </select>
                                <small class="text-muted">Emails envoyés quand un bulletin est publié</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-6 col-form-label font-weight-semibold">Notifications WhatsApp</label>
                            <div class="col-lg-6">
                                <select class="form-control select" name="whatsapp_notifications">
                                    <option <?php echo e(($s['whatsapp_notifications'] ?? 'no') == 'yes' ? 'selected' : ''); ?> value="yes">Oui</option>
                                    <option <?php echo e(($s['whatsapp_notifications'] ?? 'no') == 'no' ? 'selected' : ''); ?> value="no">Non</option>
                                </select>
                                <small class="text-muted">WhatsApp envoyé si parent a un numéro de téléphone</small>
                            </div>
                        </div>
                    </fieldset>

                    
                    <fieldset class="mb-4">
                        <legend><strong><i class="icon-file-text2 mr-2"></i>Paramètres Bulletin RDC</strong></legend>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Province</label>
                            <div class="col-lg-9">
                                <input name="province" value="<?php echo e($s['province'] ?? 'KINSHASA'); ?>" type="text" class="form-control" placeholder="Ex: KINSHASA">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Ville</label>
                            <div class="col-lg-9">
                                <input name="city" value="<?php echo e($s['city'] ?? ''); ?>" type="text" class="form-control" placeholder="Ex: Kinshasa">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Commune</label>
                            <div class="col-lg-9">
                                <input name="commune" value="<?php echo e($s['commune'] ?? ''); ?>" type="text" class="form-control" placeholder="Ex: Lemba">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Code École</label>
                            <div class="col-lg-9">
                                <input name="school_code" value="<?php echo e($s['school_code'] ?? ''); ?>" type="text" class="form-control" placeholder="Ex: KIN/LEMBA/001">
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-md-6">
                    
               <fieldset>
                   <legend><strong>Next Term Fees</strong></legend>
                   <?php $__currentLoopData = $class_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   <div class="form-group row">
                       <label class="col-lg-3 col-form-label font-weight-semibold"><?php echo e($ct->name); ?></label>
                       <div class="col-lg-9">
                           <input class="form-control" value="<?php echo e($s['next_term_fees_'.strtolower($ct->code)] ?? ''); ?>" name="next_term_fees_<?php echo e(strtolower($ct->code)); ?>" placeholder="<?php echo e($ct->name); ?>" type="text">
                       </div>
                   </div>
                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </fieldset>
                    <hr class="divider">

                    
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label font-weight-semibold">Change Logo:</label>
                        <div class="col-lg-9">
                            <div class="mb-3">
                                <img style="width: 100px" height="100px" src="<?php echo e($s['logo']); ?>" alt="">
                            </div>
                            <input name="logo" accept="image/*" type="file" class="file-input" data-show-caption="false" data-show-upload="false" data-fouc>
                        </div>
                    </div>
                </div>
            </div>

                <hr class="divider">

                <div class="text-right">
                    <button type="submit" class="btn btn-danger">Enregistrer <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/super_admin/settings.blade.php ENDPATH**/ ?>