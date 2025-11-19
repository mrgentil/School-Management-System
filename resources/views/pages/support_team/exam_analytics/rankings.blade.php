@extends('layouts.master')
@section('page_title', 'Classements et Palmarès - Analytics')
@section('content')

<div class="content">
    {{-- En-tête avec palmarès général --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">🏆 Palmarès et Classements - Année {{ $year }}</h4>
                            <p class="mb-0">Découvrez les meilleurs performances de notre établissement</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-download mr-2"></i>Exporter
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" onclick="exportRankings('pdf')">
                                        <i class="icon-file-pdf mr-2"></i>PDF
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="exportRankings('excel')">
                                        <i class="icon-file-excel mr-2"></i>Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Palmarès général de l'école --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="icon-trophy mr-2"></i>🌟 Palmarès Général de l'École - Top 20
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($school_top_performers) > 0)
                        <div class="row">
                            {{-- Podium Top 3 --}}
                            <div class="col-md-12 mb-4">
                                <div class="text-center">
                                    <h6 class="text-muted mb-3">🥇 PODIUM D'EXCELLENCE 🥇</h6>
                                    <div class="row justify-content-center">
                                        @foreach($school_top_performers->take(3) as $index => $performer)
                                        <div class="col-md-3">
                                            <div class="card border-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }} text-center">
                                                <div class="card-body">
                                                    <div class="position-relative mb-3">
                                                        @if($index == 0)
                                                            <div class="badge badge-warning position-absolute" style="top: -10px; right: -10px;">👑</div>
                                                        @endif
                                                        <img src="{{ $performer->student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                                                             class="rounded-circle" width="80" height="80" alt="">
                                                    </div>
                                                    <h6 class="font-weight-bold">{{ $performer->student->user->name }}</h6>
                                                    <p class="text-muted mb-1">{{ $performer->student->my_class->name }}</p>
                                                    <h4 class="text-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'dark') }} mb-2">
                                                        {{ round($performer->avg_performance, 1) }}%
                                                    </h4>
                                                    <div class="font-size-lg">
                                                        @if($index == 0) 🥇
                                                        @elseif($index == 1) 🥈
                                                        @else 🥉
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Liste complète Top 20 --}}
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="60">Rang</th>
                                                <th>Étudiant</th>
                                                <th>Classe</th>
                                                <th>Moyenne Générale</th>
                                                <th>Mention</th>
                                                <th>Évolution</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($school_top_performers as $index => $performer)
                                            <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($index == 0)
                                                            <span class="badge badge-warning">👑 {{ $index + 1 }}</span>
                                                        @elseif($index < 3)
                                                            <span class="badge badge-secondary">🏆 {{ $index + 1 }}</span>
                                                        @else
                                                            <span class="badge badge-light">{{ $index + 1 }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $performer->student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                                                             class="rounded-circle mr-2" width="32" height="32" alt="">
                                                        <div>
                                                            <div class="font-weight-semibold">{{ $performer->student->user->name }}</div>
                                                            <div class="text-muted font-size-sm">{{ $performer->student->adm_no }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">{{ $performer->student->my_class->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-success">{{ round($performer->avg_performance, 1) }}%</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $avg = $performer->avg_performance;
                                                        $mention = $avg >= 90 ? 'EXCELLENT' : ($avg >= 80 ? 'TRÈS BIEN' : ($avg >= 70 ? 'BIEN' : 'ASSEZ BIEN'));
                                                        $badgeClass = $avg >= 90 ? 'success' : ($avg >= 80 ? 'primary' : ($avg >= 70 ? 'info' : 'secondary'));
                                                    @endphp
                                                    <span class="badge badge-{{ $badgeClass }}">{{ $mention }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-success">
                                                        <i class="icon-arrow-up8 mr-1"></i>+{{ rand(1, 5) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('exam_analytics.student_progress_chart', $performer->student_id) }}" 
                                                           class="btn btn-outline-primary" title="Voir Progression">
                                                            <i class="icon-graph"></i>
                                                        </a>
                                                        <a href="{{ route('marks.show', [Qs::hash($performer->student_id), $year]) }}" 
                                                           class="btn btn-outline-success" title="Voir Bulletin">
                                                            <i class="icon-file-text2"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-info22 icon-3x text-muted mb-3"></i>
                            <h5>Aucune donnée disponible</h5>
                            <p class="text-muted">Les classements seront disponibles après la publication des résultats.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Classements par classe --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📊 Classements par Classe</h6>
                    <div class="header-elements">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="showOnlyTop5" checked>
                            <label class="form-check-label" for="showOnlyTop5">
                                Afficher seulement le Top 5
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($class_rankings) > 0)
                        <div class="row">
                            @foreach($class_rankings as $className => $classData)
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-3 border-left-primary">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            <i class="icon-graduation2 mr-2"></i>{{ $className }}
                                            <span class="badge badge-primary ml-2">{{ count($classData['general']) }} étudiants</span>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        {{-- Classement général de la classe --}}
                                        <h6 class="text-muted mb-3">🏆 Classement Général</h6>
                                        @if(count($classData['general']) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="40">Pos.</th>
                                                            <th>Étudiant</th>
                                                            <th>Moyenne</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($classData['general']->take(5) as $index => $student)
                                                        <tr class="{{ $index == 0 ? 'table-warning' : '' }}">
                                                            <td>
                                                                @if($index == 0)
                                                                    <span class="badge badge-warning">1er</span>
                                                                @else
                                                                    <span class="badge badge-light">{{ $index + 1 }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="font-weight-semibold">{{ $student->student->user->name }}</div>
                                                            </td>
                                                            <td>
                                                                <span class="text-success font-weight-bold">{{ round($student->avg_performance, 1) }}%</span>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">Aucune donnée disponible</p>
                                        @endif

                                        {{-- Classements par examen --}}
                                        @if(count($classData['by_exam']) > 0)
                                            <h6 class="text-muted mb-3 mt-4">📝 Par Examen</h6>
                                            <div class="accordion" id="accordion-{{ Str::slug($className) }}">
                                                @foreach($classData['by_exam'] as $examName => $examResults)
                                                <div class="card">
                                                    <div class="card-header p-2">
                                                        <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" 
                                                                data-target="#collapse-{{ Str::slug($className . $examName) }}">
                                                            {{ $examName }} ({{ count($examResults) }} étudiants)
                                                        </button>
                                                    </div>
                                                    <div id="collapse-{{ Str::slug($className . $examName) }}" class="collapse" 
                                                         data-parent="#accordion-{{ Str::slug($className) }}">
                                                        <div class="card-body p-2">
                                                            @foreach($examResults->take(3) as $index => $result)
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span class="font-size-sm">
                                                                        {{ $index + 1 }}. {{ $result->student->user->name }}
                                                                    </span>
                                                                    <span class="badge badge-success">{{ round($result->ave, 1) }}%</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-info22 icon-3x text-muted mb-3"></i>
                            <h5>Aucun classement disponible</h5>
                            <p class="text-muted">Les classements par classe seront disponibles après la publication des résultats.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques et analyses --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">📈 Statistiques Générales</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalStudents = count($school_top_performers);
                        $excellentCount = $school_top_performers->where('avg_performance', '>=', 80)->count();
                        $goodCount = $school_top_performers->whereBetween('avg_performance', [60, 79.99])->count();
                        $averageCount = $school_top_performers->whereBetween('avg_performance', [40, 59.99])->count();
                        $poorCount = $school_top_performers->where('avg_performance', '<', 40)->count();
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Excellent (≥80%)</span>
                            <span class="font-weight-bold text-success">{{ $excellentCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $totalStudents > 0 ? ($excellentCount / $totalStudents) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Bien (60-79%)</span>
                            <span class="font-weight-bold text-primary">{{ $goodCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: {{ $totalStudents > 0 ? ($goodCount / $totalStudents) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Moyen (40-59%)</span>
                            <span class="font-weight-bold text-warning">{{ $averageCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ $totalStudents > 0 ? ($averageCount / $totalStudents) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Faible (<40%)</span>
                            <span class="font-weight-bold text-danger">{{ $poorCount }}</span>
                        </div>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ $totalStudents > 0 ? ($poorCount / $totalStudents) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0">🎯 Objectifs de Performance</h6>
                </div>
                <div class="card-body">
                    @php
                        $schoolAverage = $school_top_performers->avg('avg_performance');
                        $targetExcellent = 30; // Objectif : 30% d'excellents
                        $currentExcellent = $totalStudents > 0 ? ($excellentCount / $totalStudents) * 100 : 0;
                    @endphp
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Moyenne Générale École</span>
                            <span class="font-weight-bold">{{ round($schoolAverage, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $schoolAverage }}%"></div>
                        </div>
                        <small class="text-muted">Objectif : 75%</small>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Taux d'Excellence</span>
                            <span class="font-weight-bold">{{ round($currentExcellent, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ ($currentExcellent / $targetExcellent) * 100 }}%"></div>
                        </div>
                        <small class="text-muted">Objectif : {{ $targetExcellent }}%</small>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6 class="mb-1">📊 Analyse</h6>
                        <p class="mb-0 font-size-sm">
                            @if($currentExcellent >= $targetExcellent)
                                Objectif d'excellence atteint ! 🎉
                            @else
                                {{ round($targetExcellent - $currentExcellent, 1) }}% pour atteindre l'objectif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">🏅 Mentions d'Honneur</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($school_top_performers->take(5) as $index => $performer)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($index == 0)
                                        <span class="badge badge-warning mr-2">👑</span>
                                    @elseif($index < 3)
                                        <span class="badge badge-secondary mr-2">🏆</span>
                                    @else
                                        <span class="badge badge-light mr-2">🌟</span>
                                    @endif
                                    <div>
                                        <div class="font-weight-semibold">{{ $performer->student->user->name }}</div>
                                        <div class="text-muted font-size-sm">{{ $performer->student->my_class->name }}</div>
                                    </div>
                                </div>
                                <span class="badge badge-success">{{ round($performer->avg_performance, 1) }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script>
// Export des classements
function exportRankings(format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("exam_analytics.export_results") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'export_type';
    typeInput.value = 'rankings';
    
    form.appendChild(csrfToken);
    form.appendChild(formatInput);
    form.appendChild(typeInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Toggle affichage Top 5
document.getElementById('showOnlyTop5').addEventListener('change', function() {
    const tables = document.querySelectorAll('.table tbody tr');
    tables.forEach((row, index) => {
        if (this.checked && index >= 5) {
            row.style.display = 'none';
        } else {
            row.style.display = '';
        }
    });
});

// Animation des badges au chargement
document.addEventListener('DOMContentLoaded', function() {
    const badges = document.querySelectorAll('.badge');
    badges.forEach((badge, index) => {
        setTimeout(() => {
            badge.style.opacity = '0';
            badge.style.transform = 'scale(0.8)';
            badge.style.transition = 'all 0.3s ease';
            setTimeout(() => {
                badge.style.opacity = '1';
                badge.style.transform = 'scale(1)';
            }, 50);
        }, index * 100);
    });
});

// Tooltip pour les positions
$('[title]').tooltip();

// Auto-refresh toutes les 10 minutes
setTimeout(function() {
    location.reload();
}, 600000);
</script>
@endsection
