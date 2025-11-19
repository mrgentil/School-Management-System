@extends('layouts.master')
@section('page_title', 'Analytics Avancés - Examens')
@section('content')

<div class="content">
    {{-- En-tête principal --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="mb-1">🚀 Analytics Avancés - Système d'Examens</h3>
                            <p class="mb-0">Tableau de bord complet pour l'analyse des performances scolaires</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('exam_analytics.dashboard') }}" class="btn btn-light btn-lg">
                                <i class="icon-stats-dots mr-2"></i>Dashboard Principal
                            </a>
                        </div>
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
                    <h6 class="card-title">⚡ Accès Rapide aux Fonctionnalités</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.dashboard') }}" class="btn btn-outline-primary btn-block mb-2">
                                <i class="icon-stats-dots mr-2"></i>Dashboard Interactif
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.struggling_students') }}" class="btn btn-outline-danger btn-block mb-2">
                                <i class="icon-user-minus mr-2"></i>Étudiants en Difficulté
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.rankings') }}" class="btn btn-outline-success btn-block mb-2">
                                <i class="icon-trophy mr-2"></i>Classements & Palmarès
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.historical_comparison') }}" class="btn btn-outline-info btn-block mb-2">
                                <i class="icon-stats-bars mr-2"></i>Comparaisons Historiques
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.subject_teacher_reports') }}" class="btn btn-outline-warning btn-block">
                                <i class="icon-graph mr-2"></i>Rapports par Matière
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#importModal">
                                <i class="icon-upload mr-2"></i>Import Excel
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#exportModal">
                                <i class="icon-download mr-2"></i>Export Résultats
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#notifyModal">
                                <i class="icon-mail5 mr-2"></i>Notifier Parents
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Analyses par Examen --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-file-text2 mr-2"></i>📊 Analyses par Examen
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($exams) > 0)
                        <div class="row">
                            @foreach($exams as $exam)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-left-3 border-left-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="font-weight-bold mb-0">{{ $exam->name }}</h6>
                                                <span class="badge badge-primary">S{{ $exam->semester }}</span>
                                            </div>
                                            <p class="text-muted mb-2">
                                                <i class="icon-calendar mr-1"></i>{{ $exam->year }}
                                                <i class="icon-clock ml-2 mr-1"></i>{{ $exam->created_at->diffForHumans() }}
                                            </p>
                                            
                                            {{-- Statistiques rapides --}}
                                            @php
                                                $examStats = \App\Models\ExamRecord::where('exam_id', $exam->id)->get();
                                                $avgScore = $examStats->avg('ave');
                                                $studentsCount = $examStats->count();
                                            @endphp
                                            
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <div class="font-size-sm text-muted">Étudiants</div>
                                                    <div class="font-weight-bold">{{ $studentsCount }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="font-size-sm text-muted">Moyenne</div>
                                                    <div class="font-weight-bold text-{{ $avgScore >= 70 ? 'success' : ($avgScore >= 50 ? 'warning' : 'danger') }}">
                                                        {{ $avgScore ? round($avgScore, 1) : 'N/A' }}%
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="btn-group btn-group-sm d-flex">
                                                <a href="{{ route('exam_analytics.overview', $exam->id) }}" class="btn btn-primary">
                                                    <i class="icon-stats-dots mr-1"></i>Analytics
                                                </a>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('marks.tabulation', [$exam->id]) }}">
                                                            <i class="icon-table2 mr-2"></i>Tabulation
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('marks.batch_fix') }}">
                                                            <i class="icon-wrench mr-2"></i>Correction Lot
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#" onclick="exportExamResults({{ $exam->id }})">
                                                            <i class="icon-download mr-2"></i>Exporter
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-file-text2 icon-3x text-muted mb-3"></i>
                            <h5>Aucun examen disponible</h5>
                            <p class="text-muted">Créez un examen pour commencer les analyses.</p>
                            <a href="{{ route('exams.create') }}" class="btn btn-primary">
                                <i class="icon-plus2 mr-2"></i>Créer un Examen
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Analyses par Classe --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-graduation2 mr-2"></i>🏫 Analyses par Classe
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($my_classes) > 0)
                        <div class="row">
                            @foreach($my_classes as $class)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-left-3 border-left-info">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="font-weight-bold mb-0">{{ $class->name }}</h6>
                                                @php
                                                    $classStudents = \App\Models\StudentRecord::where('my_class_id', $class->id)
                                                        ->where('session', Qs::getSetting('current_session'))->count();
                                                @endphp
                                                <span class="badge badge-info">{{ $classStudents }} élèves</span>
                                            </div>
                                            
                                            <div class="form-group mb-3">
                                                <label class="font-size-sm text-muted">Sélectionner un examen :</label>
                                                <select class="form-control form-control-sm" onchange="if(this.value) window.location.href=this.value">
                                                    <option value="">Choisir un examen...</option>
                                                    @foreach($exams as $exam)
                                                        <option value="{{ route('exam_analytics.class_analysis', [$exam->id, $class->id]) }}">
                                                            {{ $exam->name }} (S{{ $exam->semester }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="btn-group btn-group-sm d-flex">
                                                <button type="button" class="btn btn-outline-info" onclick="showClassStats({{ $class->id }})">
                                                    <i class="icon-stats-bars mr-1"></i>Statistiques
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" onclick="showClassProgress({{ $class->id }})">
                                                    <i class="icon-graph mr-1"></i>Progression
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-graduation2 icon-3x text-muted mb-3"></i>
                            <h5>Aucune classe disponible</h5>
                            <p class="text-muted">Créez des classes pour commencer les analyses.</p>
                        </div>
                    @endif
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
<script>
// Fonctions pour les actions rapides
function exportExamResults(examId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("exam_analytics.export_results") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const examInput = document.createElement('input');
    examInput.type = 'hidden';
    examInput.name = 'exam_id';
    examInput.value = examId;
    
    form.appendChild(csrfToken);
    form.appendChild(examInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function showClassStats(classId) {
    alert('Statistiques de classe - Fonctionnalité en développement');
}

function showClassProgress(classId) {
    alert('Progression de classe - Fonctionnalité en développement');
}

// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});

// Tooltips
$('[title]').tooltip();
</script>
@endsection
