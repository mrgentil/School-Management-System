
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
                <h6><i class="icon-info22 mr-2"></i>Comment utiliser la Feuille de Tabulation :</h6>
                <ul class="mb-0">
                    <li><strong>Sélectionnez un examen</strong> - Choisissez l'examen pour lequel vous voulez voir les résultats</li>
                    <li><strong>Choisissez une classe</strong> - Sélectionnez la classe concernée</li>
                    <li><strong>Sélectionnez une section</strong> - Choisissez la section/division de la classe</li>
                    <li><strong>Cliquez sur "Afficher la Feuille"</strong> - Le tableau avec toutes les notes apparaîtra</li>
                </ul>
                <small class="text-muted"><strong>Note :</strong> Des notes doivent avoir été saisies au préalable pour que la feuille s'affiche.</small>
            </div>
            
        <form method="post" action="<?php echo e(route('marks.tabulation_select')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exam_id" class="col-form-label font-weight-bold">Examen :</label>
                                            <select required id="exam_id" name="exam_id" class="form-control select" data-placeholder="Sélectionner un examen">
                                                <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php echo e(($selected && $exam_id == $exm->id) ? 'selected' : ''); ?> value="<?php echo e($exm->id); ?>"><?php echo e($exm->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <h6 class="card-title font-weight-bold">Feuille de Tabulation pour <?php echo e(($my_class->full_name ?: $my_class->name).' '.$section->name.' - '.$ex->name.' ('.$year.')'); ?></h6>
            </div>
            <div class="card-body">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMS DES ÉTUDIANTS</th>
                       <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       <th title="<?php echo e($sub->name); ?>" rowspan="2"><?php echo e(strtoupper($sub->slug ?: $sub->name)); ?></th>
                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <th style="color: darkred">TOTAL</th>
                        <th style="color: darkblue">MOYENNE</th>
                        <th style="color: darkgreen">POSITION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td style="text-align: center"><?php echo e($s->user->name); ?></td>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e($marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->$tex ?? '-' ?: '-'); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            

                            <td style="color: darkred"><?php echo e($exr->where('student_id', $s->user_id)->first()->total ?: '-'); ?></td>
                            <td style="color: darkblue"><?php echo e($exr->where('student_id', $s->user_id)->first()->ave ?: '-'); ?></td>
                            <td style="color: darkgreen"><?php echo Mk::getSuffix($exr->where('student_id', $s->user_id)->first()->pos) ?: '-'; ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                
                <div class="text-center mt-4">
                    <a target="_blank" href="<?php echo e(route('marks.print_tabulation', [$exam_id, $my_class_id, $section_id])); ?>" class="btn btn-danger btn-lg"><i class="icon-printer mr-2"></i> Imprimer la Feuille de Tabulation</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/marks/tabulation/index.blade.php ENDPATH**/ ?>