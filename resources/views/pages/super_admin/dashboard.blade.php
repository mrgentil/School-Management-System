@extends('layouts.master')
@section('page_title', '📊 Tableau de bord Super Admin')

@section('content')

<style>
/* ========== ANIMATIONS MODERNES ========== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

.fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.slide-in-left {
    animation: slideInLeft 0.6s ease-out forwards;
}

/* Décalage d'animation pour chaque carte */
.card-animated:nth-child(1) { animation-delay: 0.1s; }
.card-animated:nth-child(2) { animation-delay: 0.2s; }
.card-animated:nth-child(3) { animation-delay: 0.3s; }
.card-animated:nth-child(4) { animation-delay: 0.4s; }

/* Effet hover sur les cartes statistiques */
.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.stat-card .icon-animated {
    transition: all 0.3s ease;
}

.stat-card:hover .icon-animated {
    transform: scale(1.2) rotate(5deg);
}

/* Effet de brillance sur les cartes */
.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.stat-card:hover::before {
    left: 100%;
}

/* Cartes avec dégradés modernes */
.gradient-blue {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-green {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-orange {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}

.gradient-purple {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

/* Animation de compteur */
.counter-value {
    display: inline-block;
    transition: all 0.3s ease;
}

/* Charts avec animation */
.chart-container {
    opacity: 0;
    animation: fadeIn 1s ease-out 0.5s forwards;
}

/* Alert moderne */
.modern-alert {
    border-radius: 15px;
    border-left: 5px solid #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    animation: slideInLeft 0.6s ease-out;
}

/* Mini-cartes avec bordure gauche */
.border-left-3 {
    border-left: 3px solid !important;
}

/* Effet hover amélioré */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

/* Espacement des sections */
.row + .row {
    margin-top: 1.5rem;
}

/* Backgrounds légers pour icônes */
.bg-primary-100 {
    background-color: rgba(102, 126, 234, 0.1);
}

.bg-success-100 {
    background-color: rgba(76, 175, 80, 0.1);
}

.bg-warning-100 {
    background-color: rgba(255, 152, 0, 0.1);
}

.bg-danger-100 {
    background-color: rgba(244, 67, 54, 0.1);
}

/* Badge animations */
.badge-pill {
    animation: pulse 2s infinite;
}

/* Progress bar smooth */
.progress-bar {
    transition: width 1s ease-in-out;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="alert alert-info border-0 alert-dismissible modern-alert">
            <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
            <span class="font-weight-semibold">👋 Bienvenue, {{ Auth::user()->name }} !</span> 
            Voici un aperçu global de votre établissement.
        </div>
    </div>
</div>

{{-- Statistiques principales avec barres de progression et badges --}}
<div class="row">
    <div class="col-sm-6 col-xl-3 card-animated fade-in-up">
        <div class="card card-body gradient-blue stat-card position-relative">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="mb-0 text-white counter-value" data-target="{{ $stats['total_students'] }}">0</h3>
                    <span class="text-uppercase font-size-xs text-white font-weight-bold">👨‍🎓 Étudiants</span>
                </div>
                <div>
                    <i class="icon-users4 icon-3x text-white icon-animated"></i>
                </div>
            </div>
            {{-- Barre de progression --}}
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.2);">
                <div class="progress-bar" style="width: 85%; background: rgba(255,255,255,0.9);"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-white opacity-75">85% de capacité</small>
                <span class="badge badge-light badge-pill">+5%</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 card-animated fade-in-up">
        <div class="card card-body gradient-green stat-card position-relative">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="mb-0 text-white counter-value" data-target="{{ $stats['total_teachers'] }}">0</h3>
                    <span class="text-uppercase font-size-xs text-white font-weight-bold">👨‍🏫 Enseignants</span>
                </div>
                <div>
                    <i class="icon-user-tie icon-3x text-white icon-animated"></i>
                </div>
            </div>
            {{-- Barre de progression --}}
            <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.2);">
                <div class="progress-bar" style="width: 92%; background: rgba(255,255,255,0.9);"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-white opacity-75">Actifs</small>
                <span class="badge badge-light badge-pill">+2</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 card-animated fade-in-up">
        <div class="card card-body gradient-orange stat-card position-relative">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="mb-0 text-dark counter-value" data-target="{{ $stats['total_classes'] }}">0</h3>
                    <span class="text-uppercase font-size-xs text-dark font-weight-bold">🏫 Classes</span>
                </div>
                <div>
                    <i class="icon-office icon-3x text-dark icon-animated"></i>
                </div>
            </div>
            {{-- Barre de progression --}}
            <div class="progress mt-2" style="height: 6px; background: rgba(0,0,0,0.1);">
                <div class="progress-bar bg-dark" style="width: 100%;"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-dark opacity-75">Toutes actives</small>
                <span class="badge badge-dark badge-pill">100%</span>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 card-animated fade-in-up">
        <div class="card card-body gradient-purple stat-card position-relative">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h3 class="mb-0 text-dark counter-value" data-target="{{ $stats['total_staff'] }}">0</h3>
                    <span class="text-uppercase font-size-xs text-dark font-weight-bold">👔 Personnel</span>
                </div>
                <div>
                    <i class="icon-briefcase icon-3x text-dark icon-animated"></i>
                </div>
            </div>
            {{-- Barre de progression --}}
            <div class="progress mt-2" style="height: 6px; background: rgba(0,0,0,0.1);">
                <div class="progress-bar bg-dark" style="width: 78%;"></div>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <small class="text-dark opacity-75">En service</small>
                <span class="badge badge-dark badge-pill">78%</span>
            </div>
        </div>
    </div>
</div>

{{-- Graphiques modernes --}}
<div class="row">
    {{-- Graphique mixte (barres + courbe) - Pleine largeur --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header header-elements-inline border-0 pb-0">
                <h6 class="card-title font-weight-bold">📊 Évolution des paiements</h6>
                <div class="header-elements">
                    <span class="badge badge-success badge-pill">6 derniers mois</span>
                </div>
            </div>
            <div class="card-body py-2">
                <div style="height: 220px;">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mini-cartes Activités récentes --}}
<div class="row">
    <div class="col-12">
        <h6 class="font-weight-bold mb-3"><i class="icon-pulse2 mr-2"></i>Activités récentes</h6>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-3 border-left-primary">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="bg-primary-100 rounded p-2">
                            <i class="icon-users4 icon-2x text-primary"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $stats['total_students'] }}</h6>
                        <small class="text-muted">Inscriptions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-3 border-left-success">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="bg-success-100 rounded p-2">
                            <i class="icon-checkmark-circle icon-2x text-success"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $payments_count }}</h6>
                        <small class="text-muted">Paiements validés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-3 border-left-warning">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="bg-warning-100 rounded p-2">
                            <i class="icon-book icon-2x text-warning"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $stats['total_classes'] }}</h6>
                        <small class="text-muted">Classes actives</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-3 border-left-danger">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <div class="bg-danger-100 rounded p-2">
                            <i class="icon-calendar icon-2x text-danger"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ count($upcoming_events ?? []) }}</h6>
                        <small class="text-muted">Événements</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Répartition des étudiants --}}
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📊 Étudiants par classe</h6>
            </div>
            <div class="card-body py-2">
                <div style="height: 180px;">
                    <canvas id="studentsClassChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">👥 Répartition par genre</h6>
            </div>
            <div class="card-body py-2">
                <div style="height: 180px;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Événements et Annonces --}}
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📅 Événements à venir</h6>
                <div class="header-elements">
                    <a href="{{ route('events.index') }}" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                @forelse($upcoming_events as $event)
                <div class="media mb-3">
                    <div class="mr-3">
                        <div class="bg-primary text-white rounded p-2 text-center" style="width: 50px;">
                            <div class="font-weight-semibold">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</div>
                            <div class="font-size-sm">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</div>
                        </div>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0">{{ $event->title }}</h6>
                        <span class="text-muted font-size-sm">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Aucun événement à venir</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📢 Dernières annonces</h6>
                <div class="header-elements">
                    <a href="{{ route('notices.index') }}" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_notices as $notice)
                <div class="media mb-3">
                    <div class="mr-3">
                        <i class="icon-megaphone icon-2x text-info"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0">{{ Str::limit($notice->title, 40) }}</h6>
                        <span class="text-muted font-size-sm">{{ $notice->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Aucune annonce récente</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Demandes en attente et Activité récente --}}
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">📚 Demandes de livres en attente</h6>
                <div class="header-elements">
                    <a href="{{ route('book-requests.index') }}" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                @forelse($pending_book_requests as $request)
                <div class="media mb-3">
                    <div class="mr-3">
                        <i class="icon-book icon-2x text-warning"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0">{{ $request->book->name }}</h6>
                        <span class="text-muted font-size-sm">{{ $request->student->user->name }} - {{ $request->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Aucune demande en attente</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">🆕 Utilisateurs récents</h6>
                <div class="header-elements">
                    <a href="{{ route('users.index') }}" class="text-muted"><i class="icon-more2"></i></a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_users as $user)
                <div class="media mb-3">
                    <div class="mr-3">
                        <img src="{{ $user->photo ?: asset('global_assets/images/user.png') }}" width="40" height="40" class="rounded-circle" alt="{{ $user->name }}" onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                    </div>
                    <div class="media-body">
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <span class="text-muted font-size-sm">{{ ucwords(str_replace('_', ' ', $user->user_type)) }} - {{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Aucun utilisateur récent</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Scripts pour les graphiques --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== ANIMATION DE COMPTEUR ==========
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // 2 secondes
        const step = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }
    
    // Animer tous les compteurs au chargement
    document.querySelectorAll('.counter-value').forEach(element => {
        animateCounter(element);
    });
    
    // ========== GRAPHIQUE MIXTE PAIEMENTS (BARRES + COURBE) ==========
    var paymentCtx = document.getElementById('paymentChart').getContext('2d');
    var paymentData = @json($payment_chart);
    
    new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: paymentData.map(item => item.month),
            datasets: [
                {
                    type: 'bar',
                    label: 'Montant',
                    data: paymentData.map(item => item.amount),
                    backgroundColor: function(context) {
                        const value = context.parsed.y;
                        const max = Math.max(...paymentData.map(item => item.amount));
                        return value === max ? 'rgba(102, 126, 234, 0.8)' : 'rgba(200, 200, 200, 0.3)';
                    },
                    borderColor: function(context) {
                        const value = context.parsed.y;
                        const max = Math.max(...paymentData.map(item => item.amount));
                        return value === max ? 'rgba(102, 126, 234, 1)' : 'rgba(200, 200, 200, 0.6)';
                    },
                    borderWidth: 2,
                    borderRadius: 8,
                    maxBarThickness: 40
                },
                {
                    type: 'line',
                    label: 'Tendance',
                    data: paymentData.map(item => item.amount),
                    borderColor: 'rgba(245, 87, 108, 0.8)',
                    backgroundColor: 'transparent',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgba(245, 87, 108, 1)',
                    pointBorderWidth: 2,
                    pointBorderColor: '#fff'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        padding: 15,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += '{{ Qs::getCurrency() }} ' + new Intl.NumberFormat().format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '{{ Qs::getCurrency() }} ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // ========== GRAPHIQUE ÉTUDIANTS PAR CLASSE (AMÉLIORÉ) ==========
    var classCtx = document.getElementById('studentsClassChart').getContext('2d');
    var classData = @json($students_by_class);
    
    // Créer un dégradé pour chaque barre
    var gradient = classCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
    gradient.addColorStop(1, 'rgba(118, 75, 162, 0.8)');
    
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: classData.map(item => item.my_class ? item.my_class.name : 'N/A'),
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: classData.map(item => item.total),
                backgroundColor: gradient,
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 'flex',
                maxBarThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart',
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default') {
                        delay = context.dataIndex * 100;
                    }
                    return delay;
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // ========== GRAPHIQUE PAR GENRE (AMÉLIORÉ) ==========
    var genderCtx = document.getElementById('genderChart').getContext('2d');
    var genderData = @json($students_by_gender);
    
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: genderData.map(item => item.gender === 'Male' ? '👦 Garçons' : '👧 Filles'),
            datasets: [{
                data: genderData.map(item => item.total),
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(245, 87, 108, 0.8)'
                ],
                borderColor: [
                    'rgba(102, 126, 234, 1)',
                    'rgba(245, 87, 108, 1)'
                ],
                borderWidth: 3,
                hoverOffset: 15,
                spacing: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
});
</script>

@endsection
