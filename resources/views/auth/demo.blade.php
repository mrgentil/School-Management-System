@extends('layouts.login_master')

@section('content')
<div class="page-content login-cover">
    <div class="content-wrapper">
        <div class="content d-flex justify-content-center align-items-center">
            <div class="demo-container" style="max-width: 800px; width: 100%;">
                
                <!-- Header -->
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="icon-graduation2 icon-3x text-primary"></i>
                    </div>
                    <h2 class="font-weight-bold text-dark mb-2">Démonstration</h2>
                    <p class="text-muted mb-0">Découvrez notre système de gestion scolaire</p>
                    <p class="text-muted">Cliquez sur un profil pour vous connecter instantanément</p>
                </div>

                <!-- Demo Cards -->
                <div class="row">
                    <!-- Admin -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'admin') }}" class="btn btn-outline-primary btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-user-tie icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Administrateur</h5>
                            <small class="text-muted d-block">Gestion complète de l'école</small>
                        </a>
                    </div>

                    <!-- Teacher -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'teacher') }}" class="btn btn-outline-success btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-book icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Enseignant</h5>
                            <small class="text-muted d-block">Notes, devoirs, présences</small>
                        </a>
                    </div>

                    <!-- Student -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'student') }}" class="btn btn-outline-info btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-graduation2 icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Élève</h5>
                            <small class="text-muted d-block">Bulletins, emploi du temps</small>
                        </a>
                    </div>

                    <!-- Parent -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'parent') }}" class="btn btn-outline-warning btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-users icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Parent</h5>
                            <small class="text-muted d-block">Suivi de l'enfant</small>
                        </a>
                    </div>

                    <!-- Accountant -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'accountant') }}" class="btn btn-outline-danger btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-calculator icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Comptable</h5>
                            <small class="text-muted d-block">Paiements, factures</small>
                        </a>
                    </div>

                    <!-- Librarian -->
                    <div class="col-md-4 col-sm-6 mb-3">
                        <a href="{{ route('demo.login', 'librarian') }}" class="btn btn-outline-secondary btn-block p-3 demo-card h-100 text-decoration-none">
                            <div class="demo-icon mb-2">
                                <i class="icon-library2 icon-2x"></i>
                            </div>
                            <h5 class="font-weight-bold mb-1">Bibliothécaire</h5>
                            <small class="text-muted d-block">Gestion des livres</small>
                        </a>
                    </div>
                </div>

                <!-- Classic Login Link -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="btn btn-light">
                        <i class="icon-enter mr-2"></i> Connexion classique
                    </a>
                </div>

                <!-- Info -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="icon-info22 mr-1"></i>
                        Ceci est un environnement de démonstration. Les données sont fictives.
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .demo-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .demo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .demo-card .demo-icon {
        opacity: 0.8;
    }
    
    .demo-card:hover .demo-icon {
        opacity: 1;
    }
    
    .demo-container {
        padding: 20px;
    }
    
    @media (max-width: 576px) {
        .demo-card {
            min-height: 120px;
        }
    }
</style>
@endsection
