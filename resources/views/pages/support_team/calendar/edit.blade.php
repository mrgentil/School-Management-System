@extends('layouts.master')
@section('page_title', 'Modifier Événement')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-pencil mr-2"></i> Modifier: {{ $event->title }}
        </h5>
    </div>

    <form action="{{ route('calendar.update', $event) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>Titre de l'événement <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ old('title', $event->title) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Type <span class="text-danger">*</span></strong></label>
                        @php $eventType = $event->type ?? $event->event_type ?? 'event'; @endphp
                        <select name="type" class="form-control select" required>
                            <option value="event" {{ $eventType == 'event' ? 'selected' : '' }}>📅 Événement</option>
                            <option value="holiday" {{ $eventType == 'holiday' ? 'selected' : '' }}>🏖️ Congé/Vacances</option>
                            <option value="exam" {{ $eventType == 'exam' ? 'selected' : '' }}>📝 Examen</option>
                            <option value="meeting" {{ $eventType == 'meeting' ? 'selected' : '' }}>👥 Réunion</option>
                            <option value="deadline" {{ $eventType == 'deadline' ? 'selected' : '' }}>⏰ Date limite</option>
                            <option value="activity" {{ $eventType == 'activity' ? 'selected' : '' }}>🎉 Activité</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de début <span class="text-danger">*</span></strong></label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ old('start_date', ($event->start_date ?? $event->event_date)?->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de fin</strong></label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure début</strong></label>
                        <input type="time" name="start_time" class="form-control" 
                               value="{{ old('start_time', $event->start_time) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure fin</strong></label>
                        <input type="time" name="end_time" class="form-control" 
                               value="{{ old('end_time', $event->end_time) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Public cible</strong></label>
                        <select name="target_audience" class="form-control select">
                            <option value="all" {{ $event->target_audience == 'all' ? 'selected' : '' }}>👥 Tout le monde</option>
                            <option value="students" {{ $event->target_audience == 'students' ? 'selected' : '' }}>🎓 Élèves</option>
                            <option value="teachers" {{ $event->target_audience == 'teachers' ? 'selected' : '' }}>👨‍🏫 Enseignants</option>
                            <option value="parents" {{ $event->target_audience == 'parents' ? 'selected' : '' }}>👨‍👩‍👧 Parents</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="send_notification" 
                                   name="send_notification" value="1" {{ $event->send_notification ? 'checked' : '' }}>
                            <label class="custom-control-label" for="send_notification">
                                🔔 Envoyer une notification
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <div>
                <a href="{{ route('calendar.index') }}" class="btn btn-secondary">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
                <button type="button" class="btn btn-danger ml-2" 
                        onclick="if(confirm('Supprimer cet événement ?')) document.getElementById('delete-form').submit();">
                    <i class="icon-trash mr-1"></i> Supprimer
                </button>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-1"></i> Enregistrer
            </button>
        </div>
    </form>

    <form id="delete-form" action="{{ route('calendar.destroy', $event) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
