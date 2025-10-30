<div class="receipt-details">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Détails du Reçu #{{ $receipt->ref_no }}</h6>
            <div>
                <a href="{{ route('student.finance.receipt.download', $receipt->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-download"></i> Télécharger
                </a>
                <a href="{{ route('student.finance.receipt.print', $receipt->id) }}" class="btn btn-sm btn-success" target="_blank">
                    <i class="fas fa-print"></i> Imprimer
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Informations sur le Paiement</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Référence:</th>
                            <td>{{ $receipt->ref_no }}</td>
                        </tr>
                        <tr>
                            <th>Date de Paiement:</th>
                            <td>{{ $receipt->payment_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Libellé:</th>
                            <td>{{ $receipt->paymentRecord->payment->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Montant Payé:</th>
                            <td class="font-weight-bold">{{ number_format($receipt->amt_paid, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <th>Méthode de Paiement:</th>
                            <td>{{ ucfirst($receipt->payment_method) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Informations sur l'Étudiant</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="40%">Nom Complet:</th>
                            <td>{{ $receipt->paymentRecord->student->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Matricule:</th>
                            <td>{{ $receipt->paymentRecord->student->student_code ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Classe:</th>
                            <td>{{ $receipt->paymentRecord->student->studentRecord->myClass->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Section:</th>
                            <td>{{ $receipt->paymentRecord->student->studentRecord->section->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($receipt->transaction_ref || $receipt->notes)
            <div class="row mb-4">
                <div class="col-12">
                    <h6>Informations Supplémentaires</h6>
                    <table class="table table-sm table-borderless">
                        @if($receipt->transaction_ref)
                        <tr>
                            <th width="15%">Réf. Transaction:</th>
                            <td>{{ $receipt->transaction_ref }}</td>
                        </tr>
                        @endif
                        @if($receipt->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $receipt->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x mr-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Important</h6>
                                <p class="mb-0">Conservez ce reçu comme preuve de paiement. En cas de problème, veuillez contacter l'administration avec la référence du reçu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted text-center">
            <small>Reçu généré le {{ now()->format('d/m/Y à H:i') }} par {{ config('app.name') }}</small>
        </div>
    </div>
</div>
