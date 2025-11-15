@extends('layouts.master')
@section('page_title', 'Gestion des Devoirs')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-book mr-2"></i>
            Liste des Devoirs
        </h6>
        <div class="header-elements">
            <a href="{{ route('assignments.create') }}" class="btn btn-light btn-sm">
                <i class="icon-plus2 mr-2"></i>
                Créer un Devoir
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Filtres --}}
        <form method="GET" action="{{ route('assignments.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control">
                            <option value="">Toutes les classes</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}" {{ $filters['my_class_id'] == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section</label>
                        <select name="section_id" id="section_id" class="form-control">
                            <option value="">Toutes</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Matière</label>
                        <select name="subject_id" class="form-control">
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
                        <label class="font-weight-semibold">Période</label>
                        <select name="period" class="form-control">
                            <option value="">Toutes</option>
                            <option value="1" {{ $filters['period'] == 1 ? 'selected' : '' }}>Période 1 (S1)</option>
                            <option value="2" {{ $filters['period'] == 2 ? 'selected' : '' }}>Période 2 (S1)</option>
                            <option value="3" {{ $filters['period'] == 3 ? 'selected' : '' }}>Période 3 (S2)</option>
                            <option value="4" {{ $filters['period'] == 4 ? 'selected' : '' }}>Période 4 (S2)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Statut</label>
                        <select name="status" class="form-control">
                            <option value="">Tous</option>
                            <option value="active" {{ $filters['status'] == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="closed" {{ $filters['status'] == 'closed' ? 'selected' : '' }}>Fermé</option>
                            <option value="draft" {{ $filters['status'] == 'draft' ? 'selected' : '' }}>Brouillon</option>
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
                            <th>Classe</th>
                            <th>Section</th>
                            <th>Matière</th>
                            <th>Période</th>
                            <th>Date Limite</th>
                            <th>Note Max</th>
                            <th>Statut</th>
                            <th>Soumissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            <tr>
                                <td><strong>{{ $assignment->title }}</strong></td>
                                <td>{{ $assignment->myClass->name ?? 'N/A' }}</td>
                                <td>{{ $assignment->section->name ?? 'N/A' }}</td>
                                <td>{{ $assignment->subject->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $periodLabels = [
                                            1 => 'Période 1',
                                            2 => 'Période 2',
                                            3 => 'Période 3',
                                            4 => 'Période 4'
                                        ];
                                        $periodBadges = [
                                            1 => 'badge-primary',
                                            2 => 'badge-info',
                                            3 => 'badge-success',
                                            4 => 'badge-warning'
                                        ];
                                    @endphp
                                    <span class="badge {{ $periodBadges[$assignment->period] ?? 'badge-secondary' }}">
                                        {{ $periodLabels[$assignment->period] ?? 'N/A' }}
                                    </span>
                                    <small class="d-block text-muted mt-1">
                                        {{ $assignment->period <= 2 ? 'Semestre 1' : 'Semestre 2' }}
                                    </small>
                                </td>
                                <td>
                                    {{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'N/A' }}
                                    @if($assignment->due_date && $assignment->due_date->isPast())
                                        <span class="badge badge-danger ml-2">Expiré</span>
                                    @endif
                                </td>
                                <td>{{ $assignment->max_score }}</td>
                                <td>
                                    @if($assignment->status == 'active')
                                        <span class="badge badge-success">Actif</span>
                                    @elseif($assignment->status == 'closed')
                                        <span class="badge badge-danger">Fermé</span>
                                    @else
                                        <span class="badge badge-secondary">Brouillon</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $assignment->submissions->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('assignments.show', $assignment->id) }}" class="btn btn-sm btn-primary" title="Voir">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="{{ route('assignments.edit', $assignment->id) }}" class="btn btn-sm btn-info" title="Modifier">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        @if(Qs::userIsTeamSA())
                                            <form method="POST" action="{{ route('assignments.destroy', $assignment->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')" title="Supprimer">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de {{ $assignments->firstItem() ?? 0 }} à {{ $assignments->lastItem() ?? 0 }} sur {{ $assignments->total() }} résultats
                </div>
                <div>
                    {{ $assignments->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun devoir trouvé. Cliquez sur "Créer un Devoir" pour commencer ou modifiez les filtres.
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
                url: '/assignments/get-sections/' + classId,
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
    
    // Trigger on page load if class is selected
    @if($filters['my_class_id'])
        $('#my_class_id').trigger('change');
        @if($filters['section_id'])
            setTimeout(function() {
                $('#section_id').val('{{ $filters["section_id"] }}');
            }, 500);
        @endif
    @endif
});
</script>
@endsection
