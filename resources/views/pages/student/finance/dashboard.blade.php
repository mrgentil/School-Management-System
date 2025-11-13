@extends('layouts.student') 

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de Bord Financier</h1>
        <div class="d-none d-sm-inline-block">
            <span class="badge badge-primary">Année Scolaire: {{ $current_year }}</span>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Dû Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dû</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_due, 0, ',', ' ') }} $</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payé Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Payé</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_paid, 0, ',', ' ') }} $</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solde Restant Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Solde Restant</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($total_balance, 0, ',', ' ') }} $</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paiements en Attente Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Paiements en Attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $outstanding_payments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Derniers Paiements -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Derniers Paiements</h6>
                    <a href="{{ route('student.finance.payments') }}" class="btn btn-sm btn-primary">Voir Tout</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Libellé</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $payment->payment->title }}</td>
                                    <td class="text-right">{{ number_format($payment->amt_paid, 0, ',', ' ') }} $</td>
                                    <td>
                                        @if($payment->balance == 0)
                                            <span class="badge badge-success">Payé</span>
                                        @elseif($payment->amt_paid > 0)
                                            <span class="badge badge-warning">Partiel</span>
                                        @else
                                            <span class="badge badge-danger">Impayé</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun paiement trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paiements en Retard -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Paiements en Retard</h6>
                </div>
                <div class="card-body">
                    @if($outstanding_payments->count() > 0)
                        <div class="list-group">
                            @foreach($outstanding_payments as $payment)
                            <div class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $payment->payment->title }}</h6>
                                    <small>{{ number_format($payment->balance, 0, ',', ' ') }} $</small>
                                </div>
                                <p class="mb-1">Échéance: {{ $payment->payment->deadline ? $payment->payment->deadline->format('d/m/Y') : 'Non définie' }}</p>
                                <small><a href="#" class="text-primary">Payer maintenant</a></small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="mb-0">Aucun paiement en retard</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Historique des Paiements -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des Paiements</h6>
                    <a href="{{ route('student.finance.receipts') }}" class="btn btn-sm btn-primary">Voir les reçus</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Référence</th>
                                    <th>Libellé</th>
                                    <th>Montant</th>
                                    <th>Payé</th>
                                    <th>Reste</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payment_records as $record)
                                <tr>
                                    <td>{{ $record->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $record->ref_no }}</td>
                                    <td>{{ $record->payment->title }}</td>
                                    <td class="text-right">{{ number_format($record->payment->amount, 0, ',', ' ') }} $</td>
                                    <td class="text-right">{{ number_format($record->amt_paid, 0, ',', ' ') }} $</td>
                                    <td class="text-right">{{ number_format($record->balance, 0, ',', ' ') }} $</td>
                                    <td>
                                        @if($record->balance == 0)
                                            <span class="badge badge-success">Payé</span>
                                        @elseif($record->amt_paid > 0)
                                            <span class="badge badge-warning">Partiel</span>
                                        @else
                                            <span class="badge badge-danger">Impayé</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@push('js')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            },
            "order": [[0, "desc"]]
        });
    });
</script>
@endpush
