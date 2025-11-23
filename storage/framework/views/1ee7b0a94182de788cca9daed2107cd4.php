<form class="ajax-update" action="<?php echo e(route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id])); ?>" method="post">
    <?php echo csrf_field(); ?> <?php echo method_field('put'); ?>
    
    
    <div class="alert alert-info border-0 mb-3">
        <strong>🔍 DEBUG:</strong>
        grade_config: <?php echo e($grade_config ? 'OUI' : 'NON'); ?> |
        is_semester_exam: <?php echo e(isset($is_semester_exam) ? ($is_semester_exam ? 'OUI' : 'NON') : 'NON DÉFINI'); ?> |
        current_semester: <?php echo e($current_semester ?? 'NON DÉFINI'); ?>

    </div>

    <?php if($grade_config): ?>
        <div class="alert alert-success border-0 mb-3">
            <i class="icon-info22 mr-2"></i>
            <strong>Configuration RDC:</strong> 
            Cote Période: <span class="badge badge-primary"><?php echo e($grade_config->period_max_score); ?></span>
            | Cote Examen: <span class="badge badge-success"><?php echo e($grade_config->exam_max_score); ?></span>
            <?php if(isset($is_semester_exam) && $is_semester_exam): ?>
                | <strong>Examen Semestre <?php echo e($current_semester); ?></strong>
            <?php else: ?>
                | <strong>Évaluations de Période</strong>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning border-0 mb-3">
            <i class="icon-warning22 mr-2"></i>
            <strong>Attention:</strong> Aucune configuration de cotes RDC trouvée. Veuillez configurer les cotes pour cette classe/matière.
        </div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <?php if(isset($is_semester_exam) && $is_semester_exam && $grade_config): ?>
            
            <table class="table table-bordered table-striped">
                <thead class="bg-success text-white">
                <tr>
                    <th width="5%">N°</th>
                    <th width="25%">Nom de l'Étudiant</th>
                    <th width="10%">Matricule</th>
                    <th width="20%">Examen S<?php echo e($current_semester); ?> (<?php echo e($grade_config->exam_max_score); ?>)</th>
                    <th width="15%">Pourcentage</th>
                    <th width="15%">Points/20</th>
                    <th width="10%">Mention</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $marks->sortBy('user.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $examColumn = $current_semester == 1 ? 's1_exam' : 's2_exam';
                        $examScore = $mk->$examColumn ?? 0;
                        $percentage = $grade_config->exam_max_score > 0 ? ($examScore / $grade_config->exam_max_score) * 100 : 0;
                        $points = ($percentage / 100) * 20;
                        
                        if ($percentage >= 80) $mention = 'Très Bien';
                        elseif ($percentage >= 70) $mention = 'Bien';
                        elseif ($percentage >= 60) $mention = 'Assez Bien';
                        elseif ($percentage >= 50) $mention = 'Passable';
                        else $mention = 'Insuffisant';
                    ?>
                    <tr>
                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                        <td><strong><?php echo e($mk->user->name); ?></strong></td>
                        <td class="text-center"><?php echo e($mk->user->student_record->adm_no ?? 'N/A'); ?></td>
                        <td>
                            <input title="Examen Semestre <?php echo e($current_semester); ?>" 
                                   min="0" 
                                   max="<?php echo e($grade_config->exam_max_score); ?>" 
                                   class="form-control text-center exam-input" 
                                   name="<?php echo e($examColumn); ?>_<?php echo e($mk->id); ?>" 
                                   value="<?php echo e($examScore); ?>" 
                                   type="number" 
                                   step="0.25"
                                   data-max="<?php echo e($grade_config->exam_max_score); ?>"
                                   data-student-id="<?php echo e($mk->id); ?>">
                        </td>
                        <td class="text-center">
                            <span class="badge badge-primary percentage-display" data-student-id="<?php echo e($mk->id); ?>">
                                <?php echo e(number_format($percentage, 1)); ?>%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="points-display" data-student-id="<?php echo e($mk->id); ?>">
                                <?php echo e(number_format($points, 2)); ?>/20
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="mention-display badge 
                                <?php if($percentage >= 80): ?> badge-success
                                <?php elseif($percentage >= 70): ?> badge-info
                                <?php elseif($percentage >= 60): ?> badge-warning
                                <?php elseif($percentage >= 50): ?> badge-secondary
                                <?php else: ?> badge-danger <?php endif; ?>" 
                                data-student-id="<?php echo e($mk->id); ?>">
                                <?php echo e($mention); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            
            <table class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                <tr>
                    <th width="3%">N°</th>
                    <th width="20%">Nom de l'Étudiant</th>
                    <th width="8%">Matricule</th>
                    <th width="8%">T1 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">T2 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">T3 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">T4 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">TCA (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">TEX1 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">TEX2 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="8%">TEX3 (<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>)</th>
                    <th width="5%">%</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $marks->sortBy('user.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                        <td><strong><?php echo e($mk->user->name); ?></strong></td>
                        <td class="text-center"><?php echo e($mk->user->student_record->adm_no ?? 'N/A'); ?></td>
                        
                        
                        <td><input title="Devoir Période 1" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="t1_<?php echo e($mk->id); ?>" value="<?php echo e($mk->t1); ?>" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 2" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="t2_<?php echo e($mk->id); ?>" value="<?php echo e($mk->t2); ?>" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 3" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="t3_<?php echo e($mk->id); ?>" value="<?php echo e($mk->t3); ?>" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 4" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="t4_<?php echo e($mk->id); ?>" value="<?php echo e($mk->t4); ?>" type="number" step="0.25"></td>
                        
                        
                        <td><input title="TCA - Travaux Continus" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="tca_<?php echo e($mk->id); ?>" value="<?php echo e($mk->tca); ?>" type="number" step="0.25"></td>
                        <td><input title="TEX1 - Travaux Expression 1" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="tex1_<?php echo e($mk->id); ?>" value="<?php echo e($mk->tex1); ?>" type="number" step="0.25"></td>
                        <td><input title="TEX2 - Travaux Expression 2" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="tex2_<?php echo e($mk->id); ?>" value="<?php echo e($mk->tex2); ?>" type="number" step="0.25"></td>
                        <td><input title="TEX3 - Travaux Expression 3" min="0" max="<?php echo e($grade_config ? $grade_config->period_max_score : 20); ?>" class="form-control text-center period-input" name="tex3_<?php echo e($mk->id); ?>" value="<?php echo e($mk->tex3); ?>" type="number" step="0.25"></td>
                        
                        
                        <td class="text-center">
                            <span class="badge badge-info period-percentage" data-student-id="<?php echo e($mk->id); ?>">
                                --
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="icon-checkmark3 mr-2"></i>Enregistrer les Notes
        </button>
    </div>
</form>

<?php if($grade_config): ?>
<script>
$(document).ready(function() {
    const maxPoints = <?php echo e($grade_config->period_max_score ?? 20); ?>;
    const examMaxPoints = <?php echo e($grade_config->exam_max_score ?? 40); ?>;
    const isExam = <?php echo e($is_semester_exam ? 'true' : 'false'); ?>;
    
    // Calcul automatique pour les examens
    if (isExam) {
        $('.exam-input').on('input', function() {
            const studentId = $(this).data('student-id');
            const score = parseFloat($(this).val()) || 0;
            
            if (score > examMaxPoints) {
                $(this).val(examMaxPoints);
                return;
            }
            
            const percentage = examMaxPoints > 0 ? (score / examMaxPoints) * 100 : 0;
            const points = (percentage / 100) * 20;
            
            let mention = 'Insuffisant';
            let badgeClass = 'badge-danger';
            
            if (percentage >= 80) { mention = 'Très Bien'; badgeClass = 'badge-success'; }
            else if (percentage >= 70) { mention = 'Bien'; badgeClass = 'badge-info'; }
            else if (percentage >= 60) { mention = 'Assez Bien'; badgeClass = 'badge-warning'; }
            else if (percentage >= 50) { mention = 'Passable'; badgeClass = 'badge-secondary'; }
            
            $(`[data-student-id="${studentId}"].percentage-display`).text(percentage.toFixed(1) + '%');
            $(`[data-student-id="${studentId}"].points-display`).text(points.toFixed(2) + '/20');
            $(`[data-student-id="${studentId}"].mention-display`)
                .removeClass('badge-success badge-info badge-warning badge-secondary badge-danger')
                .addClass(badgeClass)
                .text(mention);
        });
    } else {
        // Calcul automatique pour les périodes (système RDC)
        $('.period-input').on('input', function() {
            const row = $(this).closest('tr');
            const studentId = row.find('.period-percentage').data('student-id');
            
            // Validation de la note
            const inputMax = parseFloat($(this).attr('max'));
            const score = parseFloat($(this).val()) || 0;
            
            if (score > inputMax) {
                $(this).val(inputMax);
                return;
            }
            
            // Calcul de la moyenne pondérée RDC
            const t1 = parseFloat(row.find('input[name^="t1_"]').val()) || 0;
            const t2 = parseFloat(row.find('input[name^="t2_"]').val()) || 0;
            const t3 = parseFloat(row.find('input[name^="t3_"]').val()) || 0;
            const t4 = parseFloat(row.find('input[name^="t4_"]').val()) || 0;
            const tca = parseFloat(row.find('input[name^="tca_"]').val()) || 0;
            const tex1 = parseFloat(row.find('input[name^="tex1_"]').val()) || 0;
            const tex2 = parseFloat(row.find('input[name^="tex2_"]').val()) || 0;
            const tex3 = parseFloat(row.find('input[name^="tex3_"]').val()) || 0;
            
            // Pondération RDC
            const testsAvg = (t1 + t2 + t3 + t4) / 4; // Moyenne des devoirs
            const tcaWeight = 0.3;
            const tex1Weight = 0.1;
            const tex2Weight = 0.05;
            const tex3Weight = 0.05;
            const testsWeight = 0.5;
            
            const weightedAvg = (testsAvg * testsWeight) + (tca * tcaWeight) + 
                               (tex1 * tex1Weight) + (tex2 * tex2Weight) + (tex3 * tex3Weight);
            
            const percentage = maxPoints > 0 ? (weightedAvg / maxPoints) * 100 : 0;
            
            row.find('.period-percentage').text(percentage.toFixed(1) + '%');
        });
        
        // Calcul initial
        $('.period-input').trigger('input');
    }
});
</script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/edit.blade.php ENDPATH**/ ?>