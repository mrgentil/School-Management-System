@extends('layouts.master')
@section('page_title', 'Nouvel Événement')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="icon-plus-circle2 mr-2"></i> Créer un Événement
        </h5>
    </div>

    <form action="{{ route('calendar.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label><strong>Titre de l'événement <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" placeholder="Ex: Vacances de Noël" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Type <span class="text-danger">*</span></strong></label>
                        <select name="type" class="form-control select" required>
                            <option value="event" {{ request('type') == 'event' || old('type') == 'event' ? 'selected' : '' }}>📅 Événement</option>
                            <option value="holiday" {{ request('type') == 'holiday' || old('type') == 'holiday' ? 'selected' : '' }}>🏖️ Congé/Vacances</option>
                            <option value="exam" {{ request('type') == 'exam' || old('type') == 'exam' ? 'selected' : '' }}>📝 Examen</option>
                            <option value="meeting" {{ request('type') == 'meeting' || old('type') == 'meeting' ? 'selected' : '' }}>👥 Réunion</option>
                            <option value="deadline" {{ request('type') == 'deadline' || old('type') == 'deadline' ? 'selected' : '' }}>⏰ Date limite</option>
                            <option value="activity" {{ request('type') == 'activity' || old('type') == 'activity' ? 'selected' : '' }}>🎉 Activité</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de début <span class="text-danger">*</span></strong></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                               value="{{ old('start_date', date('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Date de fin</strong></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                        <small class="text-muted">Laisser vide si même jour</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure début</strong></label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Heure fin</strong></label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="3" 
                          placeholder="Détails de l'événement...">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Public cible <span class="text-danger">*</span></strong></label>
                        <select name="target_audience" class="form-control select" required>
                            <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>👥 Tout le monde</option>
                            <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>🎓 Élèves uniquement</option>
                            <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>👨‍🏫 Enseignants uniquement</option>
                            <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>👨‍👩‍👧 Parents uniquement</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="custom-control custom-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="send_notification" 
                                   name="send_notification" value="1">
                            <label class="custom-control-label" for="send_notification">
                                🔔 Envoyer une notification
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('calendar.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Retour
            </a>
            <button type="submit" class="btn btn-success">
                <i class="icon-checkmark mr-1"></i> Créer l'événement
            </button>
        </div>
    </form>
</div>
@endsection
