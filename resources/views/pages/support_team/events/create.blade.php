@extends('layouts.master')
@section('page_title', 'Créer un Événement')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Nouvel Événement</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" action="{{ route('events.store') }}">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Titre de l'Événement <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="{{ old('title') }}" required placeholder="Titre de l'événement">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="event_type">Type d'Événement <span class="text-danger">*</span></label>
                        <select name="event_type" id="event_type" class="form-control select" required>
                            <option value="">Choisir le type</option>
                            <option value="academic" {{ old('event_type') == 'academic' ? 'selected' : '' }}>Académique</option>
                            <option value="sports" {{ old('event_type') == 'sports' ? 'selected' : '' }}>Sport</option>
                            <option value="cultural" {{ old('event_type') == 'cultural' ? 'selected' : '' }}>Culturel</option>
                            <option value="meeting" {{ old('event_type') == 'meeting' ? 'selected' : '' }}>Réunion</option>
                            <option value="exam" {{ old('event_type') == 'exam' ? 'selected' : '' }}>Examen</option>
                            <option value="holiday" {{ old('event_type') == 'holiday' ? 'selected' : '' }}>Vacances</option>
                            <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" 
                          placeholder="Description de l'événement">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="event_date">Date de l'Événement <span class="text-danger">*</span></label>
                        <input type="date" name="event_date" id="event_date" class="form-control" 
                               value="{{ old('event_date') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_time">Heure de Début</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" 
                               value="{{ old('start_time') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_time">Heure de Fin</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" 
                               value="{{ old('end_time') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location">Lieu</label>
                        <input type="text" name="location" id="location" class="form-control" 
                               value="{{ old('location') }}" placeholder="Lieu de l'événement">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="target_audience">Public Cible <span class="text-danger">*</span></label>
                        <select name="target_audience" id="target_audience" class="form-control select" required>
                            <option value="">Choisir le public</option>
                            <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Tous</option>
                            <option value="students" {{ old('target_audience') == 'students' ? 'selected' : '' }}>Étudiants</option>
                            <option value="teachers" {{ old('target_audience') == 'teachers' ? 'selected' : '' }}>Enseignants</option>
                            <option value="parents" {{ old('target_audience') == 'parents' ? 'selected' : '' }}>Parents</option>
                            <option value="staff" {{ old('target_audience') == 'staff' ? 'selected' : '' }}>Personnel</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="color">Couleur du Calendrier</label>
                        <input type="color" name="color" id="color" class="form-control" 
                               value="{{ old('color', '#3788d8') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="is_recurring" value="1" class="form-check-input" 
                                       {{ old('is_recurring') ? 'checked' : '' }} onchange="toggleRecurrence()">
                                Événement récurrent
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="recurrence_pattern_div" style="display: none;">
                    <div class="form-group">
                        <label for="recurrence_pattern">Modèle de Récurrence</label>
                        <select name="recurrence_pattern" id="recurrence_pattern" class="form-control select">
                            <option value="">Choisir la récurrence</option>
                            <option value="daily" {{ old('recurrence_pattern') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                            <option value="weekly" {{ old('recurrence_pattern') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="monthly" {{ old('recurrence_pattern') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                            <option value="yearly" {{ old('recurrence_pattern') == 'yearly' ? 'selected' : '' }}>Annuel</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Créer l'Événement
            </button>
            <a href="{{ route('events.index') }}" class="btn btn-link">Annuler</a>
        </div>
    </form>
</div>

@endsection

@section('page_script')
<script>
function toggleRecurrence() {
    const checkbox = document.querySelector('input[name="is_recurring"]');
    const recurrenceDiv = document.getElementById('recurrence_pattern_div');
    
    if (checkbox.checked) {
        recurrenceDiv.style.display = 'block';
    } else {
        recurrenceDiv.style.display = 'none';
        document.getElementById('recurrence_pattern').value = '';
    }
}

// Vérifier l'état initial
document.addEventListener('DOMContentLoaded', function() {
    toggleRecurrence();
});
</script>
@endsection
