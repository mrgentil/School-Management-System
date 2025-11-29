
<?php $__env->startSection('page_title', 'Gérer l\'Emploi du Temps'); ?>
<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold"><?php echo e($ttr->name.' ('.($my_class ? ($my_class->full_name ?: $my_class->name) : 'N/A').')'.' '.$ttr->year); ?></h6>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#manage-ts" class="nav-link active" data-toggle="tab">⏰ Gérer les Créneaux Horaires</a></li>
                <li class="nav-item"><a href="#add-sub" class="nav-link" data-toggle="tab">➕ Ajouter une Matière</a></li>
                <li class="nav-item"><a href="#edit-subs" class="nav-link " data-toggle="tab">✏️ Modifier les Matières</a></li>
                <li class="nav-item"><a href="#import-excel" class="nav-link" data-toggle="tab">📥 Import/Export Excel</a></li>
                <li class="nav-item"><a target="_blank" href="<?php echo e(route('ttr.show', $ttr->id)); ?>" class="nav-link" >👁️ Voir l'Emploi du Temps</a></li>
            </ul>

            <div class="tab-content">
                
                <?php echo $__env->make('pages.support_team.timetables.time_slots.index', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php echo $__env->make('pages.support_team.timetables.subjects.add', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php echo $__env->make('pages.support_team.timetables.subjects.edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php echo $__env->make('pages.support_team.timetables.import_excel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/timetables/manage.blade.php ENDPATH**/ ?>