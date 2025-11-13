ilisateur <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Reçus - {{ $student->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin-bottom: 10px;
            color: #666;
            font-size: 16px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>École Supérieure de Technologie</h1>
        <p>Historique des Reçus de Paiement</p>
        <p>Généré le {{ date('d/m/Y à H:i') }}</p>
    </div>

    <div class="info-section">
        <h3>Informations de l'Étudiant</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nom:</span> {{ $student->user->name }}
            </div>
            <div class="info-item">
                <span class="info-label">Matricule:</span> {{ $student->matricule ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Classe:</span> {{ $student->class->name ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Année:</span> {{ $student->academic_year ?? date('Y') }}
            </div>
        </div>
    </div>

    @if($range !== 'all' || $selected_year || $selected_month || $status !== 'all')
    <div class="info-section">
        <h3>Filtres Appliqués</h3>
        <div class="info-grid">
            @if($range === 'current_month')
                <div class="info-item">
                    <span class="info-label">Période:</span> Mois en cours ({{ date('m/Y') }})
                </div>
            @elseif($range === 'last_month')
                <div class="info-item">
                    <span class="info-label">Période:</span> Mois dernier ({{ date('m/Y', strtotime('last month')) }})
                </div>
            @elseif($range === 'current_year')
                <div class="info-item">
                    <span class="info-label">Période:</span> Année en cours ({{ date('Y') }})
                </div>
            @elseif($range === 'custom' && $start_date && $end_date)
                <div class="info-item">
                    <span class="info-label">Période:</span> Du {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                </div>
            @endif
            @if($selected_year)
                <div class="info-item">
                    <span class="info-label">Année:</span> {{ $selected_year }}
                </div>
            @endif
            @if($selected_month)
                <div class="info-item">
                    <span class="info-label">Mois:</span> {{ DateTime::createFromFormat('!m', $selected_month)->format('F') }}
                </div>
            @endif
            @if($status !== 'all')
                <div class="info-item">
                    <span class="info-label">Statut:</span>
                    @if($status === 'approved') Approuvé
                    @elseif($status === 'pending') En attente
                    @elseif($status === 'rejected') Rejeté
                    @endif
                </div>
            @endif
        </div>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Référence</th>
                <th>Libellé</th>
                <th class="text-right">Montant</th>
                <th>Méthode</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receipts as $receipt)
            <tr>
                <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $receipt->ref_no }}</td>
                <td>{{ $receipt->paymentRecord->payment->title ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($receipt->amt_paid, 0, ',', ' ') }} $</td>
                <td>{{ ucfirst($receipt->payment_method) }}</td>
                <td class="text-center">
                    @if($receipt->status == 'approved')
                        <span class="badge badge-success">Approuvé</span>
                    @elseif($receipt->status == 'pending')
                        <span class="badge badge-warning">En attente</span>
                    @else
                        <span class="badge badge-danger">Rejeté</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">
                    Aucun reçu trouvé avec les critères sélectionnés.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($receipts->count() > 0)
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="3" class="text-right">TOTAUX:</td>
                <td class="text-right">{{ number_format($receipts->sum('amt_paid'), 0, ',', ' ') }} $</td>
                <td colspan="2" class="text-center">-</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Document généré automatiquement le {{ date('d/m/Y à H:i:s') }}</p>
        <p>École Supérieure de Technologie - Système de Gestion Scolaire</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
