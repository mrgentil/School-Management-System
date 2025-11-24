<?php $__env->startSection('page_title', '📊 Tableau de bord Super Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-info border-0 alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">👋 Bienvenue, <?php echo e(Auth::user()->name); ?> !</span> 
            Voici un aperçu global de votre établissement.
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0 text-white"><?php echo e($stats['total_students']); ?></h3>
                    <span class="text-uppercase font-size-xs text-white">👨‍🎓 Étudiants</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0 text-white"><?php echo e($stats['total_teachers']); ?></h3>
                    <span class="text-uppercase font-size-xs text-white">👨‍🏫 Enseignants</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-user-tie icon-3x opacity-75 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0 text-white"><?php echo e($stats['total_classes']); ?></h3>
                    <span class="text-uppercase font-size-xs text-white">🏫 Classes</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-office icon-3x opacity-75 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-indigo-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0 text-white"><?php echo e($stats['total_staff']); ?></h3>
                    <span class="text-uppercase font-size-xs text-white">👔 Personnel</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-briefcase icon-3x opacity-75 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">💰 Évolution des paiements (6 derniers mois)</h6>
            </div>
            <div class="card-body">
                <canvas id="paymentChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title">💵 Paiements ce mois</h6>
            </div>
            <div class="card-body text-center">
                <h2 class="text-success mb-0"><?php echo e(Qs::getCurrency()); ?> <?php echo e(number_format($payments_this_month, 2)); ?></h2>
                <p class="text-muted mb-3"><?php echo e($payments_count); ?> paiement(s) reçu(s)</p>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                
                <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-success btn-sm">
                    <i class="icon-eye mr-2"></i> Voir tous les paiements
                </a>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📊 Étudiants par classe</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="studentsClassChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">👥 Répartition par genre</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="genderChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📅 Événements à venir</h6>
                <div class="header-elements">
                    <a href="<?php echo e(route('events.index')); ?>" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $upcoming_events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="media mb-3">
                    <div class="mr-3">
                        <div class="bg-primary text-white rounded p-2 text-center" style="width: 50px;">
                            <div class="font-weight-semibold"><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('d')); ?></div>
                            <div class="font-size-sm"><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('M')); ?></div>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0"><?php echo e($event->title); ?></h6>
                        <span class="text-muted font-size-sm"><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('d/m/Y')); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center">Aucun événement à venir</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📢 Dernières annonces</h6>
                <div class="header-elements">
                    <a href="<?php echo e(route('notices.index')); ?>" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $recent_notices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="media mb-3">
                    <div class="mr-3">
                        <i class="icon-megaphone icon-2x text-info"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0"><?php echo e(Str::limit($notice->title, 40)); ?></h6>
                        <span class="text-muted font-size-sm"><?php echo e($notice->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center">Aucune annonce récente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📚 Demandes de livres en attente</h6>
                <div class="header-elements">
                    <a href="<?php echo e(route('book-requests.index')); ?>" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $pending_book_requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="media mb-3">
                    <div class="mr-3">
                        <i class="icon-book icon-2x text-warning"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0"><?php echo e($request->book->name); ?></h6>
                        <span class="text-muted font-size-sm"><?php echo e($request->student->user->name); ?> - <?php echo e($request->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center">Aucune demande en attente</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">🆕 Utilisateurs récents</h6>
                <div class="header-elements">
                    <a href="<?php echo e(route('users.index')); ?>" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $recent_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="media mb-3">
                    <div class="mr-3">
                        <img src="<?php echo e($user->photo); ?>" width="40" height="40" class="rounded-circle" alt="">
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0"><?php echo e($user->name); ?></h6>
                        <span class="text-muted font-size-sm"><?php echo e(ucwords(str_replace('_', ' ', $user->user_type))); ?> - <?php echo e($user->created_at->diffForHumans()); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted text-center">Aucun utilisateur récent</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Graphique des paiements
    var paymentCtx = document.getElementById('paymentChart').getContext('2d');
    var paymentData = <?php echo json_encode($payment_chart, 15, 512) ?>;
    
    new Chart(paymentCtx, {
        type: 'line',
        data: {
            labels: paymentData.map(item => item.month),
            datasets: [{
                label: 'Paiements (<?php echo e(Qs::getCurrency()); ?>)',
                data: paymentData.map(item => item.amount),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique étudiants par classe
    var classCtx = document.getElementById('studentsClassChart').getContext('2d');
    var classData = <?php echo json_encode($students_by_class, 15, 512) ?>;
    
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: classData.map(item => item.my_class ? item.my_class.name : 'N/A'),
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: classData.map(item => item.total),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique par genre
    var genderCtx = document.getElementById('genderChart').getContext('2d');
    var genderData = <?php echo json_encode($students_by_gender, 15, 512) ?>;
    
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: genderData.map(item => item.gender === 'Male' ? 'Garçons' : 'Filles'),
            datasets: [{
                data: genderData.map(item => item.total),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 99, 132, 0.5)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\eschool\resources\views/pages/super_admin/dashboard.blade.php ENDPATH**/ ?>