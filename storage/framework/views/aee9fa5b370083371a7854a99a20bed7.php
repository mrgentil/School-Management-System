
<?php $__env->startSection('page_title', 'Publication des Bulletins'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-newspaper mr-2"></i>Publication des Bulletins - <?php echo e($year); ?>

        </h6>
    </div>

    <div class="card-body">
        
        <div class="alert alert-info alert-styled-left">
            <i class="icon-info22 mr-2"></i>
            <strong>Information :</strong> Les étudiants ne peuvent voir leurs bulletins que lorsqu'ils sont publiés. 
            Cliquez sur une cellule pour publier ou dépublier un bulletin.
        </div>

        
        <div class="card border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <i class="icon-rocket mr-2"></i>Publication Rapide
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('bulletin_publications.publish')); ?>" method="POST" class="form-inline">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mr-3">
                        <label class="mr-2">Type :</label>
                        <select name="type" id="quickType" class="form-control" onchange="toggleQuickSelector()">
                            <option value="period">Période</option>
                            <option value="semester">Semestre</option>
                        </select>
                    </div>

                    <div class="form-group mr-3" id="quickPeriodGroup">
                        <label class="mr-2">Période :</label>
                        <select name="period" class="form-control">
                            <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group mr-3" id="quickSemesterGroup" style="display: none;">
                        <label class="mr-2">Semestre :</label>
                        <select name="semester" class="form-control">
                            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group mr-3">
                        <label class="mr-2">Classe :</label>
                        <select name="my_class_id" class="form-control">
                            <option value="">-- Toutes les classes --</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="icon-checkmark mr-1"></i>Publier
                    </button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <i class="icon-grid mr-2"></i>Statut de Publication par Classe
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" class="align-middle">Classe</th>
                                <th colspan="4" class="text-center bg-primary text-white">Périodes</th>
                                <th colspan="2" class="text-center bg-success text-white">Semestres</th>
                            </tr>
                            <tr>
                                <th class="text-center">P1</th>
                                <th class="text-center">P2</th>
                                <th class="text-center">P3</th>
                                <th class="text-center">P4</th>
                                <th class="text-center">S1</th>
                                <th class="text-center">S2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $statusMatrix; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($data['class']->name); ?></strong></td>
                                    
                                    
                                    <?php for($p = 1; $p <= 4; $p++): ?>
                                        <?php $status = $data['periods'][$p]; ?>
                                        <td class="text-center status-cell" 
                                            data-class="<?php echo e($classId); ?>" 
                                            data-type="period" 
                                            data-value="<?php echo e($p); ?>"
                                            data-status="<?php echo e($status); ?>">
                                            <?php if($status === 'published'): ?>
                                                <span class="badge badge-success cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'period', <?php echo e($p); ?>, 'published')">
                                                    <i class="icon-checkmark"></i> Publié
                                                </span>
                                            <?php elseif($status === 'review'): ?>
                                                <span class="badge badge-warning cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'period', <?php echo e($p); ?>, 'review')">
                                                    <i class="icon-hour-glass"></i> Révision
                                                </span>
                                            <?php elseif($status === 'draft'): ?>
                                                <span class="badge badge-secondary cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'period', <?php echo e($p); ?>, 'draft')">
                                                    <i class="icon-pencil"></i> Brouillon
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'period', <?php echo e($p); ?>, null)">
                                                    <i class="icon-minus"></i> Non défini
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>

                                    
                                    <?php for($s = 1; $s <= 2; $s++): ?>
                                        <?php $status = $data['semesters'][$s]; ?>
                                        <td class="text-center status-cell"
                                            data-class="<?php echo e($classId); ?>"
                                            data-type="semester"
                                            data-value="<?php echo e($s); ?>"
                                            data-status="<?php echo e($status); ?>">
                                            <?php if($status === 'published'): ?>
                                                <span class="badge badge-success cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'semester', <?php echo e($s); ?>, 'published')">
                                                    <i class="icon-checkmark"></i> Publié
                                                </span>
                                            <?php elseif($status === 'review'): ?>
                                                <span class="badge badge-warning cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'semester', <?php echo e($s); ?>, 'review')">
                                                    <i class="icon-hour-glass"></i> Révision
                                                </span>
                                            <?php elseif($status === 'draft'): ?>
                                                <span class="badge badge-secondary cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'semester', <?php echo e($s); ?>, 'draft')">
                                                    <i class="icon-pencil"></i> Brouillon
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light cursor-pointer" onclick="togglePublication(<?php echo e($classId); ?>, 'semester', <?php echo e($s); ?>, null)">
                                                    <i class="icon-minus"></i> Non défini
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <i class="icon-stack mr-2"></i>Publier Toutes les Classes
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('bulletin_publications.publish_all')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="type" id="massType" class="form-control" onchange="toggleMassSelector()">
                                            <option value="period">Période</option>
                                            <option value="semester">Semestre</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="massPeriodGroup">
                                        <label>Période</label>
                                        <select name="period" class="form-control">
                                            <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="form-group" id="massSemesterGroup" style="display: none;">
                                        <label>Semestre</label>
                                        <select name="semester" class="form-control">
                                            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info btn-block" onclick="return confirm('Publier pour TOUTES les classes ? Les étudiants seront notifiés.')">
                                <i class="icon-checkmark3 mr-1"></i>Publier pour Toutes les Classes
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <i class="icon-history mr-2"></i>Dernières Publications
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $publications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo e($pub->type_label); ?></strong>
                                        <small class="text-muted">
                                            - <?php echo e($pub->myClass ? $pub->myClass->name : 'Toutes les classes'); ?>

                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge badge-<?php echo e($pub->status_color); ?>"><?php echo e($pub->status_label); ?></span>
                                        <small class="text-muted ml-2"><?php echo e($pub->published_at ? $pub->published_at->format('d/m/Y H:i') : '-'); ?></small>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-muted text-center">Aucune publication</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-4">
            <h6>Légende :</h6>
            <span class="badge badge-success mr-2"><i class="icon-checkmark"></i> Publié</span>
            <span class="badge badge-warning mr-2"><i class="icon-hour-glass"></i> En révision</span>
            <span class="badge badge-secondary mr-2"><i class="icon-pencil"></i> Brouillon</span>
            <span class="badge badge-light"><i class="icon-minus"></i> Non défini</span>
        </div>
    </div>
