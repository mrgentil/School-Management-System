<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reçu de Paiement - {{ $receipt->ref_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0 0;
            color: #7f8c8d;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .receipt-info {
            margin-bottom: 20px;
        }
        .receipt-info .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 3px solid #3498db;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-amount {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            text-align: center;
            color: #7f8c8d;
        }
        .signature {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 0;
            position: relative;
        }
        .signature-text {
            text-align: center;
            font-style: italic;
            margin-top: 5px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.1;
            z-index: -1;
            white-space: nowrap;
            font-weight: bold;
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="watermark">{{ config('app.name') }}</div>
    
    <div class="header">
        @if(file_exists(public_path('storage/' . config('settings.logo'))))
            <img src="{{ public_path('storage/' . config('settings.logo')) }}" alt="Logo" class="logo">
        @endif
        <h1>REÇU DE PAIEMENT</h1>
        <p>Référence: {{ $receipt->ref_no }}</p>
        <p>Date: {{ $receipt->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="receipt-info">
        <div class="section">
            <div class="section-title">Informations sur l'Étudiant</div>
            <table class="table">
                <tr>
                    <th width="30%">Nom Complet</th>
                    <td>{{ $receipt->paymentRecord->student->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Matricule</th>
                    <td>{{ $receipt->paymentRecord->student->student_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Classe</th>
                    <td>{{ $receipt->paymentRecord->student->studentRecord->myClass->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Section</th>
                    <td>{{ $receipt->paymentRecord->student->studentRecord->section->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Détails du Paiement</div>
            <table class="table">
                <tr>
                    <th width="30%">Libellé</th>
                    <td>{{ $receipt->paymentRecord->payment->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Montant Total</th>
                    <td>{{ number_format($receipt->paymentRecord->payment->amount ?? 0, 0, ',', ' ') }} $</td>
                </tr>
                <tr>
                    <th>Montant Payé</th>
                    <td class="total-amount">{{ number_format($receipt->amt_paid, 0, ',', ' ') }} $</td>
                </tr>
                <tr>
                    <th>Reste à Payer</th>
                    <td>{{ number_format(($receipt->paymentRecord->payment->amount ?? 0) - $receipt->paymentRecord->amt_paid, 0, ',', ' ') }} $</td>
                </tr>
                <tr>
                    <th>Méthode de Paiement</th>
                    <td>{{ ucfirst($receipt->payment_method) }}</td>
                </tr>
                @if($receipt->transaction_ref)
                <tr>
                    <th>Référence de Transaction</th>
                    <td>{{ $receipt->transaction_ref }}</td>
                </tr>
                @endif
                @if($receipt->notes)
                <tr>
                    <th>Notes</th>
                    <td>{{ $receipt->notes }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div class="signature-text">Signature et cachet de l'établissement</div>
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} - {{ config('app.url') }}</p>
        <p>Ce document est un reçu officiel. Veuillez le conserver précieusement.</p>
        <p>Reçu généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
