
<?php $__env->startSection('page_title', 'Publication - ' . $exam->name); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title"><?php echo e($exam->name); ?> - <?php echo e($exam->year); ?> (Semestre <?php echo e($exam->semester); ?>)</h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-left-3 border-left-<?php echo e($exam->results_published ? 'success' : 'warning'); ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        Statut: 
                                        <?php if($exam->results_published): ?>
                                            <span class="badge badge-success">Publié</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Non Publié</span>
                                        <?php endif; ?>
                                    </h5>
                                    <?php if($exam->published_at): ?>
                                        <p class="text-muted mb-0">Publié le: <?php echo e($exam->published_at->format('d/m/Y à H:i')); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if($exam->results_published): ?>
                                        <form method="post" action="<?php echo e(route('exam_publication.unpublish', $exam->id)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Annuler la publication ?')">
                                                <i class="icon-cross"></i> Dépublier
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="<?php echo e(route('exam_publication.publish', $exam->id)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Publier les résultats ?')">
                                                <i class="icon-checkmark"></i> Publier Résultats
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#notification-modal">
                                        <i class="icon-bell"></i> Envoyer Notification
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <h6 class="font-weight-bold">Progression de la Notation par Classe</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-light">
                        <th>Classe</th>
                        <th>Total Étudiants</th>
                        <th>Notes Saisies</th>
                        <th>Progression</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isset($class_stats[$class->id])): ?>
                        <tr>
                            <td><strong><?php echo e($class->name); ?></strong></td>
                            <td><?php echo e($class_stats[$class->id]['total_students']); ?></td>
                            <td><?php echo e($class_stats[$class->id]['graded']); ?></td>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-<?php echo e($class_stats[$class->id]['percentage'] == 100 ? 'success' : 'primary'); ?>" 
                                         style="width: <?php echo e($class_stats[$class->id]['percentage']); ?>%">
                                        <?php echo e($class_stats[$class->id]['percentage']); ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($class_stats[$class->id]['percentage'] == 100): ?>
                                    <span class="badge badge-success">Complet</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">En cours</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div id="notification-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer une Notification</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="<?php echo e(route('exam_publication.notify', $exam->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type de Notification</label>
                            <select name="type" class="form-control" required>
                                <option value="results_published">Résultats Publiés</option>
                                <option value="schedule_published">Calendrier Publié</option>
                                <option value="reminder">Rappel</option>
                                <option value="modification">Modification</option>
                                <option value="cancellation">Annulation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="title" class="form-control" 
                                   value="Notification - <?php echo e($exam->name); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Classes Concernées (laissez vide pour tous)</label>
                            <select name="classes[]" class="form-control select-search" multiple>
                                <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Envoyer Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_publication/show.blade.php ENDPATH**/ ?>