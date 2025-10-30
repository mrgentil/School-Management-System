@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mes Reçus de Paiement</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('student.finance.dashboard') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour au tableau de bord
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtrer les reçus</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('student.finance.receipts') }}" method="GET" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <label for="year" class="mr-2">Année scolaire:</label>
                    <select name="year" id="year" class="form-control form-control-sm">
                        <option value="">Toutes les années</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-3 mb-2">
                    <label for="month" class="mr-2">Mois:</label>
                    <select name="month" id="month" class="form-control form-control-sm">
                        <option value="">Tous les mois</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $selected_month == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm mb-2">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                @if(request()->has('year') || request()->has('month'))
                    <a href="{{ route('student.finance.receipts') }}" class="btn btn-secondary btn-sm mb-2 ml-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Reçus -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Reçus</h6>
            <div>
                <a href="#" class="btn btn-sm btn-success" data-toggle="modal" data-target="#printModal">
                    <i class="fas fa-print"></i> Imprimer
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($receipts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Référence</th>
                                <th>Libellé</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $receipt)
                            <tr>
                                <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $receipt->ref_no }}</td>
                                <td>{{ $receipt->paymentRecord->payment->title ?? 'N/A' }}</td>
                                <td class="text-right">{{ number_format($receipt->amt_paid, 0, ',', ' ') }} FCFA</td>
                                <td>{{ ucfirst($receipt->payment_method) }}</td>
                                <td>
                                    @if($receipt->status == 'approved')
                                        <span class="badge badge-success">Approuvé</span>
                                    @elseif($receipt->status == 'pending')
                                        <span class="badge badge-warning">En attente</span>
                                    @else
                                        <span class="badge badge-danger">Rejeté</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.finance.receipt', $receipt->id) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('student.finance.receipt.download', $receipt->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $receipts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-4x text-gray-300 mb-4"></i>
                    <h5 class="text-gray-500">Aucun reçu trouvé</h5>
                    <p class="text-muted">Vous n'avez pas encore de reçus de paiement.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printModalLabel">Options d'Impression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="printRange">Période</label>
                    <select class="form-control" id="printRange">
                        <option value="all">Tous les reçus</option>
                        <option value="current_month">Ce mois-ci</option>
                        <option value="last_month">Le mois dernier</option>
                        <option value="current_year">Cette année</option>
                        <option value="custom">Période personnalisée</option>
                    </select>
                </div>
                <div class="form-group" id="customDateRange" style="display: none;">
                    <label>Période personnalisée</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="startDate" placeholder="Date de début">
                        </div>
                        <div class="col-md-6">
                            <input type="date" class="form-control" id="endDate" placeholder="Date de fin">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="printStatus">Statut</label>
                    <select class="form-control" id="printStatus">
                        <option value="all">Tous les statuts</option>
                        <option value="approved">Approuvé</option>
                        <option value="pending">En attente</option>
                        <option value="rejected">Rejeté</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="printButton">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Detail Modal -->
<div class="modal fade" id="receiptDetailModal" tabindex="-1" role="dialog" aria-labelledby="receiptDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptDetailModalLabel">Détails du Reçu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="receiptDetailContent">
                <!-- Dynamic content will be loaded here -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="printReceiptBtn">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<!-- Custom styles for this page -->
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('js')
<!-- Page level plugins -->
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
            },
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": [6] } // Disable sorting on actions column
            ]
        });

        // Show/hide custom date range
        $('#printRange').change(function() {
            if ($(this).val() === 'custom') {
                $('#customDateRange').show();
            } else {
                $('#customDateRange').hide();
            }
        });

        // Print button handler
        $('#printButton').click(function() {
            let range = $('#printRange').val();
            let status = $('#printStatus').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            
            let url = "{{ route('student.finance.receipts.print') }}?print=1";
            
            if (range === 'custom' && startDate && endDate) {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            } else if (range !== 'all') {
                url += `&range=${range}`;
            }
            
            if (status !== 'all') {
                url += `&status=${status}`;
            }
            
            window.open(url, '_blank');
            $('#printModal').modal('hide');
        });

        // Handle receipt detail modal
        $('.view-receipt').on('click', function(e) {
            e.preventDefault();
            let receiptId = $(this).data('id');
            let url = `{{ url('student/finance/receipts') }}/${receiptId}/details`;
            
            // Show loading state
            $('#receiptDetailContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                </div>
            `);
            
            // Load receipt details
            $.get(url, function(data) {
                $('#receiptDetailContent').html(data);
                // Update print button
                $('#printReceiptBtn').off('click').on('click', function() {
                    window.open(`{{ url('student/finance/receipts') }}/${receiptId}/print`, '_blank');
                });
            }).fail(function() {
                $('#receiptDetailContent').html(`
                    <div class="alert alert-danger">
                        Une erreur est survenue lors du chargement des détails du reçu.
                    </div>
                `);
            });
            
            $('#receiptDetailModal').modal('show');
        });
    });
</script>
@endpush
