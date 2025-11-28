
<?php $__env->startSection('page_title', 'Gestion des Codes PIN'); ?>
<?php $__env->startSection('content'); ?>

    <!-- Statistiques -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="icon-key icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0"><?php echo e($stats['total_valid'] ?? 0); ?></h3>
                            <span>PINs Valides</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="icon-checkmark3 icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0"><?php echo e($stats['total_used'] ?? 0); ?></h3>
                            <span>PINs Utilisés</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="icon-calendar3 icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0"><?php echo e($stats['used_today'] ?? 0); ?></h3>
                            <span>Utilisés Aujourd'hui</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="icon-coins icon-2x mr-3"></i>
                        <div>
                            <h3 class="mb-0"><?php echo e(number_format($stats['revenue'] ?? 0, 0, ',', ' ')); ?> FC</h3>
                            <span>Revenus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paramètre PIN obligatoire -->
    <div class="card mb-3">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1"><i class="icon-lock mr-2"></i>Code PIN obligatoire pour les bulletins</h6>
                    <small class="text-muted">Si activé, les étudiants devront entrer un code PIN pour voir leur bulletin</small>
                </div>
                <form method="post" action="<?php echo e(route('pins.toggle_required')); ?>" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php $pinRequired = \App\Helpers\Qs::getSetting('pin_required_for_bulletin', 'no') === 'yes'; ?>
                    <button type="submit" class="btn btn-<?php echo e($pinRequired ? 'success' : 'secondary'); ?> btn-lg">
                        <i class="icon-<?php echo e($pinRequired ? 'checkmark3' : 'cross2'); ?> mr-1"></i>
                        <?php echo e($pinRequired ? 'ACTIVÉ' : 'DÉSACTIVÉ'); ?>

                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title"><i class="icon-key mr-2"></i>Gestion des Codes PIN</h6>
            <div class="header-elements">
                <a href="<?php echo e(route('pins.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="icon-plus3 mr-1"></i> Générer des PINs
                </a>
                <a href="<?php echo e(route('pins.export')); ?>?type=<?php echo e($selected_type); ?>" class="btn btn-success btn-sm ml-1">
                    <i class="icon-download mr-1"></i> Exporter CSV
                </a>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item">
                    <a href="#valid-pins" class="nav-link active" data-toggle="tab">
                        <i class="icon-checkmark3 mr-1"></i> PINs Valides 
                        <span class="badge badge-primary ml-1"><?php echo e($pin_count); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#used-pins" class="nav-link" data-toggle="tab">
                        <i class="icon-history mr-1"></i> PINs Utilisés
                        <span class="badge badge-secondary ml-1"><?php echo e($used_pins->count()); ?></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- PINs Valides -->
                <div class="tab-pane fade show active" id="valid-pins">
                    <?php if($valid_pins->isEmpty()): ?>
                        <div class="alert alert-info mt-3">
                            <i class="icon-info22 mr-2"></i> Aucun code PIN valide. 
                            <a href="<?php echo e(route('pins.create')); ?>" class="alert-link">Générez-en maintenant</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover datatable-basic">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code PIN</th>
                                        <th>Type</th>
                                        <th>Classe</th>
                                        <th>Période</th>
                                        <th>Prix</th>
                                        <th>Utilisations</th>
                                        <th>Expire le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $valid_pins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><code class="font-weight-bold text-primary"><?php echo e($pin->code); ?></code></td>
                                            <td>
                                                <span class="badge badge-<?php echo e($pin->type == 'bulletin' ? 'info' : ($pin->type == 'exam' ? 'warning' : 'secondary')); ?>">
                                                    <?php echo e(ucfirst($pin->type)); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($pin->myClass->name ?? 'Toutes'); ?></td>
                                            <td>
                                                <?php if($pin->period): ?>
                                                    Période <?php echo e($pin->period); ?>

                                                <?php elseif($pin->semester): ?>
                                                    Semestre <?php echo e($pin->semester); ?>

                                                <?php else: ?>
                                                    Tous
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(number_format($pin->price, 0, ',', ' ')); ?> FC</td>
                                            <td><?php echo e($pin->times_used); ?>/<?php echo e($pin->max_uses ?? 1); ?></td>
                                            <td>
                                                <?php if($pin->expires_at): ?>
                                                    <span class="<?php echo e($pin->expires_at->isPast() ? 'text-danger' : ''); ?>">
                                                        <?php echo e($pin->expires_at->format('d/m/Y')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Jamais</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PINs Utilisés -->
                <div class="tab-pane fade" id="used-pins">
                    <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                        <span><?php echo e($used_pins->count()); ?> PIN(s) utilisé(s)</span>
                        <?php if($used_pins->count() > 0): ?>
                            <form method="post" action="<?php echo e(route('pins.destroy')); ?>" class="d-inline" 
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer tous les PINs utilisés ?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="icon-trash mr-1"></i> Supprimer tous
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if($used_pins->isEmpty()): ?>
                        <div class="alert alert-info">
                            <i class="icon-info22 mr-2"></i> Aucun code PIN utilisé.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped datatable-basic">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code PIN</th>
                                        <th>Utilisé par</th>
                                        <th>Étudiant</th>
                                        <th>Prix</th>
                                        <th>Date d'utilisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $used_pins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><code><?php echo e($pin->code); ?></code></td>
                                            <td><?php echo e($pin->user->name ?? 'N/A'); ?></td>
                                            <td><?php echo e($pin->student->name ?? 'N/A'); ?></td>
                                            <td><?php echo e(number_format($pin->price, 0, ',', ' ')); ?> FC</td>
                                            <td><?php echo e($pin->updated_at->format('d/m/Y H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/pins/index.blade.php ENDPATH**/ ?>