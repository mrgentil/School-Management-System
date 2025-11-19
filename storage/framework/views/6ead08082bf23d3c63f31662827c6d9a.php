
<?php $__env->startSection('page_title', 'Fix Mark Errors'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="alert alert-warning border-0">
        <div class="d-flex align-items-center">
            <i class="icon-warning mr-3 icon-2x"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Correction en Masse des Notes</h6>
                <p class="mb-0">Cette fonctionnalité recalcule automatiquement les grades, positions et moyennes pour une classe entière.</p>
            </div>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="<?php echo e(route('marks.index')); ?>" class="btn btn-primary btn-block">
                <i class="icon-pencil5 mr-2"></i>Retour à la Saisie
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo e(route('marks.tabulation')); ?>" class="btn btn-info btn-block">
                <i class="icon-table2 mr-2"></i>Tabulation
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo e(route('exam_analytics.index')); ?>" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analytics
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-wrench mr-2"></i> Batch Fix </h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <form class="ajax-update" method="post" action="<?php echo e(route('marks.batch_update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="row">
                    <div class="col-md-10">
                        <fieldset>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                        <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e($selected && $exam_id == $ex->id ? 'selected' : ''); ?> value="<?php echo e($ex->id); ?>"><?php echo e($ex->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                        <select required onchange="getClassSections(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                            <option value="">Select Class</option>
                                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php echo e(($selected && $my_class_id == $c->id) ? 'selected' : ''); ?> value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                        <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                            <?php if($selected): ?>
                                                <?php $__currentLoopData = $sections->where('my_class_id', $my_class_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e($section_id == $s->id ? 'selected' : ''); ?> value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
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
                            <button type="submit" class="btn btn-danger">Fix Errors <i class="icon-wrench2 ml-2"></i></button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/batch_fix.blade.php ENDPATH**/ ?>