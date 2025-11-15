@extends('layouts.master')
@section('page_title', 'Mes Notes par Période')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-certificate mr-2"></i>
            Mes Notes - {{ $year }}
        </h6>
        <div class="header-elements">
            <a href="{{ route('student.grades.bulletin') }}" class="btn btn-light btn-sm">
                <i class="icon-file-text2 mr-2"></i>Voir le Bulletin Complet
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Sélecteur de période --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="btn-group btn-group-lg w-100" role="group">
                    @for($i = 1; $i <= 4; $i++)
                        @php
                            $badgeColors = [1 => 'primary', 2 => 'info', 3 => 'success', 4 => 'warning'];
                            $isActive = $selectedPeriod == $i;
                        @endphp
                        <a href="{{ route('student.grades.index', ['period' => $i]) }}" 
                           class="btn btn-{{ $isActive ? $badgeColors[$i] : 'light' }} {{ $isActive ? 'active' : '' }}">
                            <i class="icon-calendar mr-2"></i>
                            Période {{ $i }}
                            <small class="d-block">{{ $i <= 2 ? 'Semestre 1' : 'Semestre 2' }}</small>
                        </a>
                    @endfor
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0">
            <i class="icon-info22 mr-2"></i>
            <strong>Période {{ $selectedPeriod }}</strong> - 
            {{ $selectedPeriod <= 2 ? 'Semestre 1' : 'Semestre 2' }}
        </div>

        @if(count($gradesData) > 0)
            @foreach($gradesData as $data)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-0">
                                    <i class="icon-book mr-2"></i>
                                    <strong>{{ $data['subject']->name }}</strong>
                                </h6>
                            </div>
                            <div class="col-md-6 text-right">
                                @if($data['graded_count'] > 0)
                                    <span class="badge badge-primary badge-pill">
                                        Total: {{ $data['total_score'] }}/{{ $data['total_max_score'] }}
                                        ({{ $data['total_on_twenty'] }}/20)
                                    </span>
                                    @if($data['period_average'])
                                        <span class="badge badge-success badge-pill ml-2">
                                            Moyenne P{{ $selectedPeriod }}: {{ $data['period_average'] }}/20
                                        </span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary badge-pill">Aucune note</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(count($data['submissions']) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 35%">Devoir</th>
                                            <th style="width: 15%">Date Limite</th>
                                            <th style="width: 15%">Ma Note</th>
                                            <th style="width: 15%">Sur 20</th>
                                            <th style="width: 15%">Pourcentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['submissions'] as $index => $sub)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $sub['assignment']->title }}</strong>
                                                    @if($sub['submission'])
                                                        <br><small class="text-success">
                                                            <i class="icon-checkmark-circle"></i> Soumis
                                                        </small>
                                                    @else
                                                        <br><small class="text-muted">
                                                            <i class="icon-cross-circle2"></i> Non noté
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $sub['assignment']->due_date ? $sub['assignment']->due_date->format('d/m/Y') : 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @if($sub['submission'])
                                                        <strong class="text-success">
                                                            {{ $sub['score'] }}/{{ $sub['max_score'] }}
                                                        </strong>
                                                    @else
                                                        <span class="text-muted">-/-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sub['submission'])
                                                        <strong class="text-primary">
                                                            {{ $sub['on_twenty'] }}/20
                                                        </strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sub['submission'])
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-{{ $sub['percentage'] >= 60 ? 'success' : ($sub['percentage'] >= 50 ? 'warning' : 'danger') }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $sub['percentage'] }}%"
                                                                 aria-valuenow="{{ $sub['percentage'] }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                                {{ $sub['percentage'] }}%
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if($data['graded_count'] > 0)
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="3" class="text-right">TOTAL</td>
                                                <td>
                                                    <strong class="text-success">
                                                        {{ $data['total_score'] }}/{{ $data['total_max_score'] }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-primary">
                                                        {{ $data['total_on_twenty'] }}/20
                                                    </strong>
                                                </td>
                                                <td>
                                                    <strong class="text-info">
                                                        {{ $data['total_percentage'] }}%
                                                    </strong>
                                                </td>
                                            </tr>
                                            @if($data['period_average'])
                                                <tr class="table-success">
                                                    <td colspan="3" class="text-right">MOYENNE PÉRIODE {{ $selectedPeriod }}</td>
                                                    <td colspan="3">
                                                        <strong class="text-success h6">
                                                            {{ $data['period_average'] }}/20
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="icon-warning mr-2"></i>
                                Aucun devoir pour cette période.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune note disponible pour la Période {{ $selectedPeriod }}.
            </div>
        @endif
    </div>
</div>

<div class="card mt-3">
    <div class="card-header bg-secondary text-white">
        <h6 class="mb-0">
            <i class="icon-info22 mr-2"></i>
            Légende
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Total :</strong> Somme de vos notes brutes (ex: 10/35)</p>
                <p><strong>Sur 20 :</strong> Notes ramenées sur 20 pour comparaison</p>
            </div>
            <div class="col-md-6">
                <p><strong>Pourcentage :</strong> Votre taux de réussite</p>
                <p><strong>Moyenne Période :</strong> Calculée automatiquement à partir de toutes vos notes</p>
            </div>
        </div>
    </div>
</div>

@endsection
