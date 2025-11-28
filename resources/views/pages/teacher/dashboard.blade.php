@extends('layouts.master')
@section('page_title', 'Tableau de Bord Enseignant')

@section('content')
{{-- En-tête de bienvenue --}}
<div class="card bg-primary text-white mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">👋 Bonjour, {{ $teacher->name }}</h4>
                <p class="mb-0 opacity-75">Année scolaire {{ $year }}</p>
            </div>
            <div class="text-right">
                <div class="d-flex">
                    <div class="mr-4 text-center">
                        <h3 class="mb-0">{{ $stats['total_classes'] }}</h3>
                        <small>Classes</small>
                    </div>
                    <div class="mr-4 text-center">
                        <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        <small>Élèves</small>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-0">{{ $stats['total_subjects'] }}</h3>
                        <small>Matières</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alertes --}}
@if($stats['pending_grades'] > 0)
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <span><i class="icon-warning mr-2"></i> Vous avez <strong>{{ $stats['pending_grades'] }}</strong> devoir(s) en attente de notation.</span>
    <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-warning">Voir les devoirs</a>
</div>
@endif

{{-- Stats rapides --}}
<div class="row">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="icon-calendar3 icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['today_courses'] }}</h3>
                <small>Cours aujourd'hui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="icon-users icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                <small>Élèves total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="icon-star-full2 icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['titular_classes'] }}</h3>
                <small>Classes titulaires</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="icon-warning icon-2x mb-2"></i>
                <h3 class="mb-0">{{ count($strugglingStudents) }}</h3>
                <small>Élèves en difficulté</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Cours du jour --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="icon-calendar3 mr-2"></i> Cours Aujourd'hui
                    <span class="badge badge-light ml-2">{{ now()->format('l d M') }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($todayCourses as $course)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $course->subject->name ?? 'N/A' }}</strong>
                            <br><small class="text-muted">{{ $course->my_class->full_name ?? $course->my_class->name ?? 'N/A' }}</small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-primary">{{ $course->time_start }} - {{ $course->time_end }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 32px;"></i>
                        Pas de cours aujourd'hui
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Mes classes --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-library mr-2"></i> Mes Classes</h6>
            </div>
            <div class="card-body p-0">
                @forelse($teachingClasses as $class)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $class->full_name ?: $class->name }}</strong>
                            @if($class->teacher_id == $teacher->id)
                                <span class="badge badge-warning ml-2">Titulaire</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $class->class_type->name ?? '' }}</small>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">Aucune classe</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Élèves en difficulté --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Élèves en Difficulté</h6>
            </div>
            <div class="card-body p-0">
                <div style="max-height: 300px; overflow-y: auto;">
                    @forelse($strugglingStudents as $item)
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['student']->name ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ $item['class']->name ?? '' }}</small>
                            </div>
                            <span class="badge badge-danger">{{ $item['average'] }}/20</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-success">
                            <i class="icon-checkmark-circle d-block mb-2" style="font-size: 32px;"></i>
                            Tous les élèves vont bien !
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Moyennes par classe --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Moyennes par Classe</h6>
            </div>
            <div class="card-body">
                @if(count($classStats) > 0)
                    <canvas id="classChart" height="120"></canvas>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="icon-stats-bars d-block mb-2" style="font-size: 32px;"></i>
                        Pas encore de données
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Dernières notes --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-history mr-2"></i> Dernières Notes Saisies</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentMarks as $mark)
                    <div class="p-2 border-bottom">
                        <small>
                            <strong>{{ $mark->student->name ?? 'N/A' }}</strong>
                            - {{ $mark->subject->name ?? '' }}
                            <br>
                            <span class="text-muted">{{ $mark->my_class->name ?? '' }}</span>
                        </small>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">Aucune note récente</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Emploi du temps de la semaine --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-calendar mr-2"></i> Emploi du Temps de la Semaine</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Lundi</th>
                        <th>Mardi</th>
                        <th>Mercredi</th>
                        <th>Jeudi</th>
                        <th>Vendredi</th>
                        <th>Samedi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                            <td style="vertical-align: top; min-width: 150px;">
                                @if(isset($weekCourses[$day]))
                                    @foreach($weekCourses[$day] as $course)
                                        <div class="mb-2 p-2 bg-primary text-white rounded" style="font-size: 11px;">
                                            <strong>{{ $course->time_start }}</strong><br>
                                            {{ $course->subject->name ?? 'N/A' }}<br>
                                            <small>{{ $course->my_class->name ?? '' }}</small>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Actions rapides --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-grid6 mr-2"></i> Actions Rapides</h6>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('marks.index') }}" class="btn btn-lg btn-outline-primary w-100">
                    <i class="icon-pencil d-block mb-2" style="font-size: 24px;"></i>
                    <small>Saisir Notes</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-lg btn-outline-success w-100">
                    <i class="icon-checkmark-circle d-block mb-2" style="font-size: 24px;"></i>
                    <small>Présences</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('assignments.index') }}" class="btn btn-lg btn-outline-info w-100">
                    <i class="icon-clipboard3 d-block mb-2" style="font-size: 24px;"></i>
                    <small>Devoirs</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('teacher.messages.index') }}" class="btn btn-lg btn-outline-warning w-100">
                    <i class="icon-envelop d-block mb-2" style="font-size: 24px;"></i>
                    <small>Messages</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('calendar.public') }}" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                    <small>Calendrier</small>
                </a>
            </div>
            <div class="col-md-2 col-4 mb-3">
                <a href="{{ route('my_account') }}" class="btn btn-lg btn-outline-dark w-100">
                    <i class="icon-user d-block mb-2" style="font-size: 24px;"></i>
                    <small>Mon Compte</small>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(count($classStats) > 0)
    const classStats = @json($classStats);
    
    new Chart(document.getElementById('classChart'), {
        type: 'bar',
        data: {
            labels: classStats.map(c => c.class),
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
                y: { 
                    beginAtZero: true, 
                    max: 20,
                    title: { display: true, text: 'Moyenne /20' }
                }
            }
        }
    });
    @endif
</script>
@endsection
