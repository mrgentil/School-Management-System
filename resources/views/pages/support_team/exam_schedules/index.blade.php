@extends('layouts.master')
@section('page_title', 'Calendrier des Examens')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Calendrier des Examens</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{ route('exam_schedules.calendar') }}" class="btn btn-primary">
                        <i class="icon-calendar"></i> Vue Calendrier
                    </a>
                </div>
            </div>

            <table class="table datatable-button-html5-columns">
                <thead>
                <tr>
                    <th>S/N</th>
                    <th>Examen</th>
                    <th>Année</th>
                    <th>Semestre</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($exams as $ex)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ex->name }}</td>
                        <td>{{ $ex->year }}</td>
                        <td>Semestre {{ $ex->semester }}</td>
                        <td>
                            @if($ex->status == 'published')
                                <span class="badge badge-success">Publié</span>
                            @elseif($ex->status == 'active')
                                <span class="badge badge-primary">Actif</span>
                            @elseif($ex->status == 'grading')
                                <span class="badge badge-warning">Notation</span>
                            @elseif($ex->status == 'archived')
                                <span class="badge badge-secondary">Archivé</span>
                            @else
                                <span class="badge badge-info">Brouillon</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <div class="dropdown">
                                    <a href="#" class="list-icons-item" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-left">
                                        <a href="{{ route('exam_schedules.show', $ex->id) }}" class="dropdown-item">
                                            <i class="icon-calendar"></i> Gérer Horaires
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
