<?php $__env->startSection('page_title', 'Gestion de la Présence'); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-checkmark-circle mr-2"></i>
            Prendre la Présence
        </h6>
        <?php echo Qs::getPanelOptions(); ?>

    </div>

    <div class="card-body">
        <form id="attendance-form" method="POST" action="<?php echo e(route('attendance.store')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner une classe</option>
                            <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->full_name ?: $class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section <small class="text-muted">(optionnel)</small></label>
                        <select name="section_id" id="section_id" class="form-control select">
                            <option value="">Toutes les sections</option>
                        </select>
                        <small class="form-text text-muted">Laissez vide pour toute la classe</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière <small class="text-muted">(optionnel)</small></label>
                        <select name="subject_id" id="subject_id" class="form-control select">
                            <option value="">Toutes les matières</option>
                        </select>
                        <small class="form-text text-muted">Filtrées selon la classe sélectionnée</small>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" id="load-students" class="btn btn-primary">
                        <i class="icon-users mr-2"></i>
                        Charger les Étudiants
                    </button>
                </div>
            </div>

            <div id="students-container" class="mt-4" style="display: none;">
                <div class="alert alert-info border-0">
                    <i class="icon-info22 mr-2"></i>
                    <strong>Instructions :</strong> Cochez le statut de présence pour chaque étudiant. Vous pouvez ajouter des notes si nécessaire.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">N°</th>
                                <th width="15%">N° Admission</th>
                                <th width="25%">Nom de l'Étudiant</th>
                                <th width="35%">Statut</th>
                                <th width="20%">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="students-list">
                            <!-- Students will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="icon-checkmark3 mr-2"></i>
                        Enregistrer la Présence
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Load sections and subjects when class is selected
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        
        // Reset dropdowns
        $('#section_id').html('<option value="">Chargement...</option>');
        $('#subject_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            // Load sections
            $.ajax({
                url: '/attendance/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes les sections</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                },
                error: function() {
                    $('#section_id').html('<option value="">Erreur de chargement</option>');
                }
            });
            
            // Load subjects for this class
            $.ajax({
                url: '/attendance/get-subjects/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes les matières</option>';
                    if (response.success && response.subjects.length > 0) {
                        response.subjects.forEach(function(subject) {
                            options += '<option value="' + subject.id + '">' + subject.name + '</option>';
                        });
                    } else {
                        options += '<option value="" disabled>Aucune matière trouvée pour cette classe</option>';
                    }
                    $('#subject_id').html(options);
                },
                error: function() {
                    $('#subject_id').html('<option value="">Erreur de chargement</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Toutes les sections</option>');
            $('#subject_id').html('<option value="">Toutes les matières</option>');
        }
    });

    // Load students
    $('#load-students').click(function() {
        var classId = $('#my_class_id').val();
        var sectionId = $('#section_id').val();
        var date = $('#date').val();

        if (!classId || !date) {
            alert('Veuillez sélectionner une classe et une date');
            return;
        }

        $.ajax({
            url: '<?php echo e(route("attendance.get_students")); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                my_class_id: classId,
                section_id: sectionId,
                date: date
            },
            beforeSend: function() {
                $('#students-list').html('<tr><td colspan="5" class="text-center"><i class="icon-spinner2 spinner mr-2"></i>Chargement des étudiants...</td></tr>');
                $('#students-container').show();
            },
            success: function(response) {
                // Debug info
                if (response.debug) {
                    console.log('Debug Info:', response.debug);
                }
                
                if (response.success && response.students.length > 0) {
                    var html = '';
                    response.students.forEach(function(student, index) {
                        var selectedPresent = student.status === 'present' ? 'checked' : '';
                        var selectedAbsent = student.status === 'absent' ? 'checked' : '';
                        var selectedLate = student.status === 'late' ? 'checked' : '';
                        var selectedExcused = student.status === 'excused' ? 'checked' : '';
                        
                        html += '<tr>';
                        html += '<td>' + (index + 1) + '</td>';
                        html += '<td>' + student.adm_no + '</td>';
                        html += '<td><strong>' + student.name + '</strong></td>';
                        html += '<td>';
                        html += '<input type="hidden" name="attendance[' + index + '][student_id]" value="' + student.id + '">';
                        html += '<div class="btn-group btn-group-toggle" data-toggle="buttons">';
                        html += '<label class="btn btn-sm btn-outline-success ' + (selectedPresent ? 'active' : '') + '">';
                        html += '<input type="radio" name="attendance[' + index + '][status]" value="present" ' + selectedPresent + '> Présent';
                        html += '</label>';
                        html += '<label class="btn btn-sm btn-outline-danger ' + (selectedAbsent ? 'active' : '') + '">';
                        html += '<input type="radio" name="attendance[' + index + '][status]" value="absent" ' + selectedAbsent + '> Absent';
                        html += '</label>';
                        html += '<label class="btn btn-sm btn-outline-warning ' + (selectedLate ? 'active' : '') + '">';
                        html += '<input type="radio" name="attendance[' + index + '][status]" value="late" ' + selectedLate + '> Retard';
                        html += '</label>';
                        html += '<label class="btn btn-sm btn-outline-info ' + (selectedExcused ? 'active' : '') + '">';
                        html += '<input type="radio" name="attendance[' + index + '][status]" value="excused" ' + selectedExcused + '> Excusé';
                        html += '</label>';
                        html += '</div>';
                        html += '</td>';
                        html += '<td><input type="text" name="attendance[' + index + '][notes]" class="form-control form-control-sm" placeholder="Notes..." value="' + (student.notes || '') + '"></td>';
                        html += '</tr>';
                    });
                    $('#students-list').html(html);
                } else {
                    $('#students-list').html('<tr><td colspan="5" class="text-center text-muted">Aucun étudiant trouvé</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                var errorMsg = 'Erreur lors du chargement des étudiants';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                }
                $('#students-list').html('<tr><td colspan="5" class="text-center text-danger">' + errorMsg + '</td></tr>');
            }
        });
    });

    // Form validation
    $('#attendance-form').submit(function(e) {
        var checkedCount = $('input[type="radio"]:checked').length;

        if (checkedCount === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner le statut de présence pour au moins un étudiant');
            return false;
        }
        
        // Confirm before submit
        if (!confirm('Voulez-vous enregistrer la présence pour ' + checkedCount + ' étudiant(s) ?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/attendance/index.blade.php ENDPATH**/ ?>