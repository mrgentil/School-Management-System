<form method="post" action="{{ route('marks.selector') }}">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <fieldset>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exam_id" class="col-form-label font-weight-bold">Examen <span class="text-danger">*</span></label>
                                <select required id="exam_id" name="exam_id" data-placeholder="Sélectionner un examen" class="form-control select">
                                    @foreach($exams as $ex)
                                        <option {{ $selected && $exam_id == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }} (S{{ $ex->semester }} - {{ $ex->year }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="my_class_id" class="col-form-label font-weight-bold">Classe <span class="text-danger">*</span></label>
                                <select required onchange="getClassSubjects(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                    <option value="">-- Choisir une classe --</option>
                                    @foreach($my_classes as $c)
                                        <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->full_name ?: $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id" class="col-form-label font-weight-bold">Section <span class="text-danger">*</span></label>
                                <select required id="section_id" name="section_id" data-placeholder="Sélectionner d'abord la classe" class="form-control select">
                                   @if($selected)
                                        @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                       @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="subject_id" class="col-form-label font-weight-bold">Matière <span class="text-danger">*</span></label>
                                <select required id="subject_id" name="subject_id" data-placeholder="Sélectionner d'abord la classe" class="form-control select-search">
                                  @if($selected)
                                        @foreach($subjects->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $subject_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                      @endif
                                </select>
                            </div>
                        </div>
                    </div>

                </fieldset>
            </div>

            <div class="col-md-2 mt-4">
                <div class="text-right mt-1">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="icon-arrow-right8 mr-2"></i>Continuer
                    </button>
                </div>
            </div>

        </div>

    </form>

<script>
    // Données des matières par classe (passées depuis le contrôleur)
    const subjectsByClass = @json($subjects->groupBy('my_class_id'));
    const sectionsByClass = @json($sections->groupBy('my_class_id'));
    
    function getClassSubjects(classId) {
        const subjectSelect = document.getElementById('subject_id');
        const sectionSelect = document.getElementById('section_id');
        
        // Vider les options actuelles
        subjectSelect.innerHTML = '<option value="">-- Sélectionner une matière --</option>';
        sectionSelect.innerHTML = '<option value="">-- Sélectionner une section --</option>';
        
        if (classId && subjectsByClass[classId]) {
            // Ajouter les matières de cette classe
            subjectsByClass[classId].forEach(function(subject) {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
        }
        
        if (classId && sectionsByClass[classId]) {
            // Ajouter les sections de cette classe
            sectionsByClass[classId].forEach(function(section) {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
        }
        
        // Réinitialiser les selects
        if (typeof $ !== 'undefined') {
            $('#subject_id').trigger('change');
            $('#section_id').trigger('change');
        }
    }
    
    // Initialiser au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('my_class_id');
        if (classSelect.value) {
            getClassSubjects(classSelect.value);
        }
    });
</script>
