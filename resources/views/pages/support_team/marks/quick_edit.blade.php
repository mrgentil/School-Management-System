@extends('layouts.master')
@section('page_title', 'Modification Rapide des Notes')
@section('content')

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="icon-pencil5 mr-2"></i>Modification Rapide des Notes</h5>
    </div>
    <div class="card-body">
        
        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif
        
        @if(session('flash_danger'))
            <div class="alert alert-danger">{{ session('flash_danger') }}</div>
        @endif

        <div class="alert alert-info">
            <i class="icon-info22 mr-2"></i>
            <strong>Instructions :</strong> Sélectionnez la classe et la matière pour afficher et modifier les notes de tous les étudiants.
        </div>

        <form method="GET" action="{{ route('marks.quick_edit') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Classe</label>
                        <select name="class_id" id="class_id" class="form-control select" required onchange="this.form.submit()">
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                @if(request('class_id') && isset($subjects))
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Matière</label>
                        <select name="subject_id" id="subject_id" class="form-control select" required onchange="this.form.submit()">
                            <option value="">-- Sélectionner une matière --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                
                <div class="col-md-4 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search4 mr-2"></i>Afficher les Notes
                    </button>
                </div>
            </div>
        </form>

        @if(isset($marks) && $marks->count() > 0)
        <div class="card border-primary">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="icon-book mr-2"></i>
                    <strong>{{ $selectedSubject->name }}</strong> - 
                    {{ $selectedClass->name }}
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('marks.quick_update') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Étudiant</th>
                                    <th width="10%">P1 (/20)</th>
                                    <th width="10%">P2 (/20)</th>
                                    <th width="10%">P3 (/20)</th>
                                    <th width="10%">P4 (/20)</th>
                                    <th width="10%">TCA (/20)</th>
                                    <th width="10%">Examen S1</th>
                                    <th width="10%">Examen S2</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marks as $mark)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $mark->user->name ?? 'N/A' }}</strong></td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[{{ $mark->id }}][t1]" 
                                               value="{{ $mark->t1 }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[{{ $mark->id }}][t2]" 
                                               value="{{ $mark->t2 }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[{{ $mark->id }}][t3]" 
                                               value="{{ $mark->t3 }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[{{ $mark->id }}][t4]" 
                                               value="{{ $mark->t4 }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20" 
                                               name="marks[{{ $mark->id }}][tca]" 
                                               value="{{ $mark->tca }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="100" 
                                               name="marks[{{ $mark->id }}][s1_exam]" 
                                               value="{{ $mark->s1_exam }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="100" 
                                               name="marks[{{ $mark->id }}][s2_exam]" 
                                               value="{{ $mark->s2_exam }}" 
                                               class="form-control form-control-sm text-center">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="icon-checkmark3 mr-2"></i>Enregistrer les Modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @elseif(request('class_id') && request('subject_id'))
        <div class="alert alert-warning">
            <i class="icon-warning22 mr-2"></i>
            Aucune note trouvée pour cette classe et cette matière. Les notes seront créées automatiquement quand vous les saisirez.
        </div>
        @endif

    </div>
</div>

@endsection
