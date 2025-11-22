<form method="post" action="<?php echo e(route('marks.selector')); ?>">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-10">
                <fieldset>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exam_id" class="col-form-label font-weight-bold">Examen <span class="text-danger">*</span></label>
                                <select required id="exam_id" name="exam_id" data-placeholder="Sélectionner un examen" class="form-control select">
                                    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e($selected && $exam_id == $ex->id ? 'selected' : ''); ?> value="<?php echo e($ex->id); ?>"><?php echo e($ex->name); ?> (S<?php echo e($ex->semester); ?> - <?php echo e($ex->year); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subject_id" class="col-form-label font-weight-bold">Matière <span class="text-danger">*</span></label>
                                <select required id="subject_id" name="subject_id" data-placeholder="Sélectionner d'abord la classe" class="form-control select-search">
                                  <?php if($selected): ?>
                                        <?php $__currentLoopData = $subjects->where('my_class_id', $my_class_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($subject_id == $s->id ? 'selected' : ''); ?> value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        
                        <input type="hidden" id="section_id" name="section_id" value="<?php echo e($selected ? $section_id : ''); ?>">
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
    const subjectsByClass = <?php echo json_encode($subjects->groupBy('my_class_id'), 15, 512) ?>;
    const sectionsByClass = <?php echo json_encode($sections->groupBy('my_class_id'), 15, 512) ?>;
    
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
        
        // Réinitialiser le select des matières
        if (typeof $ !== 'undefined') {
            $('#subject_id').trigger('change');
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
<?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/selector.blade.php ENDPATH**/ ?>