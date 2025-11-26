@extends('layouts.master')
@section('page_title', 'Créer Devoir/Interrogation')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-plus2 mr-2"></i>
            Créer un Devoir ou une Interrogation
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Type d'évaluation <span class="text-danger">*</span></label>
                        <select name="type" class="form-control select" required>
                            <option value="devoir" {{ old('type') == 'devoir' ? 'selected' : '' }}>📝 Devoir</option>
                            <option value="interrogation" {{ old('type') == 'interrogation' ? 'selected' : '' }}>📋 Interrogation</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}" 
                                        data-section="{{ $class->academicSection ? $class->academicSection->name : '' }}"
                                        data-option="{{ $class->option ? $class->option->name : '' }}">
                                    {{ $class->full_name ?: $class->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">La classe contient déjà la section et l'option</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière <span class="text-danger">*</span></label>
                        <select name="subject_id" id="subject_id" class="form-control select" required>
                            <option value="">Sélectionner une classe d'abord</option>
                        </select>
                        <small class="form-text text-muted">Matières liées à la classe sélectionnée</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Note Maximale <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="100" min="1" max="1000" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Période <span class="text-danger">*</span></label>
                        <select name="period" class="form-control select" required>
                            <option value="">Sélectionner une période</option>
                            @foreach($periods as $period)
                                <option value="{{ $period['id'] }}" {{ old('period') == $period['id'] ? 'selected' : '' }}>
                                    {{ $period['name'] }} (Semestre {{ $period['semester'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date Limite <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="due_date" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-semibold">Fichier Joint (optionnel)</label>
                        <input type="file" name="file" class="form-control-file">
                        <small class="text-muted">PDF, DOC, DOCX, PPT, PPTX, ZIP (Max: 10MB)</small>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a href="{{ route('assignments.index') }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-success">
                    <i class="icon-checkmark3 mr-2"></i>
                    Créer le Devoir
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Données des matières
    const allSubjects = @json($subjects);
    
    // Pour l'instant, utilisons un filtrage basé sur les mots-clés dans les noms de matières
    // au lieu de noms exacts qui n'existent pas dans la base
    const subjectKeywordsByType = {
        'Technique': ['math', 'physique', 'électron', 'mécan', 'inform', 'français', 'anglais', 'technique'],
        'Commercial': ['math', 'compt', 'économ', 'gest', 'français', 'anglais', 'commercial'],
        'Scientifique': ['math', 'physique', 'chim', 'bio', 'français', 'anglais', 'science'],
        'Sécondaire': ['math', 'français', 'anglais', 'histoire', 'géo', 'science'],
        'Litteraire': ['français', 'anglais', 'histoire', 'géo', 'philo', 'litt'],
        'Maternelle': ['jeux', 'éveil', 'motric', 'langage', 'éducatif'],
        'Primaire': ['math', 'français', 'science', 'histoire', 'géo', 'anglais']
    };
    
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var section = selectedOption.data('section');
        var option = selectedOption.data('option');
        
        var subjectOptions = '<option value="">Sélectionner</option>';
        
        if (classId) {
            // Déterminer le type de classe pour filtrer les matières
            var classType = 'Primaire'; // Par défaut
            
            // Priorité 1: Utiliser la section académique si disponible
            if (section && section.trim() !== '') {
                classType = section;
            } else {
                // Priorité 2: Détecter le type selon le nom de la classe
                var className = selectedOption.text().toLowerCase();
                if (className.includes('maternelle') || className.includes('crèche') || className.includes('pré-maternelle')) {
                    classType = 'Maternelle';
                } else if (className.includes('primaire')) {
                    classType = 'Primaire';
                } else if (className.includes('technique')) {
                    classType = 'Technique';
                } else if (className.includes('commercial')) {
                    classType = 'Commercial';
                } else if (className.includes('scientifique')) {
                    classType = 'Scientifique';
                } else if (className.includes('secondaire') || className.includes('sec ')) {
                    classType = 'Sécondaire';
                }
            }
            
            // Filtrer les matières selon le type avec mots-clés
            var relevantKeywords = subjectKeywordsByType[classType] || [];
            
            if (relevantKeywords.length === 0) {
                // Aucun filtre spécifique, afficher toutes les matières
                allSubjects.forEach(function(subject) {
                    subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                });
            } else {
                // Filtrer selon les mots-clés pertinents
                allSubjects.forEach(function(subject) {
                    var subjectName = subject.name.toLowerCase();
                    var isRelevant = relevantKeywords.some(function(keyword) {
                        return subjectName.includes(keyword);
                    });
                    
                    if (isRelevant) {
                        subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                    }
                });
                
                // Si aucune matière trouvée avec les mots-clés, afficher toutes
                var foundCount = subjectOptions.split('</option>').length - 2; // -2 pour l'option par défaut
                if (foundCount === 0) {
                    allSubjects.forEach(function(subject) {
                        subjectOptions += '<option value="' + subject.id + '">' + subject.name + '</option>';
                    });
                }
            }
            
            // Afficher les informations de la classe sélectionnée
            console.log('=== DEBUG CLASSE ===');
            console.log('Classe sélectionnée:', selectedOption.text());
            console.log('Section académique:', section || 'N/A');
            console.log('Option:', option || 'N/A');
            console.log('Type détecté:', classType);
            console.log('Mots-clés utilisés:', relevantKeywords);
            console.log('Nombre de matières trouvées:', subjectOptions.split('</option>').length - 1);
            console.log('==================');
            
            // Debug supprimé - système fonctionnel
        }
        
        $('#subject_id').html(subjectOptions);
    });
    
    // Debug initial
    console.log('Classes chargées:', @json($my_classes->count()), 'classes');
    console.log('Matières disponibles:', allSubjects.length, 'matières');
});
</script>
@endsection
