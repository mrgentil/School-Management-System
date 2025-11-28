@extends('layouts.master')
@section('page_title', 'Progression des Élèves')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-stats-growth mr-2"></i> Rapport de Progression
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('student_progress.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Classe</strong></label>
                        <select name="class_id" class="form-control select" id="class_select" required>
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name ?? $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Section (optionnel)</strong></label>
                        <select name="section_id" class="form-control select" id="section_select">
                            <option value="">-- Toutes les sections --</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4 mr-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($students->count() > 0)
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0">
            <i class="icon-users mr-2"></i> Élèves trouvés ({{ $students->count() }})
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Photo</th>
                    <th>Nom</th>
                    <th>Classe</th>
                    <th>Section</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>
                            <img src="{{ $student->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                                 class="rounded-circle" width="40" height="40">
                        </td>
                        <td>
                            <strong>{{ $student->user->name }}</strong>
                            <br><small class="text-muted">{{ $student->adm_no }}</small>
                        </td>
                        <td>{{ $student->my_class->full_name ?? $student->my_class->name ?? 'N/A' }}</td>
                        <td>{{ $student->section->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('student_progress.show', $student->user_id) }}" 
                               class="btn btn-info btn-sm">
                                <i class="icon-stats-growth mr-1"></i> Voir Progression
                            </a>
                            <a href="{{ route('student_progress.pdf', $student->user_id) }}" 
                               class="btn btn-danger btn-sm" target="_blank">
                                <i class="icon-file-pdf mr-1"></i> PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif(request('class_id'))
<div class="alert alert-warning">
    <i class="icon-warning mr-2"></i>
    Aucun élève trouvé pour cette classe.
</div>
@endif
@endsection

@section('scripts')
<script>
    const classes = @json($classes);
    
    document.getElementById('class_select').addEventListener('change', function() {
        const classId = this.value;
        const sectionSelect = document.getElementById('section_select');
        sectionSelect.innerHTML = '<option value="">-- Toutes les sections --</option>';
        
        if (classId) {
            const selectedClass = classes.find(c => c.id == classId);
            if (selectedClass && selectedClass.section) {
                selectedClass.section.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            }
        }
    });

    // Trigger on page load
    document.getElementById('class_select').dispatchEvent(new Event('change'));
</script>
@endsection
