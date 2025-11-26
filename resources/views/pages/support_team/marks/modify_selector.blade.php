<form method="GET" action="{{ route('marks.modify') }}" id="modify-form">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="font-weight-bold">Classe <span class="text-danger">*</span></label>
                <select name="class_id" id="modify_class_id" class="form-control select" required onchange="loadModifySubjects()">
                    <option value="">-- Sélectionner une classe --</option>
                    @foreach($my_classes as $c)
                        <option value="{{ $c->id }}">{{ $c->full_name ?: $c->name }}</option>
                    @endforeach
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

{{-- Zone d'affichage des notes à modifier (chargée via AJAX ou nouvelle page) --}}
<div id="modify-notes-container"></div>

<script>
// Données des matières par classe
const modifySubjectsByClass = @json($subjects->groupBy('my_class_id'));

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
