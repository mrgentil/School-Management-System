@extends('layouts.master')
@section('page_title', 'Gestion des Notes')
@section('content')

    {{-- Menu Rapide --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="{{ route('exam_schedules.index') }}" class="btn btn-primary btn-block">
                <i class="icon-calendar mr-2"></i>Calendrier d'Examens
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('exam_analytics.index') }}" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analytics & Rapports
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('marks.tabulation') }}" class="btn btn-warning btn-block">
                <i class="icon-table2 mr-2"></i>Feuille de Tabulation
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('marks.batch_fix') }}" class="btn btn-info btn-block">
                <i class="icon-wrench mr-2"></i>Correction Batch
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary">
            <h6 class="card-title text-white font-weight-bold"><i class="icon-pencil5 mr-2"></i>Sélectionner l'Examen et la Classe</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.selector')
        </div>
    </div>

    <div class="card">

        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-book mr-2 text-primary"></i>
                        <strong>Matière:</strong> {{ $m->subject->name }}
                    </h6>
                </div>
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-users mr-2 text-success"></i>
                        <strong>Classe:</strong> {{ $m->my_class->name.' '.$m->section->name }}
                    </h6>
                </div>
                <div class="col-md-4">
                    <h6 class="card-title mb-0">
                        <i class="icon-file-text2 mr-2 text-warning"></i>
                        <strong>Examen:</strong> {{ $m->exam->name.' - '.$m->year }}
                    </h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('pages.support_team.marks.edit')
            {{--@include('pages.support_team.marks.random')--}}
        </div>
    </div>

    {{--Marks Manage End--}}

@endsection
