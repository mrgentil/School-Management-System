
<?php $__env->startSection('page_title', 'Nouvel Événement'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="icon-plus-circle2 mr-2"></i> Créer un Événement
        </h5>
    </div>

    <form action="<?php echo e(route('calendar.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>Titre de l'événement <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('title')); ?>" placeholder="Ex: Vacances de Noël" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Type <span class="text-danger">*</span></strong></label>
                        <select name="type" class="form-control select" required>
                            <option value="event" <?php echo e(request('type') == 'event' || old('type') == 'event' ? 'selected' : ''); ?>>📅 Événement</option>
                            <option value="holiday" <?php echo e(request('type') == 'holiday' || old('type') == 'holiday' ? 'selected' : ''); ?>>🏖️ Congé/Vacances</option>
                            <option value="exam" <?php echo e(request('type') == 'exam' || old('type') == 'exam' ? 'selected' : ''); ?>>📝 Examen</option>
                            <option value="meeting" <?php echo e(request('type') == 'meeting' || old('type') == 'meeting' ? 'selected' : ''); ?>>👥 Réunion</option>
                            <option value="deadline" <?php echo e(request('type') == 'deadline' || old('type') == 'deadline' ? 'selected' : ''); ?>>⏰ Date limite</option>
                            <option value="activity" <?php echo e(request('type') == 'activity' || old('type') == 'activity' ? 'selected' : ''); ?>>🎉 Activité</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de début <span class="text-danger">*</span></strong></label>
                        <input type="date" name="start_date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('start_date', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de fin</strong></label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo e(old('end_date')); ?>">
                        <small class="text-muted">Laisser vide si même jour</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure début</strong></label>
                        <input type="time" name="start_time" class="form-control" value="<?php echo e(old('start_time')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure fin</strong></label>
                        <input type="time" name="end_time" class="form-control" value="<?php echo e(old('end_time')); ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="3" 
                          placeholder="Détails de l'événement..."><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Public cible <span class="text-danger">*</span></strong></label>
                        <select name="target_audience" class="form-control select" required>
                            <option value="all" <?php echo e(old('target_audience') == 'all' ? 'selected' : ''); ?>>👥 Tout le monde</option>
                            <option value="students" <?php echo e(old('target_audience') == 'students' ? 'selected' : ''); ?>>🎓 Élèves uniquement</option>
                            <option value="teachers" <?php echo e(old('target_audience') == 'teachers' ? 'selected' : ''); ?>>👨‍🏫 Enseignants uniquement</option>
                            <option value="parents" <?php echo e(old('target_audience') == 'parents' ? 'selected' : ''); ?>>👨‍👩‍👧 Parents uniquement</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="send_notification" 
                                   name="send_notification" value="1">
                            <label class="custom-control-label" for="send_notification">
                                🔔 Envoyer une notification
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="<?php echo e(route('calendar.index')); ?>" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Retour
            </a>
            <button type="submit" class="btn btn-success">
                <i class="icon-checkmark mr-1"></i> Créer l'événement
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/calendar/create.blade.php ENDPATH**/ ?>