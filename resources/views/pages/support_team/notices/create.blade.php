@extends('layouts.master')
@section('page_title', 'Créer une Annonce')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Nouvelle Annonce</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" action="{{ route('notices.store') }}">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="{{ old('title') }}" required placeholder="Titre de l'annonce">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control select" required>
                            <option value="">Choisir le type</option>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Général</option>
                            <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Annonce</option>
                            <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Événement</option>
                            <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="content">Contenu <span class="text-danger">*</span></label>
                <textarea name="content" id="content" class="form-control" rows="6" 
                          required placeholder="Contenu de l'annonce">{{ old('content') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Date de Début <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" 
                               value="{{ old('start_date') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">Date de Fin (Optionnel)</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" 
                               value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        Publier immédiatement
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Créer l'Annonce
            </button>
            <a href="{{ route('notices.index') }}" class="btn btn-link">Annuler</a>
        </div>
    </form>
</div>

@endsection
