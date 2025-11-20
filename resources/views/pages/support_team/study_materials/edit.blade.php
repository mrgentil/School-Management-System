@extends('layouts.master')
@section('page_title', 'Modifier le Support Pédagogique')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Modifier le Support Pédagogique</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" action="{{ route('study-materials.update', $study_material->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="title">Titre du Support <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" 
                       value="{{ old('title', $study_material->title) }}" required placeholder="Titre du support pédagogique">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" 
                          placeholder="Description du support pédagogique">{{ old('description', $study_material->description) }}</textarea>
            </div>

            <div class="alert alert-light border">
                <h6 class="font-weight-semibold mb-3">🎯 Visibilité du matériel</h6>
                <div class="form-group">
                    <label class="d-block mb-3">Qui peut voir ce matériel ?</label>
                    <div class="form-check mb-2">
                        <label class="form-check-label">
                            <input type="radio" name="visibility_type" value="public" class="form-check-input" 
                                   {{ old('visibility_type', $study_material->is_public ? 'public' : 'class') == 'public' ? 'checked' : '' }} onchange="toggleClassSelector()">
                            <strong>📢 Tous les étudiants</strong> (matériel public)
                        </label>
                        <small class="form-text text-muted ml-4">Visible par tous les étudiants, quelle que soit leur classe</small>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" name="visibility_type" value="class" class="form-check-input" 
                                   {{ old('visibility_type', $study_material->is_public ? 'public' : 'class') == 'class' ? 'checked' : '' }} onchange="toggleClassSelector()">
                            <strong>🎓 Une classe spécifique</strong>
                        </label>
                        <small class="form-text text-muted ml-4">Visible uniquement par les étudiants de la classe sélectionnée</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" id="class-selector-group">
                        <label for="my_class_id">Classe <span class="text-danger class-required" style="display:none;">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('my_class_id', $study_material->my_class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class ? ($class->full_name ?: $class->name) : 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Requis si vous choisissez "Une classe spécifique"</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subject_id">Matière (Optionnel)</label>
                        <select name="subject_id" id="subject_id" class="form-control select">
                            <option value="">Sélectionner une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $study_material->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pour filtrer et organiser les matériaux</small>
                    </div>
                </div>
            </div>

            <input type="hidden" name="is_public" id="is_public" value="{{ old('is_public', $study_material->is_public ? '1' : '0') }}">

            <div class="form-group">
                <label>Fichier actuel</label>
                <div class="alert alert-info mb-2">
                    <i class="{{ $study_material->file_icon }} mr-2"></i>
                    <strong>{{ $study_material->file_name }}</strong>
                    <span class="ml-2 text-muted">({{ $study_material->file_size_formatted }})</span>
                </div>
            </div>

            <div class="form-group">
                <label for="file">Remplacer le fichier (optionnel)</label>
                <div class="custom-file">
                    <input type="file" name="file" id="file" class="custom-file-input" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mp3,.wav">
                    <label class="custom-file-label" for="file">Choisir un nouveau fichier...</label>
                </div>
                <small class="form-text text-muted">
                    Laissez vide pour conserver le fichier actuel. Formats acceptés : PDF, Word, Excel, PowerPoint, Images, Vidéos, Audio. Taille max : 50 MB
                </small>
            </div>

            <div class="alert alert-warning">
                <h6><i class="icon-warning mr-2"></i>Attention</h6>
                <p class="mb-0">Si vous remplacez le fichier, l'ancien fichier sera supprimé définitivement.</p>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Mettre à jour
            </button>
            <a href="{{ route('study-materials.index') }}" class="btn btn-link">Annuler</a>
        </div>
    </form>
</div>

@endsection

@section('page_script')
<script>
// Mettre à jour le label du fichier sélectionné
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Choisir un nouveau fichier...';
    const label = document.querySelector('.custom-file-label');
    label.textContent = fileName;
});

// Fonction pour gérer l'affichage du sélecteur de classe
function toggleClassSelector() {
    const visibilityType = document.querySelector('input[name="visibility_type"]:checked').value;
    const classSelector = document.getElementById('my_class_id');
    const classRequired = document.querySelector('.class-required');
    const isPublicField = document.getElementById('is_public');
    
    if (visibilityType === 'public') {
        // Public : is_public = 1, classe non requise
        classSelector.removeAttribute('required');
        classSelector.value = '';
        classRequired.style.display = 'none';
        isPublicField.value = '1';
        // Désactiver visuellement le select
        classSelector.closest('.form-group').style.opacity = '0.5';
    } else {
        // Classe spécifique : is_public = 0, classe requise
        classSelector.setAttribute('required', 'required');
        classRequired.style.display = 'inline';
        isPublicField.value = '0';
        // Réactiver visuellement le select
        classSelector.closest('.form-group').style.opacity = '1';
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    toggleClassSelector();
});
</script>
@endsection
