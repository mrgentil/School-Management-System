
<?php $__env->startSection('page_title', 'Générer des Codes PIN'); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-key mr-2"></i> Générer des Codes PIN</h5>
            <a href="<?php echo e(route('pins.index')); ?>" class="btn btn-light btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>

        <div class="card-body">
            <form method="post" action="<?php echo e(route('pins.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre de PINs à générer <span class="text-danger">*</span></label>
                            <input class="form-control" placeholder="Ex: 10" required name="pin_count" type="number" min="1" max="100" value="<?php echo e(old('pin_count', 10)); ?>">
                            <small class="text-muted">Maximum 100 PINs par génération</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Type de PIN <span class="text-danger">*</span></label>
                            <select name="type" class="form-control select" required>
                                <option value="bulletin">Bulletin scolaire</option>
                                <option value="exam">Résultats d'examen</option>
                                <option value="result">Autre résultat</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Prix par PIN (FC)</label>
                            <input class="form-control" name="price" type="number" min="0" step="100" value="<?php echo e(old('price', 500)); ?>" placeholder="0">
                            <small class="text-muted">Laisser 0 si gratuit</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Nombre max d'utilisations <span class="text-danger">*</span></label>
                            <select name="max_uses" class="form-control">
                                <option value="1">1 fois (recommandé)</option>
                                <option value="2">2 fois</option>
                                <option value="3">3 fois</option>
                                <option value="5">5 fois</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Année scolaire</label>
                            <input class="form-control" name="year" type="text" value="<?php echo e($year); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Restreindre à une classe</label>
                            <select name="my_class_id" class="form-control select-search">
                                <option value="">Toutes les classes</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->full_name ?? $class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="text-muted">Laisser vide pour toutes les classes</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Restreindre à une période</label>
                            <select name="period" class="form-control">
                                <option value="">Toutes les périodes</option>
                                <option value="1">Période 1</option>
                                <option value="2">Période 2</option>
                                <option value="3">Période 3</option>
                                <option value="4">Période 4</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Ou restreindre à un semestre</label>
                            <select name="semester" class="form-control">
                                <option value="">Tous les semestres</option>
                                <option value="1">Semestre 1</option>
                                <option value="2">Semestre 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Expiration (jours)</label>
                            <input class="form-control" name="expires_days" type="number" min="1" placeholder="Jamais">
                            <small class="text-muted">Laisser vide pour pas d'expiration</small>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="icon-key mr-2"></i> Générer les PINs
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/pins/create.blade.php ENDPATH**/ ?>