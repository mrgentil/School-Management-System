@extends('layouts.master')
@section('page_title', 'Modifier ' . $academicSession->name)

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-pencil mr-2"></i> Modifier l'Année Scolaire {{ $academicSession->name }}
        </h5>
    </div>

    <form action="{{ route('academic_sessions.update', $academicSession) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Nom de l'année</strong></label>
                        <input type="text" class="form-control" value="{{ $academicSession->name }}" disabled>
                        <small class="text-muted">Le nom ne peut pas être modifié</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Libellé</strong></label>
                        <input type="text" name="label" class="form-control" 
                               value="{{ old('label', $academicSession->label) }}">
                    </div>
                </div>
            </div>

            <fieldset class="mb-4">
                <legend><strong>📅 Dates de l'année scolaire</strong></legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de début</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ old('start_date', $academicSession->start_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date de fin</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ old('end_date', $academicSession->end_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-4">
                <legend><strong>📝 Inscriptions</strong></legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Début des inscriptions</label>
                            <input type="date" name="registration_start" class="form-control" 
                                   value="{{ old('registration_start', $academicSession->registration_start?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fin des inscriptions</label>
                            <input type="date" name="registration_end" class="form-control" 
                                   value="{{ old('registration_end', $academicSession->registration_end?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-4">
                <legend><strong>📋 Examens</strong></legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Début des examens</label>
                            <input type="date" name="exam_start" class="form-control" 
                                   value="{{ old('exam_start', $academicSession->exam_start?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fin des examens</label>
                            <input type="date" name="exam_end" class="form-control" 
                                   value="{{ old('exam_end', $academicSession->exam_end?->format('Y-m-d')) }}">
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $academicSession->description) }}</textarea>
            </div>

            {{-- Informations en lecture seule --}}
            <div class="alert alert-light border">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Statut:</strong> {!! $academicSession->status_badge !!}
                    </div>
                    <div class="col-md-3">
                        <strong>Élèves:</strong> {{ $academicSession->total_students }}
                    </div>
                    <div class="col-md-3">
                        <strong>Classes:</strong> {{ $academicSession->total_classes }}
                    </div>
                    <div class="col-md-3">
                        <strong>Moyenne:</strong> {{ $academicSession->average_score ? number_format($academicSession->average_score, 1) . '/20' : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('academic_sessions.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i> Retour
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="icon-checkmark mr-1"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
