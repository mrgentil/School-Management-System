@extends('layouts.master')
@section('page_title', 'Placements SESSION - ' . $exam->name)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h5 class="card-title">
            <i class="icon-users4 mr-2"></i>
            Placements SESSION - {{ $exam->name }}
        </h5>
        <div class="header-elements">
            <a href="{{ route('exam_schedules.show', $exam->id) }}" class="btn btn-sm btn-light">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux horaires
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-success border-0">
            <div class="row">
                <div class="col-md-4">
                    <strong>Examen:</strong> {{ $exam->name }}
                </div>
                <div class="col-md-4">
                    <strong>Année:</strong> {{ $exam->year }} - Semestre {{ $exam->semester }}
                </div>
                <div class="col-md-4">
                    <strong>Horaires SESSION:</strong> {{ $exam->schedules->where('exam_type', 'session')->count() }} horaire(s)
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <small class="text-muted">
                        <i class="icon-info22 mr-1"></i>
                        Les élèves ont la <strong>même salle et la même place</strong> pour TOUS les horaires SESSION de cet examen.
                    </small>
                </div>
            </div>
        </div>

        @if($placementsByRoom->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <h6 class="mb-3">
                        <i class="icon-home9 mr-2"></i>
                        Placements par Salle ({{ $placementsByRoom->sum->count() }} étudiants)
                    </h6>
                </div>
            </div>

            <div class="row">
                @foreach($placementsByRoom as $roomId => $placements)
                    @php
                        $room = $rooms->find($roomId);
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header bg-{{ $room->level == 'excellence' ? 'success' : ($room->level == 'moyen' ? 'warning' : 'danger') }}-400">
                                <h6 class="card-title">
                                    <i class="icon-home9 mr-2"></i>
                                    Salle {{ $room->name }}
                                    <span class="badge badge-light badge-pill ml-2">{{ $placements->count() }} / {{ $room->capacity }}</span>
                                </h6>
                                <div class="header-elements">
                                    <span class="badge badge-light">
                                        {{ strtoupper($room->level) }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Place</th>
                                                <th>Étudiant</th>
                                                <th>Classe</th>
                                                <th class="text-right">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($placements->sortBy('seat_number') as $placement)
                                            <tr>
                                                <td><strong>#{{ $placement->seat_number }}</strong></td>
                                                <td>{{ $placement->student->name }}</td>
                                                <td>
                                                    @if($placement->studentRecord)
                                                        {{ $placement->studentRecord->my_class->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <span class="badge badge-flat border-primary text-primary">
                                                        {{ number_format($placement->ranking_score, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-3">
                <a href="#" onclick="window.print(); return false;" class="btn btn-primary">
                    <i class="icon-printer mr-2"></i> Imprimer les placements
                </a>
            </div>
        @else
            <div class="text-center py-5">
                <i class="icon-user-cancel icon-3x text-muted mb-3"></i>
                <p class="text-muted">Aucun placement n'a encore été généré pour cet examen.</p>
                <a href="{{ route('exam_schedules.show', $schedule->exam_id) }}" class="btn btn-primary">
                    <i class="icon-arrow-left8 mr-2"></i> Retour aux horaires
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
