@extends('layouts.master')
@section('page_title', 'Progression de l\'étudiant')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-light">
        <h5 class="card-title">
            <i class="icon-stats-growth mr-2"></i> Progression de l'étudiant
        </h5>
        <div class="header-elements">
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if($student && $sr)
            {{-- Informations de l'étudiant --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="media">
                        <div class="mr-3">
                            <img src="{{ $student->photo ?: asset('global_assets/images/user.png') }}" 
                                 width="80" height="80" class="rounded-circle" alt="{{ $student->name }}">
                        </div>
                        <div class="media-body">
                            <h4 class="mb-1">{{ $student->name }}</h4>
                            <p class="mb-1">
                                <span class="badge badge-primary">{{ $sr->my_class->name ?? 'N/A' }}</span>
                                <span class="badge badge-secondary">{{ $sr->section->name ?? '' }}</span>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="icon-calendar3 mr-1"></i> Session: {{ $sr->session }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <div class="d-inline-block text-left">
                        <p class="mb-1"><strong>N° Matricule:</strong> {{ $sr->adm_no }}</p>
                        <p class="mb-0"><strong>Année d'admission:</strong> {{ $sr->year_admitted }}</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Graphique de progression --}}
            @if(count($progress_data) > 0)
                <div class="row">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">
                            <i class="icon-chart mr-2"></i> Évolution des résultats
                        </h6>
                        <div style="height: 300px;">
                            <canvas id="progressChart"></canvas>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Tableau des résultats --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="font-weight-bold mb-3">
                            <i class="icon-list mr-2"></i> Détail des examens
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Année</th>
                                        <th>Examen</th>
                                        <th>Total</th>
                                        <th>Moyenne</th>
                                        <th>Position</th>
                                        <th>Appréciation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($progress_data as $data)
                                        <tr>
                                            <td>{{ $data['year'] }}</td>
                                            <td>{{ $data['exam_name'] }}</td>
                                            <td>{{ $data['total'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $data['average'] >= 50 ? 'success' : 'danger' }}">
                                                    {{ number_format($data['average'], 2) }}%
                                                </span>
                                            </td>
                                            <td>
                                                @if($data['position'] <= 3)
                                                    <span class="badge badge-warning">{{ $data['position'] }}{{ $data['position'] == 1 ? 'er' : 'ème' }}</span>
                                                @else
                                                    {{ $data['position'] }}ème
                                                @endif
                                            </td>
                                            <td>
                                                @if($data['average'] >= 80)
                                                    <span class="text-success">Excellent</span>
                                                @elseif($data['average'] >= 70)
                                                    <span class="text-info">Très Bien</span>
                                                @elseif($data['average'] >= 60)
                                                    <span class="text-primary">Bien</span>
                                                @elseif($data['average'] >= 50)
                                                    <span class="text-warning">Passable</span>
                                                @else
                                                    <span class="text-danger">Insuffisant</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Statistiques --}}
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3 class="mb-0">{{ count($progress_data) }}</h3>
                                <small>Examens passés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                @php
                                    $avgAll = count($progress_data) > 0 ? collect($progress_data)->avg('average') : 0;
                                @endphp
                                <h3 class="mb-0">{{ number_format($avgAll, 1) }}%</h3>
                                <small>Moyenne générale</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                @php
                                    $bestPos = count($progress_data) > 0 ? collect($progress_data)->min('position') : 0;
                                @endphp
                                <h3 class="mb-0">{{ $bestPos }}{{ $bestPos == 1 ? 'er' : 'ème' }}</h3>
                                <small>Meilleure position</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                @php
                                    $passed = collect($progress_data)->where('average', '>=', 50)->count();
                                @endphp
                                <h3 class="mb-0">{{ $passed }}/{{ count($progress_data) }}</h3>
                                <small>Examens réussis</small>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="alert alert-info">
                    <i class="icon-info22 mr-2"></i>
                    Aucun résultat d'examen trouvé pour cet étudiant.
                </div>
            @endif
        @else
            <div class="alert alert-danger">
                <i class="icon-warning mr-2"></i>
                Étudiant non trouvé.
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
@if(isset($progress_data) && count($progress_data) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('progressChart').getContext('2d');
    var progressData = @json($progress_data);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: progressData.map(item => item.exam_name + ' (' + item.year + ')'),
            datasets: [{
                label: 'Moyenne (%)',
                data: progressData.map(item => item.average),
                borderColor: 'rgba(102, 126, 234, 1)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 6,
                pointBackgroundColor: progressData.map(item => item.average >= 50 ? '#28a745' : '#dc3545'),
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Moyenne: ' + context.parsed.y.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection
