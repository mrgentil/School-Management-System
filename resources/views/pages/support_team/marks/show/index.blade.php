@extends('layouts.master')
@section('page_title', 'Relevé de Notes')
@section('content')

    {{-- En-tête amélioré --}}
    <div class="card bg-primary text-white">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">📄 Relevé de Notes - {{ $sr->user->name }}</h4>
                    <p class="mb-0">{{ $my_class->full_name ?: $my_class->name }} | Année Scolaire : {{ $year }}</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('marks.bulk') }}" class="btn btn-light btn-sm mr-2">
                        <i class="icon-arrow-left5 mr-1"></i> Retour
                    </a>
                    <a href="{{ route('exam_analytics.student_progress', $student_id) }}" class="btn btn-light btn-sm">
                        <i class="icon-graph mr-1"></i> Progression
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques Étudiant --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card border-left-3 border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Moyenne Générale</h6>
                            <h3 class="mb-0 text-success">{{ $exam_records->avg('ave') ? round($exam_records->avg('ave'), 1) : 'N/A' }}%</h3>
                        </div>
                        <i class="icon-trophy icon-3x text-success-400"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-3 border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Meilleur Rang</h6>
                            <h3 class="mb-0 text-primary">{{ $exam_records->min('pos') ?? 'N/A' }}{{ $exam_records->min('pos') == 1 ? 'er' : 'ème' }}</h3>
                        </div>
                        <i class="icon-medal icon-3x text-primary-400"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-3 border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-semibold mb-0">Évaluations</h6>
                            <h3 class="mb-0 text-warning">{{ $exam_records->count() }}</h3>
                        </div>
                        <i class="icon-file-text2 icon-3x text-warning-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($exams as $ex)
        @foreach($exam_records->where('exam_id', $ex->id) as $exr)

                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h6 class="font-weight-bold">{{ $ex->name.' - '.$ex->year }}</h6>
                        {!! Qs::getPanelOptions() !!}
                    </div>

                    <div class="card-body collapse">

                        {{--Sheet Table--}}
                        @include('pages.support_team.marks.show.sheet')

                        {{--Print Button--}}
                        <div class="text-center mt-3">
                            <a target="_blank" href="{{ route('marks.print', [Qs::hash($student_id), $ex->id, $year]) }}" class="btn btn-secondary btn-lg">
                                <i class="icon-printer mr-2"></i> Imprimer le Relevé
                            </a>
                        </div>

                    </div>

                </div>

            {{--    EXAM COMMENTS   --}}
            @include('pages.support_team.marks.show.comments')

            {{-- SKILL RATING --}}
            @include('pages.support_team.marks.show.skills')

        @endforeach
    @endforeach

@endsection
