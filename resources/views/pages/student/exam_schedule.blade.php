@extends('layouts.master')
@section('page_title', 'Mon Calendrier d\'Examens')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="card-title text-white font-weight-bold">
                        <i class="icon-calendar3 mr-2"></i> 📅 Mes Horaires d'Examens
                    </h5>
                    <div class="header-elements">
                        <span class="badge badge-light badge-pill">Session {{ Qs::getSetting('current_session') }}</span>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Informations de l'étudiant --}}
                    <div class="alert" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                        <div class="d-flex align-items-center text-white">
                            <i class="icon-user icon-2x mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-white">{{ Auth::user()->name }}</h6>
                                <small class="text-white font-weight-bold">{{ $sr->my_class ? ($sr->my_class->full_name ?: $sr->my_class->name) : 'N/A' }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Upcoming Exams - Section améliorée --}}
                    @if($upcoming->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none;">
                            <h6 class="card-title text-white mb-0">
                                <i class="icon-alarm-add mr-2"></i> ⏰ Examens à Venir (30 prochains jours)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="row no-gutters">
                                @foreach($upcoming as $schedule)
                                <div class="col-md-6">
                                    <div class="card m-3 shadow-sm" style="border-left: 4px solid {{ $schedule->exam_type == 'session' ? '#f5576c' : '#4fc3f7' }};">
                                        <div class="card-body">
                                            {{-- Badge type d'examen --}}
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                @if($schedule->exam_type == 'session')
                                                    <span class="badge badge-danger badge-pill">🔄 SESSION</span>
                                                @else
                                                    <span class="badge badge-info badge-pill">🏠 HORS SESSION</span>
                                                @endif
                                                <span class="badge badge-warning">{{ \Carbon\Carbon::parse($schedule->exam_date)->diffForHumans() }}</span>
                                            </div>

                                            <h6 class="mb-1 font-weight-bold">{{ $schedule->subject->name }}</h6>
                                            <p class="text-muted mb-3" style="font-size: 0.85rem;">{{ $schedule->exam->name }}</p>

                                            <div class="mb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="icon-calendar3 mr-2" style="color: #667eea;"></i>
                                                    <span><strong>Date:</strong> {{ $schedule->exam_date->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="icon-clock mr-2" style="color: #f5576c;"></i>
                                                    <span><strong>Heure:</strong> {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                                                </div>

                                                {{-- Affichage de la salle selon le type --}}
                                                @if($schedule->exam_type == 'session')
                                                    @php
                                                        // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    @endphp
                                                    @if($myPlacement)
                                                        <div class="alert alert-success mb-2 py-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="icon-home9 mr-2"></i>
                                                                <div>
                                                                    <strong>Salle d'examen:</strong> {{ $myPlacement->room->name ?? 'N/A' }}<br>
                                                                    <strong>Place N°:</strong> <span class="badge badge-success">#{{ $myPlacement->seat_number }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-warning mb-2 py-2">
                                                            <i class="icon-info22 mr-2"></i>
                                                            <small>Placement non encore disponible</small>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="icon-home mr-2" style="color: #4fc3f7;"></i>
                                                        <span><strong>Salle:</strong> Votre salle habituelle</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($schedule->instructions)
                                            <div class="alert alert-light mb-0 py-2">
                                                <small><strong>Instructions:</strong> {{ $schedule->instructions }}</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tous les Examens - Séparés par type avec design moderne --}}
                    @php
                        $sessionSchedules = $schedules->where('exam_type', 'session');
                        $horsSessionSchedules = $schedules->where('exam_type', 'hors_session');
                    @endphp

                    {{-- Onglets --}}
                    <ul class="nav nav-tabs nav-tabs-highlight mb-0">
                        <li class="nav-item">
                            <a href="#hors-session-tab" class="nav-link active" data-toggle="tab">
                                <i class="icon-home mr-2"></i> 🏠 Examens HORS SESSION
                                <span class="badge badge-info badge-pill ml-2">{{ $horsSessionSchedules->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#session-tab" class="nav-link" data-toggle="tab">
                                <i class="icon-shuffle mr-2"></i> 🔄 Examens SESSION
                                <span class="badge badge-danger badge-pill ml-2">{{ $sessionSchedules->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content card shadow-sm">
                        {{-- HORS SESSION --}}
                        <div class="tab-pane fade show active" id="hors-session-tab">
                            <div class="card-body">
                                @if($horsSessionSchedules->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-info text-white">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Durée</th>
                                                <th>Salle</th>
                                                <th>Statut</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($horsSessionSchedules->sortBy('exam_date') as $schedule)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $schedule->subject->name }}</strong><br>
                                                        <small class="text-muted">{{ $schedule->exam->name }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-flat border-primary text-primary-600">
                                                            {{ $schedule->exam_date->format('d/m/Y') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-clock mr-1"></i>
                                                        {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                                            $duration = $start->diffInMinutes($end);
                                                        @endphp
                                                        <span class="badge badge-secondary">{{ $duration }} min</span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-home mr-1 text-info"></i>
                                                        Votre salle habituelle
                                                    </td>
                                                    <td>
                                                        @if($schedule->status == 'scheduled')
                                                            <span class="badge badge-info">Planifié</span>
                                                        @elseif($schedule->status == 'ongoing')
                                                            <span class="badge badge-warning">En cours</span>
                                                        @elseif($schedule->status == 'completed')
                                                            <span class="badge badge-success">Terminé</span>
                                                        @else
                                                            <span class="badge badge-danger">Annulé</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="icon-home icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun examen HORS SESSION planifié</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- SESSION --}}
                        <div class="tab-pane fade" id="session-tab">
                            <div class="card-body">
                                @if($sessionSchedules->count() > 0)
                                    <div class="alert alert-info border-0 mb-3">
                                        <i class="icon-info22 mr-2"></i>
                                        <strong>Examens SESSION :</strong> Vous serez placé automatiquement dans une salle d'examen selon vos performances.
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-danger text-white">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Date</th>
                                                <th>Heure</th>
                                                <th>Durée</th>
                                                <th>Salle d'Examen</th>
                                                <th>Place N°</th>
                                                <th>Statut</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($sessionSchedules->sortBy('exam_date') as $schedule)
                                                @php
                                                    // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                    $myPlacement = null;
                                                    if ($schedule->exam_id) {
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <strong>{{ $schedule->subject->name }}</strong><br>
                                                        <small class="text-muted">{{ $schedule->exam ? $schedule->exam->name : 'N/A' }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-flat border-danger text-danger-600">
                                                            {{ $schedule->exam_date->format('d/m/Y') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <i class="icon-clock mr-1"></i>
                                                        {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                                            $duration = $start->diffInMinutes($end);
                                                        @endphp
                                                        <span class="badge badge-secondary">{{ $duration }} min</span>
                                                    </td>
                                                    @php
                                                        // LOGIQUE CORRECTE: Récupérer le placement au niveau de l'EXAMEN
                                                        $myPlacement = \App\Models\ExamStudentPlacement::where('exam_id', $schedule->exam_id)
                                                            ->where('student_id', $student_id)
                                                            ->with('room')
                                                            ->first();
                                                    @endphp
                                                    <td>
                                                        @if($myPlacement)
                                                            <span class="badge badge-success">
                                                                <i class="icon-home9 mr-1"></i>
                                                                {{ $myPlacement->room->name ?? 'N/A' }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted"><i class="icon-hour-glass2 mr-1"></i> En attente</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($myPlacement)
                                                            <span class="badge badge-pill badge-primary" style="font-size: 1rem;">
                                                                #{{ $myPlacement->seat_number }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($schedule->status == 'scheduled')
                                                            <span class="badge badge-info">Planifié</span>
                                                        @elseif($schedule->status == 'ongoing')
                                                            <span class="badge badge-warning">En cours</span>
                                                        @elseif($schedule->status == 'completed')
                                                            <span class="badge badge-success">Terminé</span>
                                                        @else
                                                            <span class="badge badge-danger">Annulé</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="icon-shuffle icon-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun examen SESSION planifié</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
