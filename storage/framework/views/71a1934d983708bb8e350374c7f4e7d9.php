
<?php $__env->startSection('page_title', 'Gestion des Années Scolaires'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header header-elements-inline bg-dark text-white">
        <h5 class="card-title"><i class="icon-calendar mr-2"></i> Années Scolaires</h5>
        <div class="header-elements">
            <a href="<?php echo e(route('academic_sessions.create')); ?>" class="btn btn-success btn-sm">
                <i class="icon-plus2 mr-1"></i> Nouvelle Année
            </a>
            <a href="<?php echo e(route('academic_sessions.prepare_new_year')); ?>" class="btn btn-warning btn-sm ml-2">
                <i class="icon-forward mr-1"></i> Préparer Nouvelle Année
            </a>
        </div>
    </div>

    <div class="card-body">
        <?php if($sessions->isEmpty()): ?>
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune année scolaire enregistrée. 
                <a href="<?php echo e(route('academic_sessions.create')); ?>" class="alert-link">Créez-en une maintenant</a>.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Année</th>
                            <th>Dates</th>
                            <th class="text-center">Élèves</th>
                            <th class="text-center">Classes</th>
                            <th class="text-center">Moyenne</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($session->is_current ? 'table-primary' : ''); ?>">
                                <td>
                                    <strong><?php echo e($session->name); ?></strong>
                                    <?php if($session->is_current): ?>
                                        <span class="badge badge-primary ml-2">📍 Courante</span>
                                    <?php endif; ?>
                                    <?php if($session->label && $session->label != 'Année Scolaire ' . $session->name): ?>
                                        <br><small class="text-muted"><?php echo e($session->label); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($session->start_date && $session->end_date): ?>
                                        <small>
                                            <?php echo e($session->start_date->format('d/m/Y')); ?> 
                                            <i class="icon-arrow-right7 mx-1"></i>
                                            <?php echo e($session->end_date->format('d/m/Y')); ?>

                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Non défini</small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?php echo e($session->total_students); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary"><?php echo e($session->total_classes); ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if($session->average_score): ?>
                                        <span class="badge badge-<?php echo e($session->average_score >= 10 ? 'success' : 'warning'); ?>">
                                            <?php echo e(number_format($session->average_score, 1)); ?>/20
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $session->status_badge; ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('academic_sessions.show', $session)); ?>" 
                                           class="btn btn-info btn-sm" title="Détails">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('academic_sessions.edit', $session)); ?>" 
                                           class="btn btn-primary btn-sm" title="Modifier">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        
                                        <?php if(!$session->is_current): ?>
                                            <form action="<?php echo e(route('academic_sessions.set_current', $session)); ?>" 
                                                  method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Définir comme courante"
                                                        onclick="return confirm('Définir <?php echo e($session->name); ?> comme année courante ?')">
                                                    <i class="icon-checkmark"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if($session->status != 'archived' && !$session->is_current): ?>
                                            <form action="<?php echo e(route('academic_sessions.archive', $session)); ?>" 
                                                  method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-secondary btn-sm" 
                                                        title="Archiver"
                                                        onclick="return confirm('Archiver cette année ?')">
                                                    <i class="icon-drawer"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <a href="<?php echo e(route('academic_sessions.copy_structure', $session)); ?>" 
                                           class="btn btn-warning btn-sm" title="Copier vers nouvelle année">
                                            <i class="icon-copy"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="card mt-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center">
            <span class="mr-4"><strong>Légende:</strong></span>
            <span class="badge badge-success mr-2">Active</span> En cours
            <span class="badge badge-secondary mx-2">Archivée</span> Terminée
            <span class="badge badge-info mx-2">À venir</span> Prochaine année
            <span class="badge badge-primary mx-2">📍 Courante</span> Année de travail actuelle
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/academic_sessions/index.blade.php ENDPATH**/ ?>