@extends('layouts.master')
@section('page_title', 'Bulletin Non Disponible')

@section('content')

<div class="card">
    <div class="card-header bg-warning">
        <h6 class="card-title">
            <i class="icon-lock mr-2"></i>Bulletin Non Disponible
        </h6>
    </div>

    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="icon-file-locked text-warning" style="font-size: 80px;"></i>
        </div>
        
        <h4 class="text-warning mb-3">Ce bulletin n'est pas encore publié</h4>
        
        <p class="text-muted mb-4">
            Le bulletin de 
            <strong>
                @if($type == 'period')
                    Période {{ $period }}
                @else
                    Semestre {{ $semester }}
                @endif
            </strong>
            n'est pas encore disponible pour consultation.<br>
            Veuillez patienter jusqu'à ce que l'administration le publie.
        </p>

        {{-- Bulletins disponibles --}}
        @if(count($publishedBulletins['periods']) > 0 || count($publishedBulletins['semesters']) > 0)
            <div class="card border-success mx-auto" style="max-width: 500px;">
                <div class="card-header bg-success text-white">
                    <i class="icon-checkmark mr-2"></i>Bulletins Disponibles
                </div>
                <div class="card-body">
                    @if(count($publishedBulletins['periods']) > 0)
                        <h6 class="text-muted mb-2">Périodes :</h6>
                        <div class="mb-3">
                            @foreach($publishedBulletins['periods'] as $p)
                                <a href="{{ route('student.grades.bulletin', ['type' => 'period', 'period' => $p]) }}" 
                                   class="btn btn-outline-primary mr-2 mb-2">
                                    <i class="icon-file-text2 mr-1"></i>Période {{ $p }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(count($publishedBulletins['semesters']) > 0)
                        <h6 class="text-muted mb-2">Semestres :</h6>
                        <div>
                            @foreach($publishedBulletins['semesters'] as $s)
                                <a href="{{ route('student.grades.bulletin', ['type' => 'semester', 'semester' => $s]) }}" 
                                   class="btn btn-outline-success mr-2 mb-2">
                                    <i class="icon-file-text2 mr-1"></i>Semestre {{ $s }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info mx-auto" style="max-width: 500px;">
                <i class="icon-info22 mr-2"></i>
                Aucun bulletin n'est encore publié pour cette année scolaire.
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('student.grades.index') }}" class="btn btn-secondary">
                <i class="icon-arrow-left7 mr-1"></i>Retour à Mes Notes
            </a>
        </div>
    </div>
</div>

{{-- Informations de l'étudiant --}}
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1"><strong>Nom :</strong> {{ $student->name }}</p>
                <p class="mb-0"><strong>Matricule :</strong> {{ $student->code ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Classe :</strong> {{ $studentRecord->my_class->name ?? 'N/A' }}</p>
                <p class="mb-0"><strong>Année :</strong> {{ $year }}</p>
            </div>
        </div>
    </div>
</div>

@endsection
