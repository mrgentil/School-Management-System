<?php $__env->startSection('page_title', 'Tableau de Bord Étudiant'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-1">
                        <i class="icon-user"></i> Bienvenue, <?php echo e(auth()->user()->name); ?>!
                    </h3>
                    <p class="mb-0">
                        <i class="icon-calendar"></i> <?php echo e(Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Devoirs en attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($stats['pending_assignments']); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-book text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo e(route('student.assignments.index')); ?>" class="small text-primary">
                        Voir tous les devoirs <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Taux de présence
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($stats['attendance_rate']); ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-checkmark-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo e(route('student.attendance.index')); ?>" class="small text-success">
                        Voir les présences <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Livres empruntés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($stats['borrowed_books']); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-books text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo e(route('student.library.requests.index')); ?>" class="small text-info">
                        Voir mes demandes <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Messages non lus
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($stats['unread_messages']); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-mail text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="<?php echo e(route('student.messages.index')); ?>" class="small text-warning">
                        Voir les messages <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-8">
            <!-- Devoirs à venir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="icon-clipboard"></i> Devoirs à venir
                    </h6>
                    <a href="<?php echo e(route('student.assignments.index')); ?>" class="btn btn-sm btn-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <?php if($upcomingAssignments->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matière</th>
                                        <th>Titre</th>
                                        <th>Date limite</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $upcomingAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo e($assignment->subject->name ?? 'N/A'); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e(Str::limit($assignment->title, 30)); ?></td>
                                            <td>
                                                <span class="text-<?php echo e(Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'danger' : 'muted'); ?>">
                                                    <i class="icon-calendar"></i>
                                                    <?php echo e(Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <?php if($assignment->submissions_count > 0): ?>
                                                    <span class="badge badge-success">
                                                        <i class="icon-checkmark"></i> Soumis
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">
                                                        <i class="icon-clock"></i> En attente
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('student.assignments.show', $assignment->id)); ?>"
                                                   class="btn btn-sm btn-info">
                                                    <i class="icon-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="icon-checkmark-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucun devoir en attente pour le moment</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Livres empruntés -->
            <?php if($borrowedBooks->count() > 0): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="icon-books"></i> Mes livres empruntés
                    </h6>
                    <a href="<?php echo e(route('student.library.requests.index')); ?>" class="btn btn-sm btn-info">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Auteur</th>
                                    <th>Date de retour</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $borrowedBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($request->book->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($request->book->author ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($request->expected_return_date): ?>
                                                <span class="text-<?php echo e(Carbon\Carbon::parse($request->expected_return_date)->isPast() ? 'danger' : 'muted'); ?>">
                                                    <i class="icon-calendar"></i>
                                                    <?php echo e(Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y')); ?>

                                                    <?php if(Carbon\Carbon::parse($request->expected_return_date)->isPast()): ?>
                                                        <br><small class="text-danger">En retard!</small>
                                                    <?php else: ?>
                                                        <br><small>(<?php echo e(Carbon\Carbon::parse($request->expected_return_date)->diffForHumans()); ?>)</small>
                                                    <?php endif; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Non définie</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($request->badge_class); ?>">
                                                <?php echo e(ucfirst($request->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Supports pédagogiques récents -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="icon-folder"></i> Supports pédagogiques récents
                    </h6>
                    <a href="<?php echo e(route('student.materials.index')); ?>" class="btn btn-sm btn-success">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <?php if($recentMaterials->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $recentMaterials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('student.materials.show', $material->id)); ?>"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="icon-file-text"></i> <?php echo e($material->title); ?>

                                        </h6>
                                        <small class="text-muted">
                                            <?php echo e($material->created_at->diffForHumans()); ?>

                                        </small>
                                    </div>
                                    <p class="mb-1 text-muted small">
                                        <?php echo e(Str::limit($material->description, 80)); ?>

                                    </p>
                                    <small>
                                        <span class="badge badge-primary">
                                            <?php echo e($material->subject->name ?? 'Général'); ?>

                                        </span>
                                        <span class="text-muted">
                                            Par <?php echo e($material->user->name ?? 'N/A'); ?>

                                        </span>
                                    </small>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="icon-folder text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucun support pédagogique disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-4">
            <!-- Résumé financier -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="icon-credit-card"></i> Situation financière
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total à payer:</span>
                            <strong><?php echo e(number_format($financialSummary['total_amount'], 0, ',', ' ')); ?> FC</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Montant payé:</span>
                            <strong class="text-success"><?php echo e(number_format($financialSummary['total_paid'], 0, ',', ' ')); ?> FC</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Solde restant:</span>
                            <strong class="text-<?php echo e($financialSummary['total_balance'] > 0 ? 'danger' : 'success'); ?>">
                                <?php echo e(number_format($financialSummary['total_balance'], 0, ',', ' ')); ?> FC
                            </strong>
                        </div>
                    </div>

                    <?php if($financialSummary['total_balance'] > 0): ?>
                        <div class="alert alert-warning mb-3">
                            <i class="icon-warning"></i>
                            <small>Vous avez un solde impayé</small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-3">
                            <i class="icon-checkmark-circle"></i>
                            <small>Tous vos paiements sont à jour!</small>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('student.finance.payments')); ?>" class="btn btn-warning btn-block">
                        <i class="icon-eye"></i> Voir les détails
                    </a>
                </div>
            </div>

            <!-- Statistiques de présence -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="icon-calendar"></i> Présences ce mois
                    </h6>
                </div>
                <div class="card-body">
                    <?php if($attendanceStats['total'] > 0): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">
                                    <i class="icon-checkmark-circle"></i> Présent
                                </span>
                                <strong><?php echo e($attendanceStats['present']); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger">
                                    <i class="icon-close-circle"></i> Absent
                                </span>
                                <strong><?php echo e($attendanceStats['absent']); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-warning">
                                    <i class="icon-clock"></i> Retard
                                </span>
                                <strong><?php echo e($attendanceStats['late']); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-info">
                                    <i class="icon-info"></i> Excusé
                                </span>
                                <strong><?php echo e($attendanceStats['excused']); ?></strong>
                            </div>
                        </div>

                        <div class="progress mb-3" style="height: 25px;">
                            <?php
                                $presentPercent = $attendanceStats['total'] > 0 ? ($attendanceStats['present'] / $attendanceStats['total']) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: <?php echo e($presentPercent); ?>%"
                                 aria-valuenow="<?php echo e($presentPercent); ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo e(round($presentPercent, 1)); ?>%
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="icon-calendar text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Aucune donnée de présence ce mois</p>
                        </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('student.attendance.index')); ?>" class="btn btn-success btn-block">
                        <i class="icon-eye"></i> Voir l'historique
                    </a>
                </div>
            </div>

            <!-- Notifications récentes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="icon-bell"></i> Notifications récentes
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if($recentNotifications->count() > 0): ?>
                        <div class="list-group">
                            <?php $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item <?php echo e($notification->read_at ? '' : 'bg-light'); ?>">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-primary">
                                            <i class="icon-bell"></i>
                                        </small>
                                        <small class="text-muted">
                                            <?php echo e($notification->created_at->diffForHumans()); ?>

                                        </small>
                                    </div>
                                    <p class="mb-1 small">
                                        <?php echo e($notification->data['message'] ?? 'Nouvelle notification'); ?>

                                    </p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="icon-bell text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Aucune notification</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/dashboard.blade.php ENDPATH**/ ?>