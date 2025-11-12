@extends('layouts.master')
@section('page_title', 'Mes Devoirs')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Devoirs</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @if($assignments->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Matière</th>
                            <th>Date de remise</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $key => $assignment)
                            @php
                                $submission = $assignment->submissions->first();
                                $status = $submission 
                                    ? ($submission->marks !== null ? 'Noté' : 'Soumis')
                                    : 'Non soumis';
                                $statusClass = $submission 
                                    ? ($submission->marks !== null ? 'badge-success' : 'badge-info')
                                    : 'badge-warning';
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $assignment->title }}</td>
                                <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                <td>{{ $assignment->due_date->format('d/m/Y H:i') }}</td>
                                <td><span class="badge {{ $statusClass }}">{{ $status }}</span></td>
                                <td class="text-center">
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
                {{ $assignments->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="icon-book3 icon-3x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun devoir trouvé</h5>
                <p class="text-muted">Aucun devoir n'a été assigné pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips
        $('[data-popup="tooltip"]').tooltip();
    });
</script>
@endpush
