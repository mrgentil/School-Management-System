<form method="post" action="<?php echo e(route('marks.selector')); ?>">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-10">
                <fieldset>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="evaluation_type" class="col-form-label font-weight-bold">Type d'Évaluation <span class="text-danger">*</span></label>
                                <select required id="evaluation_type" name="evaluation_type" onchange="handleEvaluationTypeChange()" class="form-control select">
                                    <option value="">-- Choisir le type --</option>
                                    <option value="devoir">📝 Devoir (Notes par période)</option>
                                    <option value="interrogation">📋 Interrogation (Notes par période)</option>
                                    <option value="examen">📚 Examen (Semestriel)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period" class="col-form-label font-weight-bold">Période <span class="text-danger">*</span></label>
                                <select id="period" name="period" class="form-control select">
                                    <option value="">-- Sélectionner une période --</option>
                                    <option value="1">Période 1</option>
                                    <option value="2">Période 2</option>
                                    <option value="3">Période 3</option>
                                    <option value="4">Période 4</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6" id="interrogation-score-group" style="display: none;">
                            <div class="form-group">
                                <label for="interrogation_max_score">Cette interrogation est notée sur <span class="text-danger">*</span></label>
                                <input type="number" 
                                       id="interrogation_max_score" 
                                       name="interrogation_max_score" 
                                       class="form-control" 
                                       placeholder="Ex: 10, 15, 20..." 
                                       min="1" 
                                       step="0.5">
                                <small class="form-text text-muted">
                                    <i class="icon-info22 mr-1"></i>
                                    Entrez le total de points de cette interrogation (ex: 10 pour une interro sur 10)
                                </small>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="my_class_id" class="col-form-label font-weight-bold">Classe <span class="text-danger">*</span></label>
                                <select required onchange="getClassSubjects(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                    <option value="">-- Choisir une classe --</option>
                                    <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e(($selected && $my_class_id == $c->id) ? 'selected' : ''); ?> value="<?php echo e($c->id); ?>"><?php echo e($c->full_name ?: $c->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject_id" class="col-form-label font-weight-bold">Matière <span class="text-danger">*</span></label>
                                <select required id="subject_id" name="subject_id" onchange="loadSubjectConfig(); loadAssignments();" data-placeholder="Sélectionner d'abord la classe" class="form-control select-search">
                                  <?php if($selected): ?>
                                        <?php $__currentLoopData = $subjects->where('my_class_id', $my_class_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($subject_id == $s->id ? 'selected' : ''); ?> value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group exam-selector" style="display:none;">
                                <label for="exam_id" class="col-form-label font-weight-bold">Examen <span class="text-danger">*</span></label>
                                <select id="exam_id" name="exam_id" data-placeholder="Sélectionner un examen" class="form-control select">
                                    <option value="">-- Sélectionner un examen --</option>
                                    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e($selected && $exam_id == $ex->id ? 'selected' : ''); ?> value="<?php echo e($ex->id); ?>"><?php echo e($ex->name); ?> (S<?php echo e($ex->semester); ?> - <?php echo e($ex->year); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="form-group assignment-selector" style="display:none;">
                                <label for="assignment_id" class="col-form-label font-weight-bold">Devoir <span class="text-danger">*</span></label>
                                <select id="assignment_id" name="assignment_id" class="form-control select">
                                    <option value="">-- Sélectionner un devoir --</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label class="col-form-label font-weight-bold">Configuration RDC</label>
                                <div id="subject-config" class="alert alert-info border-0 p-2" style="display:none;">
                                    <small>
                                        <strong>Cotes:</strong> 
                                        <span class="badge badge-primary period-score">Période: --</span>
                                        <span class="badge badge-success exam-score">Examen: --</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    
                    <input type="hidden" id="section_id" name="section_id" value="<?php echo e($selected ? $section_id : ''); ?>">

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
    const subjectsByClass = <?php echo json_encode($subjects->groupBy('my_class_id'), 15, 512) ?>;
    const sectionsByClass = <?php echo json_encode($sections->groupBy('my_class_id'), 15, 512) ?>;
    
    // Gestion du changement de type d'évaluation
    function handleEvaluationTypeChange() {
        const evaluationType = document.getElementById('evaluation_type').value;
        const periodSelect = document.getElementById('period');
        const examSelector = document.querySelector('.exam-selector');
        const assignmentSelector = document.querySelector('.assignment-selector');
        const examIdSelect = document.getElementById('exam_id');
        const assignmentIdSelect = document.getElementById('assignment_id');
        
        // Réinitialiser tous les champs
        periodSelect.disabled = true;
        periodSelect.value = '';
        periodSelect.required = false;
        examSelector.style.display = 'none';
        assignmentSelector.style.display = 'none';
        examIdSelect.required = false;
        assignmentIdSelect.required = false;
        // Cacher et réinitialiser le champ de cote d'interrogation
        document.getElementById('interrogation-score-group').style.display = 'none';
        document.getElementById('interrogation_max_score').required = false;
        document.getElementById('interrogation_max_score').value = '';
        
        if (evaluationType === 'devoir') {
            // Pour devoirs : période obligatoire + sélecteur de devoirs
            periodSelect.disabled = false;
            periodSelect.required = true;
            assignmentSelector.style.display = 'block';
            assignmentIdSelect.required = true;
        } else if (evaluationType === 'interrogation') {
            // Pour interrogations : période obligatoire + champ pour la cote de l'interrogation
            periodSelect.disabled = false;
            periodSelect.required = true;
            // assignmentSelector reste caché pour les interrogations
            assignmentSelector.style.display = 'none';
            assignmentIdSelect.required = false;
            // Afficher le champ pour la cote de l'interrogation
            document.getElementById('interrogation-score-group').style.display = 'block';
            document.getElementById('interrogation_max_score').required = true;
        } else if (evaluationType === 'examen') {
            // Pour examens : sélecteur d'examen
            examSelector.style.display = 'block';
            examIdSelect.required = true;
        }
        
        // Recharger les devoirs si nécessaire
        loadAssignments();
    }
    
    function getClassSubjects(classId) {
        const subjectSelect = document.getElementById('subject_id');
        const sectionHidden = document.getElementById('section_id');
        
        // Vider les options actuelles
        subjectSelect.innerHTML = '<option value="">-- Sélectionner une matière --</option>';
        
        if (classId && subjectsByClass[classId]) {
            // Ajouter les matières de cette classe
            subjectsByClass[classId].forEach(function(subject) {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectSelect.appendChild(option);
            });
        }
        
        // Automatiquement sélectionner la première section de la classe
        if (classId && sectionsByClass[classId] && sectionsByClass[classId].length > 0) {
            sectionHidden.value = sectionsByClass[classId][0].id;
            console.log('Section auto-sélectionnée:', sectionsByClass[classId][0].name, 'ID:', sectionsByClass[classId][0].id);
        } else {
            sectionHidden.value = '';
            console.log('Aucune section trouvée pour la classe:', classId);
        }
        
        // Réinitialiser le select des matières et charger la config
        if (typeof $ !== 'undefined') {
            $('#subject_id').trigger('change');
        }
        
        // Recharger les devoirs et la configuration
        loadSubjectConfig();
        loadAssignments();
    }
    
    // Charger la configuration des cotes pour la matière
    function loadSubjectConfig() {
        const classId = document.getElementById('my_class_id').value;
        const subjectId = document.getElementById('subject_id').value;
        const configDiv = document.getElementById('subject-config');
        
        if (!classId || !subjectId) {
            configDiv.style.display = 'none';
            return;
        }
        
        // Appel AJAX pour récupérer la configuration
        fetch(`/subject-grades-config/get-config?class_id=${classId}&subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.config) {
                    const periodScore = document.querySelector('.period-score');
                    const examScore = document.querySelector('.exam-score');
                    
                    periodScore.textContent = `Période: ${data.config.period_max_score}`;
                    examScore.textContent = `Examen: ${data.config.exam_max_score}`;
                    
                    configDiv.style.display = 'block';
                } else {
                    configDiv.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement de la configuration:', error);
                configDiv.style.display = 'none';
            });
    }
    
    // Charger les devoirs disponibles
    function loadAssignments() {
        const evaluationType = document.getElementById('evaluation_type').value;
        const classId = document.getElementById('my_class_id').value;
        const subjectId = document.getElementById('subject_id').value;
        const period = document.getElementById('period').value;
        const assignmentSelect = document.getElementById('assignment_id');
        
        // Vider les options
        assignmentSelect.innerHTML = '<option value="">-- Sélectionner un devoir --</option>';
        
        if (evaluationType === 'devoir' && classId && subjectId && period) {
            // Appel AJAX pour récupérer les devoirs (seulement pour le type 'devoir')
            fetch(`/assignments/get-by-criteria?class_id=${classId}&subject_id=${subjectId}&period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.assignments) {
                        data.assignments.forEach(assignment => {
                            const option = document.createElement('option');
                            option.value = assignment.id;
                            option.textContent = `${assignment.title} (${assignment.max_score} pts) - ${assignment.due_date}`;
                            assignmentSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des devoirs:', error);
                });
        }
    }
    
    // Initialiser au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('my_class_id');
        if (classSelect.value) {
            getClassSubjects(classSelect.value);
        }
        
        // Initialiser le type d'évaluation
        handleEvaluationTypeChange();
    });
</script>
<?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/selector.blade.php ENDPATH**/ ?>