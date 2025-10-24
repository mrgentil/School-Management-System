@extends('layouts.master')
@section('page_title', 'Événements Scolaires')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Calendrier des Événements</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @if(Qs::userIsTeamSA())
        <div class="mb-3">
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <i class="icon-plus2 mr-2"></i> Nouvel Événement
            </a>
        </div>
        @endif

        <!-- Calendrier -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Vue Calendrier</h6>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des événements à venir -->
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3">Événements à Venir</h6>
                @forelse($events as $event)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="bg-light rounded p-3">
                                    <div class="h4 mb-0">{{ $event->event_date->format('d') }}</div>
                                    <div class="text-muted">{{ $event->event_date->format('M Y') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <p class="text-muted mb-1">{{ Str::limit($event->description, 100) }}</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-{{ $event->event_type == 'exam' ? 'danger' : ($event->event_type == 'sports' ? 'success' : 'primary') }} mr-2">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                    @if($event->location)
                                    <small class="text-muted">
                                        <i class="icon-location4 mr-1"></i>{{ $event->location }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                @if($event->formatted_time)
                                <div class="text-center">
                                    <i class="icon-clock2 mr-1"></i>
                                    <small>{{ $event->formatted_time }}</small>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-2 text-right">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-primary">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if(Qs::userIsTeamSA())
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-outline-warning">
                                        <i class="icon-pencil"></i>
                                    </a>
                                    <form method="post" action="{{ route('events.destroy', $event->id) }}" class="d-inline">
                                        @csrf @method('delete')
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="icon-calendar22 icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun événement à venir</h5>
                    <p class="text-muted">Il n'y a actuellement aucun événement planifié.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{ $events->links() }}
    </div>
</div>

@endsection

@section('page_script')
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/fullcalendar.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        defaultView: 'month',
        editable: false,
        events: '{{ route("events.calendar") }}',
        eventClick: function(event) {
            window.location.href = '{{ url("events") }}/' + event.id;
        },
        locale: 'fr'
    });
});
</script>
@endsection
