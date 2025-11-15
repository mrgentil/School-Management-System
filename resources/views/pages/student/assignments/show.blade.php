@extends('layouts.master')
@section('page_title', 'Détails du Devoir')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            {{ $assignment->title }}
        </h6>
        <div class="header-elements">
            <a href="{{ route('student.assignments.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left7 mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Matière:</th>
                        <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Enseignant:</th>
                        <td>{{ $assignment->teacher->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date limite:</th>
                        <td>
                            {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A' }}
                            @if($assignment->due_date && $assignment->due_date->isPast())
                                <span class="badge badge-danger ml-2">Expiré</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Note maximale:</th>
                        <td>{{ $assignment->max_score }}</td>
                    </tr>
                    <tr>
                        <th>Fichier joint:</th>
                        <td>
                            @if($assignment->file_path)
                                <a href="{{ asset('storage/' . $assignment->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="icon-download"></i> Télécharger
                                </a>
                            @else
                                Aucun
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            @if($submission)
                                @if($submission->score !== null)
                                    <span class="badge badge-success">Noté</span>
                                @else
                                    <span class="badge badge-info">Soumis</span>
                                @endif
                            @else
                                <span class="badge badge-warning">Non soumis</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <h6 class="font-weight-semibold">Description:</h6>
            <p>{{ $assignment->description }}</p>
        </div>
    </div>
</div>

{{-- Ma soumission --}}
@if($submission)
    <div class="card">
        <div class="card-header bg-success">
            <h6 class="card-title text-white">Ma Soumission</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date de soumission:</strong> {{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p><strong>Statut:</strong> 
                        @if($submission->status == 'late')
                            <span class="badge badge-warning">Soumis en retard</span>
                        @elseif($submission->status == 'graded')
                            <span class="badge badge-success">Noté</span>
                        @else
                            <span class="badge badge-info">Soumis</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    @if($submission->score !== null)
                        <div class="alert alert-success">
                            <h4 class="mb-0">Note: {{ $submission->score }}/{{ $assignment->max_score }}</h4>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">En attente de notation</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($submission->submission_text)
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Mon texte:</h6>
                    <p class="border p-3">{{ $submission->submission_text }}</p>
                </div>
            @endif

            @if($submission->file_path)
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Mon fichier:</h6>
                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-info">
                        <i class="icon-download mr-2"></i>Télécharger mon fichier
                    </a>
                </div>
            @endif

            @if($submission->teacher_feedback)
                <div class="mt-3">
                    <h6 class="font-weight-semibold">Feedback de l'enseignant:</h6>
                    <div class="alert alert-primary">
                        {{ $submission->teacher_feedback }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@else
    {{-- Formulaire de soumission --}}
    @if($assignment->status == 'active')
        <div class="card">
            <div class="card-header bg-info">
                <h6 class="card-title text-white">Soumettre mon Devoir</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.assignments.submit', $assignment->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-semibold">Votre réponse (texte)</label>
                        <textarea name="submission_text" class="form-control" rows="6" placeholder="Écrivez votre réponse ici..."></textarea>
                        <small class="text-muted">Vous pouvez écrire votre réponse ou joindre un fichier (ou les deux)</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-semibold">Fichier (optionnel)</label>
                        <input type="file" name="submission_file" class="form-control-file">
                        <small class="text-muted">PDF, DOC, DOCX, images (Max: 10MB)</small>
                    </div>

                    @if($assignment->due_date && $assignment->due_date->isPast())
                        <div class="alert alert-warning">
                            <i class="icon-warning mr-2"></i>
                            Attention: La date limite est dépassée. Votre soumission sera marquée comme "en retard".
                        </div>
                    @endif

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-checkmark3 mr-2"></i>
                            Soumettre mon Devoir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <i class="icon-blocked mr-2"></i>
            Ce devoir n'est plus actif. Vous ne pouvez plus le soumettre.
        </div>
    @endif
@endif

@endsection
