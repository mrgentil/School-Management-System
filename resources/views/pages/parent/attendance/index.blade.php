@extends('layouts.master')
@section('page_title', 'Présences de mes Enfants')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-checkmark-circle mr-2"></i> Présences de mes Enfants - {{ $year }}
        </h5>
    </div>
</div>

@foreach($childrenData as $data)
<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="{{ $data['info']->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                 class="rounded-circle mr-3" width="50" height="50">
            <div>
                <h5 class="mb-0">{{ $data['info']->user->name }}</h5>
                <small class="text-muted">
                    {{ $data['info']->my_class->full_name ?? $data['info']->my_class->name ?? 'N/A' }}
                </small>
            </div>
        </div>
        <a href="{{ route('parent.attendance.show', $data['info']->user_id) }}" class="btn btn-primary">
            <i class="icon-calendar3 mr-1"></i> Détails
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            {{-- Statistiques globales --}}
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <div class="mb-2">
                        <span class="display-4 text-{{ $data['stats']['rate'] >= 80 ? 'success' : ($data['stats']['rate'] >= 60 ? 'warning' : 'danger') }}">
                            {{ $data['stats']['rate'] }}%
                        </span>
                    </div>
                    <small class="text-muted">Taux de présence global</small>
                </div>
                <div class="row text-center">
                    <div class="col-3">
                        <span class="badge badge-success d-block mb-1">{{ $data['stats']['present'] }}</span>
                        <small>Présent</small>
                    </div>
                    <div class="col-3">
                        <span class="badge badge-danger d-block mb-1">{{ $data['stats']['absent'] }}</span>
                        <small>Absent</small>
                    </div>
                    <div class="col-3">
                        <span class="badge badge-warning d-block mb-1">{{ $data['stats']['late'] }}</span>
                        <small>Retard</small>
                    </div>
                    <div class="col-3">
                        <span class="badge badge-info d-block mb-1">{{ $data['stats']['excused'] }}</span>
                        <small>Excusé</small>
                    </div>
                </div>
            </div>

            {{-- Graphique mensuel --}}
            <div class="col-md-8">
                <h6 class="text-muted mb-3"><i class="icon-stats-bars mr-1"></i> Évolution (6 derniers mois)</h6>
                <canvas id="chart-{{ $data['info']->user_id }}" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endforeach

@if(count($childrenData) == 0)
<div class="alert alert-info text-center">
    <i class="icon-info22 mr-2"></i> Aucun enfant inscrit pour cette année scolaire.
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@foreach($childrenData as $data)
    new Chart(document.getElementById('chart-{{ $data['info']->user_id }}'), {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($data['monthly'])->pluck('month')) !!},
            datasets: [{
                label: 'Taux %',
                data: {!! json_encode(collect($data['monthly'])->pluck('rate')) !!},
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, ticks: { callback: v => v + '%' } }
            }
        }
    });
@endforeach
</script>
@endsection
