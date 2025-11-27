
<?php $__env->startSection('page_title', 'Gérer les Promotions'); ?>
<?php $__env->startSection('content'); ?>

    
    <div class="card">
        <div class="card-body text-center">
            <button id="promotion-reset-all" class="btn btn-danger btn-large">Réinitialiser toutes les promotions pour la session</button>
        </div>
    </div>


    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title font-weight-bold">
                Gérer les Promotions - Étudiants promus de
                <span class="text-danger"><?php echo e($old_year); ?></span>
                vers
                <span class="text-success"><?php echo e($new_year); ?></span>
                
            </h5>
            <?php echo Qs::getPanelOptions(); ?>

        </div>

        <div class="card-body">

            <table id="promotions-list" class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>N°</th>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Classe d'origine</th>
                    <th>Classe de destination</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $promotions->sortBy('fc.name')->sortBy('student.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><img class="rounded-circle" style="height: 40px; width: 40px;" src="<?php echo e($p->student->photo); ?>" alt="photo"></td>
                        <td><?php echo e($p->student->name); ?></td>
                        <td><?php echo e(($p->fc->full_name ?: $p->fc->name).' '.$p->fs->name); ?></td>
                        <td><?php echo e(($p->tc->full_name ?: $p->tc->name).' '.$p->ts->name); ?></td>
                        <?php if($p->status === 'P'): ?>
                            <td><span class="text-success">Promu</span></td>
                        <?php elseif($p->status === 'D'): ?>
                            <td><span class="text-danger">Non promu</span></td>
                        <?php else: ?>
                            <td><span class="text-primary">Diplômé</span></td>
                        <?php endif; ?>
                        <td class="text-center">
                            <button data-id="<?php echo e($p->id); ?>" class="btn btn-danger promotion-reset">Réinitialiser</button>
                            <form id="promotion-reset-<?php echo e($p->id); ?>" method="post" action="<?php echo e(route('students.promotion_reset', $p->id)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?></form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        /* Réinitialisation individuelle */
        $('.promotion-reset').on('click', function () {
            let pid = $(this).data('id');
            if (confirm('Voulez-vous vraiment réinitialiser cette promotion ?')){
                $('form#promotion-reset-'+pid).submit();
            }
            return false;
        });

        /* Réinitialiser toutes les promotions */
        $('#promotion-reset-all').on('click', function () {
            if (confirm('Voulez-vous vraiment réinitialiser toutes les promotions pour cette session ?')){
                $.ajax({
                    url:"<?php echo e(route('students.promotion_reset_all')); ?>",
                    type:'DELETE',
                    data:{ '_token' : $('#csrf-token').attr('content') },
                    success:function (resp) {
                        $('table#promotions-list > tbody').fadeOut().remove();
                        flash({msg : resp.msg, type : 'success'});
                    }
                })
            }
            return false;
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/students/promotion/reset.blade.php ENDPATH**/ ?>