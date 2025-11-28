@extends('layouts.master')
@section('page_title', 'Progression - ' . $student->user->name)

@section('content')
{{-- En-tête élève --}}
<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                     class="rounded-circle" width="80" height="80">
            </div>
            <div class="col">
                <h4 class="mb-1">{{ $student->user->name }}</h4>
                <p class="text-muted mb-0">
                    <span class="badge badge-primary">{{ $student->my_class->full_name ?? $student->my_class->name ?? 'N/A' }}</span>
                    <span class="badge badge-secondary">{{ $student->section->name ?? '' }}</span>
                    <span class="ml-2">Année: {{ $year }}</span>
                </p>
            </div>
            <div class="col-auto">
                {{-- Tendance --}}
                <div class="text-center">
                    <i class="{{ $trend['icon'] ?? 'icon-minus3' }} text-{{ $trend['color'] ?? 'info' }}" style="font-size: 32px;"></i>
                    <br><span class="badge badge-{{ $trend['color'] ?? 'info' }}">{{ $trend['message'] ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('parent.progress.index') }}" class="btn btn-secondary">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistiques clés --}}
<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['general_average'] }}/20</h2>
                <small>Moyenne Générale</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['total_subjects'] }}</h2>
                <small>Matières évaluées</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-{{ count($stats['strengths']) > count($stats['weaknesses']) ? 'success' : 'warning' }} text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ count($stats['strengths']) }} / {{ count($stats['weaknesses']) }}</h2>
                <small>Forces / Faiblesses</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Graphique d'évolution --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="icon-stats-growth mr-2"></i> Évolution par Période
                </h6>
            </div>
            <div class="card-body">
                <canvas id="progressChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Points forts/faibles --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-thumbs-up2 mr-2"></i> Points Forts</h6>
            </div>
            <div class="card-body p-0">
                @forelse($stats['strengths'] as $subject)
                    <div class="p-2 border-bottom">
                        <i class="icon-checkmark text-success mr-1"></i> {{ $subject }}
                    </div>
                @empty
                    <div class="p-3 text-muted text-center">Aucun (≥14/20)</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> À Améliorer</h6>
            </div>
            <div class="card-body p-0">
                @forelse($stats['weaknesses'] as $subject)
                    <div class="p-2 border-bottom">
                        <i class="icon-cross text-danger mr-1"></i> {{ $subject }}
                    </div>
                @empty
                    <div class="p-3 text-muted text-center">Aucun (<10/20)</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Détail par matière --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0">
            <i class="icon-book mr-2"></i> Détail par Matière
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Matière</th>
                        <th class="text-center">P1</th>
                        <th class="text-center">P2</th>
                        <th class="text-center">P3</th>
                        <th class="text-center">P4</th>
                        <th class="text-center">Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjectData as $subject)
                        <tr>
                            <td><strong>{{ $subject['subject'] }}</strong></td>
                            <td class="text-center">
                                @if($subject['p1'] !== null)
                                    <span class="badge badge-{{ $subject['p1'] >= 10 ? 'success' : 'danger' }}">{{ $subject['p1'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($subject['p2'] !== null)
                                    <span class="badge badge-{{ $subject['p2'] >= 10 ? 'success' : 'danger' }}">{{ $subject['p2'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($subject['p3'] !== null)
                                    <span class="badge badge-{{ $subject['p3'] >= 10 ? 'success' : 'danger' }}">{{ $subject['p3'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($subject['p4'] !== null)
                                    <span class="badge badge-{{ $subject['p4'] >= 10 ? 'success' : 'danger' }}">{{ $subject['p4'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <strong class="text-{{ $subject['average'] >= 10 ? 'success' : 'danger' }}">
                                    {{ $subject['average'] }}/20
                                </strong>
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
    const progressData = @json($progressData);

    new Chart(document.getElementById('progressChart'), {
        type: 'line',
        data: {
            labels: progressData.periods,
            datasets: [{
                label: 'Moyenne',
                data: progressData.averages,
                borderColor: '#2196F3',
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 6,
                pointBackgroundColor: '#2196F3',
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
</script>
@endsection
