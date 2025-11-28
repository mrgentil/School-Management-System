@extends('layouts.master')
@section('page_title', 'Liste des Étudiants - Bulletins')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h5 class="card-title text-white">
            <i class="icon-users mr-2"></i> 
            Bulletins - {{ $type == 'period' ? 'Période ' . $period : 'Semestre ' . $semester }} ({{ $year }})
        </h5>
        <div class="header-elements">
            <a href="{{ route('bulletins.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left5 mr-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($students->count() > 0)
            {{-- Actions en lot --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge badge-info badge-lg">{{ $students->count() }} étudiants</span>
                </div>
                <div>
                    <form action="{{ route('bulletins.batch') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="my_class_id" value="{{ $class_id }}">
                        <input type="hidden" name="section_id" value="{{ $section_id }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="period" value="{{ $period }}">
                        <input type="hidden" name="semester" value="{{ $semester }}">
                        <button type="submit" class="btn btn-success">
                            <i class="icon-file-zip mr-2"></i> Télécharger tous les bulletins (ZIP)
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable-basic">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Photo</th>
                            <th>Nom Complet</th>
                            <th>Matricule</th>
                            <th>Classe</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-center">
                                    <img src="{{ $student->user->photo ?? asset('global_assets/images/user.png') }}" 
                                         width="40" height="40" class="rounded-circle" 
                                         alt="{{ $student->user->name }}"
                                         onerror="this.src='{{ asset('global_assets/images/user.png') }}'">
                                </td>
                                <td>
                                    <strong>{{ $student->user->name }}</strong>
                                </td>
                                <td>{{ $student->adm_no }}</td>
                                <td>{{ $student->my_class->full_name ?? $student->my_class->name }}</td>
                                <td>
                                    {{-- Prévisualiser --}}
                                    <a href="{{ route('bulletins.preview', $student->user_id) }}?type={{ $type }}&period={{ $period }}&semester={{ $semester }}" 
                                       class="btn btn-info btn-sm" title="Prévisualiser" target="_blank">
                                        <i class="icon-eye"></i>
                                    </a>
                                    
                                    {{-- Télécharger PDF --}}
                                    <a href="{{ route('bulletins.pdf', $student->user_id) }}?type={{ $type }}&period={{ $period }}&semester={{ $semester }}" 
                                       class="btn btn-primary btn-sm" title="Télécharger PDF">
                                        <i class="icon-file-pdf"></i>
                                    </a>
                                    
                                    {{-- Imprimer --}}
                                    <a href="{{ route('bulletins.preview', $student->user_id) }}?type={{ $type }}&period={{ $period }}&semester={{ $semester }}&print=1" 
                                       class="btn btn-secondary btn-sm" title="Imprimer" target="_blank">
                                        <i class="icon-printer"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                Aucun étudiant trouvé dans cette classe.
            </div>
        @endif
    </div>
</div>

@endsection
