@extends('layouts.master')
@section('page_title', 'Ajouter un Support Pédagogique')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Nouveau Support Pédagogique</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <form method="post" action="{{ route('study-materials.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Titre du Support <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="{{ old('title') }}" required placeholder="Titre du support pédagogique">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-check mt-4">
                            <label class="form-check-label">
                                <input type="checkbox" name="is_public" value="1" class="form-check-input" 
                                       {{ old('is_public', true) ? 'checked' : '' }}>
                                Visible par tous les utilisateurs
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" 
                          placeholder="Description du support pédagogique">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="my_class_id">Classe (Optionnel)</label>
                        <select name="my_class_id" id="my_class_id" class="form-control select">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('my_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subject_id">Matière (Optionnel)</label>
                        <select name="subject_id" id="subject_id" class="form-control select">
                            <option value="">Sélectionner une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="file">Fichier <span class="text-danger">*</span></label>
                <div class="custom-file">
                    <input type="file" name="file" id="file" class="custom-file-input" required 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mp3,.wav">
                    <label class="custom-file-label" for="file">Choisir un fichier...</label>
                </div>
                <small class="form-text text-muted">
                    Formats acceptés : PDF, Word, Excel, PowerPoint, Images, Vidéos, Audio. Taille max : 50 MB
                </small>
            </div>

            <div class="alert alert-info">
                <h6><i class="icon-info22 mr-2"></i>Conseils pour l'upload</h6>
                <ul class="mb-0">
                    <li>Utilisez des noms de fichiers descriptifs</li>
                    <li>Organisez vos supports par classe et matière</li>
                    <li>Vérifiez que le contenu est approprié avant publication</li>
                    <li>Les fichiers PDF sont recommandés pour une meilleure compatibilité</li>
                </ul>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-2"></i> Télécharger le Support
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
    const fileName = e.target.files[0]?.name || 'Choisir un fichier...';
    const label = document.querySelector('.custom-file-label');
    label.textContent = fileName;
});
</script>
@endsection
