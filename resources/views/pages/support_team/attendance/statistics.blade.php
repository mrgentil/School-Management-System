@extends('layouts.master')
@section('page_title', 'Statistiques de Présence')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-success">
        <h6 class="card-title text-white">
            <i class="icon-stats-dots mr-2"></i>
            Statistiques de Présence
        </h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('attendance.statistics') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-semibold">Classe <span class="text-danger">*</span></label>
                        <select name="my_class_id" id="my_class_id" class="form-control select" required>
                            <option value="">Sélectionner une classe</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}" {{ $filters['class_id'] == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-semibold">Section</label>
                        <select name="section_id" id="section_id" class="form-control select">
                            <option value="">Toutes les sections</option>
                            @if(isset($sections))
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $filters['section_id'] == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Mois</label>
                        <select name="month" class="form-control select">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $filters['month'] == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="font-weight-semibold">Année</label>
                        <select name="year" class="form-control select">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $filters['year'] == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label class="font-weight-semibold">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="icon-search4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if(isset($stats) && $stats->count() > 0)
            <div class="alert alert-info border-0 mt-3">
                <i class="icon-info22 mr-2"></i>
                <strong>Période :</strong> {{ $months[$filters['month']] }} {{ $filters['year'] }}
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover datatable-basic">
                    <thead class="bg-light">
                        <tr>
                            <th>N°</th>
                            <th>N° Admission</th>
                            <th>Nom de l'Étudiant</th>
                            <th class="text-center">Total Jours</th>
                            <th class="text-center">Présent</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Retard</th>
                            <th class="text-center">Excusé</th>
                            <th class="text-center">Taux de Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats as $index => $stat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $stat['adm_no'] }}</td>
                                <td><strong>{{ $stat['student']->name }}</strong></td>
                                <td class="text-center">{{ $stat['total'] }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">{{ $stat['present'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger">{{ $stat['absent'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning">{{ $stat['late'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $stat['excused'] }}</span>
                                </td>
                                <td class="text-center">
                                    @if($stat['percentage'] >= 90)
                                        <span class="badge badge-success badge-lg">{{ $stat['percentage'] }}%</span>
                                    @elseif($stat['percentage'] >= 75)
                                        <span class="badge badge-primary badge-lg">{{ $stat['percentage'] }}%</span>
                                    @elseif($stat['percentage'] >= 60)
                                        <span class="badge badge-warning badge-lg">{{ $stat['percentage'] }}%</span>
                                    @else
                                        <span class="badge badge-danger badge-lg">{{ $stat['percentage'] }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th colspan="3" class="text-right">TOTAUX :</th>
                            <th class="text-center">{{ $stats->sum('total') }}</th>
                            <th class="text-center">{{ $stats->sum('present') }}</th>
                            <th class="text-center">{{ $stats->sum('absent') }}</th>
                            <th class="text-center">{{ $stats->sum('late') }}</th>
                            <th class="text-center">{{ $stats->sum('excused') }}</th>
                            <th class="text-center">
                                @php
                                    $totalPresence = $stats->sum('total');
                                    $totalPresent = $stats->sum('present');
                                    $avgPercentage = $totalPresence > 0 ? round(($totalPresent / $totalPresence) * 100, 2) : 0;
                                @endphp
                                <strong>{{ $avgPercentage }}%</strong>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Graphique -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="card-title">Graphique de Présence</h6>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-3">
                <i class="icon-info22 mr-2"></i>
                Aucune statistique disponible. Veuillez sélectionner une classe et soumettre le formulaire.
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Load sections when class is selected
    $('#my_class_id').change(function() {
        var classId = $(this).val();
        $('#section_id').html('<option value="">Chargement...</option>');
        
        if (classId) {
            $.ajax({
                url: '/attendance/get-sections/' + classId,
                type: 'GET',
                success: function(response) {
                    var options = '<option value="">Toutes les sections</option>';
                    if (response.success && response.sections.length > 0) {
                        response.sections.forEach(function(section) {
                            options += '<option value="' + section.id + '">' + section.name + '</option>';
                        });
                    }
                    $('#section_id').html(options);
                },
                error: function() {
                    $('#section_id').html('<option value="">Erreur</option>');
                }
            });
        } else {
            $('#section_id').html('<option value="">Toutes les sections</option>');
        }
    });

    @if(isset($stats) && $stats->count() > 0)
    // Chart
    var ctx = document.getElementById('attendanceChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Présent', 'Absent', 'Retard', 'Excusé'],
            datasets: [{
                label: 'Nombre d\'étudiants',
                data: [
                    {{ $stats->sum('present') }},
                    {{ $stats->sum('absent') }},
                    {{ $stats->sum('late') }},
                    {{ $stats->sum('excused') }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(23, 162, 184, 0.7)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection
