<?php $__env->startSection('page_title', 'Mon Bulletin - ' . (($type ?? 'period') == 'semester' ? 'Semestre ' . ($semester ?? 1) : 'Période ' . ($period ?? 1))); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    
    <div class="card-header bg-dark-green text-white py-3">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <?php if(isset($school['logo']) && $school['logo']): ?>
                    <img src="<?php echo e(asset('uploads/' . $school['logo'])); ?>" alt="Logo" style="max-height: 70px; max-width: 100%;">
                <?php else: ?>
                    <i class="icon-graduation2" style="font-size: 50px;"></i>
                <?php endif; ?>
            </div>
            <div class="col-md-7 text-center">
                <h4 class="mb-0"><?php echo e($school['name'] ?? 'École'); ?></h4>
                <small><?php echo e($school['address'] ?? ''); ?></small><br>
                <small><?php echo e($school['phone'] ?? ''); ?> | <?php echo e($school['email'] ?? ''); ?></small>
            </div>
            <div class="col-md-3 text-right">
                <span class="badge badge-light px-3 py-2">Année Scolaire<br><strong><?php echo e($year); ?></strong></span>
            </div>
        </div>
    </div>

    
    <div class="bg-success text-white text-center py-3">
        <h4 class="mb-0">
            <i class="icon-file-text2 mr-2"></i>
            <?php if(($type ?? 'period') == 'semester'): ?>
                BULLETIN DE NOTES - SEMESTRE <?php echo e($semester ?? 1); ?>

            <?php else: ?>
                BULLETIN DE NOTES - PÉRIODE <?php echo e($period ?? 1); ?>

            <?php endif; ?>
        </h4>
        <small>Année Scolaire <?php echo e($year); ?></small>
    </div>

    <div class="card-body">
        
        <div class="row mb-3">
            <div class="col-md-8">
                <form method="GET" class="form-inline">
                    <label class="mr-2">Type :</label>
                    <select name="type" id="bulletinType" class="form-control form-control-sm mr-3" onchange="toggleSelector()">
                        <option value="period" <?php echo e(($type ?? 'period') == 'period' ? 'selected' : ''); ?>>Période</option>
                        <option value="semester" <?php echo e(($type ?? 'period') == 'semester' ? 'selected' : ''); ?>>Semestre (avec Examen)</option>
                    </select>
                    
                    <span id="periodSelector" style="<?php echo e(($type ?? 'period') == 'semester' ? 'display:none;' : ''); ?>">
                        <label class="mr-2">Période :</label>
                        <select name="period" class="form-control form-control-sm mr-2">
                            <?php for($p = 1; $p <= 4; $p++): ?>
                                <option value="<?php echo e($p); ?>" <?php echo e(($period ?? 1) == $p ? 'selected' : ''); ?>>Période <?php echo e($p); ?></option>
                            <?php endfor; ?>
                        </select>
                    </span>
                    
                    <span id="semesterSelector" style="<?php echo e(($type ?? 'period') != 'semester' ? 'display:none;' : ''); ?>">
                        <label class="mr-2">Semestre :</label>
                        <select name="semester" class="form-control form-control-sm mr-2">
                            <option value="1" <?php echo e(($semester ?? 1) == 1 ? 'selected' : ''); ?>>Semestre 1</option>
                            <option value="2" <?php echo e(($semester ?? 1) == 2 ? 'selected' : ''); ?>>Semestre 2</option>
                        </select>
                    </span>
                    
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="icon-search4 mr-1"></i>Afficher
                    </button>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <button onclick="window.print()" class="btn btn-info btn-sm">
                    <i class="icon-printer mr-1"></i>Imprimer
                </button>
            </div>
        </div>

        
        <div class="row mb-4">
            <div class="col-md-2 text-center">
                <img src="<?php echo e($student->photo); ?>" alt="Photo" class="rounded" style="width: 90px; height: 90px; object-fit: cover; border: 2px solid #28a745;">
            </div>
            <div class="col-md-4">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Nom et Prénom:</td>
                        <td><strong><?php echo e($student->name); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Matricule:</td>
                        <td><?php echo e($student->code ?? 'N/A'); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Classe:</td>
                        <td><?php echo e($studentRecord->my_class ? ($studentRecord->my_class->full_name ?: $studentRecord->my_class->name) : 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Période:</td>
                        <td><span class="badge badge-success"><?php echo e($period ?? 1); ?></span></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Rang:</td>
                        <td><span class="badge badge-primary px-2"><?php echo e($rank ?? 'N/A'); ?><?php echo e($rank == 1 ? 'er' : 'ème'); ?> / <?php echo e($totalStudents); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Moyenne:</td>
                        <td><span class="badge badge-<?php echo e($overallPercentage >= 50 ? 'success' : 'danger'); ?> px-2"><?php echo e($overallAverage ?? 'N/A'); ?> / 20</span></td>
                    </tr>
                </table>
            </div>
        </div>

        
        <?php if(count($bulletinData) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th style="width: 20%;">MATIÈRE</th>
                            <?php if(($type ?? 'period') == 'semester'): ?>
                                <th class="text-center">MOY. PÉRIODES</th>
                                <th class="text-center">EXAMEN</th>
                            <?php endif; ?>
                            <th class="text-center">POINTS</th>
                            <th class="text-center">MAX</th>
                            <th class="text-center">%</th>
                            <th class="text-center">NOTE</th>
                            <th>APPRÉCIATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $bulletinData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($data['subject']->name); ?></strong></td>
                                <?php if(($type ?? 'period') == 'semester'): ?>
                                    <td class="text-center">
                                        <?php if($data['period_average'] !== null): ?>
                                            <?php echo e($data['period_average']); ?>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if($data['exam_average'] !== null): ?>
                                            <?php echo e($data['exam_average']); ?>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center">
                                    <?php if($data['points'] !== null): ?>
                                        <strong><?php echo e($data['points']); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo e($data['max']); ?></td>
                                <td class="text-center">
                                    <?php if($data['percentage'] !== null): ?>
                                        <span class="badge badge-<?php echo e($data['percentage'] >= 50 ? 'success' : 'danger'); ?>">
                                            <?php echo e($data['percentage']); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($data['grade']): ?>
                                        <strong><?php echo e($data['grade']); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($data['remark']): ?>
                                        <span class="text-<?php echo e($data['percentage'] >= 50 ? 'success' : 'danger'); ?>">
                                            <?php echo e($data['remark']); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <tr class="bg-light font-weight-bold">
                            <td>TOTAL GÉNÉRAL</td>
                            <?php if(($type ?? 'period') == 'semester'): ?>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                            <?php endif; ?>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">
                                <span class="badge badge-<?php echo e($overallPercentage >= 50 ? 'success' : 'danger'); ?> px-3">
                                    <?php echo e($overallPercentage); ?>%
                                </span>
                            </td>
                            <td class="text-center">-</td>
                            <td><?php echo e($appreciation); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-0"><?php echo e($overallAverage); ?></h4>
                            <small>Moyenne Générale</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-0"><?php echo e($rank ?? 'N/A'); ?><sup><?php echo e($rank == 1 ? 'er' : 'ème'); ?></sup></h4>
                            <small>Rang / <?php echo e($totalStudents); ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-0"><?php echo e($passedCount ?? 0); ?></h4>
                            <small>Matières Réussies</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-0"><?php echo e($failedCount ?? 0); ?></h4>
                            <small>Matières Échouées</small>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mt-4 border-success">
                <div class="card-header bg-success text-white">
                    <i class="icon-comment-discussion mr-2"></i>APPRÉCIATION GÉNÉRALE
                </div>
                <div class="card-body text-center">
                    <h5 class="mb-0">
                        <?php echo e($appreciation); ?> - 
                        <span class="text-<?php echo e($overallPercentage >= 50 ? 'success' : 'danger'); ?>">
                            <?php echo e($overallPercentage >= 50 ? 'Admis(e) à poursuivre' : 'Doit redoubler ses efforts'); ?>

                        </span>
                    </h5>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                Aucune note disponible pour cette période.
            </div>
        <?php endif; ?>
    </div>

    <div class="card-footer text-center text-muted">
        <small>Document généré le <?php echo e(date('d/m/Y à H:i')); ?> | <?php echo e($school['name'] ?? 'École'); ?> - Année Scolaire <?php echo e($year); ?></small>
    </div>
</div>

<style>
    .bg-dark-green { background-color: #1a3a2f !important; }
    @media print {
        .btn, form, .nav, .sidebar, .navbar { display: none !important; }
        .card { border: none !important; }
    }
</style>

<script>
    function toggleSelector() {
        var type = document.getElementById('bulletinType').value;
        var periodSelector = document.getElementById('periodSelector');
        var semesterSelector = document.getElementById('semesterSelector');
        
        if (type === 'semester') {
            periodSelector.style.display = 'none';
            semesterSelector.style.display = 'inline';
        } else {
            periodSelector.style.display = 'inline';
            semesterSelector.style.display = 'none';
        }
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/grades/bulletin.blade.php ENDPATH**/ ?>