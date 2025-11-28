<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Récapitulatif - {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; }
        .stats-box { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat { text-align: center; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .stat h2 { margin: 0; font-size: 24px; color: #2196F3; }
        .stat p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <p><strong>RÉCAPITULATIF GÉNÉRAL</strong></p>
        <p>Année scolaire: {{ $year }}</p>
    </div>

    <table style="width: 50%; margin: 0 auto 30px;">
        <tr>
            <td style="text-align: center; padding: 20px; border: 2px solid #2196F3;">
                <h2 style="margin: 0; color: #2196F3; font-size: 28px;">{{ $totalStudents }}</h2>
                <p style="margin: 5px 0 0;">Élèves inscrits</p>
            </td>
            <td style="text-align: center; padding: 20px; border: 2px solid #4CAF50;">
                <h2 style="margin: 0; color: #4CAF50; font-size: 28px;">{{ $totalTeachers }}</h2>
                <p style="margin: 5px 0 0;">Enseignants</p>
            </td>
            <td style="text-align: center; padding: 20px; border: 2px solid #FF9800;">
                <h2 style="margin: 0; color: #FF9800; font-size: 28px;">{{ count($classStats) }}</h2>
                <p style="margin: 5px 0 0;">Classes actives</p>
            </td>
        </tr>
    </table>

    <h3>Répartition par Classe</h3>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Classe</th>
                <th>Type</th>
                <th>Titulaire</th>
                <th class="text-center">Garçons</th>
                <th class="text-center">Filles</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBoys = 0; $totalGirls = 0; @endphp
            @foreach($classStats as $index => $stat)
                @php 
                    $totalBoys += $stat['boys']; 
                    $totalGirls += $stat['girls']; 
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $stat['class']->name }}</strong></td>
                    <td>{{ $stat['class']->class_type->name ?? '-' }}</td>
                    <td>{{ $stat['class']->teacher->name ?? 'Non assigné' }}</td>
                    <td class="text-center">{{ $stat['boys'] }}</td>
                    <td class="text-center">{{ $stat['girls'] }}</td>
                    <td class="text-center"><strong>{{ $stat['students'] }}</strong></td>
                </tr>
            @endforeach
            <tr style="background: #e0e0e0; font-weight: bold;">
                <td colspan="4">TOTAL GÉNÉRAL</td>
                <td class="text-center">{{ $totalBoys }}</td>
                <td class="text-center">{{ $totalGirls }}</td>
                <td class="text-center">{{ $totalStudents }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3>Répartition par Genre</h3>
        <p>
            <strong>Garçons:</strong> {{ $totalBoys }} ({{ $totalStudents > 0 ? round(($totalBoys / $totalStudents) * 100, 1) : 0 }}%)
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <strong>Filles:</strong> {{ $totalGirls }} ({{ $totalStudents > 0 ? round(($totalGirls / $totalStudents) * 100, 1) : 0 }}%)
        </p>
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} | {{ $schoolName }}
    </div>
</body>
</html>
