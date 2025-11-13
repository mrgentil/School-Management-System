@extends('layouts.master')
@section('page_title', 'Mon Emploi du Temps')

@section('content')

<div class="container-fluid">
    <!-- En-tête -->
    <div class="card mb-3">
        <div class="card-body bg-primary-400 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1"><i class="icon-calendar mr-2"></i>Mon Emploi du Temps</h4>
                    <p class="mb-0 opacity-75">
                        @if(isset($className))
                            Classe: <strong>{{ $className }}</strong>
                        @endif
                    </p>
                </div>
                @if(isset($timetableRecord))
                <div>
                    <a href="{{ route('student.timetable.calendar') }}" class="btn btn-light">
                        <i class="icon-calendar3 mr-2"></i> Vue Calendrier
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if(isset($message))
        <!-- Message d'information -->
        <div class="alert alert-info border-left-info">
            <div class="d-flex align-items-center">
                <i class="icon-info22 icon-2x mr-3"></i>
                <div>
                    <h6 class="alert-heading mb-1">Information</h6>
                    <p class="mb-0">{{ $message }}</p>
                </div>
            </div>
        </div>
    @elseif(isset($schedule))
        <!-- Emploi du temps -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="icon-calendar22 mr-2"></i>Emploi du Temps Hebdomadaire
                    @if(isset($timetableRecord) && $timetableRecord->exam)
                        <span class="badge badge-warning ml-2">Examens: {{ $timetableRecord->exam->name }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="15%" class="text-center">
                                    <i class="icon-calendar3 mr-1"></i>Jour
                                </th>
                                <th width="15%" class="text-center">
                                    <i class="icon-clock mr-1"></i>Horaire
                                </th>
                                <th>
                                    <i class="icon-book mr-1"></i>Matière
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule as $day => $courses)
                                @if($courses->count() > 0)
                                    @foreach($courses as $index => $course)
                                        <tr>
                                            @if($index === 0)
                                                <td rowspan="{{ $courses->count() }}" class="align-middle bg-light font-weight-bold text-center">
                                                    <i class="icon-calendar22 mr-1"></i>{{ $day }}
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if($course->time_slot)
                                                    <div class="font-weight-semibold text-primary">
                                                        {{ date('H:i', $course->time_slot->timestamp_from) }}
                                                    </div>
                                                    <div class="text-muted small">
                                                        {{ date('H:i', $course->time_slot->timestamp_to) }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <span class="badge badge-primary badge-pill">
                                                            <i class="icon-book"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">
                                                            {{ $course->subject->name ?? 'N/A' }}
                                                        </div>
                                                        @if($course->time_slot)
                                                            <div class="text-muted small">
                                                                Durée: {{ $course->time_slot->full ?? 'N/A' }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="align-middle bg-light font-weight-bold text-center">
                                            <i class="icon-calendar22 mr-1"></i>{{ $day }}
                                        </td>
                                        <td colspan="2" class="text-center text-muted">
                                            <i class="icon-blocked mr-1"></i>Aucun cours prévu
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Légende -->
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title"><i class="icon-info22 mr-2"></i>Informations</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="icon-checkmark-circle text-success mr-2"></i>
                                Consultez votre emploi du temps pour planifier votre semaine
                            </li>
                            <li class="mb-2">
                                <i class="icon-calendar3 text-primary mr-2"></i>
                                Utilisez la vue calendrier pour une visualisation différente
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="icon-printer text-info mr-2"></i>
                                Vous pouvez imprimer cet emploi du temps (Ctrl+P)
                            </li>
                            <li class="mb-2">
                                <i class="icon-bell2 text-warning mr-2"></i>
                                Vérifiez régulièrement les mises à jour
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@push('css')
<style>
    @media print {
        .btn, .navbar, .sidebar, .no-print, .card-footer {
            display: none !important;
        }
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .badge-pill {
        padding: 0.5rem 0.75rem;
    }
</style>
@endpush
