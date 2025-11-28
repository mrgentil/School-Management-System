
<?php $__env->startSection('page_title', 'Entrer le Code PIN'); ?>
<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0"><i class="icon-key mr-2"></i> Code PIN Requis</h5>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="icon-lock2 icon-3x text-primary mb-3"></i>
                        <p class="text-muted">
                            Pour accéder à votre 
                            <strong><?php echo e($type == 'period' ? 'bulletin de la période ' . $period : 'bulletin du semestre ' . $semester); ?></strong>,
                            veuillez entrer votre code PIN.
                        </p>
                    </div>

                    <form method="post" action="<?php echo e(route('pins.verify_bulletin')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="<?php echo e($type); ?>">
                        <input type="hidden" name="period" value="<?php echo e($period); ?>">
                        <input type="hidden" name="semester" value="<?php echo e($semester); ?>">
                        <input type="hidden" name="redirect_url" value="<?php echo e($redirect_url); ?>">
                        
                        <div class="form-group">
                            <label for="pin_code" class="font-weight-bold">Code PIN</label>
                            <input 
                                class="form-control form-control-lg text-center" 
                                placeholder="XXXX-XXXX-XXXX" 
                                style="text-transform:uppercase; letter-spacing: 2px; font-size: 1.5rem;"
                                pattern="[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}" 
                                required 
                                name="pin_code" 
                                autocomplete="off" 
                                value="<?php echo e(old('pin_code')); ?>" 
                                type="text"
                                autofocus
                            >
                            <small class="text-muted d-block text-center mt-2">
                                Format: XXXX-XXXX-XXXX
                            </small>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="icon-checkmark mr-2"></i> Valider
                            </button>
                        </div>
                    </form>

                    <hr>
                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            <i class="icon-info22 mr-1"></i>
                            Vous pouvez obtenir un code PIN auprès de l'administration de l'école.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/pins/enter.blade.php ENDPATH**/ ?>