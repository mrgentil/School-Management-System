<?php $__env->startSection('page_title', 'Bulletin - ' . $student->user->name); ?>

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

<div class="card bulletin-rdc">
    <div class="card-body p-3" style="font-family: 'Times New Roman', Times, serif; font-size: 11px;">
        
        
        <div class="row align-items-center border-bottom pb-2 mb-2">
            <div class="col-2 text-center">
                
                <div style="width: 50px; height: 35px; border: 1px solid #000; margin: auto;">
                    <div style="background: #007FFF; height: 33.33%;"></div>
                    <div style="background: #F7D618; height: 33.33%;"></div>
                    <div style="background: #CE1126; height: 33.33%;"></div>
                </div>
            </div>
            <div class="col-8 text-center">
                <h6 class="font-weight-bold mb-0" style="font-size: 12px;">REPUBLIQUE DEMOCRATIQUE DU CONGO</h6>
                <p class="mb-0" style="font-size: 10px;">MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET TECHNIQUE</p>
                <p class="mb-0" style="font-size: 9px;">INITIATION A LA NOUVELLE CITOYENNETE</p>
            </div>
            <div class="col-2 text-center">
                
                <div style="width: 50px; height: 50px; border: 1px solid #000; margin: auto; display: flex; align-items: center; justify-content: center;">
                    <small style="font-size: 7px;">Armoiries</small>
                </div>
            </div>
        </div>

        
        <div class="mb-2">
            <span style="border: 1px solid #000; padding: 2px 8px; font-weight: bold;">N° ID. <?php echo e($student->adm_no); ?></span>
        </div>

        
        <div class="row mb-2" style="font-size: 10px;">
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td><strong>PROVINCE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($school['province'] ?? 'KINSHASA'); ?></td></tr>
                    <tr><td><strong>VILLE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($school['city'] ?? ''); ?></td></tr>
                    <tr><td><strong>COMMUNE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($school['commune'] ?? ''); ?></td></tr>
                    <tr><td><strong>ECOLE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($school['name'] ?? config('app.name')); ?></td></tr>
                    <tr><td><strong>CODE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($school['code'] ?? ''); ?></td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td><strong>ELEVE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->user->name); ?></td></tr>
                    <tr><td><strong>SEXE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->gender ?? ''); ?></td></tr>
                    <tr><td><strong>NE(E) A :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->lga ?? ''); ?></td></tr>
                    <tr><td><strong>LE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : ''); ?></td></tr>
                    <tr><td><strong>CLASSE :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->my_class->name ?? ''); ?> <?php echo e($student->section->name ?? ''); ?></td></tr>
                    <tr><td><strong>N° PERM. :</strong></td><td style="border-bottom: 1px dotted #000;"><?php echo e($student->adm_no); ?></td></tr>
                </table>
            </div>
        </div>

        
        <div class="text-center py-2 mb-3" style="background: #f0f0f0; border: 1px solid #000; font-weight: bold;">
            BULLETIN DE <?php echo e($type == 'semester' ? 'SEMESTRE ' . $semester : 'PERIODE ' . $period); ?>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ANNEE SCOLAIRE <?php echo e($year); ?>

        </div>

        
        <?php
            $totalMax = 0;
            $totalObtained = 0;
        ?>
        
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm" style="font-size: 9px;">
                <thead>
                    <tr style="background: #e0e0e0;">
                        <th rowspan="2" class="text-center align-middle" style="width: 25%;">BRANCHES</th>
                        <th colspan="2" class="text-center" style="background: #c0c0c0;">POINTS</th>
                        <th rowspan="2" class="text-center align-middle" style="width: 8%;">%</th>
                        <th rowspan="2" class="text-center align-middle" style="width: 20%;">APPRECIATION</th>
                    </tr>
                    <tr style="background: #e0e0e0;">
                        <th class="text-center" style="width: 10%;">Obtenu</th>
                        <th class="text-center" style="width: 10%;">Max</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $bulletinData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $max = $data['total_max'] ?? 20;
                            $obtained = $data['total_obtained'] ?? 0;
                            $totalMax += $max;
                            $totalObtained += $obtained;
                        ?>
                        <tr>
                            <td style="padding-left: 5px;"><?php echo e($data['subject']); ?></td>
                            <td class="text-center"><?php echo e($data['total_obtained'] !== null ? number_format($data['total_obtained'], 1) : '-'); ?></td>
                            <td class="text-center"><?php echo e($max); ?></td>
                            <td class="text-center">
                                <?php if($data['percentage'] !== null): ?>
                                    <span class="badge badge-<?php echo e($data['percentage'] >= 50 ? 'success' : 'danger'); ?>" style="font-size: 8px;">
                                        <?php echo e(number_format($data['percentage'], 1)); ?>%
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-center" style="font-size: 8px;">
                                <span class="text-<?php echo e($data['percentage'] !== null && $data['percentage'] >= 50 ? 'success' : 'danger'); ?>">
                                    <?php echo e($data['remark']); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    <tr style="background: #f5f5f5; font-weight: bold;">
                        <td style="padding-left: 5px;">MAXIMA</td>
                        <td class="text-center">-</td>
                        <td class="text-center"><?php echo e($totalMax); ?></td>
                        <td class="text-center">100%</td>
                        <td></td>
                    </tr>
                    
                    
                    <tr style="background: #d0d0d0; font-weight: bold;">
                        <td style="padding-left: 5px;">TOTAUX</td>
                        <td class="text-center"><?php echo e(number_format($totalObtained, 1)); ?></td>
                        <td class="text-center"><?php echo e($totalMax); ?></td>
                        <td class="text-center">
                            <span class="badge badge-<?php echo e($stats['average'] >= 50 ? 'success' : 'danger'); ?>">
                                <?php echo e(number_format($stats['average'], 2)); ?>%
                            </span>
                        </td>
                        <td class="text-center"><?php echo e($appreciation['text']); ?></td>
                    </tr>
                    
                    
                    <tr>
                        <td style="padding-left: 5px; font-weight: bold;">PLACE / NBRE ELEVES</td>
                        <td colspan="4" class="text-center">
                            <strong><?php echo e($rank); ?><?php echo e($rank == 1 ? 'er' : 'ème'); ?> / <?php echo e($totalStudents); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        
        <div class="row mb-3">
            <div class="col-6">
                <table class="table table-bordered table-sm" style="font-size: 9px;">
                    <tr>
                        <td style="width: 40%;"><strong>APPLICATION</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong>CONDUITE</strong></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><strong>SIGN. RESPONSABLE</strong></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="col-6">
                <div style="font-size: 9px; border: 1px solid #000; padding: 5px;">
                    <div class="mb-1">
                        <input type="checkbox" <?php echo e($stats['average'] >= 50 ? 'checked' : ''); ?>> Passe (1)
                    </div>
                    <div class="mb-1">
                        <input type="checkbox"> Double (1)
                    </div>
                    <div>
                        <input type="checkbox" <?php echo e($stats['average'] < 50 ? 'checked' : ''); ?>> A échoué (1)
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row mt-3" style="font-size: 9px;">
            <div class="col-4 text-center">
                <p class="font-weight-bold mb-4">Le Titulaire de Classe</p>
                <hr style="border-top: 1px solid #000;">
                <small>Signature</small>
            </div>
            <div class="col-4 text-center">
                <p class="font-weight-bold mb-4">Le Parent / Tuteur</p>
                <hr style="border-top: 1px solid #000;">
                <small>Signature</small>
            </div>
            <div class="col-4 text-center">
                <p class="font-weight-bold mb-4">Le Chef d'Établissement</p>
                <hr style="border-top: 1px solid #000;">
                <small>Signature & Cachet</small>
            </div>
        </div>

        
        <div class="mt-3 pt-2" style="border-top: 1px solid #000; font-size: 8px;">
            <p class="mb-1">- L'élève ne pourra passer dans la classe supérieure s'il ne subit avec succès un examen de repêchage en ........................... (1)</p>
            <p class="mb-1">- L'élève passe dans la classe supérieure (1)</p>
            <p class="mb-1">- L'élève double sa classe (1)</p>
            <p class="mb-1">- L'élève a échoué et orienté vers ........................... (1)</p>
            
            <div class="row mt-3">
                <div class="col-4">
                    <p>Signature de l'élève</p>
                    <br>
                    <p>_______________________</p>
                </div>
                <div class="col-4 text-center">
                    <p>Sceau de l'école</p>
                </div>
                <div class="col-4 text-right">
                    <p>Fait à <?php echo e($school['city'] ?? '..................'); ?>, le <?php echo e(now()->format('d/m/Y')); ?></p>
                    <p>Le Chef d'Établissement,</p>
                    <br>
                    <p>Nom et signature</p>
                </div>
            </div>
            
            <p class="mt-2"><em>(1) Biffer la mention inutile</em></p>
            <p><strong>Note importante :</strong> Le bulletin est sans valeur s'il est raturé ou surchargé</p>
            <p class="text-right">Document généré le <?php echo e($generated_at); ?></p>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .bulletin-rdc {
        background: white;
    }
    
    @media print {
        .no-print { display: none !important; }
        .bulletin-rdc { 
            box-shadow: none !important; 
            border: none !important;
            margin: 0;
            padding: 10px;
        }
        body { 
            font-size: 10px;
            background: white;
        }
        .card-body {
            padding: 10px !important;
        }
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