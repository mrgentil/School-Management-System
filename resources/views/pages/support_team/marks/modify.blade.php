@extends('layouts.master')
@section('page_title', 'Modifier les Notes')
@section('content')

<div class="card">
    <div class="card-header bg-warning">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">
                <i class="icon-pencil mr-2"></i>
                Modifier les Notes - <strong>{{ $subject->name }}</strong> | 
                {{ $class->full_name ?: $class->name }}
                @if($class->section && $class->section->count() > 0)
                    ({{ $class->section->first()->name }})
                @endif
                @if($period != 'all')
                    | <span class="badge badge-dark">Période {{ $period }}</span>
                @else
                    | <span class="badge badge-info">Toutes les périodes</span>
                @endif
            </h5>
            <a href="{{ route('marks.index') }}" class="btn btn-dark btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('flash_success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-checkmark3 mr-2"></i>{{ session('flash_success') }}
            </div>
        @endif
        
        @if($marks->isEmpty())
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucun étudiant trouvé dans cette classe pour l'année en cours.
            </div>
        @else
            <div class="alert alert-success border-0 mb-3">
                <i class="icon-users mr-2"></i>
                <strong>{{ $marks->count() }} étudiant(s)</strong> dans cette classe
            </div>
            <form method="POST" action="{{ route('marks.modify_update') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="period" value="{{ $period }}">
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="25%">Nom de l'Étudiant</th>
                                @if($period == 'all' || $period == '1')
                                    <th width="10%" class="text-center bg-primary">P1</th>
                                @endif
                                @if($period == 'all' || $period == '2')
                                    <th width="10%" class="text-center bg-primary">P2</th>
                                @endif
                                @if($period == 'all' || $period == '3')
                                    <th width="10%" class="text-center bg-primary">P3</th>
                                @endif
                                @if($period == 'all' || $period == '4')
                                    <th width="10%" class="text-center bg-primary">P4</th>
                                @endif
                                @if($period == 'all')
                                    <th width="10%" class="text-center bg-success">TCA</th>
                                    <th width="10%" class="text-center bg-info">Exam S1</th>
                                    <th width="10%" class="text-center bg-info">Exam S2</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($marks as $mark)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $mark->user->name ?? 'N/A' }}</strong>
                                    <br><small class="text-muted">{{ $mark->user->student_record->adm_no ?? '' }}</small>
                                </td>
                                
                                @if($period == 'all' || $period == '1')
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][t1]" 
                                           value="{{ $mark->t1 }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                @endif
                                
                                @if($period == 'all' || $period == '2')
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][t2]" 
                                           value="{{ $mark->t2 }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                @endif
                                
                                @if($period == 'all' || $period == '3')
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][t3]" 
                                           value="{{ $mark->t3 }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                @endif
                                
                                @if($period == 'all' || $period == '4')
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][t4]" 
                                           value="{{ $mark->t4 }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                @endif
                                
                                @if($period == 'all')
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][tca]" 
                                           value="{{ $mark->tca }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][s1_exam]" 
                                           value="{{ $mark->s1_exam }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                <td>
                                    <input type="number" step="0.25" min="0" max="100" 
                                           name="marks[{{ $mark->id }}][s2_exam]" 
                                           value="{{ $mark->s2_exam }}" 
                                           class="form-control form-control-sm text-center"
                                           placeholder="--">
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="icon-checkmark3 mr-2"></i>Enregistrer les Modifications
                    </button>
                    <a href="{{ route('marks.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="icon-cross2 mr-2"></i>Annuler
                    </a>
                </div>
            </form>
        @endif
    </div>
</div>

@endsection
