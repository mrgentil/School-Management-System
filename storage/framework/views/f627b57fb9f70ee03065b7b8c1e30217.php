<?php $__env->startSection('page_title', 'Consulter les Présences'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-info">
        <h6 class="card-title text-white">
            <i class="icon-eye mr-2"></i>
            Consulter les Présences
        </h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <form method="GET" action="<?php echo e(route('attendance.view')); ?>" id="filter-form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control select">
                            <option value="">Toutes les classes</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e($filters['class_id'] == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section</label>
                        <select name="section_id" id="section_id" class="form-control select">
                            <option value="">Toutes</option>
                            <?php if(isset($sections)): ?>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section->id); ?>" <?php echo e($filters['section_id'] == $section->id ? 'selected' : ''); ?>>
                                        <?php echo e($section->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control select">
                            <option value="">Toutes</option>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($filters['subject_id'] == $subject->id ? 'selected' : ''); ?>>
                                    <?php echo e($subject->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date début</label>
                        <input type="date" name="date_from" class="form-control" value="<?php echo e($filters['date_from'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date fin</label>
                        <input type="date" name="date_to" class="form-control" value="<?php echo e($filters['date_to'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <?php if(isset($attendances) && $attendances->count() > 0): ?>
            <div class="mb-3">
                <a href="<?php echo e(route('attendance.export', request()->query())); ?>" class="btn btn-success">
                    <i class="icon-file-excel mr-2"></i>
                    Exporter vers Excel (<?php echo e($attendances->total()); ?> résultats)
                </a>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <th>Section</th>
                            <th>Matière</th>
                            <th>Statut</th>
                            <th>Notes</th>
                            <th>Enregistré par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($attendance->date->format('d/m/Y')); ?></td>
                                <td>
                                    <strong><?php echo e($attendance->student->name ?? 'N/A'); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($attendance->student->student_record->adm_no ?? ''); ?></small>
                                </td>
                                <td><?php echo e($attendance->class->name ?? 'N/A'); ?></td>
                                <td><?php echo e($attendance->section->name ?? '-'); ?></td>
                                <td><?php echo e($attendance->subject->name ?? '-'); ?></td>
                                <td>
                                    <?php if($attendance->status == 'present'): ?>
                                        <span class="badge badge-success">Présent</span>
                                    <?php elseif($attendance->status == 'absent'): ?>
                                        <span class="badge badge-danger">Absent</span>
                                    <?php elseif($attendance->status == 'late'): ?>
                                        <span class="badge badge-warning">Retard</span>
                                    <?php elseif($attendance->status == 'excused'): ?>
                                        <span class="badge badge-info">Excusé</span>
                                    <?php elseif($attendance->status == 'late_justified'): ?>
                                        <span class="badge badge-warning">Retard Justifié</span>
                                    <?php elseif($attendance->status == 'absent_justified'): ?>
                                        <span class="badge badge-info">Absent Justifié</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($attendance->notes ?? '-'); ?></td>
                                <td><?php echo e($attendance->takenBy->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if(Qs::userIsTeamSA()): ?>
                                        <form method="POST" action="<?php echo e(route('attendance.destroy', $attendance->id)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de <?php echo e($attendances->firstItem()); ?> à <?php echo e($attendances->lastItem()); ?> sur <?php echo e($attendances->total()); ?> résultats
                </div>
                <div>
                    <?php echo e($attendances->appends(request()->query())->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">
                <i class="icon-info22 mr-2"></i>
                Aucune présence trouvée. Utilisez les filtres ci-dessus pour rechercher.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Load sections when class is selected
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        $('#section_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            $.ajax({
                url: '/attendance/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                },
                error: function() {
                    $('#section_id').html('<option value="">Erreur</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Toutes</option>');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/attendance/view.blade.php ENDPATH**/ ?>