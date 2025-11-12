@extends('layouts.master')
@section('page_title', 'Calendrier des Présences')

@push('css')
    <link href="{{ asset('assets/plugins/fullcalendar/main.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Calendrier des Présences</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/plugins/fullcalendar/main.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: [
                @foreach($attendances as $attendance)
                {
                    title: '{{ ucfirst($attendance->status) }}',
                    start: '{{ $attendance->attendance_date->format('Y-m-d') }}',
                    @if($attendance->status == 'present')
                        color: '#28a745',
                    @elseif($attendance->status == 'absent')
                        color: '#dc3545',
                    @elseif($attendance->status == 'late')
                        color: '#ffc107',
                    @else
                        color: '#17a2b8',
                    @endif
                },
                @endforeach
            ]
        });
        calendar.render();
    });
</script>
@endpush
