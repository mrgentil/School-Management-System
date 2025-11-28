@extends('layouts.master')
@section('page_title', 'Nouvelle Année Scolaire')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="icon-plus-circle2 mr-2"></i> Créer une Nouvelle Année Scolaire
        </h5>
    </div>

    <form action="{{ route('academic_sessions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Nom de l'année <span class="text-danger">*</span></strong></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $nextSession) }}" 
                               placeholder="Ex: 2025-2026" required pattern="\d{4}-\d{4}">
                        <small class="text-muted">Format: AAAA-AAAA (ex: 2025-2026)</small>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Libellé (optionnel)</strong></label>
                        <input type="text" name="label" class="form-control" 
                               value="{{ old('label') }}" 
                               placeholder="Ex: Année Scolaire 2025-2026">
                        <small class="text-muted">Sera généré automatiquement si vide</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Date de début</strong></label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Date de fin</strong></label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Statut <span class="text-danger">*</span></strong></label>
                        <select name="status" class="form-control select" required>
                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archivée</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description (optionnel)</strong></label>
                <textarea name="description" class="form-control" rows="3" 
                          placeholder="Notes ou remarques sur cette année scolaire...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('academic_sessions.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Retour
            </a>
            <button type="submit" class="btn btn-success">
                <i class="icon-checkmark mr-1"></i> Créer l'année scolaire
            </button>
        </div>
    </form>
</div>
@endsection
