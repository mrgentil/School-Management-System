@extends('layouts.master')
@section('page_title', 'Professeur: ' . $teacher->name)

@section('content')
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ Qs::getUserPhoto($teacher->photo) }}" 
                     class="rounded-circle mr-3" 
                     style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h4 class="mb-0">{{ $teacher->name }}</h4>
                    <small class="opacity-75">{{ $teacher->email }} | {{ $teacher->code }}</small>
                </div>
            </div>
            <div>
                <a href="{{ route('teachers.management.edit', $teacher->id) }}" class="btn btn-light">
                    <i class="icon-pencil mr-1"></i> Modifier Attributions
                </a>
                <a href="{{ route('teachers.management.index') }}" class="btn btn-outline-light">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Infos générales --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-user mr-2"></i> Informations</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Téléphone:</strong></td>
                        <td>{{ $teacher->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Adresse:</strong></td>
                        <td>{{ $teacher->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Titulaire de:</strong></td>
                        <td>
                            @if($titularClass)
                                <span class="badge badge-success">{{ $titularClass->name }}</span>
                            @else
                                <span class="text-muted">Aucune classe</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Stats --}}
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Statistiques</h6>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-6">
                        <h3 class="text-primary mb-0">{{ $teachingClasses->count() }}</h3>
                        <small class="text-muted">Classes</small>
                    </div>
                    <div class="col-6">
                        <h3 class="text-success mb-0">{{ $teacher->subjects->count() }}</h3>
                        <small class="text-muted">Matières</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Classes et Matières --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h6 class="card-title mb-0"><i class="icon-library mr-2"></i> Classes et Matières Attribuées</h6>
            </div>
            <div class="card-body">
                @if($teacher->subjects->count() > 0)
                    @php
                        $subjectsByClass = $teacher->subjects->groupBy('my_class_id');
                    @endphp
                    
                    @foreach($subjectsByClass as $classId => $subjects)
                        @php $class = $subjects->first()->my_class; @endphp
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">
                                <i class="icon-graduation mr-1"></i> 
                                <strong>{{ $class->name ?? 'Classe inconnue' }}</strong>
                                <span class="badge badge-info ml-2">{{ $subjects->count() }} matière(s)</span>
                            </h6>
                            <div class="row">
                                @foreach($subjects as $subject)
                                    <div class="col-md-4 mb-2">
                                        <div class="d-flex align-items-center p-2 bg-light rounded">
                                            <i class="icon-book text-primary mr-2"></i>
                                            <span>{{ $subject->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="icon-warning d-block mb-2" style="font-size: 48px;"></i>
                        <h5>Aucune matière attribuée</h5>
                        <p>Ce professeur n'a pas encore de classes/matières assignées.</p>
                        <a href="{{ route('teachers.management.edit', $teacher->id) }}" class="btn btn-primary">
                            <i class="icon-plus3 mr-1"></i> Attribuer des matières
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
