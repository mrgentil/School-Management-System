@extends('layouts.master')
@section('page_title', 'Ma Progression')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline bg-primary text-white">
                    <h6 class="card-title">
                        <i class="icon-graph mr-2"></i> Ma Progression Académique
                    </h6>
                    {!! Qs::getPanelOptions() !!}
                </div>

                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <strong>Classe:</strong> {{ $sr->my_class->full_name ?: $sr->my_class->name }} - {{ $sr->section->name }} | 
                        <strong>Année:</strong> {{ $current_year }}
                    </div>

                    {{-- Moyennes Période --}}
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success-400">
                                    <h6 class="card-title">
                                        <i class="icon-calendar mr-2"></i> Moyennes par Période
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @for($i = 1; $i <= 4; $i++)
                                        <div class="col-md-3">
                                            <div class="card border-left-3 border-left-{{ $period_averages[$i] >= 60 ? 'success' : ($period_averages[$i] >= 50 ? 'warning' : 'danger') }}">
                                                <div class="card-body text-center">
                                                    <h5 class="mb-0">Période {{ $i }}</h5>
                                                    <h2 class="font-weight-bold text-{{ $period_averages[$i] >= 60 ? 'success' : ($period_averages[$i] >= 50 ? 'warning' : 'danger') }}">
                                                        {{ $period_averages[$i] ?? 'N/A' }}
                                                    </h2>
                                                    <p class="text-muted mb-0">
                                                        @if($period_averages[$i])
                                                            / 20
                                                        @else
                                                            Pas de notes
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Moyennes Semestre --}}
                    <div class="row mb-4">
                        @for($i = 1; $i <= 2; $i++)
                        <div class="col-md-6">
                            <div class="card bg-{{ $i == 1 ? 'primary' : 'info' }}-400 text-white">
                                <div class="card-body text-center">
                                    <h5 class="mb-2">Semestre {{ $i }}</h5>
                                    <h1 class="font-weight-bold">{{ $semester_averages[$i] ?? 'N/A' }}</h1>
                                    <p class="mb-0">Périodes {{ $i == 1 ? '1 & 2' : '3 & 4' }}</p>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    {{-- Graphique de Progression --}}
                    <div class="card mb-4">
                        <div class="card-header bg-warning-400">
                            <h6 class="card-title">
                                <i class="icon-graph mr-2"></i> Évolution de mes Performances
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="progressChart" height="80"></canvas>
                        </div>
                    </div>

                    {{-- Détails par Examen --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="card-title">Détails par Examen</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th>Examen</th>
                                        <th>Semestre</th>
                                        <th>Ma Moyenne</th>
                                        <th>Moyenne Classe</th>
                                        <th>Ma Position</th>
                                        <th>Performance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($progression_data as $data)
                                        <tr>
                                            <td><strong>{{ $data['exam'] }}</strong></td>
                                            <td>S{{ $data['semester'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $data['average'] >= 60 ? 'success' : ($data['average'] >= 50 ? 'warning' : 'danger') }}">
                                                    {{ $data['average'] }}%
                                                </span>
                                            </td>
                                            <td>{{ $data['class_avg'] }}%</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $data['position'] }}{{ $data['position'] == 1 ? 'er' : 'ème' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($data['average'] > $data['class_avg'])
                                                    <i class="icon-arrow-up12 text-success"></i> Au-dessus
                                                @elseif($data['average'] < $data['class_avg'])
                                                    <i class="icon-arrow-down12 text-danger"></i> En-dessous
                                                @else
                                                    <i class="icon-minus3 text-warning"></i> Dans la moyenne
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune donnée d'examen disponible</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Meilleures et Pires Matières --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-left-3 border-left-success">
                                <div class="card-header bg-success-400">
                                    <h6 class="card-title">
                                        <i class="icon-trophy mr-2"></i> Mes Meilleures Matières
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @forelse($best_subjects as $subject)
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong>{{ $subject['subject']->name }}</strong></span>
                                                <span class="badge badge-success">{{ $subject['average'] }}%</span>
                                            </div>
                                            <div class="progress mt-1" style="height: 10px;">
                                                <div class="progress-bar bg-success" style="width: {{ $subject['average'] }}%"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Aucune donnée disponible</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-left-3 border-left-danger">
                                <div class="card-header bg-danger-400">
                                    <h6 class="card-title">
                                        <i class="icon-alert mr-2"></i> Matières à Améliorer
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @forelse($worst_subjects as $subject)
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong>{{ $subject['subject']->name }}</strong></span>
                                                <span class="badge badge-danger">{{ $subject['average'] }}%</span>
                                            </div>
                                            <div class="progress mt-1" style="height: 10px;">
                                                <div class="progress-bar bg-danger" style="width: {{ $subject['average'] }}%"></div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Aucune donnée disponible</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recommandations --}}
                    @if(count($recommendations) > 0)
                    <div class="card mt-4">
                        <div class="card-header bg-info-400">
                            <h6 class="card-title">
                                <i class="icon-light-bulb mr-2"></i> Recommandations
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($recommendations as $rec)
                                <div class="alert alert-{{ $rec['type'] }} border-0">
                                    {{ $rec['message'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Progress Chart
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($progression_data as $data)
                    '{{ $data['exam'] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Ma Moyenne',
                data: [
                    @foreach($progression_data as $data)
                        {{ $data['average'] }},
                    @endforeach
                ],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.4
            },
            {
                label: 'Moyenne de Classe',
                data: [
                    @foreach($progression_data as $data)
                        {{ $data['class_avg'] }},
                    @endforeach
                ],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
