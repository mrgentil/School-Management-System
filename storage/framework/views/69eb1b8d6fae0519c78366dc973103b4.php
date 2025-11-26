
<?php $__env->startSection('page_title', 'Feuille de Tabulation'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_analytics.index')); ?>" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analyses & Rapports
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('exam_schedules.index')); ?>" class="btn btn-primary btn-block">
                <i class="icon-calendar mr-2"></i>Calendrier d'Examens
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('marks.index')); ?>" class="btn btn-info btn-block">
                <i class="icon-pencil5 mr-2"></i>Saisir les Notes
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('exams.dashboard')); ?>" class="btn btn-warning btn-block">
                <i class="icon-grid mr-2"></i>Tableau de Bord Examens
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-table2 mr-2"></i> Feuille de Tabulation</h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <h6><i class="icon-info22 mr-2"></i>Comment utiliser la Feuille de Tabulation RDC :</h6>
                <ul class="mb-0">
                    <li><strong>Sélectionnez le type</strong> - Période (P1-P4) ou Semestre (S1-S2)</li>
                    <li><strong>Choisissez la période/semestre</strong> - Selon votre sélection</li>
                    <li><strong>Choisissez une classe</strong> - Sélectionnez la classe concernée</li>
                    <li><strong>Sélectionnez une section</strong> - Choisissez la section/division de la classe</li>
                    <li><strong>Cliquez sur "Afficher la Feuille"</strong> - Le tableau avec toutes les notes apparaîtra</li>
                </ul>
                <small class="text-muted"><strong>Système RDC :</strong> Affiche les moyennes calculées avec pondération (devoirs + interrogations + interro générale)</small>
            </div>
            
        <form method="post" action="<?php echo e(route('marks.tabulation_select')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="evaluation_type" class="col-form-label font-weight-bold">Type d'évaluation :</label>
                                            <select required id="evaluation_type" name="evaluation_type" class="form-control select" onchange="handleTypeChange()">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="period" <?php echo e(($selected && isset($evaluation_type) && $evaluation_type == 'period') ? 'selected' : ''); ?>>📊 Période</option>
                                                <option value="semester" <?php echo e(($selected && isset($evaluation_type) && $evaluation_type == 'semester') ? 'selected' : ''); ?>>📚 Semestre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="period-selector" style="display: none;">
                                        <div class="form-group">
                                            <label for="period" class="col-form-label font-weight-bold">Période :</label>
                                            <select id="period" name="period" class="form-control select">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="1" <?php echo e(($selected && isset($period) && $period == 1) ? 'selected' : ''); ?>>Période 1</option>
                                                <option value="2" <?php echo e(($selected && isset($period) && $period == 2) ? 'selected' : ''); ?>>Période 2</option>
                                                <option value="3" <?php echo e(($selected && isset($period) && $period == 3) ? 'selected' : ''); ?>>Période 3</option>
                                                <option value="4" <?php echo e(($selected && isset($period) && $period == 4) ? 'selected' : ''); ?>>Période 4</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="semester-selector" style="display: none;">
                                        <div class="form-group">
                                            <label for="semester" class="col-form-label font-weight-bold">Semestre :</label>
                                            <select id="semester" name="semester" class="form-control select">
                                                <option value="">-- Sélectionner --</option>
                                                <option value="1" <?php echo e(($selected && isset($semester) && $semester == 1) ? 'selected' : ''); ?>>Semestre 1</option>
                                                <option value="2" <?php echo e(($selected && isset($semester) && $semester == 2) ? 'selected' : ''); ?>>Semestre 2</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="my_class_id" class="col-form-label font-weight-bold">Classe :</label>
                                            <select onchange="getClassSections(this.value)" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Sélectionner une classe">
                                                <option value=""></option>
                                                <?php $__currentLoopData = $my_classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e(($selected && $my_class_id == $c->id) ? 'selected' : ''); ?> value="<?php echo e($c->id); ?>"><?php echo e($c->full_name ?: $c->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id" class="col-form-label font-weight-bold">Section :</label>
                                <select required id="section_id" name="section_id" data-placeholder="Sélectionner d'abord une classe" class="form-control select">
                                    <?php if($selected): ?>
                                        <?php $__currentLoopData = $sections->where('my_class_id', $my_class_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e($section_id == $s->id ? 'selected' : ''); ?> value="<?php echo e($s->id); ?>"><?php echo e($s->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2 mt-4">
                            <div class="text-right mt-1">
                                <button type="submit" class="btn btn-primary">Afficher la Feuille <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>

    

    <?php if($selected): ?>
        <div class="card">
            <div class="card-header">
                <h6 class="card-title font-weight-bold">
                    Feuille de Tabulation <?php echo e($title ?? ''); ?> - <?php echo e(($my_class->full_name ?: $my_class->name).' '.$section->name.' ('.$year.')'); ?>

                </h6>
            </div>
            <div class="card-body">
                <?php if(empty($rankings) || count($rankings) === 0): ?>
                    <div class="alert alert-warning">
                        <i class="icon-alert mr-2"></i>
                        <strong>Aucune donnée disponible !</strong><br>
                        Aucun étudiant n'a de notes pour cette période/ce semestre. Assurez-vous d'avoir saisi :
                        <ul class="mb-0 mt-2">
                            <li>Les notes de devoirs pour cette période</li>
                            <li>Les notes d'interrogations (colonnes t1-t4)</li>
                            <li>Les notes d'interrogation générale (TCA)</li>
                        </ul>
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="50" class="text-center">#</th>
                                <th>ÉTUDIANT</th>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="text-center" title="<?php echo e($sub->name); ?>"><?php echo e(strtoupper($sub->slug ?: Str::limit($sub->name, 10))); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center bg-warning">MOYENNE</th>
                                <th class="text-center bg-success">%</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                <td><strong><?php echo e($s->user->name); ?></strong></td>
                                
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $subjectData = null;
                                        if (isset($rankings[$s->user_id]) && 
                                            isset($rankings[$s->user_id]['subject_averages']) && 
                                            isset($rankings[$s->user_id]['subject_averages'][$sub->id]) &&
                                            isset($rankings[$s->user_id]['subject_averages'][$sub->id]['points'])) {
                                            $subjectData = $rankings[$s->user_id]['subject_averages'][$sub->id]['points'];
                                        }
                                    ?>
                                    <td class="text-center">
                                        <?php echo e($subjectData !== null ? number_format($subjectData, 2) : '-'); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <td class="text-center font-weight-bold text-primary">
                                    <?php echo e(isset($rankings[$s->user_id]) && isset($rankings[$s->user_id]['overall_points']) ? number_format($rankings[$s->user_id]['overall_points'], 2) : '-'); ?>

                                </td>
                                <td class="text-center font-weight-bold text-success">
                                    <?php echo e(isset($rankings[$s->user_id]) && isset($rankings[$s->user_id]['overall_percentage']) ? number_format($rankings[$s->user_id]['overall_percentage'], 2).'%' : '-'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                
                <div class="text-center mt-4">
                    <a href="<?php echo e(route('proclamations.index')); ?>" class="btn btn-primary btn-lg">
                        <i class="icon-trophy mr-2"></i> Voir Proclamations Détaillées
                    </a>
                    
                    <button onclick="window.print()" class="btn btn-danger btn-lg ml-2">
                        <i class="icon-printer mr-2"></i> Imprimer la Feuille
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

<script>
function handleTypeChange() {
    const evaluationType = document.getElementById('evaluation_type').value;
    const periodSelector = document.getElementById('period-selector');
    const semesterSelector = document.getElementById('semester-selector');
    const periodSelect = document.getElementById('period');
    const semesterSelect = document.getElementById('semester');
    
    // Réinitialiser
    periodSelector.style.display = 'none';
    semesterSelector.style.display = 'none';
    periodSelect.required = false;
    semesterSelect.required = false;
    periodSelect.value = '';
    semesterSelect.value = '';
    
    // Afficher le bon sélecteur
    if (evaluationType === 'period') {
        periodSelector.style.display = 'block';
        periodSelect.required = true;
    } else if (evaluationType === 'semester') {
        semesterSelector.style.display = 'block';
        semesterSelect.required = true;
    }
}

// Charger l'état initial si une sélection existe
document.addEventListener('DOMContentLoaded', function() {
    <?php if($selected && isset($evaluation_type)): ?>
        handleTypeChange();
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/marks/tabulation/index.blade.php ENDPATH**/ ?>