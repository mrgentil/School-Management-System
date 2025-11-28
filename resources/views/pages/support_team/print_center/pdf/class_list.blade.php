<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste - {{ $class->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        .info { margin-bottom: 15px; }
        .info span { margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #666; }
        .photo { width: 30px; height: 30px; border-radius: 50%; }
        tr:nth-child(even) { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <p>{{ $schoolAddress ?? '' }}</p>
        <p><strong>LISTE DES ÉLÈVES</strong></p>
    </div>

    <div class="info">
        <span><strong>Classe:</strong> {{ $class->name }}</span>
        <span><strong>Année:</strong> {{ $year }}</span>
        <span><strong>Effectif:</strong> {{ $students->count() }} élèves</span>
        @if($class->teacher)
            <span><strong>Titulaire:</strong> {{ $class->teacher->name }}</span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">N°</th>
                <th>Nom Complet</th>
                <th style="width: 60px;">Genre</th>
                <th style="width: 100px;">Date Naiss.</th>
                <th>Contact Parent</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $record->user->name ?? 'N/A' }}</strong></td>
                    <td>{{ $record->user->gender == 'Male' ? 'M' : 'F' }}</td>
                    <td>{{ $record->user->dob ? \Carbon\Carbon::parse($record->user->dob)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $record->user->phone ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
