@extends('layouts.master')
@section('page_title', 'Comparaisons Historiques - Analytics')
@section('content')

<div class="content">
    {{-- En-tête --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">📈 Comparaisons Historiques et Tendances</h4>
                            <p class="mb-0">Analysez l'évolution des performances sur plusieurs années</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#filterModal">
                                    <i class="icon-filter4 mr-2"></i>Filtres
                                </button>
                                <button type="button" class="btn btn-light" onclick="exportComparison()">
                                    <i class="icon-download mr-2"></i>Exporter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques d'évolution --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📊 Évolution des Moyennes par Classe</h6>
                    <div class="header-elements">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="showTrendLines" checked>
                            <label class="form-check-label" for="showTrendLines">
                                Lignes de tendance
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="historicalChart" height="400"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">🎯 Tendances Générales</h6>
                </div>
                <div class="card-body">
                    @if(count($trends) > 0)
                        @foreach($trends as $className => $trend)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-semibold">{{ $className }}</span>
                                <span class="badge badge-{{ $trend['change'] >= 0 ? 'success' : 'danger' }}">
                                    {{ $trend['change'] >= 0 ? '+' : '' }}{{ round($trend['change'], 1) }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $trend['change'] >= 0 ? 'success' : 'danger' }}" 
                                     style="width: {{ abs($trend['percentage_change']) }}%"></div>
                            </div>
                            <small class="text-muted">
                                {{ $trend['change'] >= 0 ? 'Amélioration' : 'Baisse' }} de {{ abs($trend['percentage_change']) }}%
                            </small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Données insuffisantes pour calculer les tendances</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0">📋 Années Analysées</h6>
                </div>
                <div class="card-body">
                    @foreach($years as $year)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ $year }}</span>
                        <span class="badge badge-primary">
                            {{ collect($comparison_data)->where('year', $year)->count() }} classes
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau comparatif détaillé --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📋 Tableau Comparatif Détaillé</h6>
                    <div class="header-elements">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="sortTable('year')">
                                <i class="icon-sort-time-asc mr-1"></i>Année
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortTable('class')">
                                <i class="icon-sort-alpha-asc mr-1"></i>Classe
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="sortTable('average')">
                                <i class="icon-sort-numeric-desc mr-1"></i>Moyenne
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($comparison_data) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="comparisonTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Année Scolaire</th>
                                        <th>Classe</th>
                                        <th>Nombre d'Étudiants</th>
                                        <th>Moyenne de Classe</th>
                                        <th>Performance</th>
                                        <th>Évolution</th>
                                        <th>Tendance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sortedData = collect($comparison_data)->sortByDesc('year')->sortBy('class');
                                        $previousData = [];
                                    @endphp
                                    @foreach($sortedData as $data)
                                    @php
                                        $key = $data['class'];
                                        $evolution = 0;
                                        if (isset($previousData[$key])) {
                                            $evolution = $data['average'] - $previousData[$key];
                                        }
                                        $previousData[$key] = $data['average'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge badge-secondary">{{ $data['year'] }}</span>
                                        </td>
                                        <td>
                                            <div class="font-weight-semibold">{{ $data['class'] }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $data['students_count'] }}</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-{{ $data['average'] >= 70 ? 'success' : ($data['average'] >= 50 ? 'warning' : 'danger') }}">
                                                {{ round($data['average'], 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $performance = $data['average'] >= 80 ? 'Excellente' : ($data['average'] >= 70 ? 'Très Bonne' : ($data['average'] >= 60 ? 'Bonne' : ($data['average'] >= 50 ? 'Moyenne' : 'Faible')));
                                                $performanceClass = $data['average'] >= 80 ? 'success' : ($data['average'] >= 70 ? 'primary' : ($data['average'] >= 60 ? 'info' : ($data['average'] >= 50 ? 'warning' : 'danger')));
                                            @endphp
                                            <span class="badge badge-{{ $performanceClass }}">{{ $performance }}</span>
                                        </td>
                                        <td>
                                            @if($evolution != 0)
                                                <span class="badge badge-{{ $evolution > 0 ? 'success' : 'danger' }}">
                                                    {{ $evolution > 0 ? '+' : '' }}{{ round($evolution, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($evolution > 2)
                                                <i class="icon-arrow-up8 text-success" title="Tendance positive"></i>
                                            @elseif($evolution < -2)
                                                <i class="icon-arrow-down8 text-danger" title="Tendance négative"></i>
                                            @else
                                                <i class="icon-arrow-right8 text-muted" title="Stable"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-info" 
                                                        onclick="showClassDetails('{{ $data['class'] }}', '{{ $data['year'] }}')"
                                                        title="Détails">
                                                    <i class="icon-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="compareWithPrevious('{{ $data['class'] }}', '{{ $data['year'] }}')"
                                                        title="Comparer">
                                                    <i class="icon-compare"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-database-remove icon-3x text-muted mb-3"></i>
                            <h5>Aucune donnée historique</h5>
                            <p class="text-muted">Les comparaisons seront disponibles après plusieurs années de données.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Analyses et insights --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">🔍 Analyses Automatiques</h6>
                </div>
                <div class="card-body">
                    @php
                        $bestImprovement = collect($trends)->sortByDesc('change')->first();
                        $worstDecline = collect($trends)->sortBy('change')->first();
                        $overallTrend = collect($trends)->avg('change');
                    @endphp
                    
                    <div class="alert alert-success">
                        <h6 class="mb-1">📈 Meilleure Progression</h6>
                        @if($bestImprovement && $bestImprovement['change'] > 0)
                            <p class="mb-0">
                                <strong>{{ array_search($bestImprovement, $trends->toArray()) }}</strong> 
                                avec une amélioration de <strong>+{{ round($bestImprovement['change'], 1) }}%</strong>
                            </p>
                        @else
                            <p class="mb-0">Aucune amélioration significative détectée</p>
                        @endif
                    </div>

                    @if($worstDecline && $worstDecline['change'] < -2)
                    <div class="alert alert-warning">
                        <h6 class="mb-1">📉 Attention Requise</h6>
                        <p class="mb-0">
                            <strong>{{ array_search($worstDecline, $trends->toArray()) }}</strong> 
                            montre une baisse de <strong>{{ round($worstDecline['change'], 1) }}%</strong>
                        </p>
                    </div>
                    @endif

                    <div class="alert alert-info">
                        <h6 class="mb-1">📊 Tendance Générale</h6>
                        <p class="mb-0">
                            @if($overallTrend > 1)
                                L'établissement montre une <strong>progression positive</strong> générale
                            @elseif($overallTrend < -1)
                                L'établissement montre une <strong>tendance à la baisse</strong>
                            @else
                                L'établissement maintient des <strong>performances stables</strong>
                            @endif
                            ({{ $overallTrend > 0 ? '+' : '' }}{{ round($overallTrend, 1) }}%)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">💡 Recommandations</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if($overallTrend > 2)
                            <div class="list-group-item px-0">
                                <i class="icon-checkmark-circle2 text-success mr-2"></i>
                                <strong>Maintenir les bonnes pratiques</strong> qui ont conduit à cette amélioration
                            </div>
                        @endif
                        
                        @if($worstDecline && $worstDecline['change'] < -5)
                            <div class="list-group-item px-0">
                                <i class="icon-warning22 text-warning mr-2"></i>
                                <strong>Intervention urgente</strong> pour la classe en difficulté
                            </div>
                        @endif
                        
                        <div class="list-group-item px-0">
                            <i class="icon-graph text-primary mr-2"></i>
                            <strong>Analyser les facteurs</strong> de réussite des meilleures classes
                        </div>
                        
                        <div class="list-group-item px-0">
                            <i class="icon-users text-info mr-2"></i>
                            <strong>Partager les bonnes pratiques</strong> entre enseignants
                        </div>
                        
                        <div class="list-group-item px-0">
                            <i class="icon-calendar22 text-secondary mr-2"></i>
                            <strong>Planifier des évaluations</strong> trimestrielles de suivi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Filtres --}}
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">🔍 Filtres d'Analyse</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="form-group">
                        <label>Période d'Analyse</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control" name="start_year">
                                    <option value="">Année de début</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="end_year">
                                    <option value="">Année de fin</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Classes à Analyser</label>
                        <select class="form-control" name="classes[]" multiple>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Type d'Analyse</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="analysis_trend" name="analysis_type" value="trend" checked>
                            <label class="custom-control-label" for="analysis_trend">Analyse des tendances</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="analysis_comparison" name="analysis_type" value="comparison">
                            <label class="custom-control-label" for="analysis_comparison">Comparaison année par année</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-info" onclick="applyFilters()">Appliquer les Filtres</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script src="{{ asset('global_assets/js/plugins/visualization/chartjs/chart.min.js') }}"></script>
<script>
// Graphique d'évolution historique
const ctx = document.getElementById('historicalChart').getContext('2d');

// Préparer les données pour le graphique
const chartData = @json($comparison_data);
const years = [...new Set(chartData.map(item => item.year))].sort();
const classes = [...new Set(chartData.map(item => item.class))];

const datasets = classes.map((className, index) => {
    const classData = chartData.filter(item => item.class === className);
    const data = years.map(year => {
        const yearData = classData.find(item => item.year === year);
        return yearData ? yearData.average : null;
    });
    
    const colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', 
        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#9966FF'
    ];
    
    return {
        label: className,
        data: data,
        borderColor: colors[index % colors.length],
        backgroundColor: colors[index % colors.length] + '20',
        tension: 0.4,
        fill: false
    };
});

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: years,
        datasets: datasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                title: {
                    display: true,
                    text: 'Moyenne (%)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Année Scolaire'
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});

// Toggle lignes de tendance
document.getElementById('showTrendLines').addEventListener('change', function() {
    chart.data.datasets.forEach(dataset => {
        dataset.fill = this.checked;
    });
    chart.update();
});

// Fonctions utilitaires
function sortTable(column) {
    const table = document.getElementById('comparisonTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aVal, bVal;
        switch(column) {
            case 'year':
                aVal = a.cells[0].textContent.trim();
                bVal = b.cells[0].textContent.trim();
                break;
            case 'class':
                aVal = a.cells[1].textContent.trim();
                bVal = b.cells[1].textContent.trim();
                break;
            case 'average':
                aVal = parseFloat(a.cells[3].textContent.replace('%', ''));
                bVal = parseFloat(b.cells[3].textContent.replace('%', ''));
                return bVal - aVal; // Tri décroissant pour les moyennes
        }
        return aVal.localeCompare(bVal);
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

function showClassDetails(className, year) {
    alert(`Détails pour ${className} - ${year}\nFonctionnalité en développement`);
}

function compareWithPrevious(className, year) {
    alert(`Comparaison ${className} avec l'année précédente\nFonctionnalité en développement`);
}

function exportComparison() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("exam_analytics.export_results") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'export_type';
    typeInput.value = 'historical_comparison';
    
    form.appendChild(csrfToken);
    form.appendChild(typeInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function applyFilters() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    window.location.href = '{{ route("exam_analytics.historical_comparison") }}?' + params.toString();
}

// Tooltips
$('[title]').tooltip();

// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endsection
