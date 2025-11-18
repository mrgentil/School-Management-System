@extends('layouts.master')
@section('page_title', 'Analyses et Rapports')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title">
                <i class="icon-stats-dots mr-2"></i> Analyses et Rapports d'Examens
            </h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h5>Sélectionner un Examen pour l'Analyse</h5>
                </div>
            </div>

            <div class="row">
                @forelse($exams as $exam)
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-3 border-left-primary">
                            <div class="card-body">
                                <h6 class="font-weight-bold">{{ $exam->name }}</h6>
                                <p class="text-muted mb-2">
                                    <i class="icon-calendar mr-1"></i> {{ $exam->year }} - Semestre {{ $exam->semester }}
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('exam_analytics.overview', $exam->id) }}" class="btn btn-primary btn-sm">
                                        <i class="icon-stats-dots"></i> Voir l'Analyse
                                    </a>
                                    <a href="{{ route('exam_publication.show', $exam->id) }}" class="btn btn-info btn-sm ml-2">
                                        <i class="icon-eye"></i> Publication
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            Aucun examen disponible pour l'année en cours.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Analyses par Classe --}}
            <div class="card mt-4">
                <div class="card-header bg-success-400">
                    <h6 class="card-title">
                        <i class="icon-library2 mr-2"></i> Analyses par Classe
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($my_classes as $class)
                            <div class="col-md-3 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h6>{{ $class->name }}</h6>
                                        <select class="form-control select-search" onchange="if(this.value) window.location.href=this.value">
                                            <option value="">Sélectionner examen...</option>
                                            @foreach($exams as $exam)
                                                <option value="{{ route('exam_analytics.class_analysis', [$exam->id, $class->id]) }}">
                                                    {{ $exam->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
