@extends('layouts.master')
@section('page_title', 'Modifier le Devoir')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-info">
        <h6 class="card-title text-white">
            <i class="icon-pencil mr-2"></i>
            Modifier le Devoir
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('assignments.update', $assignment->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $assignment->title) }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $assignment->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}" {{ $assignment->my_class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section <span class="text-danger">*</span></label>
                        <select name="section_id" id="section_id" class="form-control select" required>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $assignment->section_id == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Note Maximale <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="{{ old('max_score', $assignment->max_score) }}" min="1" max="1000" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date Limite <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date', $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ $assignment->status == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="closed" {{ $assignment->status == 'closed' ? 'selected' : '' }}>Fermé</option>
                            <option value="draft" {{ $assignment->status == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Nouveau Fichier (optionnel)</label>
                        <input type="file" name="file" class="form-control-file">
                        @if($assignment->file_path)
                            <small class="text-muted">
                                Fichier actuel: <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank">Télécharger</a>
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-right">
                <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-checkmark3 mr-2"></i>
                    Mettre à Jour
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        $('#section_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            $.ajax({
                url: '/assignments/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Sélectionner</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                }
            });
        }
    });
});
</script>
@endsection
