@extends('layouts.master')
@section('page_title', 'Mes Devoirs')

@section('content')

{{-- Statistiques --}}
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-book icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $total_assignments }}</h3>
                    <span class="text-uppercase font-size-xs">Total Devoirs</span>
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
                    <h3 class="mb-0">{{ $submitted_count }}</h3>
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
                    <h3 class="mb-0">{{ $pending_count }}</h3>
                    <span class="text-uppercase font-size-xs">En attente</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
                <div class="media-body text-right">
                    <h3 class="mb-0">{{ $overdue_count }}</h3>
                    <span class="text-uppercase font-size-xs">En retard</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Devoirs</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        {{-- Filtres --}}
        <form method="GET" action="{{ route('student.assignments.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Toutes les matières</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selected_subject == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="pending" {{ $selected_status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="submitted" {{ $selected_status == 'submitted' ? 'selected' : '' }}>Soumis</option>
                            <option value="overdue" {{ $selected_status == 'overdue' ? 'selected' : '' }}>En retard</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4 mr-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if($assignments->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Enseignant</th>
                            <th>Date Limite</th>
                            <th>Note Max</th>
                            <th>Statut</th>
                            <th>Ma Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            @php
                                $submission = $assignment->submissions->first();
                                $isOverdue = $assignment->due_date && $assignment->due_date->isPast();
                                
                                if ($submission) {
                                    if ($submission->score !== null) {
                                        $status = 'Noté';
                                        $statusClass = 'badge-success';
                                    } else {
                                        $status = $submission->status == 'late' ? 'Soumis en retard' : 'Soumis';
                                        $statusClass = $submission->status == 'late' ? 'badge-warning' : 'badge-info';
                                    }
                                } else {
                                    $status = $isOverdue ? 'En retard' : 'Non soumis';
                                    $statusClass = $isOverdue ? 'badge-danger' : 'badge-secondary';
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $assignment->title }}</strong></td>
                                <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                <td>{{ $assignment->teacher->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A' }}
                                    @if($isOverdue && !$submission)
                                        <br><small class="text-danger">Expiré</small>
                                    @endif
                                </td>
                                <td>{{ $assignment->max_score }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                                <td>
                                    @if($submission && $submission->score !== null)
                                        <strong class="text-success">{{ $submission->score }}/{{ $assignment->max_score }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.assignments.show', $assignment->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="icon-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $assignments->appends(request()->query())->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun devoir trouvé. Modifiez les filtres pour voir plus de devoirs.
            </div>
        @endif
    </div>
</div>
@endsection
