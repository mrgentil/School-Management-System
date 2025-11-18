@extends('layouts.master')
@section('page_title', 'Analyse - ' . $exam->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline bg-info text-white">
            <h6 class="card-title">
                <i class="icon-stats-dots mr-2"></i> Analyse Détaillée - {{ $exam->name }} ({{ $exam->year }})
            </h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            {{-- Statistiques Globales --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="icon-users4 icon-3x mb-2"></i>
                            <h3 class="mb-0">{{ $total_students }}</h3>
                            <p class="mb-0">Étudiants</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="icon-books icon-3x mb-2"></i>
                            <h3 class="mb-0">{{ $total_subjects }}</h3>
                            <p class="mb-0">Matières</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="icon-calculator icon-3x mb-2"></i>
                            <h3 class="mb-0">{{ $avg_class_average }}%</h3>
                            <p class="mb-0">Moyenne Générale</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="icon-trophy icon-3x mb-2"></i>
                            <h3 class="mb-0">{{ $top_students->first()->ave ?? 'N/A' }}%</h3>
                            <p class="mb-0">Meilleure Moyenne</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Distribution des Grades --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title">Distribution des Grades</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($grade_distribution as $grade => $count)
                        <div class="col-md">
                            <div class="text-center p-3 border rounded">
                                <h2 class="mb-0 font-weight-bold text-{{ $grade == 'A' ? 'success' : ($grade == 'F' ? 'danger' : 'primary') }}">
                                    {{ $count }}
                                </h2>
                                <p class="mb-0">Grade {{ $grade }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3">
                        <canvas id="gradeChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top 10 Étudiants --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success-400">
                            <h6 class="card-title">
                                <i class="icon-trophy mr-2"></i> Top 10 Étudiants
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>Rang</th>
                                        <th>Nom</th>
                                        <th>Classe</th>
                                        <th>Moyenne</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($top_students as $index => $record)
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
                                            <td>{{ $record->user->name ?? 'N/A' }}</td>
                                            <td>{{ $record->my_class->name ?? 'N/A' }}</td>
                                            <td><strong>{{ $record->ave }}%</strong></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistiques par Classe --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary-400">
                            <h6 class="card-title">
                                <i class="icon-library2 mr-2"></i> Performance par Classe
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>Classe</th>
                                        <th>Étudiants</th>
                                        <th>Moyenne</th>
                                        <th>Max/Min</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($class_stats as $stat)
                                        <tr>
                                            <td><strong>{{ $stat['class_name'] }}</strong></td>
                                            <td>{{ $stat['students'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $stat['average'] >= 70 ? 'success' : ($stat['average'] >= 50 ? 'warning' : 'danger') }}">
                                                    {{ $stat['average'] }}%
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-success">{{ $stat['highest'] }}</small> / 
                                                <small class="text-danger">{{ $stat['lowest'] }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques par Matière --}}
            <div class="card mt-4">
                <div class="card-header bg-warning-400">
                    <h6 class="card-title">
                        <i class="icon-book mr-2"></i> Performance par Matière
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered datatable-basic">
                            <thead>
                            <tr class="bg-light">
                                <th>Matière</th>
                                <th>Étudiants</th>
                                <th>Moyenne</th>
                                <th>Note Max</th>
                                <th>Note Min</th>
                                <th>Graphique</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subject_stats as $stat)
                                <tr>
                                    <td><strong>{{ $stat['subject_name'] }}</strong></td>
                                    <td>{{ $stat['students'] }}</td>
                                    <td>{{ $stat['average'] }}%</td>
                                    <td><span class="text-success">{{ $stat['highest'] }}</span></td>
                                    <td><span class="text-danger">{{ $stat['lowest'] }}</span></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $stat['average'] >= 70 ? 'success' : ($stat['average'] >= 50 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $stat['average'] }}%">
                                                {{ $stat['average'] }}%
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
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Grade Distribution Chart
    const ctx = document.getElementById('gradeChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['A', 'B', 'C', 'D', 'F'],
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: [
                    {{ $grade_distribution['A'] }},
                    {{ $grade_distribution['B'] }},
                    {{ $grade_distribution['C'] }},
                    {{ $grade_distribution['D'] }},
                    {{ $grade_distribution['F'] }}
                ],
                backgroundColor: [
                    'rgba(34, 139, 34, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(34, 139, 34, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
