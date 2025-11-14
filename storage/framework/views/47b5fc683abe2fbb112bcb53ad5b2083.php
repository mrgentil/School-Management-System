
<?php $__env->startSection('page_title', 'Edit Time Slot'); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="font-weight-bold card-title">Edit Time Slots</h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <div class="col-md-6">
            <form method="post" action="<?php echo e(route('ts.update', $tms->id)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input name="ttr_id" value="<?php echo e($tms->ttr_id); ?>" type="hidden">

                
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label font-weight-semibold">Start Time <span
                                class="text-danger">*</span></label>

                    <div class="col-lg-3">
                        <select data-placeholder="Hour" required class="select-search form-control" name="hour_from" id="hour_from">

                            <option value=""></option>
                            <?php for($t=1; $t<=12; $t++): ?>
                                <option <?php echo e($tms->hour_from == $t ? 'selected' : ''); ?> value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select data-placeholder="Minute" required class="select-search form-control" name="min_from" id="min_from">

                            <option value=""></option>
                            <option value="00">00</option>
                            <option value="05">05</option>
                            <?php for($t=10; $t<=55; $t+=5): ?>
                                <option <?php echo e($tms->min_from == $t ? 'selected' : ''); ?> value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select data-placeholder="Meridian" required class="select form-control" name="meridian_from" id="meridian_from">

                            <option value=""></option>
                            <option <?php echo e($tms->meridian_from == 'AM' ? 'selected' : ''); ?> value="AM">AM
                            </option>
                            <option <?php echo e($tms->meridian_from == 'PM' ? 'selected' : ''); ?> value="PM">PM
                            </option>
                        </select>
                    </div>
                </div>

                
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label font-weight-semibold">End Time <span class="text-danger">*</span></label>

                    <div class="col-lg-3">
                        <select data-placeholder="Hour" required class="select-search form-control" name="hour_to" id="hour_to">

                            <option value=""></option>
                            <?php for($t=1; $t<=12; $t++): ?>
                                <option <?php echo e($tms->hour_to == $t ? 'selected' : ''); ?> value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select data-placeholder="Minute" required class="select-search form-control" name="min_to" id="min_to">

                            <option value=""></option>
                            <option value="00">00</option>
                            <option value="05">05</option>
                            <?php for($t=10; $t<=55; $t+=5): ?>
                                <option <?php echo e($tms->min_to == $t ? 'selected' : ''); ?> value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <select data-placeholder="Meridian" required class="select form-control" name="meridian_to" id="meridian_to">

                            <option value=""></option>
                            <option <?php echo e($tms->meridian_to == 'AM' ? 'selected' : ''); ?> value="AM">AM
                            </option>
                            <option <?php echo e($tms->meridian_to == 'PM' ? 'selected' : ''); ?> value="PM">PM
                            </option>
                        </select>
                    </div>
                </div>


                <div class="text-right">
                    <button id="ajax-btn" type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/timetables/time_slots/edit.blade.php ENDPATH**/ ?>