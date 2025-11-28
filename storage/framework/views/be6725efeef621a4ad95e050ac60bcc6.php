
<?php $__env->startSection('page_title', 'Centre d\'Impression'); ?>

<?php $__env->startSection('content'); ?>
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <h4 class="mb-0"><i class="icon-printer mr-2"></i> Centre d'Impression</h4>
        <small class="opacity-75">Générez et imprimez tous vos documents scolaires</small>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-book mr-2"></i> Documents Académiques</h6>
            </div>
            <div class="card-body">
                
                <div class="mb-4">
                    <h6><i class="icon-users mr-1"></i> Liste des Élèves</h6>
                    <form action="<?php echo e(route('print.class_list')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="mb-4">
                    <h6><i class="icon-pencil mr-1"></i> Fiche de Notes</h6>
                    <form action="<?php echo e(route('print.grade_sheet')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <select name="class_id" class="form-control mb-2" required id="grade-class">
                            <option value="">-- Classe --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <div class="input-group">
                            <select name="subject_id" class="form-control" required>
                                <option value="">-- Matière --</option>
                                <?php $__currentLoopData = \App\Models\Subject::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div>
                    <h6><i class="icon-calendar mr-1"></i> Emploi du Temps</h6>
                    <form action="<?php echo e(route('print.timetable')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-file-text mr-2"></i> Documents Administratifs</h6>
            </div>
            <div class="card-body">
                
                <div class="mb-4">
                    <h6><i class="icon-certificate mr-1"></i> Attestation de Scolarité</h6>
                    <form action="<?php echo e(route('print.certificate')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <div class="input-group">
                            <select name="student_id" class="form-control select-search" required>
                                <option value="">-- Élève --</option>
                                <?php $__currentLoopData = \App\Models\User::where('user_type', 'student')->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($student->id); ?>"><?php echo e($student->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="mb-4">
                    <h6><i class="icon-vcard mr-1"></i> Cartes d'Élèves</h6>
                    <form action="<?php echo e(route('print.student_cards')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div>
                    <h6><i class="icon-stats-bars mr-1"></i> Récapitulatif Général</h6>
                    <form action="<?php echo e(route('print.summary')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="icon-printer mr-1"></i> Imprimer le Récapitulatif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h6 class="card-title mb-0"><i class="icon-coins mr-2"></i> Documents Financiers</h6>
            </div>
            <div class="card-body">
                
                <div class="mb-4">
                    <h6><i class="icon-list mr-1"></i> État de Paiement</h6>
                    <form action="<?php echo e(route('print.payment_status')); ?>" method="POST" target="_blank">
                        <?php echo csrf_field(); ?>
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-warning"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="mt-4 pt-3 border-top">
                    <h6 class="text-muted">Liens Rapides</h6>
                    <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-outline-warning btn-sm mb-2 btn-block">
                        <i class="icon-clipboard3 mr-1"></i> Gérer les Paiements
                    </a>
                    <a href="<?php echo e(route('finance.dashboard')); ?>" class="btn btn-outline-warning btn-sm btn-block">
                        <i class="icon-stats-bars mr-1"></i> Rapports Financiers
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card mt-3">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-question3 mr-2"></i> Aide</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-info"><i class="icon-book mr-1"></i> Documents Académiques</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>Liste des élèves</strong> - Tous les élèves d'une classe</li>
                    <li>• <strong>Fiche de notes</strong> - Notes par matière</li>
                    <li>• <strong>Emploi du temps</strong> - Planning hebdomadaire</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-success"><i class="icon-file-text mr-1"></i> Documents Administratifs</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>Attestation</strong> - Certificat de scolarité</li>
                    <li>• <strong>Cartes d'élèves</strong> - Badges avec photo</li>
                    <li>• <strong>Récapitulatif</strong> - Stats de l'école</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-warning"><i class="icon-coins mr-1"></i> Documents Financiers</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>État de paiement</strong> - Situation par classe</li>
                    <li>• Les reçus individuels sont dans la section Paiements</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/print_center/index.blade.php ENDPATH**/ ?>