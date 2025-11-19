@extends('layouts.master')
@section('page_title', 'Tableau de Bord Analytics - Examens')
@section('content')

<div class="content">
    {{-- En-tête avec statistiques générales --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $total_students }}</h3>
                            <span class="font-size-sm">Étudiants Total</span>
                        </div>
                        <i class="icon-users4 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $total_exams }}</h3>
                            <span class="font-size-sm">Examens Cette Année</span>
                        </div>
                        <i class="icon-file-text2 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $total_classes }}</h3>
                            <span class="font-size-sm">Classes Actives</span>
                        </div>
                        <i class="icon-office icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ count($struggling_students) }}</h3>
                            <span class="font-size-sm">Étudiants en Difficulté</span>
                        </div>
                        <i class="icon-warning22 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu de navigation rapide --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">🚀 Accès Rapide aux Analytics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.struggling_students') }}" class="btn btn-outline-danger btn-block">
                                <i class="icon-user-minus mr-2"></i>Étudiants en Difficulté
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.rankings') }}" class="btn btn-outline-success btn-block">
                                <i class="icon-trophy mr-2"></i>Classements & Palmarès
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.historical_comparison') }}" class="btn btn-outline-primary btn-block">
                                <i class="icon-stats-bars mr-2"></i>Comparaisons Historiques
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.subject_teacher_reports') }}" class="btn btn-outline-info btn-block">
                                <i class="icon-graph mr-2"></i>Rapports par Matière
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Évolution des moyennes mensuelles --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📈 Évolution des Moyennes (12 derniers mois)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyAveragesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Top performers --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">🏆 Top 10 Performers</h6>
                </div>
                <div class="card-body">
                    @forelse($top_performers as $performer)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge badge-success mr-2">{{ $loop->iteration }}</span>
                            <span class="font-weight-semibold">{{ $performer->student->user->name }}</span>
                        </div>
                        <span class="text-success font-weight-bold">{{ round($performer->ave, 1) }}%</span>
                    </div>
                    @empty
                    <p class="text-muted text-center">Aucune donnée disponible</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Comparaisons inter-classes --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📊 Comparaisons Inter-Classes</h6>
                </div>
                <div class="card-body">
                    <canvas id="classComparisonChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Performance par matière --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📚 Performance par Matière</h6>
                </div>
                <div class="card-body">
                    <canvas id="subjectPerformanceChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Étudiants en difficulté - Alerte --}}
    @if(count($struggling_students) > 0)
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-left-3 border-left-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">⚠️ Alerte - Étudiants Nécessitant une Attention Particulière</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Classe</th>
                                    <th>Moyenne Récente</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($struggling_students->take(5) as $student)
                                <tr>
                                    <td>{{ $student->student->user->name }}</td>
                                    <td>{{ $student->student->my_class->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-danger">{{ round($student->ave, 1) }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="icon-warning22 mr-1"></i>En difficulté
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('exam_analytics.student_progress_chart', $student->student_id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="icon-graph mr-1"></i>Voir Progression
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('exam_analytics.struggling_students') }}" class="btn btn-danger">
                            <i class="icon-eye mr-2"></i>Voir Tous les Étudiants en Difficulté
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Actions rapides --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">⚡ Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#importMarksModal">
                                <i class="icon-upload mr-2"></i>Importer Notes Excel
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#exportResultsModal">
                                <i class="icon-download mr-2"></i>Exporter Résultats
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#notifyParentsModal">
                                <i class="icon-mail5 mr-2"></i>Notifier Parents
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('marks.index') }}" class="btn btn-warning btn-block">
                                <i class="icon-pencil5 mr-2"></i>Saisir Notes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals pour actions rapides --}}
@include('pages.support_team.exam_analytics.modals.import_marks')
@include('pages.support_team.exam_analytics.modals.export_results')
@include('pages.support_team.exam_analytics.modals.notify_parents')

@endsection

@section('page_script')
<script src="{{ asset('global_assets/js/plugins/visualization/chartjs/chart.min.js') }}"></script>
<script>
// Graphique des moyennes mensuelles
const monthlyCtx = document.getElementById('monthlyAveragesChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: @json(array_column($monthly_averages, 'month')),
        datasets: [{
            label: 'Moyenne Générale (%)',
            data: @json(array_column($monthly_averages, 'average')),
            borderColor: '#26a69a',
            backgroundColor: 'rgba(38, 166, 154, 0.1)',
            tension: 0.4,
            fill: true
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
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Graphique comparaisons inter-classes
const classCtx = document.getElementById('classComparisonChart').getContext('2d');
const classChart = new Chart(classCtx, {
    type: 'bar',
    data: {
        labels: @json(array_column($class_comparisons, 'class_name')),
        datasets: [{
            label: 'Moyenne de Classe (%)',
            data: @json(array_column($class_comparisons, 'average')),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
            ]
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
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Graphique performance par matière
const subjectCtx = document.getElementById('subjectPerformanceChart').getContext('2d');
const subjectChart = new Chart(subjectCtx, {
    type: 'doughnut',
    data: {
        labels: @json(array_column($subject_performance, 'subject_name')),
        datasets: [{
            data: @json(array_column($subject_performance, 'overall_avg')),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                '#4BC0C0', '#9966FF'
            ]
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

// Auto-refresh toutes les 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endsection
