@extends('layouts.master')
@section('page_title', 'Modifier l\'Annonce')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Modifier l'Annonce</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" action="{{ route('notices.update', $notice->id) }}">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="{{ old('title', $notice->title) }}" required placeholder="Titre de l'annonce">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">Type <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-control select" required>
                            <option value="">Choisir le type</option>
                            <option value="general" {{ old('type', $notice->type) == 'general' ? 'selected' : '' }}>Général</option>
                            <option value="announcement" {{ old('type', $notice->type) == 'announcement' ? 'selected' : '' }}>Annonce</option>
                            <option value="event" {{ old('type', $notice->type) == 'event' ? 'selected' : '' }}>Événement</option>
                            <option value="urgent" {{ old('type', $notice->type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="content">Contenu <span class="text-danger">*</span></label>
                <textarea name="content" id="content" class="form-control" rows="6" 
                          required placeholder="Contenu de l'annonce">{{ old('content', $notice->content) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="target_audience">Public Cible <span class="text-danger">*</span></label>
                        <select name="target_audience" id="target_audience" class="form-control select" required>
                            <option value="">Choisir le public</option>
                            <option value="all" {{ old('target_audience', $notice->target_audience) == 'all' ? 'selected' : '' }}>Tous</option>
                            <option value="students" {{ old('target_audience', $notice->target_audience) == 'students' ? 'selected' : '' }}>Étudiants</option>
                            <option value="teachers" {{ old('target_audience', $notice->target_audience) == 'teachers' ? 'selected' : '' }}>Enseignants</option>
                            <option value="parents" {{ old('target_audience', $notice->target_audience) == 'parents' ? 'selected' : '' }}>Parents</option>
                            <option value="staff" {{ old('target_audience', $notice->target_audience) == 'staff' ? 'selected' : '' }}>Personnel</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Date de Début <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" 
                               value="{{ old('start_date', $notice->start_date->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">Date de Fin (Optionnel)</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" 
                               value="{{ old('end_date', $notice->end_date ? $notice->end_date->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                               {{ old('is_active', $notice->is_active) ? 'checked' : '' }}>
                        Publier immédiatement
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Mettre à Jour
            </button>
            <a href="{{ route('notices.show', $notice->id) }}" class="btn btn-link">Annuler</a>
        </div>
    </form>
</div>

@endsection
