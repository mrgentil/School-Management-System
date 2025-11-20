@extends('layouts.master')
@section('page_title', 'Mon Bulletin')

@section('content')

    {{-- Menu Rapide Étudiant --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="{{ route('student.exams.index') }}" class="btn btn-primary btn-block">
                <i class="icon-home mr-2"></i>Accueil Examens
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('student.exam_schedule') }}" class="btn btn-info btn-block">
                <i class="icon-calendar mr-2"></i>Calendrier
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('student.progress.index') }}" class="btn btn-success btn-block">
                <i class="icon-graph mr-2"></i>Ma Progression
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('student.grades.index') }}" class="btn btn-warning btn-block">
                <i class="icon-certificate mr-2"></i>Mes Notes
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-file-text2 mr-2"></i>
            Bulletin Scolaire - {{ $year }}
        </h6>
        <div class="header-elements">
            <button onclick="window.print()" class="btn btn-light btn-sm mr-2">
                <i class="icon-printer mr-2"></i>Imprimer
            </button>
            <a href="{{ route('student.grades.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left7 mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        {{-- Informations de l'étudiant --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Nom:</th>
                        <td><strong>{{ $student->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Classe:</th>
                        <td>{{ $studentRecord->my_class ? ($studentRecord->my_class->full_name ?: $studentRecord->my_class->name) : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Section:</th>
                        <td>{{ $studentRecord->section->name ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Matricule:</th>
                        <td>{{ $student->code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Année Scolaire:</th>
                        <td><strong>{{ $year }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tableau des notes --}}
        @if(count($bulletinData) > 0)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th rowspan="2" style="width: 20%; vertical-align: middle;">MATIÈRE</th>
                            <th colspan="4" class="text-center">PÉRIODES</th>
                            <th colspan="2" class="text-center">SEMESTRES</th>
                        </tr>
                        <tr>
                            <th class="text-center">P1</th>
                            <th class="text-center">P2</th>
                            <th class="text-center">P3</th>
                            <th class="text-center">P4</th>
                            <th class="text-center">S1</th>
                            <th class="text-center">S2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalP1 = $totalP2 = $totalP3 = $totalP4 = 0;
                            $countP1 = $countP2 = $countP3 = $countP4 = 0;
                            $totalS1 = $totalS2 = 0;
                            $countS1 = $countS2 = 0;
                        @endphp

                        @foreach($bulletinData as $data)
                            <tr>
                                <td><strong>{{ $data['subject']->name }}</strong></td>
                                
                                {{-- Période 1 --}}
                                <td class="text-center {{ $data['p1_avg'] ? 'table-primary' : '' }}">
                                    @if($data['p1_avg'])
                                        <strong>{{ $data['p1_avg'] }}</strong>
                                        @php $totalP1 += $data['p1_avg']; $countP1++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Période 2 --}}
                                <td class="text-center {{ $data['p2_avg'] ? 'table-info' : '' }}">
                                    @if($data['p2_avg'])
                                        <strong>{{ $data['p2_avg'] }}</strong>
                                        @php $totalP2 += $data['p2_avg']; $countP2++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Période 3 --}}
                                <td class="text-center {{ $data['p3_avg'] ? 'table-success' : '' }}">
                                    @if($data['p3_avg'])
                                        <strong>{{ $data['p3_avg'] }}</strong>
                                        @php $totalP3 += $data['p3_avg']; $countP3++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Période 4 --}}
                                <td class="text-center {{ $data['p4_avg'] ? 'table-warning' : '' }}">
                                    @if($data['p4_avg'])
                                        <strong>{{ $data['p4_avg'] }}</strong>
                                        @php $totalP4 += $data['p4_avg']; $countP4++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Semestre 1 --}}
                                <td class="text-center {{ $data['s1_avg'] ? 'table-primary font-weight-bold' : '' }}">
                                    @if($data['s1_avg'])
                                        <strong class="h6">{{ $data['s1_avg'] }}</strong>
                                        @php $totalS1 += $data['s1_avg']; $countS1++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Semestre 2 --}}
                                <td class="text-center {{ $data['s2_avg'] ? 'table-success font-weight-bold' : '' }}">
                                    @if($data['s2_avg'])
                                        <strong class="h6">{{ $data['s2_avg'] }}</strong>
                                        @php $totalS2 += $data['s2_avg']; $countS2++; @endphp
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        {{-- Moyennes générales --}}
                        <tr class="bg-light font-weight-bold">
                            <td>MOYENNE GÉNÉRALE</td>
                            <td class="text-center">
                                {{ $countP1 > 0 ? round($totalP1 / $countP1, 2) : '-' }}
                            </td>
                            <td class="text-center">
                                {{ $countP2 > 0 ? round($totalP2 / $countP2, 2) : '-' }}
                            </td>
                            <td class="text-center">
                                {{ $countP3 > 0 ? round($totalP3 / $countP3, 2) : '-' }}
                            </td>
                            <td class="text-center">
                                {{ $countP4 > 0 ? round($totalP4 / $countP4, 2) : '-' }}
                            </td>
                            <td class="text-center table-primary">
                                <strong class="h6">
                                    {{ $countS1 > 0 ? round($totalS1 / $countS1, 2) : '-' }}
                                </strong>
                            </td>
                            <td class="text-center table-success">
                                <strong class="h6">
                                    {{ $countS2 > 0 ? round($totalS2 / $countS2, 2) : '-' }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Statistiques --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="mb-0">
                                {{ $countS1 > 0 ? round($totalS1 / $countS1, 2) : 'N/A' }}/20
                            </h5>
                            <small>Moyenne Générale Semestre 1</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="mb-0">
                                {{ $countS2 > 0 ? round($totalS2 / $countS2, 2) : 'N/A' }}/20
                            </h5>
                            <small>Moyenne Générale Semestre 2</small>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                Aucune note disponible pour le moment.
            </div>
        @endif
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">Date d'édition: {{ date('d/m/Y') }}</small>
            </div>
            <div class="col-md-6 text-right">
                <button onclick="window.print()" class="btn btn-info btn-sm">
                    <i class="icon-printer mr-2"></i>Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