</div>


<div class="modal fade" id="publicationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la Publication</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="publicationForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="type" id="modalType">
                    <input type="hidden" name="period" id="modalPeriod">
                    <input type="hidden" name="semester" id="modalSemester">
                    <input type="hidden" name="my_class_id" id="modalClassId">

                    <p id="modalMessage"></p>

                    <div class="form-group">
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Commentaire pour cette publication..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger" id="btnUnpublish" formaction="<?php echo e(route('bulletin_publications.unpublish')); ?>">
                        <i class="icon-cross mr-1"></i>Dépublier
                    </button>
                    <button type="submit" class="btn btn-success" id="btnPublish" formaction="<?php echo e(route('bulletin_publications.publish')); ?>">
                        <i class="icon-checkmark mr-1"></i>Publier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .status-cell:hover { background-color: #f5f5f5; }
</style>

<script>
function toggleQuickSelector() {
    var type = document.getElementById('quickType').value;
    document.getElementById('quickPeriodGroup').style.display = type === 'period' ? 'block' : 'none';
    document.getElementById('quickSemesterGroup').style.display = type === 'semester' ? 'block' : 'none';
}

function toggleMassSelector() {
    var type = document.getElementById('massType').value;
    document.getElementById('massPeriodGroup').style.display = type === 'period' ? 'block' : 'none';
    document.getElementById('massSemesterGroup').style.display = type === 'semester' ? 'block' : 'none';
}

function togglePublication(classId, type, value, currentStatus) {
    document.getElementById('modalType').value = type;
    document.getElementById('modalClassId').value = classId;
    
    if (type === 'period') {
        document.getElementById('modalPeriod').value = value;
        document.getElementById('modalSemester').value = '';
    } else {
        document.getElementById('modalPeriod').value = '';
        document.getElementById('modalSemester').value = value;
    }

    var label = type === 'period' ? 'Période ' + value : 'Semestre ' + value;
    document.getElementById('modalMessage').innerHTML = 
        'Voulez-vous modifier la publication du <strong>' + label + '</strong> ?';

    // Afficher/masquer les boutons selon le statut actuel
    if (currentStatus === 'published') {
        document.getElementById('btnUnpublish').style.display = 'inline-block';
        document.getElementById('btnPublish').style.display = 'none';
    } else {
        document.getElementById('btnUnpublish').style.display = 'none';
        document.getElementById('btnPublish').style.display = 'inline-block';
    }

    $('#publicationModal').modal('show');
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/bulletin_publications/index.blade.php ENDPATH**/ ?>