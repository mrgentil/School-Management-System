@extends('layouts.student')

@section('title', 'Reçu de Paiement #' . $receipt->ref_no)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reçu de Paiement</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('student.finance.receipts') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
            </a>
            <a href="{{ route('student.finance.receipt.download', $receipt->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Télécharger
            </a>
            <a href="{{ route('student.finance.receipt.print', $receipt->id) }}" class="btn btn-sm btn-success shadow-sm" target="_blank">
                <i class="fas fa-print fa-sm text-white-50"></i> Imprimer
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt"></i> Reçu #{{ $receipt->ref_no }}
                    </h6>
                    <span class="badge badge-{{ $receipt->status == 'approved' ? 'success' : ($receipt->status == 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($receipt->status) }}
                    </span>
                </div>
                <div class="card-body">
                    @include('pages.student.finance.partials.receipt_details')
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x mr-3"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Besoin d'aide ?</h6>
                                        <p class="mb-0">Si vous avez des questions concernant ce reçu, veuillez contacter le service financier de l'établissement.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>Reçu généré le {{ $receipt->created_at->format('d/m/Y à H:i') }} par {{ config('app.name') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .receipt-details .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    .receipt-details .table th {
        background-color: #f8f9fc;
    }
    .receipt-details .section-title {
        color: #4e73df;
        font-size: 1rem;
        border-left: 4px solid #4e73df;
        padding-left: 10px;
        margin: 20px 0 15px;
    }
    .receipt-details .alert {
        border-left: 4px solid #4e73df;
    }
</style>
@endpush
