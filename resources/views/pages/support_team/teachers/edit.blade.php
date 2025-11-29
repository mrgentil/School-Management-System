@extends('layouts.master')
@section('page_title', 'Attribuer Classes - ' . $teacher->name)

@section('content')
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ Qs::getUserPhoto($teacher->photo) }}" 
                     class="rounded-circle mr-3" 
                     style="width: 50px; height: 50px; object-fit: cover;">
                <div>
                    <h4 class="mb-0">Attribuer Classes & Matières</h4>
                    <small class="opacity-75">{{ $teacher->name }}</small>
                </div>
            </div>
            <a href="{{ route('teachers.management.show', $teacher->id) }}" class="btn btn-outline-light">
                <i class="icon-arrow-left7 mr-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<form action="{{ route('teachers.management.update', $teacher->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Classe Titulaire --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0"><i class="icon-graduation mr-2"></i> Classe Titulaire</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Le professeur sera responsable de cette classe.</p>
                    <select name="titular_class_id" class="form-control select-search">
                        <option value="">-- Aucune --</option>
                        @foreach($classes as $class)
                            @php
                                $currentTitular = \App\Models\User::find($class->teacher_id);
                                $isTitular = $class->teacher_id == $teacher->id;
                            @endphp
                            <option value="{{ $class->id }}" {{ $isTitular ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if($currentTitular && !$isTitular)
                                    ({{ $currentTitular->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Résumé --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Résumé</h6>
                </div>
                <div class="card-body">
                    <div id="summary">
                        <p class="text-muted">Cochez les matières à attribuer...</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="icon-checkmark mr-1"></i> Enregistrer les Attributions
            </button>
        </div>

        {{-- Matières par Classe --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-book mr-2"></i> Matières par Classe
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="icon-info22 mr-1"></i> 
                        Cochez les matières que ce professeur enseigne. 
                        Les matières avec un autre prof sont indiquées en <span class="text-warning">orange</span>.
                    </p>

                    <div class="accordion" id="classAccordion">
                        @foreach($classes as $index => $class)
                            @php
                                $classSubjects = $subjectsByClass[$class->id] ?? collect();
                                $assignedCount = $classSubjects->whereIn('id', $assignedSubjectIds)->count();
                            @endphp
                            <div class="card mb-2">
                                <div class="card-header p-0" id="heading{{ $class->id }}">
                                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center p-3 {{ $assignedCount > 0 ? 'bg-light' : '' }}" 
                                            type="button" 
                                            data-toggle="collapse" 
                                            data-target="#collapse{{ $class->id }}">
                                        <span>
                                            <i class="icon-graduation mr-2"></i>
                                            <strong>{{ $class->name }}</strong>
                                            <span class="badge badge-secondary ml-2">{{ $classSubjects->count() }} matières</span>
                                        </span>
                                        @if($assignedCount > 0)
                                            <span class="badge badge-success">{{ $assignedCount }} attribuée(s)</span>
                                        @endif
                                    </button>
                                </div>

                                <div id="collapse{{ $class->id }}" 
                                     class="collapse {{ $assignedCount > 0 ? 'show' : '' }}" 
                                     data-parent="#classAccordion">
                                    <div class="card-body">
                                        @if($classSubjects->count() > 0)
                                            <div class="row">
                                                @foreach($classSubjects as $subject)
                                                    @php
                                                        $isAssigned = in_array($subject->id, $assignedSubjectIds);
                                                        $hasOtherTeacher = $subject->teacher_id && $subject->teacher_id != $teacher->id;
                                                    @endphp
                                                    <div class="col-md-6 mb-2">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" 
                                                                   class="custom-control-input subject-checkbox" 
                                                                   id="subject_{{ $subject->id }}"
                                                                   name="subject_ids[]"
                                                                   value="{{ $subject->id }}"
                                                                   data-class="{{ $class->name }}"
                                                                   data-subject="{{ $subject->name }}"
                                                                   {{ $isAssigned ? 'checked' : '' }}>
                                                            <label class="custom-control-label {{ $hasOtherTeacher ? 'text-warning' : '' }}" 
                                                                   for="subject_{{ $subject->id }}">
                                                                {{ $subject->name }}
                                                                @if($hasOtherTeacher)
                                                                    <br><small class="text-warning">
                                                                        <i class="icon-user mr-1"></i>{{ $subject->teacher->name ?? 'Inconnu' }}
                                                                    </small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            {{-- Boutons sélection rapide --}}
                                            <div class="mt-2 pt-2 border-top">
                                                <button type="button" class="btn btn-sm btn-outline-primary select-all-class" data-class="{{ $class->id }}">
                                                    Tout sélectionner
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary deselect-all-class" data-class="{{ $class->id }}">
                                                    Tout désélectionner
                                                </button>
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">Aucune matière définie pour cette classe.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
$(function() {
    // Mise à jour du résumé
    function updateSummary() {
        var checked = $('.subject-checkbox:checked');
        var summary = $('#summary');
        
        if (checked.length === 0) {
            summary.html('<p class="text-muted">Aucune matière sélectionnée</p>');
            return;
        }

        var grouped = {};
        checked.each(function() {
            var cls = $(this).data('class');
            var subj = $(this).data('subject');
            if (!grouped[cls]) grouped[cls] = [];
            grouped[cls].push(subj);
        });

        var html = '<ul class="list-unstyled mb-0">';
        for (var cls in grouped) {
            html += '<li class="mb-2"><strong>' + cls + '</strong><br>';
            html += '<small class="text-muted">' + grouped[cls].join(', ') + '</small></li>';
        }
        html += '</ul>';
        html += '<hr><strong>Total: ' + checked.length + ' matière(s)</strong>';
        
        summary.html(html);
    }

    $('.subject-checkbox').on('change', updateSummary);
    updateSummary();

    // Sélection par classe
    $('.select-all-class').click(function() {
        var classId = $(this).data('class');
        $('#collapse' + classId + ' .subject-checkbox').prop('checked', true);
        updateSummary();
    });

    $('.deselect-all-class').click(function() {
        var classId = $(this).data('class');
        $('#collapse' + classId + ' .subject-checkbox').prop('checked', false);
        updateSummary();
    });
});
</script>
@endsection
