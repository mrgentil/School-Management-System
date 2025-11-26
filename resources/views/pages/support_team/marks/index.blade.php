@extends('layouts.master')
@section('page_title', 'Saisie des Notes')
@section('content')

    {{-- Menu Rapide --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="{{ route('marks.tabulation') }}" class="card bg-warning text-white">
                <div class="card-body text-center py-2">
                    <i class="icon-table2 icon-2x mb-1"></i>
                    <h6 class="mb-0">Feuille de Tabulation</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('exam_analytics.index') }}" class="card bg-success text-white">
                <div class="card-body text-center py-2">
                    <i class="icon-stats-dots icon-2x mb-1"></i>
                    <h6 class="mb-0">Analytics & Rapports</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('exams.dashboard') }}" class="card bg-primary text-white">
                <div class="card-body text-center py-2">
                    <i class="icon-grid icon-2x mb-1"></i>
                    <h6 class="mb-0">Dashboard Examens</h6>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('marks.batch_fix') }}" class="card bg-info text-white">
                <div class="card-body text-center py-2">
                    <i class="icon-wrench icon-2x mb-1"></i>
                    <h6 class="mb-0">Correction Batch</h6>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark">
            <ul class="nav nav-tabs nav-tabs-highlight card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-white" data-toggle="tab" href="#tab-nouvelle-saisie">
                        <i class="icon-pencil5 mr-2"></i>Nouvelle Saisie
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-toggle="tab" href="#tab-modifier-notes">
                        <i class="icon-pencil mr-2"></i>Modifier Notes Existantes
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                {{-- Onglet 1: Nouvelle Saisie --}}
                <div class="tab-pane fade show active" id="tab-nouvelle-saisie">
                    <div class="alert alert-info border-0 mb-3">
                        <i class="icon-info22 mr-2"></i>
                        <strong>Nouvelle Saisie :</strong> Utilisez ce formulaire pour saisir de nouvelles notes (devoirs, interrogations, examens).
                    </div>
                    @include('pages.support_team.marks.selector')
                </div>
                
                {{-- Onglet 2: Modifier Notes Existantes --}}
                <div class="tab-pane fade" id="tab-modifier-notes">
                    <div class="alert alert-warning border-0 mb-3">
                        <i class="icon-pencil mr-2"></i>
                        <strong>Modification :</strong> Sélectionnez la classe, la matière et la période pour modifier les notes existantes.
                    </div>
                    @include('pages.support_team.marks.modify_selector')
                </div>
            </div>
        </div>
    </div>

    <style>
    .card a.card {
        text-decoration: none;
        transition: transform 0.2s;
    }
    .card a.card:hover {
        transform: translateY(-5px);
    }
    .nav-tabs-highlight .nav-link.active {
        background-color: #2196F3 !important;
        border-color: #2196F3 !important;
    }
    .nav-tabs-highlight .nav-link:not(.active):hover {
        background-color: rgba(255,255,255,0.1);
    }
    </style>

@endsection
