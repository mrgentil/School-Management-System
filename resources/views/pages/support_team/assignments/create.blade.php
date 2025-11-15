@extends('layouts.master')
@section('page_title', 'Créer un Devoir')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-plus2 mr-2"></i>
            Créer un Nouveau Devoir
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('assignments.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-semibold">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section <span class="text-danger">*</span></label>
                        <select name="section_id" id="section_id" class="form-control select" required>
                            <option value="">Sélectionner une classe d'abord</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-control select" required>
                            <option value="">Sélectionner</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Note Maximale <span class="text-danger">*</span></label>
                        <input type="number" name="max_score" class="form-control" value="100" min="1" max="1000" required>
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
