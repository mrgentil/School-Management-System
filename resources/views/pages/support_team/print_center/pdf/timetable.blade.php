<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du temps - {{ $class->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: center; vertical-align: top; }
        th { background: #2196F3; color: white; }
        .course { background: #E3F2FD; padding: 5px; margin: 2px 0; border-radius: 3px; }
        .course strong { display: block; }
        .course small { color: #666; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $schoolName }}</h1>
        <p><strong>EMPLOI DU TEMPS</strong></p>
        <p>Classe: {{ $class->name }} | Année: {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 14%;">Lundi</th>
                <th style="width: 14%;">Mardi</th>
                <th style="width: 14%;">Mercredi</th>
                <th style="width: 14%;">Jeudi</th>
                <th style="width: 14%;">Vendredi</th>
                <th style="width: 14%;">Samedi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                    <td style="min-height: 200px;">
                        @if(isset($timetables[$day]))
                            @foreach($timetables[$day] as $course)
                                <div class="course">
                                    <strong>{{ $course->time_start }} - {{ $course->time_end }}</strong>
                                    {{ $course->subject->name ?? 'N/A' }}
                                    <small>{{ $course->teacher->name ?? '' }}</small>
                                </div>
                            @endforeach
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    @if($class->teacher)
    <p style="margin-top: 15px;"><strong>Titulaire:</strong> {{ $class->teacher->name }}</p>
    @endif

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
