@extends('layouts.master')
@section('page_title', 'Détails ' . $academicSession->name)

@section('content')
<div class="row">
    {{-- Informations générales --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar mr-2"></i> {{ $academicSession->name }}
                    {!! $academicSession->current_badge !!}
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Libellé</strong></td>
                        <td>{{ $academicSession->label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Statut</strong></td>
                        <td>{!! $academicSession->status_badge !!}</td>
                    </tr>
                    <tr>
                        <td><strong>Période</strong></td>
                        <td>
                            @if($academicSession->start_date && $academicSession->end_date)
                                {{ $academicSession->start_date->format('d/m/Y') }} - {{ $academicSession->end_date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Non défini</span>
                            @endif
                        </td>
                    </tr>
                    @if($academicSession->registration_start)
                    <tr>
                        <td><strong>Inscriptions</strong></td>
                        <td>{{ $academicSession->registration_start->format('d/m/Y') }} - {{ $academicSession->registration_end?->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                    @if($academicSession->exam_start)
                    <tr>
                        <td><strong>Examens</strong></td>
                        <td>{{ $academicSession->exam_start->format('d/m/Y') }} - {{ $academicSession->exam_end?->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                </table>

                @if($academicSession->description)
                    <div class="alert alert-light">
                        {{ $academicSession->description }}
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('academic_sessions.edit', $academicSession) }}" class="btn btn-primary btn-sm">
                        <i class="icon-pencil mr-1"></i> Modifier
                    </a>
                    <a href="{{ route('academic_sessions.copy_structure', $academicSession) }}" class="btn btn-warning btn-sm">
                        <i class="icon-copy mr-1"></i> Copier
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistiques clés --}}
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="h3 text-primary mb-0">{{ $academicSession->total_students }}</div>
                        <small class="text-muted">Élèves</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-success mb-0">{{ $academicSession->total_classes }}</div>
                        <small class="text-muted">Classes</small>
                    </div>
                    <div class="col-4">
                        <div class="h3 text-info mb-0">{{ $academicSession->average_score ? number_format($academicSession->average_score, 1) : '-' }}</div>
                        <small class="text-muted">Moyenne</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques et détails --}}
    <div class="col-lg-8">
        {{-- Élèves par classe --}}
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-users mr-2"></i> Répartition par Classe</h6>
            </div>
            <div class="card-body">
                @if($stats['students_by_class']->count() > 0)
                    <canvas id="classChart" height="150"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="icon-info22 d-block mb-2" style="font-size: 32px;"></i>
                        Aucun élève inscrit pour cette année
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- Par genre --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="icon-user mr-2"></i> Par Genre</h6>
                    </div>
                    <div class="card-body">
                        @if(array_sum($stats['students_by_gender']->toArray()) > 0)
                            <canvas id="genderChart" height="200"></canvas>
                        @else
                            <div class="text-center text-muted">Pas de données</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Performance par période --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="icon-chart mr-2"></i> Moyennes par Période</h6>
                    </div>
                    <div class="card-body">
                        @if(array_sum($stats['performance_by_period']) > 0)
                            <canvas id="periodChart" height="200"></canvas>
                        @else
                            <div class="text-center text-muted">Pas de données</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-3">
    <a href="{{ route('academic_sessions.index') }}" class="btn btn-secondary">
        <i class="icon-arrow-left7 mr-1"></i> Retour à la liste
    </a>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const classData = @json($stats['students_by_class']);
    const genderData = @json($stats['students_by_gender']);
    const periodData = @json($stats['performance_by_period']);

    // Graphique par classe
    if (classData.length > 0) {
        new Chart(document.getElementById('classChart'), {
            type: 'bar',
            data: {
                labels: classData.map(c => c.my_class?.name || 'N/A'),
                datasets: [{
                    label: 'Élèves',
                    data: classData.map(c => c.total),
                    backgroundColor: '#2196F3',
                    borderRadius: 5,
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    // Graphique par genre
    if (Object.keys(genderData).length > 0) {
        new Chart(document.getElementById('genderChart'), {
            type: 'doughnut',
            data: {
                labels: ['Garçons', 'Filles'],
                datasets: [{
                    data: [genderData.Male || 0, genderData.Female || 0],
                    backgroundColor: ['#2196F3', '#E91E63'],
                }]
            },
            options: { responsive: true }
        });
    }

    // Graphique par période
    if (Object.values(periodData).some(v => v > 0)) {
        new Chart(document.getElementById('periodChart'), {
            type: 'line',
            data: {
                labels: Object.keys(periodData),
                datasets: [{
                    label: 'Moyenne',
                    data: Object.values(periodData),
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: 20 } } }
        });
    }
</script>
@endsection
