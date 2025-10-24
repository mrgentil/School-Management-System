@extends('layouts.master')
@section('page_title', 'Tableau de Bord - Comptable')
@section('content')

<div class="row">
    <!-- Statistiques financières -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ number_format(\App\Models\PaymentRecord::sum('amount_paid'), 0, ',', ' ') }} FCFA</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Total Encaissé</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-cash3 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ number_format(\App\Models\Payment::sum('amount') - \App\Models\PaymentRecord::sum('amount_paid'), 0, ',', ' ') }} FCFA</h3>
                    <span class="text-uppercase font-size-xs">Montant Dû</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\PaymentRecord::whereMonth('created_at', now()->month)->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Paiements ce Mois</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-calendar22 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\Payment::whereHas('pr', function($q) { $q->where('balance', '>', 0); })->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Comptes en Retard</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-warning22 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Paiements récents -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Paiements Récents</h6>
                <div class="header-elements">
                    <a href="{{ route('payments.index') }}" class="btn btn-link btn-sm">Voir tout</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Type de Paiement</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\PaymentRecord::with(['payment', 'student.user'])->latest()->take(8)->get() as $record)
                            <tr>
                                <td>{{ $record->student->user->name }}</td>
                                <td>{{ $record->payment->title }}</td>
                                <td>{{ number_format($record->amount_paid, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $record->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $record->balance > 0 ? 'warning' : 'success' }}">
                                        {{ $record->balance > 0 ? 'Partiel' : 'Complet' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('payments.receipts', $record->id) }}" class="btn btn-sm btn-outline-primary">
                                        Reçu
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Aucun paiement récent</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et statistiques -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('payments.create') }}" class="list-group-item list-group-item-action">
                        <i class="icon-plus2 mr-3"></i>Nouveau Paiement
                    </a>
                    <a href="{{ route('payments.manage') }}" class="list-group-item list-group-item-action">
                        <i class="icon-cash mr-3"></i>Enregistrer Paiement
                    </a>
                    <a href="{{ route('payments.index') }}" class="list-group-item list-group-item-action">
                        <i class="icon-list mr-3"></i>Gérer les Paiements
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport()">
                        <i class="icon-file-stats mr-3"></i>Rapport Financier
                    </a>
                </div>
            </div>
        </div>

        <!-- Graphique des paiements mensuels -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Paiements par Mois</h6>
            </div>
            <div class="card-body">
                <canvas id="paymentsChart" height="200"></canvas>
            </div>
        </div>

        <!-- Alertes -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Alertes</h6>
            </div>
            <div class="card-body">
                @php
                    $overduePayments = \App\Models\Payment::whereHas('pr', function($q) { 
                        $q->where('balance', '>', 0); 
                    })->count();
                @endphp
                
                @if($overduePayments > 0)
                <div class="alert alert-warning">
                    <i class="icon-warning22 mr-2"></i>
                    <strong>{{ $overduePayments }}</strong> comptes ont des arriérés de paiement.
                </div>
                @endif

                @php
                    $todayPayments = \App\Models\PaymentRecord::whereDate('created_at', today())->count();
                @endphp
                
                @if($todayPayments > 0)
                <div class="alert alert-success">
                    <i class="icon-checkmark3 mr-2"></i>
                    <strong>{{ $todayPayments }}</strong> paiements enregistrés aujourd'hui.
                </div>
                @endif

                @if($overduePayments == 0 && $todayPayments == 0)
                <div class="text-center text-muted">
                    <i class="icon-checkmark-circle2 icon-2x mb-2"></i>
                    <p>Tout est à jour !</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script src="{{ asset('global_assets/js/plugins/visualization/chartjs/chart.min.js') }}"></script>
<script>
// Graphique des paiements mensuels
const ctx = document.getElementById('paymentsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Paiements',
            data: @json(array_values(\App\Models\PaymentRecord::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray())),
            borderColor: '#26a69a',
            backgroundColor: 'rgba(38, 166, 154, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function generateReport() {
    // Fonction pour générer un rapport financier
    alert('Fonctionnalité de rapport en cours de développement');
}
</script>
@endsection
