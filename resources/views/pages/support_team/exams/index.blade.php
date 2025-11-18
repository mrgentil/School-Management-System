@extends('layouts.master')
@section('page_title', 'Gestion des Examens')
@section('content')

    {{-- Alerte Nouvelles Fonctionnalités --}}
    <div class="alert alert-info border-0 alert-dismissible">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <div class="d-flex align-items-center">
            <i class="icon-info22 mr-3 icon-2x"></i>
            <div>
                <strong>Nouvelles fonctionnalités disponibles!</strong>
                <p class="mb-0">Accédez au <a href="{{ route('exams.dashboard') }}" class="alert-link font-weight-bold">Tableau de Bord</a> pour gérer les calendriers, publications et analytics.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline bg-primary">
            <h6 class="card-title text-white"><i class="icon-graduation mr-2"></i>Gestion des Examens</h6>
            <div class="header-elements">
                <a href="{{ route('exams.dashboard') }}" class="btn btn-light btn-sm">
                    <i class="icon-grid mr-2"></i>Tableau de Bord
                </a>
                {!! Qs::getPanelOptions() !!}
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-exams" class="nav-link active" data-toggle="tab"><i class="icon-list mr-2"></i>Liste des Examens</a></li>
                <li class="nav-item"><a href="#new-exam" class="nav-link" data-toggle="tab"><i class="icon-plus2 mr-2"></i>Créer un Examen</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-exams">
                        <table class="table datatable-button-html5-columns">
                            <thead class="bg-light">
                            <tr>
                                <th>N°</th>
                                <th>Nom de l'Examen</th>
                                <th>Semestre</th>
                                <th>Année Scolaire</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($exams as $ex)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $ex->name }}</strong></td>
                                    <td><span class="badge badge-{{ $ex->semester == 1 ? 'primary' : 'success' }}">Semestre {{ $ex->semester }}</span></td>
                                    <td>{{ $ex->year }}</td>
                                    <td>
                                        @if($ex->results_published)
                                            <span class="badge badge-success"><i class="icon-checkmark3 mr-1"></i>Publié</span>
                                        @else
                                            <span class="badge badge-secondary"><i class="icon-lock mr-1"></i>Non publié</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    {{--Calendrier--}}
                                                    <a href="{{ route('exam_schedules.show', $ex->id) }}" class="dropdown-item">
                                                        <i class="icon-calendar text-primary"></i> Calendrier d'Examen
                                                    </a>
                                                    {{--Analytics--}}
                                                    <a href="{{ route('exam_analytics.overview', $ex->id) }}" class="dropdown-item">
                                                        <i class="icon-stats-dots text-success"></i> Analyses & Rapports
                                                    </a>
                                                    {{--Publication--}}
                                                    <a href="{{ route('exam_publication.show', $ex->id) }}" class="dropdown-item">
                                                        <i class="icon-eye text-warning"></i> Gérer la Publication
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    @if(Qs::userIsTeamSA())
                                                    {{--Edit--}}
                                                    <a href="{{ route('exams.edit', $ex->id) }}" class="dropdown-item"><i class="icon-pencil text-info"></i> Modifier</a>
                                                   @endif
                                                    @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{ $ex->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash text-danger"></i> Supprimer</a>
                                                    <form method="post" id="item-delete-{{ $ex->id }}" action="{{ route('exams.destroy', $ex->id) }}" class="hidden">@csrf @method('delete')</form>
                                                        @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                <div class="tab-pane fade" id="new-exam">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                <i class="icon-info22 mr-2"></i>
                                <span>Vous créez un examen pour l'année scolaire <strong>{{ Qs::getSetting('current_session') }}</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <form method="post" action="{{ route('exams.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Nom de l'Examen <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text" class="form-control" placeholder="Ex: Examen Final, Examen Blanc, etc.">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="semester" class="col-lg-3 col-form-label font-weight-semibold">Semestre <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <select data-placeholder="Sélectionner le semestre" class="form-control select-search" name="semester" id="semester" required>
                                            <option value="">-- Choisir un semestre --</option>
                                            <option {{ old('semester') == 1 ? 'selected' : '' }} value="1">Semestre 1 (Périodes 1 & 2)</option>
                                            <option {{ old('semester') == 2 ? 'selected' : '' }} value="2">Semestre 2 (Périodes 3 & 4)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="icon-checkmark3 mr-2"></i>Créer l'Examen</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}

@endsection
