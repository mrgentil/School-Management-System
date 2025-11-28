@extends('layouts.master')
@section('page_title', 'Copier Structure - ' . $academicSession->name)

@section('content')
<div class="card">
    <div class="card-header bg-warning">
        <h5 class="card-title mb-0">
            <i class="icon-copy mr-2"></i> Copier la Structure de {{ $academicSession->name }}
        </h5>
    </div>

    <form action="{{ route('academic_sessions.copy_structure.execute', $academicSession) }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Cette action permet de copier les élèves de l'année <strong>{{ $academicSession->name }}</strong> 
                vers une nouvelle année scolaire.
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Année source</strong></label>
                        <input type="text" class="form-control" value="{{ $academicSession->name }}" disabled>
                        <small class="text-muted">{{ $academicSession->total_students }} élève(s)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Année de destination <span class="text-danger">*</span></strong></label>
                        <input type="text" name="target_session" class="form-control" 
                               value="{{ old('target_session', $nextSession) }}" 
                               required pattern="\d{4}-\d{4}"
                               placeholder="Ex: 2025-2026">
                        <small class="text-muted">Format: AAAA-AAAA</small>
                    </div>
                </div>
            </div>

            <fieldset class="mb-4">
                <legend><strong>Options de copie</strong></legend>
                
                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" id="copy_students" 
                           name="copy_students" value="1" checked>
                    <label class="custom-control-label" for="copy_students">
                        <strong>Copier les élèves</strong>
                        <br><small class="text-muted">Les élèves seront inscrits dans la nouvelle année avec les mêmes classes</small>
                    </label>
                </div>
            </fieldset>

            {{-- Résumé des classes --}}
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0">Aperçu des classes à copier</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Classe</th>
                                <th>Sections</th>
                                <th class="text-center">Élèves actuels</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                                @php
                                    $studentCount = \App\Models\StudentRecord::where('session', $academicSession->name)
                                        ->where('my_class_id', $class->id)->count();
                                @endphp
                                @if($studentCount > 0)
                                <tr>
                                    <td>{{ $class->name }}</td>
                                    <td>
                                        @foreach($class->sections as $section)
                                            <span class="badge badge-secondary">{{ $section->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $studentCount }}</span>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="icon-warning mr-2"></i>
                <strong>Note:</strong> Les élèves déjà existants dans l'année de destination ne seront pas dupliqués.
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('academic_sessions.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Annuler
            </a>
            <button type="submit" class="btn btn-warning" 
                    onclick="return confirm('Confirmer la copie vers la nouvelle année ?')">
                <i class="icon-copy mr-1"></i> Copier la structure
            </button>
        </div>
    </form>
</div>
@endsection
