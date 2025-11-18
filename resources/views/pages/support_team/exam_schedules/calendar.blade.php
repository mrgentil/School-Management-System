@extends('layouts.master')
@section('page_title', 'Calendrier des Examens')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title">
                <i class="icon-calendar mr-2"></i> Vue Calendrier - Examens à Venir
            </h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('exam_schedules.index') }}" class="btn btn-light">
                    <i class="icon-list"></i> Vue Liste
                </a>
            </div>

            @if($upcoming->count() > 0)
                <div class="timeline timeline-left">
                    @php $currentDate = null; @endphp
                    @foreach($upcoming as $schedule)
                        @if($currentDate != $schedule->exam_date->format('Y-m-d'))
                            @php $currentDate = $schedule->exam_date->format('Y-m-d'); @endphp
                            <div class="timeline-container">
                                <div class="timeline-icon bg-primary">
                                    <i class="icon-calendar3"></i>
                                </div>
                                <div class="timeline-card card">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">
                                            {{ $schedule->exam_date->format('l d F Y') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-container">
                            <div class="timeline-icon bg-{{ $schedule->status == 'scheduled' ? 'info' : ($schedule->status == 'completed' ? 'success' : 'warning') }}">
                                <i class="icon-clock"></i>
                            </div>
                            <div class="timeline-card card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="font-weight-bold mb-1">
                                                {{ $schedule->subject->name }}
                                            </h6>
                                            <p class="text-muted mb-1">
                                                <strong>Examen:</strong> {{ $schedule->exam->name }} | 
                                                <strong>Classe:</strong> {{ $schedule->my_class->name }}
                                                @if($schedule->section)
                                                    - {{ $schedule->section->name }}
                                                @endif
                                            </p>
                                            <p class="mb-1">
                                                <i class="icon-clock mr-1"></i>
                                                {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                            </p>
                                            @if($schedule->room)
                                                <p class="mb-1">
                                                    <i class="icon-location4 mr-1"></i>
                                                    Salle: <strong>{{ $schedule->room }}</strong>
                                                </p>
                                            @endif
                                            @if($schedule->supervisors->count() > 0)
                                                <p class="mb-0">
                                                    <i class="icon-user mr-1"></i>
                                                    Surveillants: 
                                                    @foreach($schedule->supervisors as $sup)
                                                        <span class="badge badge-secondary">{{ $sup->teacher->name }}</span>
                                                    @endforeach
                                                </p>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $schedule->status == 'scheduled' ? 'info' : ($schedule->status == 'completed' ? 'success' : 'warning') }}">
                                                @if($schedule->status == 'scheduled')
                                                    Planifié
                                                @elseif($schedule->status == 'ongoing')
                                                    En cours
                                                @elseif($schedule->status == 'completed')
                                                    Terminé
                                                @else
                                                    Annulé
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="icon-calendar-empty icon-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun examen planifié dans les 30 prochains jours</p>
                </div>
            @endif
        </div>
    </div>

@endsection
