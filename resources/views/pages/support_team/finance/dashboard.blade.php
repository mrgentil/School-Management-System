@extends('layouts.master')
@section('page_title', 'Tableau de Bord Financier')

@section('content')
{{-- Statistiques principales --}}
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['total_paid'], 0, ',', ' ') }} FC</h3>
                        <span>Total Encaissé</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-cash3 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['total_balance'], 0, ',', ' ') }} FC</h3>
                        <span>Solde Restant</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-warning icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ $stats['collection_rate'] }}%</h3>
                        <span>Taux de Recouvrement</span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-percent icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3 class="mb-0">{{ number_format($stats['this_month'], 0, ',', ' ') }} FC</h3>
                        <span>Ce Mois 
                            @if($stats['monthly_growth'] > 0)
                                <span class="badge badge-light text-success">+{{ $stats['monthly_growth'] }}%</span>
                            @elseif($stats['monthly_growth'] < 0)
                                <span class="badge badge-light text-danger">{{ $stats['monthly_growth'] }}%</span>
                            @endif
                        </span>
                    </div>
                    <div class="align-self-center">
                        <i class="icon-calendar icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mini stats --}}
<div class="row mb-3">
    <div class="col-md-6">
        <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
            <span><i class="icon-checkmark-circle mr-2"></i> Élèves à jour</span>
            <strong>{{ $stats['students_fully_paid'] }}</strong>
        </div>
    </div>
    <div class="col-md-6">
        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0">
            <span><i class="icon-warning mr-2"></i> Élèves avec solde</span>
            <strong>{{ $stats['students_with_balance'] }}</strong>
        </div>
    </div>
</div>

<div class="row">
    {{-- Graphique mensuel --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-stats-bars mr-2"></i> Paiements par Mois</h6>
                <div>
                    <a href="{{ route('finance.export') }}" class="btn btn-sm btn-outline-success">
                        <i class="icon-file-excel mr-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Répartition par statut --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-pie-chart5 mr-2"></i> Répartition</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Par classe --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Par Classe</h6>
                <a href="{{ route('finance.by_class') }}" class="btn btn-sm btn-primary">Détails</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Classe</th>
                                <th class="text-right">Payé</th>
                                <th class="text-right">Solde</th>
                                <th class="text-center">Taux</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classData as $class)
                                <tr>
                                    <td>{{ $class['class'] }}</td>
                                    <td class="text-right text-success">{{ number_format($class['paid'], 0, ',', ' ') }}</td>
                                    <td class="text-right text-danger">{{ number_format($class['balance'], 0, ',', ' ') }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $class['rate'] >= 80 ? 'success' : ($class['rate'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $class['rate'] }}%
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

    {{-- Retards de paiement --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-danger text-white d-flex justify-content-between">
                <h6 class="card-title mb-0"><i class="icon-warning mr-2"></i> Retards de Paiement</h6>
                <a href="{{ route('finance.export', ['type' => 'overdue']) }}" class="btn btn-sm btn-light">
                    <i class="icon-download mr-1"></i> Export
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-sm mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Élève</th>
                                <th>Type</th>
                                <th class="text-right">Solde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($overdueStudents as $item)
                                <tr>
                                    <td>{{ $item['student']->name ?? 'N/A' }}</td>
                                    <td>{{ $item['title'] }}</td>
                                    <td class="text-right text-danger font-weight-bold">
                                        {{ number_format($item['balance'], 0, ',', ' ') }} FC
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-success py-3">
                                        <i class="icon-checkmark-circle mr-2"></i> Aucun retard !
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Derniers paiements --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-history mr-2"></i> Derniers Paiements</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Élève</th>
                    <th>Type</th>
                    <th class="text-right">Montant</th>
                    <th>Référence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentPayments as $record)
                    <tr>
                        <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $record->student->name ?? 'N/A' }}</td>
                        <td>{{ $record->payment->title ?? 'Frais' }}</td>
                        <td class="text-right text-success font-weight-bold">
                            {{ number_format($record->amt_paid, 0, ',', ' ') }} FC
                        </td>
                        <td><code>{{ $record->ref_no ?? '-' }}</code></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyData = @json($monthlyData);
    const stats = @json($stats);

    // Graphique mensuel
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Montant (FC)',
                data: monthlyData.map(d => d.amount),
                backgroundColor: '#4CAF50',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' FC';
                        }
                    }
                }
            }
        }
    });

    // Graphique circulaire
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Payé', 'Restant'],
            datasets: [{
                data: [stats.total_paid, stats.total_balance],
                backgroundColor: ['#4CAF50', '#f44336'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection
