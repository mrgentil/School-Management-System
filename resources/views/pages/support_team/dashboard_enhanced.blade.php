@extends('layouts.master')
@section('page_title', 'Tableau de Bord')

@section('content')
<div class="row">
    {{-- Cartes statistiques principales --}}
    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-primary has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Élèves</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-danger has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Enseignants</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-users2 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-success has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['total_parents'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Parents</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-user icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-info has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Classes</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-library icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-warning has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['total_subjects'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Matières</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-2">
        <div class="card card-body bg-indigo has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ $stats['active_exams'] }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Examens</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-clipboard3 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Graphique: Élèves par classe --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-stats-bars mr-2"></i> Répartition par Classe
                </h5>
            </div>
            <div class="card-body">
                <canvas id="studentsByClassChart" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphique: Répartition par genre --}}
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-users mr-2"></i> Par Genre
                </h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Graphique: Présences --}}
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar mr-2"></i> Présences (Mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Graphique: Performance par période --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-chart mr-2"></i> Moyenne Générale par Période
                </h5>
            </div>
            <div class="card-body">
                <canvas id="performanceChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Alertes --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-bell2 mr-2"></i> Alertes
                </h5>
            </div>
            <div class="card-body">
                @forelse($alerts as $alert)
                    <div class="alert alert-{{ $alert['type'] }} d-flex align-items-center">
                        <i class="{{ $alert['icon'] }} mr-2"></i>
                        {{ $alert['message'] }}
                    </div>
                @empty
                    <div class="alert alert-success">
                        <i class="icon-checkmark mr-2"></i>
                        Tout est en ordre !
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Top 5 Élèves --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-trophy mr-2 text-warning"></i> Top 5 Meilleurs Élèves
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Classe</th>
                            <th>Moyenne</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topStudents as $index => $student)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <span class="badge badge-warning">🥇</span>
                                    @elseif($index == 1)
                                        <span class="badge badge-secondary">🥈</span>
                                    @elseif($index == 2)
                                        <span class="badge badge-danger">🥉</span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td><strong>{{ $student['name'] }}</strong></td>
                                <td>{{ $student['class'] }}</td>
                                <td>
                                    <span class="badge badge-{{ $student['average'] >= 14 ? 'success' : ($student['average'] >= 10 ? 'warning' : 'danger') }}">
                                        {{ $student['average'] }}/20
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune donnée disponible</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tendance des inscriptions --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-trending_up mr-2"></i> Inscriptions (6 derniers mois)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Accès rapides --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="icon-lightning mr-2"></i> Accès Rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('students.create') }}" class="btn btn-outline-primary btn-block">
                            <i class="icon-user-plus d-block mb-1" style="font-size: 24px;"></i>
                            Nouvel Élève
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('bulletins.index') }}" class="btn btn-outline-success btn-block">
                            <i class="icon-file-text d-block mb-1" style="font-size: 24px;"></i>
                            Bulletins
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('marks.index') }}" class="btn btn-outline-info btn-block">
                            <i class="icon-pencil d-block mb-1" style="font-size: 24px;"></i>
                            Notes
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('attendance.index') }}" class="btn btn-outline-warning btn-block">
                            <i class="icon-calendar d-block mb-1" style="font-size: 24px;"></i>
                            Présences
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('exams.index') }}" class="btn btn-outline-danger btn-block">
                            <i class="icon-clipboard3 d-block mb-1" style="font-size: 24px;"></i>
                            Examens
                        </a>
                    </div>
                    <div class="col-md-2 col-6 mb-3">
                        <a href="{{ route('whatsapp.test') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fab fa-whatsapp d-block mb-1" style="font-size: 24px;"></i>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données PHP vers JavaScript
    const studentsByClass = @json($studentsByClass);
    const genderData = @json($studentsByGender);
    const performanceData = @json($performanceData);
    const attendanceData = @json($attendanceData);
    const enrollmentData = @json($enrollmentTrend);

    // Couleurs
    const colors = {
        primary: '#2196F3',
        success: '#4CAF50',
        warning: '#FF9800',
        danger: '#F44336',
        info: '#00BCD4',
        purple: '#9C27B0',
    };

    // 1. Graphique: Élèves par classe (Bar)
    new Chart(document.getElementById('studentsByClassChart'), {
        type: 'bar',
        data: {
            labels: studentsByClass.map(item => item.class),
            datasets: [{
                label: 'Élèves',
                data: studentsByClass.map(item => item.count),
                backgroundColor: [
                    colors.primary, colors.success, colors.warning, 
                    colors.danger, colors.info, colors.purple,
                    '#3F51B5', '#009688', '#795548', '#607D8B'
                ],
                borderWidth: 0,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // 2. Graphique: Genre (Doughnut)
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Garçons', 'Filles'],
            datasets: [{
                data: [genderData.male, genderData.female],
                backgroundColor: [colors.primary, '#E91E63'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Graphique: Présences (Pie)
    new Chart(document.getElementById('attendanceChart'), {
        type: 'pie',
        data: {
            labels: ['Présent', 'Absent', 'Retard', 'Excusé'],
            datasets: [{
                data: [
                    attendanceData.present, 
                    attendanceData.absent, 
                    attendanceData.late, 
                    attendanceData.excused
                ],
                backgroundColor: [colors.success, colors.danger, colors.warning, colors.info],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 4. Graphique: Performance (Line)
    new Chart(document.getElementById('performanceChart'), {
        type: 'line',
        data: {
            labels: performanceData.map(item => item.period),
            datasets: [{
                label: 'Moyenne Générale',
                data: performanceData.map(item => item.average),
                borderColor: colors.primary,
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true, 
                    max: 20,
                    title: { display: true, text: 'Note /20' }
                }
            }
        }
    });

    // 5. Graphique: Inscriptions (Area)
    new Chart(document.getElementById('enrollmentChart'), {
        type: 'line',
        data: {
            labels: enrollmentData.map(item => item.month),
            datasets: [{
                label: 'Inscriptions',
                data: enrollmentData.map(item => item.count),
                borderColor: colors.success,
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
