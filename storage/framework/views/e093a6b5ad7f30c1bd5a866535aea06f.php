<?php $__env->startSection('page_title', 'Rapports et Statistiques'); ?>
<?php $__env->startSection('content'); ?>

<!-- En-tête -->
<div class="card">
    <div class="card-header header-elements-inline bg-teal-400">
        <h6 class="card-title text-white">
            <i class="icon-stats-dots mr-2"></i>
            Rapports et Statistiques de la Bibliothèque
        </h6>
        <div class="header-elements">
            <span class="badge badge-light badge-pill px-3 py-2">
                Période : <?php echo e(\Carbon\Carbon::now()->format('F Y')); ?>

            </span>
        </div>
    </div>
</div>

<!-- Cartes de rapports -->
<div class="row mt-3">
    <!-- Livres Populaires -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-success-400 mx-auto">
                        <i class="icon-stars text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Livres Populaires</h5>
                <p class="text-muted mb-4">
                    Consultez les livres les plus empruntés et leurs statistiques de demande
                </p>
                <a href="<?php echo e(route('librarian.reports.popular-books')); ?>" class="btn btn-success btn-block">
                    <i class="icon-eye mr-2"></i> Voir le rapport
                </a>
            </div>
        </div>
    </div>

    <!-- Étudiants Actifs -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-info-400 mx-auto">
                        <i class="icon-users text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Étudiants Actifs</h5>
                <p class="text-muted mb-4">
                    Liste des étudiants les plus actifs et leurs habitudes d'emprunt
                </p>
                <a href="<?php echo e(route('librarian.reports.active-students')); ?>" class="btn btn-info btn-block">
                    <i class="icon-eye mr-2"></i> Voir le rapport
                </a>
            </div>
        </div>
    </div>

    <!-- Rapport Mensuel -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-warning-400 mx-auto">
                        <i class="icon-calendar text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Rapport Mensuel</h5>
                <p class="text-muted mb-4">
                    Statistiques mensuelles des emprunts, retours et demandes
                </p>
                <a href="<?php echo e(route('librarian.reports.monthly')); ?>" class="btn btn-warning btn-block">
                    <i class="icon-eye mr-2"></i> Voir le rapport
                </a>
            </div>
        </div>
    </div>

    <!-- Inventaire -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-primary-400 mx-auto">
                        <i class="icon-database2 text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Inventaire</h5>
                <p class="text-muted mb-4">
                    État des stocks, livres en rupture et disponibilité par catégorie
                </p>
                <a href="<?php echo e(route('librarian.reports.inventory')); ?>" class="btn btn-primary btn-block">
                    <i class="icon-eye mr-2"></i> Voir le rapport
                </a>
            </div>
        </div>
    </div>

    <!-- Retards et Pénalités -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-danger-400 mx-auto">
                        <i class="icon-alarm text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Retards & Pénalités</h5>
                <p class="text-muted mb-4">
                    Suivi des retards de retour et calcul des pénalités
                </p>
                <a href="<?php echo e(route('librarian.reports.penalties')); ?>" class="btn btn-danger btn-block">
                    <i class="icon-eye mr-2"></i> Voir le rapport
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="col-md-6 col-lg-4">
        <div class="card report-card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="icon-container bg-teal-400 mx-auto">
                        <i class="icon-chart text-white icon-3x"></i>
                    </div>
                </div>
                <h5 class="font-weight-semibold mb-2">Statistiques Globales</h5>
                <p class="text-muted mb-4">
                    Vue d'ensemble des performances de la bibliothèque
                </p>
                <a href="<?php echo e(route('librarian.dashboard')); ?>" class="btn btn-teal btn-block">
                    <i class="icon-eye mr-2"></i> Voir le tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Section d'information -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title">
                    <i class="icon-info22 mr-2"></i>
                    Guide d'utilisation des rapports
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="media mb-3">
                            <div class="mr-3">
                                <i class="icon-filter3 icon-2x text-teal"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="font-weight-semibold mb-1">Filtres personnalisés</h6>
                                <p class="text-muted mb-0">
                                    Chaque rapport peut être filtré par période, catégorie ou critère spécifique.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="media mb-3">
                            <div class="mr-3">
                                <i class="icon-file-download icon-2x text-teal"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="font-weight-semibold mb-1">Export des données</h6>
                                <p class="text-muted mb-0">
                                    Exportez vos rapports au format PDF ou Excel pour archivage ou partage.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="media mb-3">
                            <div class="mr-3">
                                <i class="icon-sync icon-2x text-teal"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="font-weight-semibold mb-1">Mise à jour en temps réel</h6>
                                <p class="text-muted mb-0">
                                    Les statistiques sont mises à jour automatiquement avec les dernières données.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
<style>
    /* Styles pour les rapports */
    .bg-teal-400 {
        background-color: #26a69a !important;
        color: white !important;
    }
    
    .text-teal {
        color: #26a69a !important;
    }
    
    .btn-teal {
        background-color: #26a69a;
        border-color: #26a69a;
        color: white;
    }
    
    .btn-teal:hover {
        background-color: #00897b;
        border-color: #00897b;
        color: white;
    }
    
    /* Cartes de rapport */
    .report-card {
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    /* Conteneur d'icône */
    .icon-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    /* Animation au chargement */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .report-card {
        animation: fadeInUp 0.5s ease-out;
    }
    
    .report-card:nth-child(1) { animation-delay: 0.1s; }
    .report-card:nth-child(2) { animation-delay: 0.2s; }
    .report-card:nth-child(3) { animation-delay: 0.3s; }
    .report-card:nth-child(4) { animation-delay: 0.4s; }
    .report-card:nth-child(5) { animation-delay: 0.5s; }
    .report-card:nth-child(6) { animation-delay: 0.6s; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .icon-container {
            width: 60px;
            height: 60px;
        }
        
        .icon-container i {
            font-size: 2rem !important;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/librarian/reports/index.blade.php ENDPATH**/ ?>