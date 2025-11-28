@extends('layouts.master')
@section('page_title', 'Statistiques de l\'École')

@section('content')
{{-- En-tête --}}
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques de l'École</h4>
                <small class="opacity-75">Année scolaire {{ $year }}</small>
            </div>
            <a href="{{ route('statistics.export') }}" class="btn btn-light">
                <i class="icon-download mr-1"></i> Exporter CSV
            </a>
        </div>
    </div>
</div>

{{-- Stats générales --}}
<div class="row">
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-users icon-2x text-primary mb-2"></i>
                <h3 class="mb-0">{{ $generalStats['total_students'] }}</h3>
                <small class="text-muted">Élèves</small>
                <div class="mt-2">
                    <small class="text-primary">♂ {{ $generalStats['boys'] }}</small>
                    <small class="text-danger ml-2">♀ {{ $generalStats['girls'] }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-user-tie icon-2x text-success mb-2"></i>
                <h3 class="mb-0">{{ $generalStats['total_teachers'] }}</h3>
                <small class="text-muted">Enseignants</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-library icon-2x text-info mb-2"></i>
                <h3 class="mb-0">{{ $generalStats['total_classes'] }}</h3>
                <small class="text-muted">Classes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card text-center">
            <div class="card-body py-3">
                <i class="icon-book icon-2x text-warning mb-2"></i>
                <h3 class="mb-0">{{ $generalStats['total_subjects'] }}</h3>
                <small class="text-muted">Matières</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Répartition garçons/filles --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-pie-chart5 mr-2"></i> Répartition par Genre</h6>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Taux de présence --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-checkmark-circle mr-2"></i> Assiduité Globale</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="200"></canvas>
                <div class="text-center mt-3">
                    <h4 class="text-success mb-0">{{ $attendanceStats['rate'] }}%</h4>
                    <small class="text-muted">Taux de présence</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Finances --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-coins mr-2"></i> Situation Financière</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Recouvrement</span>
                        <strong>{{ $financeStats['collection_rate'] }}%</strong>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $financeStats['collection_rate'] }}%"></div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="text-success mb-0">{{ number_format($financeStats['total_paid']) }}</h5>
                        <small class="text-muted">Payé (FC)</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-danger mb-0">{{ number_format($financeStats['total_balance']) }}</h5>
                        <small class="text-muted">Reste (FC)</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <span class="badge badge-success">{{ $financeStats['students_paid_full'] }}</span>
                        <br><small>Soldés</small>
                    </div>
                    <div class="col-6">
                        <span class="badge badge-warning">{{ $financeStats['students_with_balance'] }}</span>
                        <br><small>Avec solde</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Moyennes par classe --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars2 mr-2"></i> Moyennes par Classe</h6>
            </div>
            <div class="card-body">
                <canvas id="classChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-line-chart mr-2"></i> Inscriptions Mensuelles</h6>
            </div>
            <div class="card-body">
                <canvas id="enrollmentChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Top élèves --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-trophy mr-2"></i> Top 10 Élèves</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topStudents as $index => $item)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            🥇
                                        @elseif($index == 1)
                                            🥈
                                        @elseif($index == 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td><strong>{{ $item['student']->name ?? 'N/A' }}</strong></td>
                                    <td><small>{{ $item['class']->name ?? '' }}</small></td>
                                    <td><span class="badge badge-success">{{ $item['average'] }}/20</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">Pas de données</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Élèves en difficulté --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Élèves en Difficulté</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Élève</th>
                                <th>Classe</th>
                                <th>Moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($strugglingStudents as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item['student']->name ?? 'N/A' }}</strong></td>
                                    <td><small>{{ $item['class']->name ?? '' }}</small></td>
                                    <td><span class="badge badge-danger">{{ $item['average'] }}/20</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-success py-3">✓ Tous les élèves réussissent!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Performance par matière --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-book mr-2"></i> Performance par Matière</h6>
    </div>
    <div class="card-body">
        <canvas id="subjectChart" height="80"></canvas>
    </div>
</div>

{{-- Tableau récapitulatif --}}
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between">
        <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Récapitulatif par Classe</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped datatable-basic mb-0">
                <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Type</th>
                        <th>Titulaire</th>
                        <th>Élèves</th>
                        <th>Moyenne</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classStats as $stat)
                        <tr>
                            <td><strong>{{ $stat['name'] }}</strong></td>
                            <td>{{ $stat['type'] }}</td>
                            <td>{{ $stat['teacher'] }}</td>
                            <td>{{ $stat['students'] }}</td>
                            <td>{{ $stat['average'] }}/20</td>
                            <td>
                                <div class="progress" style="height: 20px; min-width: 100px;">
                                    @php $pct = ($stat['average'] / 20) * 100; @endphp
                                    <div class="progress-bar {{ $pct >= 50 ? 'bg-success' : 'bg-danger' }}" 
                                         style="width: {{ $pct }}%">
                                        {{ round($pct) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Données
const generalStats = @json($generalStats);
const classStats = @json($classStats);
const attendanceStats = @json($attendanceStats);
const enrollmentTrend = @json($enrollmentTrend);
const subjectPerformance = @json($subjectPerformance);

// Graphique Genre
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Garçons', 'Filles'],
        datasets: [{
            data: [generalStats.boys, generalStats.girls],
            backgroundColor: ['#2196F3', '#E91E63'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Graphique Présence
new Chart(document.getElementById('attendanceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Présent', 'Absent', 'Retard', 'Excusé'],
        datasets: [{
            data: [attendanceStats.present, attendanceStats.absent, attendanceStats.late, attendanceStats.excused],
            backgroundColor: ['#4CAF50', '#f44336', '#FF9800', '#9E9E9E'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Graphique Classes
new Chart(document.getElementById('classChart'), {
    type: 'bar',
    data: {
        labels: classStats.map(c => c.name),
        datasets: [{
            label: 'Moyenne',
            data: classStats.map(c => c.average),
            backgroundColor: classStats.map(c => c.average >= 10 ? '#4CAF50' : '#f44336'),
            borderRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, max: 20 }
        }
    }
});

// Graphique Inscriptions
new Chart(document.getElementById('enrollmentChart'), {
    type: 'line',
    data: {
        labels: enrollmentTrend.map(e => e.month),
        datasets: [{
            label: 'Inscriptions',
            data: enrollmentTrend.map(e => e.count),
            borderColor: '#2196F3',
            backgroundColor: 'rgba(33, 150, 243, 0.1)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Graphique Matières
new Chart(document.getElementById('subjectChart'), {
    type: 'bar',
    data: {
        labels: subjectPerformance.map(s => s.name),
        datasets: [{
            label: 'Moyenne',
            data: subjectPerformance.map(s => s.average),
            backgroundColor: subjectPerformance.map(s => s.average >= 10 ? '#4CAF50' : '#f44336'),
            borderRadius: 5,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, max: 20 } }
    }
});
</script>
@endsection
