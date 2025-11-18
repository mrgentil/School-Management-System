@extends('layouts.master')
@section('page_title', 'Mes Examens')
@section('content')

<div class="row">
    <div class="col-md-12">
        {{-- Header principal --}}
        <div class="card bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><i class="icon-file-text2 mr-2"></i>Mes Examens</h4>
                        <p class="mb-0">Gérez vos examens, consultez vos résultats et suivez votre progression</p>
                    </div>
                    <div>
                        <i class="icon-graduation icon-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu rapide --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('student.exam_schedule') }}" class="card bg-info-400 text-white hoverable">
                    <div class="card-body text-center">
                        <i class="icon-calendar icon-3x mb-3"></i>
                        <h5 class="mb-1">Calendrier d'Examens</h5>
                        <p class="mb-0">Voir les dates et horaires</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.progress.index') }}" class="card bg-success-400 text-white hoverable">
                    <div class="card-body text-center">
                        <i class="icon-graph icon-3x mb-3"></i>
                        <h5 class="mb-1">Ma Progression</h5>
                        <p class="mb-0">Suivi de performances</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.grades.index') }}" class="card bg-warning-400 text-white hoverable">
                    <div class="card-body text-center">
                        <i class="icon-certificate icon-3x mb-3"></i>
                        <h5 class="mb-1">Mes Notes</h5>
                        <p class="mb-0">Notes par période</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.grades.bulletin') }}" class="card bg-danger-400 text-white hoverable">
                    <div class="card-body text-center">
                        <i class="icon-file-text2 icon-3x mb-3"></i>
                        <h5 class="mb-1">Mon Bulletin</h5>
                        <p class="mb-0">Bulletin complet</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Vue d'ensemble --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="card-title"><i class="icon-eye mr-2"></i>Vue d'Ensemble</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-highlight mb-3">
                            <li class="nav-item"><a href="#exams-upcoming" class="nav-link active" data-toggle="tab">Examens à Venir</a></li>
                            <li class="nav-item"><a href="#exams-results" class="nav-link" data-toggle="tab">Mes Résultats</a></li>
                            <li class="nav-item"><a href="#exams-stats" class="nav-link" data-toggle="tab">Statistiques</a></li>
                        </ul>

                        <div class="tab-content">
                            {{-- Onglet Examens à Venir --}}
                            <div class="tab-pane fade show active" id="exams-upcoming">
                                @if(isset($upcoming_schedules) && $upcoming_schedules->count() > 0)
                                    <div class="row">
                                        @foreach($upcoming_schedules as $schedule)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-left-3 border-left-warning">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mb-1 font-weight-bold">{{ $schedule->subject->name }}</h6>
                                                            <p class="text-muted mb-1">{{ $schedule->exam->name }}</p>
                                                        </div>
                                                        <span class="badge badge-warning">{{ \Carbon\Carbon::parse($schedule->exam_date)->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="mb-1">
                                                            <i class="icon-calendar3 mr-1 text-primary"></i>
                                                            <strong>Date:</strong> {{ $schedule->exam_date->format('d/m/Y') }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <i class="icon-clock mr-1 text-success"></i>
                                                            <strong>Heure:</strong> {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                                        </p>
                                                        @if($schedule->room)
                                                        <p class="mb-0">
                                                            <i class="icon-location4 mr-1 text-danger"></i>
                                                            <strong>Salle:</strong> {{ $schedule->room }}
                                                        </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('student.exam_schedule') }}" class="btn btn-primary">
                                            <i class="icon-calendar mr-2"></i>Voir le Calendrier Complet
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="icon-calendar-empty icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun examen planifié dans les 30 prochains jours</p>
                                        <a href="{{ route('student.exam_schedule') }}" class="btn btn-light">Voir le Calendrier</a>
                                    </div>
                                @endif
                            </div>

                            {{-- Onglet Résultats --}}
                            <div class="tab-pane fade" id="exams-results">
                                @if(isset($exam_results) && $exam_results->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-light">
                                            <tr>
                                                <th>Examen</th>
                                                <th>Semestre</th>
                                                <th>Année</th>
                                                <th>Ma Moyenne</th>
                                                <th>Position</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($exam_results as $result)
                                                <tr>
                                                    <td><strong>{{ $result->exam->name }}</strong></td>
                                                    <td>S{{ $result->exam->semester }}</td>
                                                    <td>{{ $result->exam->year }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $result->ave >= 60 ? 'success' : ($result->ave >= 50 ? 'warning' : 'danger') }}">
                                                            {{ $result->ave }}%
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $result->pos }}{{ $result->pos == 1 ? 'er' : 'ème' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($result->exam->results_published)
                                                            <span class="badge badge-success">Publié</span>
                                                        @else
                                                            <span class="badge badge-secondary">En attente</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($result->exam->results_published)
                                                            <a href="{{ route('marks.show', [Qs::hash($result->student_id), $result->year]) }}" class="btn btn-sm btn-primary">
                                                                <i class="icon-eye"></i> Détails
                                                            </a>
                                                        @else
                                                            <button class="btn btn-sm btn-light" disabled>
                                                                <i class="icon-lock"></i> Non publié
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="icon-file-empty icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun résultat d'examen disponible</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Onglet Statistiques --}}
                            <div class="tab-pane fade" id="exams-stats">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h3 class="mb-0">{{ $stats['total_exams'] ?? 0 }}</h3>
                                                <p class="mb-0">Examens Passés</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h3 class="mb-0">{{ $stats['avg_general'] ?? 'N/A' }}</h3>
                                                <p class="mb-0">Moyenne Générale</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h3 class="mb-0">{{ $stats['best_position'] ?? 'N/A' }}</h3>
                                                <p class="mb-0">Meilleure Position</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h3 class="mb-0">{{ $stats['upcoming_exams'] ?? 0 }}</h3>
                                                <p class="mb-0">Examens à Venir</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <a href="{{ route('student.progress.index') }}" class="btn btn-primary btn-lg">
                                        <i class="icon-graph mr-2"></i>Voir Ma Progression Complète
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hoverable {
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
}
.hoverable:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}
</style>

@endsection
