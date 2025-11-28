<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notes - {{ $subject->name }} - {{ $class->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .info { margin-bottom: 15px; }
        .info span { margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background: #f0f0f0; font-size: 10px; }
        .text-left { text-align: left; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        tr:nth-child(even) { background: #f9f9f9; }
        .success { color: green; }
        .danger { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <p><strong>FICHE DE NOTES</strong></p>
    </div>

    <div class="info">
        <span><strong>Classe:</strong> {{ $class->name }}</span>
        <span><strong>Matière:</strong> {{ $subject->name }}</span>
        <span><strong>Année:</strong> {{ $year }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">N°</th>
                <th class="text-left" style="width: 150px;">Nom de l'Élève</th>
                <th>P1</th>
                <th>P2</th>
                <th>Moy S1</th>
                <th>Exam S1</th>
                <th>P3</th>
                <th>P4</th>
                <th>Moy S2</th>
                <th>Exam S2</th>
                <th>Moy. Gén.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($marks as $index => $mark)
                @php
                    $moyGen = null;
                    $count = 0;
                    $sum = 0;
                    foreach(['p1_avg', 'p2_avg', 'p3_avg', 'p4_avg', 's1_exam', 's2_exam'] as $field) {
                        if($mark->$field !== null) {
                            $sum += $mark->$field;
                            $count++;
                        }
                    }
                    if($count > 0) $moyGen = round($sum / $count, 1);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left"><strong>{{ $mark->user->name ?? 'N/A' }}</strong></td>
                    <td>{{ $mark->p1_avg ?? '-' }}</td>
                    <td>{{ $mark->p2_avg ?? '-' }}</td>
                    <td>
                        @if($mark->p1_avg && $mark->p2_avg)
                            {{ round(($mark->p1_avg + $mark->p2_avg) / 2, 1) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $mark->s1_exam ?? '-' }}</td>
                    <td>{{ $mark->p3_avg ?? '-' }}</td>
                    <td>{{ $mark->p4_avg ?? '-' }}</td>
                    <td>
                        @if($mark->p3_avg && $mark->p4_avg)
                            {{ round(($mark->p3_avg + $mark->p4_avg) / 2, 1) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $mark->s2_exam ?? '-' }}</td>
                    <td class="{{ $moyGen && $moyGen >= 10 ? 'success' : 'danger' }}">
                        <strong>{{ $moyGen ?? '-' }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
