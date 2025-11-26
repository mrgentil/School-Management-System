<form method="GET" action="<?php echo e(route('marks.modify')); ?>" id="modify-form">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="font-weight-bold">Classe <span class="text-danger">*</span></label>
                <select name="class_id" id="modify_class_id" class="form-control select" required onchange="loadModifySubjects()">
                    <option value="">-- Sélectionner une classe --</option>
                    <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>"><?php echo e($c->full_name ?: $c->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label class="font-weight-bold">Matière <span class="text-danger">*</span></label>
                <select name="subject_id" id="modify_subject_id" class="form-control select" required>
                    <option value="">-- Sélectionner d'abord la classe --</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label class="font-weight-bold">Période <span class="text-danger">*</span></label>
                <select name="period" id="modify_period" class="form-control select" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="1">Période 1</option>
                    <option value="2">Période 2</option>
                    <option value="3">Période 3</option>
                    <option value="4">Période 4</option>
                    <option value="all">Toutes les périodes</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label class="font-weight-bold">&nbsp;</label>
                <button type="submit" class="btn btn-warning btn-block">
                    <i class="icon-pencil mr-2"></i>Modifier les Notes
                </button>
            </div>
        </div>
    </div>
</form>


<div id="modify-notes-container"></div>

<script>
// Données des matières par classe
const modifySubjectsByClass = <?php echo json_encode($subjects->groupBy('my_class_id'), 15, 512) ?>;

function loadModifySubjects() {
    const classId = document.getElementById('modify_class_id').value;
    const subjectSelect = document.getElementById('modify_subject_id');
    
    // Vider les options actuelles
    subjectSelect.innerHTML = '<option value="">-- Sélectionner une matière --</option>';
    
    if (classId && modifySubjectsByClass[classId]) {
        modifySubjectsByClass[classId].forEach(function(subject) {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            subjectSelect.appendChild(option);
        });
    }
}
</script>
<?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/modify_selector.blade.php ENDPATH**/ ?>