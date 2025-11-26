<?php $__env->startSection('page_title', 'Aperçu Bulletin - ' . $student->user->name); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-light">
            <i class="icon-arrow-left5 mr-1"></i> Retour
        </a>
    </div>
    <div>
        <a href="<?php echo e(route('bulletins.generate', $student->user_id)); ?>?type=<?php echo e($type); ?>&period=<?php echo e($period); ?>&semester=<?php echo e($semester); ?>" 
           class="btn btn-primary" target="_blank">
            <i class="icon-file-pdf mr-1"></i> Télécharger PDF
        </a>
        <button onclick="window.print()" class="btn btn-info">
            <i class="icon-printer mr-1"></i> Imprimer
        </button>
    </div>
</div>

<div class="card bulletin-card">
    <div class="card-body p-4">
        
        <div class="bulletin-header text-center mb-4">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="<?php echo e($school['logo'] ?? asset('global_assets/images/logo.png')); ?>" 
                         width="80" class="rounded" alt="Logo">
                </div>
                <div class="col-md-8">
                    <h2 class="text-primary font-weight-bold mb-1"><?php echo e($school['name'] ?? 'ÉCOLE'); ?></h2>
                    <?php if($school['motto'] ?? false): ?>
                        <p class="font-italic text-muted mb-1">"<?php echo e($school['motto']); ?>"</p>
                    <?php endif; ?>
                    <small class="text-muted">
                        <?php echo e($school['address'] ?? ''); ?>

                        <?php if($school['phone'] ?? false): ?> | Tél: <?php echo e($school['phone']); ?> <?php endif; ?>
                    </small>
                </div>
                <div class="col-md-2 text-right">
                    <strong>Année Scolaire</strong><br>
                    <span class="badge badge-primary badge-lg"><?php echo e($year); ?></span>
                </div>
            </div>
            <hr class="my-3" style="border-top: 3px double #003366;">
        </div>

        
        <div class="bg-primary text-white text-center py-2 mb-4 rounded">
            <h4 class="mb-0 font-weight-bold">
                <i class="icon-file-text2 mr-2"></i>
                BULLETIN DE NOTES - <?php echo e($type == 'period' ? 'PÉRIODE ' . $period : 'SEMESTRE ' . $semester); ?>

            </h4>
            <small>Année Scolaire <?php echo e($year); ?></small>
        </div>

        
        <div class="bg-light p-3 rounded mb-4">
            <div class="row">
                <div class="col-md-4">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-primary">Nom et Prénom:</th>
                            <td><strong><?php echo e($student->user->name); ?></strong></td>
                        </tr>
                        <tr>
                            <th class="text-primary">Matricule:</th>
                            <td><?php echo e($student->adm_no); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-primary">Classe:</th>
                            <td><?php echo e($student->my_class->full_name ?? $student->my_class->name); ?></td>
                        </tr>
                        <tr>
                            <th class="text-primary"><?php echo e($type == 'period' ? 'Période:' : 'Semestre:'); ?></th>
                            <td><span class="badge badge-info badge-lg"><?php echo e($type == 'period' ? 'P' . $period : 'S' . $semester); ?></span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-primary">Rang:</th>
                            <td>
                                <span class="badge badge-<?php echo e($rank <= 3 ? 'warning' : 'secondary'); ?> badge-lg" style="font-size: 16px;">
                                    <?php echo e($rank); ?><?php echo e($rank == 1 ? 'er' : 'ème'); ?> / <?php echo e($totalStudents); ?>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-primary">Moyenne:</th>
                            <td>
                                <span class="badge badge-<?php echo e($stats['average'] >= 10 ? 'success' : 'danger'); ?> badge-lg" style="font-size: 16px;">
                                    <?php echo e(number_format($stats['average'], 2)); ?> / 20
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th style="width: 20%;">MATIÈRE</th>
                        <?php if($type == 'period'): ?>
                            <th class="text-center">POINTS</th>
                            <th class="text-center">MAX</th>
                        <?php else: ?>
                            <th class="text-center">Moy. Périodes</th>
                            <th class="text-center">-</th>
                            <th class="text-center">EXAM</th>
                            <th class="text-center">TOTAL</th>
                        <?php endif; ?>
                        <th class="text-center">%</th>
                        <th class="text-center">NOTE</th>
                        <th>APPRÉCIATION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bulletinData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($data['subject']); ?></strong></td>
                            <?php if($type == 'period'): ?>
                                <td class="text-center"><strong><?php echo e($data['total_obtained'] !== null ? number_format($data['total_obtained'], 2) : '-'); ?></strong></td>
                                <td class="text-center"><?php echo e($data['total_max'] ?? 20); ?></td>
                            <?php else: ?>
                                <td class="text-center"><?php echo e($data['period_average'] !== null ? number_format($data['period_average'], 1).'%' : '-'); ?></td>
                                <td class="text-center">-</td>
                                <td class="text-center"><?php echo e($data['exam_average'] !== null ? number_format($data['exam_average'], 1).'%' : '-'); ?></td>
                                <td class="text-center"><strong><?php echo e($data['total_obtained'] !== null ? number_format($data['total_obtained'], 2) : '-'); ?></strong></td>
                            <?php endif; ?>
                            <td class="text-center">
                                <?php if($data['percentage'] !== null): ?>
                                    <span class="badge badge-<?php echo e($data['percentage'] >= 70 ? 'success' : ($data['percentage'] >= 50 ? 'info' : 'danger')); ?>">
                                        <?php echo e(number_format($data['percentage'], 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><strong><?php echo e($data['grade']); ?></strong></td>
                            <td>
                                <span class="text-<?php echo e($data['percentage'] !== null && $data['percentage'] >= 70 ? 'success' : ($data['percentage'] !== null && $data['percentage'] >= 50 ? 'info' : 'danger')); ?>">
                                    <?php echo e($data['remark']); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    <tr class="bg-light font-weight-bold">
                        <td><strong>TOTAL GÉNÉRAL</strong></td>
                        <?php if($type == 'period'): ?>
                            <td colspan="2" class="text-center">-</td>
                        <?php else: ?>
                            <td colspan="4" class="text-center">-</td>
                        <?php endif; ?>
                        <td class="text-center">
                            <span class="badge badge-<?php echo e($stats['average'] >= 50 ? 'success' : ($stats['average'] >= 50 ? 'primary' : 'danger')); ?> badge-lg" style="font-size: 16px;">
                                <?php echo e(number_format($stats['average'], 2)); ?>%
                            </span>
                        </td>
                        <td class="text-center">-</td>
                        <td><strong class="text-<?php echo e($appreciation['class']); ?>"><?php echo e($appreciation['text']); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e(number_format($stats['average'], 2)); ?></h2>
                        <small>Moyenne Générale</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($rank); ?><sup><?php echo e($rank == 1 ? 'er' : 'ème'); ?></sup></h2>
                        <small>Rang / <?php echo e($totalStudents); ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($stats['passed']); ?></h2>
                        <small>Matières Réussies</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white text-center">
                    <div class="card-body py-3">
                        <h2 class="mb-0"><?php echo e($stats['failed']); ?></h2>
                        <small>Matières Échouées</small>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="icon-clipboard3 mr-2"></i>APPRÉCIATION GÉNÉRALE DU CONSEIL DE CLASSE</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-<?php echo e($appreciation['class']); ?> text-center mb-3">
                    <h4 class="mb-0">
                        <?php echo e($appreciation['text']); ?>

                        <?php if($stats['average'] >= 10): ?>
                            - <span class="text-success">Admis(e) à poursuivre</span>
                        <?php else: ?>
                            - <span class="text-danger">Doit redoubler ses efforts</span>
                        <?php endif; ?>
                    </h4>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Observations:</label>
                    <div class="border p-3 rounded" style="min-height: 60px;">
                        <span class="text-muted">_______________________________________________________________________</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="alert alert-light mb-4">
            <small>
                <strong>Échelle de notation:</strong> 
                <span class="badge badge-success">A+ (18-20)</span> Excellent |
                <span class="badge badge-info">A (16-17.99)</span> Très Bien |
                <span class="badge badge-primary">B+ (14-15.99)</span> Bien |
                <span class="badge badge-secondary">B (12-13.99)</span> Assez Bien |
                <span class="badge badge-warning">C (10-11.99)</span> Passable |
                <span class="badge badge-danger">D/E (&lt;10)</span> Insuffisant
            </small>
        </div>

        
        <div class="row mt-5">
            <div class="col-md-4 text-center">
                <p class="font-weight-bold mb-5">Le Titulaire de Classe</p>
                <hr>
                <small>Signature</small>
            </div>
            <div class="col-md-4 text-center">
                <p class="font-weight-bold mb-5">Le Parent / Tuteur</p>
                <hr>
                <small>Signature</small>
            </div>
            <div class="col-md-4 text-center">
                <p class="font-weight-bold mb-5">Le Chef d'Établissement</p>
                <hr>
                <small>Signature & Cachet</small>
            </div>
        </div>

        
        <hr class="mt-4">
        <div class="text-center text-muted">
            <small>Document généré le <?php echo e($generated_at); ?> | <?php echo e($school['name'] ?? 'École'); ?> - Année Scolaire <?php echo e($year); ?></small>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    @media print {
        .no-print { display: none !important; }
        .bulletin-card { box-shadow: none !important; border: none !important; }
        body { font-size: 12px; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php if(request('print')): ?>
<script>
    window.onload = function() {
        window.print();
    }
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/bulletins/preview.blade.php ENDPATH**/ ?>