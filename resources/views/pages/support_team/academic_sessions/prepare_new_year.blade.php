@extends('layouts.master')
@section('page_title', 'Préparer Nouvelle Année')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="icon-forward mr-2"></i> Préparer une Nouvelle Année Scolaire
        </h5>
    </div>

    <form action="{{ route('academic_sessions.execute_new_year') }}" method="POST">
        @csrf
        <div class="card-body">
            {{-- Situation actuelle --}}
            <div class="alert alert-secondary">
                <h6><i class="icon-info22 mr-2"></i> Situation actuelle</h6>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <strong>Année en cours:</strong> 
                        {{ $currentSession ? $currentSession->name : 'Non définie' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Élèves inscrits:</strong> {{ $stats['current_students'] }}
                    </div>
                    <div class="col-md-4">
                        <strong>Classes:</strong> {{ $stats['current_classes'] }}
                    </div>
                </div>
            </div>

            <hr>

            <h6><i class="icon-arrow-right7 mr-2"></i> Configuration de la nouvelle année</h6>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Nom de la nouvelle année <span class="text-danger">*</span></strong></label>
                        <input type="text" name="new_session_name" class="form-control" 
                               value="{{ old('new_session_name', $nextSessionName) }}" 
                               required pattern="\d{4}-\d{4}">
                        <small class="text-muted">Format: AAAA-AAAA</small>
                    </div>
                </div>
            </div>

            <fieldset class="mb-4">
                <legend><strong>Options</strong></legend>
                
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="archive_current" 
                           name="archive_current" value="1">
                    <label class="custom-control-label" for="archive_current">
                        <strong>Archiver l'année {{ $currentSession ? $currentSession->name : 'actuelle' }}</strong>
                        <br><small class="text-muted">L'année sera marquée comme terminée</small>
                    </label>
                </div>
            </fieldset>

            {{-- Étapes qui seront effectuées --}}
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0"><i class="icon-list mr-2"></i> Actions qui seront effectuées</h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li class="mb-2">
                            <strong>Création</strong> de l'année scolaire <span class="text-primary">{{ $nextSessionName }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Activation</strong> de la nouvelle année comme année courante
                        </li>
                        <li class="mb-2">
                            <strong>Mise à jour</strong> des paramètres système (current_session)
                        </li>
                        <li class="mb-2 archive-step" style="display: none;">
                            <strong>Archivage</strong> de l'année {{ $currentSession ? $currentSession->name : 'actuelle' }}
                        </li>
                    </ol>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="icon-warning mr-2"></i>
                <strong>Important:</strong> Après cette opération, toutes les nouvelles inscriptions et notes 
                seront enregistrées pour la nouvelle année. Les données de l'année précédente resteront accessibles.
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('academic_sessions.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Annuler
            </a>
            <button type="submit" class="btn btn-info" 
                    onclick="return confirm('Êtes-vous sûr de vouloir créer et activer la nouvelle année scolaire ?')">
                <i class="icon-checkmark mr-1"></i> Créer et Activer la Nouvelle Année
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('archive_current').addEventListener('change', function() {
        document.querySelector('.archive-step').style.display = this.checked ? 'list-item' : 'none';
    });
</script>
@endsection
