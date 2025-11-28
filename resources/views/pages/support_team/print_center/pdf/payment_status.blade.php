<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Paiements - {{ $class->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .success { color: green; }
        .danger { color: red; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        tr:nth-child(even) { background: #f9f9f9; }
        .total-row { background: #e0e0e0; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <p><strong>ÉTAT DE PAIEMENT</strong></p>
    </div>

    <div class="info">
        <span><strong>Classe:</strong> {{ $class->name }}</span>
        <span><strong>Année:</strong> {{ $year }}</span>
        <span><strong>Effectif:</strong> {{ count($paymentData) }} élèves</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">N°</th>
                <th>Nom de l'Élève</th>
                <th class="text-right" style="width: 100px;">Payé (FC)</th>
                <th class="text-right" style="width: 100px;">Reste (FC)</th>
                <th class="text-center" style="width: 70px;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPaid = 0;
                $totalBalance = 0;
            @endphp
            @foreach($paymentData as $index => $data)
                @php
                    $totalPaid += $data['total_paid'];
                    $totalBalance += $data['balance'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $data['student']->name ?? 'N/A' }}</strong></td>
                    <td class="text-right">{{ number_format($data['total_paid']) }}</td>
                    <td class="text-right {{ $data['balance'] > 0 ? 'danger' : '' }}">
                        {{ number_format($data['balance']) }}
                    </td>
                    <td class="text-center">
                        @if($data['balance'] == 0)
                            <span class="success">✓ Soldé</span>
                        @else
                            <span class="danger">En cours</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="text-right">{{ number_format($totalPaid) }} FC</td>
                <td class="text-right">{{ number_format($totalBalance) }} FC</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <strong>Résumé:</strong>
        <ul>
            <li>Élèves soldés: {{ collect($paymentData)->where('balance', 0)->count() }}</li>
            <li>Élèves avec solde: {{ collect($paymentData)->where('balance', '>', 0)->count() }}</li>
            <li>Taux de recouvrement: {{ $totalPaid + $totalBalance > 0 ? round(($totalPaid / ($totalPaid + $totalBalance)) * 100, 1) : 0 }}%</li>
        </ul>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
