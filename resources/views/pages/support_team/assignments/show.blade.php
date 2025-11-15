@extends('layouts.master')
@section('page_title', 'Détails du Devoir')
@section('content')

{{-- Info du devoir --}}
<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            {{ $assignment->title }}
        </h6>
        <div class="header-elements">
            <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-light btn-sm">
                <i class="icon-pencil mr-2"></i>Modifier
            </a>
            <a href="{{ route('assignments.export', $assignment->id) }}" class="btn btn-success btn-sm">
                <i class="icon-file-excel mr-2"></i>Exporter
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Classe:</th>
                        <td>{{ $assignment->myClass->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Section:</th>
                        <td>{{ $assignment->section->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Matière:</th>
                        <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Enseignant:</th>
                        <td>{{ $assignment->teacher->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Date limite:</th>
                        <td>{{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Note maximale:</th>
                        <td>{{ $assignment->max_score }}</td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            @if($assignment->status == 'active')
                                <span class="badge badge-success">Actif</span>
                            @elseif($assignment->status == 'closed')
                                <span class="badge badge-danger">Fermé</span>
                            @else
                                <span class="badge badge-secondary">Brouillon</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Fichier:</th>
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
                </table>
            </div>
        </div>

        <div class="mt-3">
            <h6 class="font-weight-semibold">Description:</h6>
            <p>{{ $assignment->description }}</p>
        </div>
    </div>
</div>

{{-- Statistiques --}}
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-users4 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <span class="text-uppercase font-size-xs">Total Étudiants</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-checkmark3 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['submitted'] }}</h3>
                    <span class="text-uppercase font-size-xs">Soumis</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-hour-glass2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                    <span class="text-uppercase font-size-xs">En attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-teal-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-star-full2 icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $stats['graded'] }}</h3>
                    <span class="text-uppercase font-size-xs">Notés</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Soumissions --}}
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Soumissions ({{ $submissions->count() }})</h6>
    </div>

    <div class="card-body">
        @if($submissions->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Date Soumission</th>
                            <th>Statut</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
                            <tr>
                                <td><strong>{{ $submission->student->name ?? 'N/A' }}</strong></td>
                                <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>
                                    @if($submission->status == 'graded')
                                        <span class="badge badge-success">Noté</span>
                                    @elseif($submission->status == 'late')
                                        <span class="badge badge-warning">En retard</span>
                                    @else
                                        <span class="badge badge-info">Soumis</span>
                                    @endif
                                </td>
                                <td>
                                    @if($submission->score !== null)
                                        <strong>{{ $submission->score }}/{{ $assignment->max_score }}</strong>
                                    @else
                                        <span class="text-muted">Non noté</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gradeModal{{ $submission->id }}">
                                        <i class="icon-pencil"></i> Noter
                                    </button>
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="icon-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal de notation --}}
                            <div class="modal fade" id="gradeModal{{ $submission->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Noter - {{ $submission->student->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form method="POST" action="{{ route('assignments.grade', $submission->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Texte soumis:</label>
                                                    <p class="border p-2">{{ $submission->submission_text ?? 'Aucun texte' }}</p>
                                                </div>

                                                <div class="form-group">
                                                    <label>Note (sur {{ $assignment->max_score }})</label>
                                                    <input type="number" name="score" class="form-control" min="0" max="{{ $assignment->max_score }}" value="{{ $submission->score }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Feedback</label>
                                                    <textarea name="teacher_feedback" class="form-control" rows="3">{{ $submission->teacher_feedback }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune soumission pour le moment.
            </div>
        @endif

        {{-- Étudiants n'ayant pas soumis --}}
        @if($notSubmitted->count() > 0)
            <div class="mt-4">
                <h6 class="font-weight-semibold">Étudiants n'ayant pas soumis ({{ $notSubmitted->count() }})</h6>
                <div class="alert alert-warning">
                    @foreach($notSubmitted as $student)
                        <span class="badge badge-warning mr-1">{{ $student->user->name ?? 'N/A' }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
