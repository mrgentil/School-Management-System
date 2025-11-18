@extends('layouts.master')
@section('page_title', 'Mon Calendrier d\'Examens')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements-inline bg-primary text-white">
                    <h6 class="card-title">
                        <i class="icon-calendar mr-2"></i> Mon Calendrier d'Examens
                    </h6>
                    {!! Qs::getPanelOptions() !!}
                </div>

                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <strong>Classe:</strong> {{ $my_class->name }} - {{ $section->name }}
                    </div>

                    {{-- Upcoming Exams --}}
                    @if($upcoming->count() > 0)
                    <div class="card">
                        <div class="card-header bg-warning-400">
                            <h6 class="card-title">
                                <i class="icon-alarm-add mr-2"></i> Examens à Venir (30 prochains jours)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($upcoming as $schedule)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-3 border-left-warning">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-0">{{ $schedule->subject->name }}</h6>
                                                    <p class="text-muted mb-1">{{ $schedule->exam->name }}</p>
                                                </div>
                                                <span class="badge badge-warning">{{ \Carbon\Carbon::parse($schedule->exam_date)->diffForHumans() }}</span>
                                            </div>
                                            <div class="mt-2">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-calendar3 mr-2 text-primary"></i>
                                                    <span><strong>Date:</strong> {{ $schedule->exam_date->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-clock mr-2 text-success"></i>
                                                    <span><strong>Heure:</strong> {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</span>
                                                </div>
                                                @if($schedule->room)
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="icon-location4 mr-2 text-danger"></i>
                                                    <span><strong>Salle:</strong> {{ $schedule->room }}</span>
                                                </div>
                                                @endif
                                                @if($schedule->instructions)
                                                <div class="mt-2">
                                                    <div class="alert alert-light mb-0">
                                                        <strong>Instructions:</strong><br>
                                                        {{ $schedule->instructions }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- All Scheduled Exams --}}
                    <div class="card mt-3">
                        <div class="card-header bg-info-400">
                            <h6 class="card-title">
                                <i class="icon-calendar2 mr-2"></i> Tous les Examens Planifiés
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($schedules->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr class="bg-light">
                                        <th>Examen</th>
                                        <th>Matière</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Salle</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schedules->sortBy('exam_date') as $schedule)
                                        <tr>
                                            <td><strong>{{ $schedule->exam->name }}</strong></td>
                                            <td>{{ $schedule->subject->name }}</td>
                                            <td>
                                                <span class="badge badge-flat border-primary text-primary">
                                                    {{ $schedule->exam_date->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>{{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</td>
                                            <td>{{ $schedule->room ?: '-' }}</td>
                                            <td>
                                                @php
                                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                                    $end = \Carbon\Carbon::parse($schedule->end_time);
                                                    $duration = $start->diffInMinutes($end);
                                                @endphp
                                                {{ $duration }} min
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
                                <div class="text-center py-4">
                                    <i class="icon-calendar-empty icon-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun examen planifié pour le moment</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
