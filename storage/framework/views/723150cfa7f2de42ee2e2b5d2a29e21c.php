
<?php $__env->startSection('page_title', 'Assigner des Étudiants aux Classes'); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <!-- Formulaire d'assignation -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">
                    <i class="icon-user-plus mr-2"></i>Assigner un Étudiant à une Classe
                </h6>
                <?php echo Qs::getPanelOptions(); ?>

            </div>

            <div class="card-body">
                <form method="post" action="<?php echo e(route('students.store_assignment')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="student_id" class="col-form-label font-weight-bold">
                            Sélectionner un Étudiant <span class="text-danger">*</span>
                        </label>
                        <select required id="student_id" name="student_id" class="form-control select-search" data-placeholder="Rechercher un étudiant...">
                            <option value="">-- Choisir un étudiant --</option>
                            <?php $__currentLoopData = $unassigned_students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($student->id); ?>">
                                    <?php echo e($student->name); ?> (<?php echo e($student->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted">
                            Seuls les étudiants non assignés pour la session <?php echo e(Qs::getCurrentSession()); ?> sont affichés.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="my_class_id" class="col-form-label font-weight-bold">
                            Classe <span class="text-danger">*</span>
                        </label>
                        <select required id="my_class_id" name="my_class_id" class="form-control select">
                            <option value="">-- Choisir une classe --</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->full_name ?: $class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-plus2 mr-2"></i>Assigner à la Classe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">
                    <i class="icon-stats-dots mr-2"></i>Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3 class="mb-0"><?php echo e($assigned_students->count()); ?></h3>
                                <small>Étudiants Assignés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h3 class="mb-0"><?php echo e($unassigned_students->count()); ?></h3>
                                <small>Non Assignés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des étudiants assignés -->
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">
            <i class="icon-users mr-2"></i>Étudiants Assignés (Session <?php echo e(Qs::getCurrentSession()); ?>)
        </h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <?php if($assigned_students->count() > 0): ?>
            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>N° Admission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assigned_students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <img class="rounded-circle" style="height: 40px; width: 40px;" 
                                     src="<?php echo e($sr->user->photo); ?>" alt="photo">
                            </td>
                            <td><?php echo e($sr->user->name); ?></td>
                            <td><?php echo e($sr->user->email); ?></td>
                            <td><strong><?php echo e($sr->my_class ? ($sr->my_class->full_name ?: $sr->my_class->name) : 'Non assigné'); ?></strong></td>
                            <td><?php echo e($sr->adm_no); ?></td>
                            <td class="text-center">
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item" 
                                               onclick="editAssignment('<?php echo e(Qs::hash($sr->id)); ?>', '<?php echo e($sr->my_class_id ?: ''); ?>', '<?php echo e($sr->section_id ?: ''); ?>', '<?php echo e($sr->user->name); ?>')">
                                                <i class="icon-pencil"></i> Modifier Classe
                                            </a>
                                            <a href="<?php echo e(route('students.show', Qs::hash($sr->user_id))); ?>" class="dropdown-item">
                                                <i class="icon-eye"></i> Voir Profil
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="icon-users icon-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun étudiant assigné</h5>
                <p class="text-muted">Commencez par assigner des étudiants aux classes.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal pour modifier l'assignation -->
<div id="editAssignmentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'Assignation de Classe</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="editAssignmentForm" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <p>Étudiant: <strong id="studentName"></strong></p>
                    
                    <div class="form-group">
                        <label for="edit_my_class_id" class="col-form-label font-weight-bold">
                            Nouvelle Classe <span class="text-danger">*</span>
                        </label>
                        <select required id="edit_my_class_id" name="my_class_id" class="form-control select">
                            <option value="">-- Choisir une classe --</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->full_name ?: $class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-checkmark3 mr-2"></i>Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editAssignment(srId, classId, sectionId, studentName) {
        document.getElementById('studentName').textContent = studentName;
        document.getElementById('editAssignmentForm').action = '<?php echo e(url("students/update-assignment")); ?>/' + srId;
        document.getElementById('edit_my_class_id').value = classId;
        
        $('#editAssignmentModal').modal('show');
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/students/assign_class.blade.php ENDPATH**/ ?>