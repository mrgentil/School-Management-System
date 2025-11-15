@extends('layouts.master')
@section('page_title', 'Consulter les Présences')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-info">
        <h6 class="card-title text-white">
            <i class="icon-eye mr-2"></i>
            Consulter les Présences
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('attendance.view') }}" id="filter-form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control select">
                            <option value="">Toutes les classes</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}" {{ $filters['class_id'] == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section</label>
                        <select name="section_id" id="section_id" class="form-control select">
                            <option value="">Toutes</option>
                            @if(isset($sections))
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $filters['section_id'] == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control select">
                            <option value="">Toutes</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $filters['subject_id'] == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date début</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Date fin</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if(isset($attendances) && $attendances->count() > 0)
            <div class="mb-3">
                <a href="{{ route('attendance.export', request()->query()) }}" class="btn btn-success">
                    <i class="icon-file-excel mr-2"></i>
                    Exporter vers Excel ({{ $attendances->total() }} résultats)
                </a>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Classe</th>
                            <th>Section</th>
                            <th>Matière</th>
                            <th>Statut</th>
                            <th>Notes</th>
                            <th>Enregistré par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    <strong>{{ $attendance->student->name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $attendance->student->student_record->adm_no ?? '' }}</small>
                                </td>
                                <td>{{ $attendance->class->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->section->name ?? '-' }}</td>
                                <td>{{ $attendance->subject->name ?? '-' }}</td>
                                <td>
                                    @if($attendance->status == 'present')
                                        <span class="badge badge-success">Présent</span>
                                    @elseif($attendance->status == 'absent')
                                        <span class="badge badge-danger">Absent</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge badge-warning">Retard</span>
                                    @elseif($attendance->status == 'excused')
                                        <span class="badge badge-info">Excusé</span>
                                    @elseif($attendance->status == 'late_justified')
                                        <span class="badge badge-warning">Retard Justifié</span>
                                    @elseif($attendance->status == 'absent_justified')
                                        <span class="badge badge-info">Absent Justifié</span>
                                    @endif
                                </td>
                                <td>{{ $attendance->notes ?? '-' }}</td>
                                <td>{{ $attendance->takenBy->name ?? 'N/A' }}</td>
                                <td>
                                    @if(Qs::userIsTeamSA())
                                        <form method="POST" action="{{ route('attendance.destroy', $attendance->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de {{ $attendances->firstItem() }} à {{ $attendances->lastItem() }} sur {{ $attendances->total() }} résultats
                </div>
                <div>
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="alert alert-info mt-3">
                <i class="icon-info22 mr-2"></i>
                Aucune présence trouvée. Utilisez les filtres ci-dessus pour rechercher.
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load sections when class is selected
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        $('#section_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            $.ajax({
                url: '/attendance/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                },
                error: function() {
                    $('#section_id').html('<option value="">Erreur</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Toutes</option>');
        }
    });
});
</script>
@endsection
