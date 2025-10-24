@extends('layouts.master')
@section('page_title', 'Mon tableau de bord')
@section('content')

    @if(Qs::userIsTeamSA())
       <div class="row">
           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-blue-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'student')->count() }}</h3>
                           <span class="text-uppercase font-size-xs font-weight-bold">Total Étudiants</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users4 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-danger-400 has-bg-image">
                   <div class="media">
                       <div class="media-body">
                           <h3 class="mb-0">{{ $users->where('user_type', 'teacher')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Enseignants</span>
                       </div>

                       <div class="ml-3 align-self-center">
                           <i class="icon-users2 icon-3x opacity-75"></i>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-success-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-pointer icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'admin')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Administrateurs</span>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-6 col-xl-3">
               <div class="card card-body bg-indigo-400 has-bg-image">
                   <div class="media">
                       <div class="mr-3 align-self-center">
                           <i class="icon-user icon-3x opacity-75"></i>
                       </div>

                       <div class="media-body text-right">
                           <h3 class="mb-0">{{ $users->where('user_type', 'parent')->count() }}</h3>
                           <span class="text-uppercase font-size-xs">Total Parents</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       @endif

    {{--Notices Board Begins--}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Tableau d'Affichage</h5>
                    <div class="header-elements">
                        <a href="{{ route('notices.index') }}" class="btn btn-link btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $notices = \App\Models\Notice::with('creator')
                            ->active()
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @forelse($notices as $notice)
                    <div class="media mb-3 pb-3 border-bottom">
                        <div class="mr-3">
                            <span class="badge badge-{{ $notice->type == 'urgent' ? 'danger' : ($notice->type == 'event' ? 'success' : 'primary') }}">
                                {{ ucfirst($notice->type) }}
                            </span>
                        </div>
                        <div class="media-body">
                            <h6 class="media-title mb-1">{{ $notice->title }}</h6>
                            <p class="mb-1">{{ Str::limit($notice->content, 120) }}</p>
                            <div class="text-muted small">
                                <i class="icon-user mr-1"></i>{{ $notice->creator->name }} - 
                                <i class="icon-calendar mr-1"></i>{{ $notice->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="icon-info22 icon-2x mb-2"></i>
                        <p>Aucune annonce récente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Événements à Venir</h6>
                    <div class="header-elements">
                        <a href="{{ route('events.index') }}" class="btn btn-link btn-sm">Voir tout</a>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $events = \App\Models\SchoolEvent::upcoming()->take(5)->get();
                    @endphp
                    
                    @forelse($events as $event)
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <div class="bg-light rounded text-center p-2" style="min-width: 50px;">
                                <div class="font-weight-bold">{{ $event->event_date->format('d') }}</div>
                                <div class="text-muted small">{{ $event->event_date->format('M') }}</div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h6 class="mb-0">{{ $event->title }}</h6>
                            <small class="text-muted">
                                @if($event->formatted_time)
                                    <i class="icon-clock2 mr-1"></i>{{ $event->formatted_time }}
                                @endif
                                @if($event->location)
                                    <i class="icon-location4 mr-1"></i>{{ $event->location }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="icon-calendar22 icon-2x mb-2"></i>
                        <p>Aucun événement à venir</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{--Events Calendar Begins--}}
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Calendrier des Événements Scolaires</h5>
         {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
    {{--Events Calendar Ends--}}
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
        locale: 'fr',
        height: 'auto'
    });
});
</script>
@endsection
