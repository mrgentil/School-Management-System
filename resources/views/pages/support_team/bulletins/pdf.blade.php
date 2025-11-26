<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bulletin - {{ $student->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .container {
            padding: 15px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px double #003366;
            padding-bottom: 10px;
        }

        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .header-left, .header-center, .header-right {
            display: table-cell;
            vertical-align: middle;
        }

        .header-left {
            width: 20%;
            text-align: left;
        }

        .header-center {
            width: 60%;
            text-align: center;
        }

        .header-right {
            width: 20%;
            text-align: right;
        }

        .logo {
            width: 70px;
            height: 70px;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .school-motto {
            font-style: italic;
            font-size: 10px;
            color: #666;
        }

        .school-contact {
            font-size: 9px;
            color: #666;
        }

        .bulletin-title {
            background: linear-gradient(135deg, #003366, #0066cc);
            background: #003366;
            color: white;
            padding: 8px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            border-radius: 5px;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background: #f9f9f9;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            padding: 3px 5px;
        }

        .info-label {
            font-weight: bold;
            color: #003366;
            width: 120px;
        }

        /* Grades Table */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .grades-table th, .grades-table td {
            border: 1px solid #003366;
            padding: 6px 4px;
            text-align: center;
        }

        .grades-table th {
            background: #003366;
            color: white;
            font-size: 10px;
        }

        .grades-table .subject-name {
            text-align: left;
            font-weight: bold;
        }

        .grades-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        .grades-table .total-row {
            background: #e6f0ff !important;
            font-weight: bold;
        }

        .grade-excellent { color: #28a745; font-weight: bold; }
        .grade-good { color: #17a2b8; }
        .grade-average { color: #ffc107; }
        .grade-fail { color: #dc3545; }

        /* Statistics */
        .stats-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stats-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 5px;
        }

        .stats-box-inner {
            border: 2px solid #003366;
            border-radius: 5px;
            padding: 8px;
        }

        .stats-value {
            font-size: 20px;
            font-weight: bold;
            color: #003366;
        }

        .stats-label {
            font-size: 9px;
            color: #666;
        }

        /* Appreciation */
        .appreciation-section {
            border: 2px solid #003366;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .appreciation-title {
            font-weight: bold;
            color: #003366;
            margin-bottom: 5px;
        }

        .appreciation-box {
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
        }

        .appreciation-success { background: #d4edda; color: #155724; }
        .appreciation-info { background: #d1ecf1; color: #0c5460; }
        .appreciation-primary { background: #cce5ff; color: #004085; }
        .appreciation-secondary { background: #e2e3e5; color: #383d41; }
        .appreciation-warning { background: #fff3cd; color: #856404; }
        .appreciation-danger { background: #f8d7da; color: #721c24; }

        /* Signatures */
        .signatures {
            display: table;
            width: 100%;
            margin-top: 30px;
        }

        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 10px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }

        .signature-title {
            font-weight: bold;
            font-size: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Grading Scale */
        .grading-scale {
            font-size: 8px;
            margin-top: 10px;
            padding: 5px;
            background: #f5f5f5;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-top">
                <div class="header-left">
                    @if(isset($school['logo']))
                        <img src="{{ public_path('global_assets/images/logo.png') }}" class="logo" alt="Logo">
                    @endif
                </div>
                <div class="header-center">
                    <div class="school-name">{{ $school['name'] ?? 'ÉCOLE' }}</div>
                    @if($school['motto'] ?? false)
                        <div class="school-motto">"{{ $school['motto'] }}"</div>
                    @endif
                    <div class="school-contact">
                        {{ $school['address'] ?? '' }}
                        @if($school['phone'] ?? false) | Tél: {{ $school['phone'] }} @endif
                        @if($school['email'] ?? false) | {{ $school['email'] }} @endif
                    </div>
                </div>
                <div class="header-right">
                    <strong>Année Scolaire</strong><br>
                    {{ $year }}
                </div>
            </div>
        </div>

        {{-- Title --}}
        <div class="bulletin-title">
            BULLETIN DE NOTES - {{ $type == 'period' ? 'PÉRIODE ' . $period : 'SEMESTRE ' . $semester }}
            <br><small style="font-size: 12px;">Année Scolaire {{ $year }}</small>
        </div>

        {{-- Student Info --}}
        <div class="student-info">
            <table class="info-table">
                <tr>
                    <td class="info-label">Nom et Prénom:</td>
                    <td><strong>{{ $student->user->name }}</strong></td>
                    <td class="info-label">Matricule:</td>
                    <td>{{ $student->adm_no }}</td>
                </tr>
                <tr>
                    <td class="info-label">Classe:</td>
                    <td>{{ $student->my_class->full_name ?? $student->my_class->name }}</td>
                    <td class="info-label">{{ $type == 'period' ? 'Période:' : 'Semestre:' }}</td>
                    <td><strong>{{ $type == 'period' ? 'P' . $period : 'S' . $semester }}</strong></td>
                </tr>
                <tr>
                    <td class="info-label">Rang:</td>
                    <td><strong style="font-size: 14px; color: #003366;">{{ $rank }}{{ $rank == 1 ? 'er' : 'ème' }} / {{ $totalStudents }} élèves</strong></td>
                    <td class="info-label">Moyenne:</td>
                    <td><strong style="font-size: 14px; color: {{ $stats['average'] >= 10 ? '#28a745' : '#dc3545' }};">{{ number_format($stats['average'], 2) }} / 20</strong></td>
                </tr>
            </table>
        </div>

        {{-- Grades Table --}}
        <table class="grades-table">
            <thead>
                <tr>
                    <th style="width: 22%;">MATIÈRE</th>
                    @if($type == 'period')
                        <th style="width: 15%;">POINTS</th>
                        <th style="width: 8%;">MAX</th>
                    @else
                        <th style="width: 10%;">Moy. Périodes</th>
                        <th style="width: 10%;">-</th>
                        <th style="width: 10%;">EXAM</th>
                        <th style="width: 10%;">TOTAL</th>
                    @endif
                    <th style="width: 8%;">%</th>
                    <th style="width: 6%;">NOTE</th>
                    <th style="width: 12%;">APPRÉCIATION</th>
                </tr>
            </thead>
            <tbody>
                @php $totalCoef = 0; $totalPoints = 0; @endphp
                @foreach($bulletinData as $data)
                    <tr>
                        <td class="subject-name">{{ $data['subject'] }}</td>
                        @if($type == 'period')
                            <td><strong>{{ $data['total_obtained'] !== null ? number_format($data['total_obtained'], 2) : '-' }}</strong></td>
                            <td>{{ $data['total_max'] ?? 20 }}</td>
                        @else
                            {{-- Pour le semestre: afficher période moyenne et examen --}}
                            <td>{{ $data['period_average'] !== null ? number_format($data['period_average'], 1).'%' : '-' }}</td>
                            <td>-</td>
                            <td>{{ $data['exam_average'] !== null ? number_format($data['exam_average'], 1).'%' : '-' }}</td>
                            <td><strong>{{ $data['total_obtained'] !== null ? number_format($data['total_obtained'], 2) : '-' }}</strong></td>
                        @endif
                        <td>
                            @if($data['percentage'] !== null)
                                <span class="{{ $data['percentage'] >= 70 ? 'grade-excellent' : ($data['percentage'] >= 50 ? 'grade-good' : 'grade-fail') }}">
                                    {{ number_format($data['percentage'], 1) }}%
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td><strong>{{ $data['grade'] }}</strong></td>
                        <td style="font-size: 9px;">{{ $data['remark'] }}</td>
                    </tr>
                    @if($data['percentage'] !== null)
                        @php 
                            $totalCoef += $data['coefficient']; 
                            $totalPoints += ($data['total_obtained'] ?? 0) * $data['coefficient']; 
                        @endphp
                    @endif
                @endforeach
                
                {{-- Total Row --}}
                <tr class="total-row">
                    <td class="subject-name">TOTAL GÉNÉRAL</td>
                    @if($type == 'period')
                        <td colspan="2">-</td>
                    @else
                        <td colspan="4">-</td>
                    @endif
                    <td>
                        <strong style="font-size: 12px; color: {{ $stats['average'] >= 50 ? '#28a745' : '#dc3545' }};">
                            {{ number_format(($stats['total_points'] ?? 0) / max($totalCoef, 1) * 5, 1) }}%
                        </strong>
                    </td>
                    <td><strong>-</strong></td>
                    <td style="font-size: 9px;"><strong>{{ $appreciation['text'] }}</strong></td>
                </tr>
            </tbody>
        </table>

        {{-- Statistics --}}
        <div class="stats-section">
            <div class="stats-box">
                <div class="stats-box-inner">
                    <div class="stats-value">{{ number_format($stats['average'], 2) }}</div>
                    <div class="stats-label">Moyenne Générale</div>
                </div>
            </div>
            <div class="stats-box">
                <div class="stats-box-inner">
                    <div class="stats-value">{{ $rank }}<sup>{{ $rank == 1 ? 'er' : 'ème' }}</sup></div>
                    <div class="stats-label">Rang / {{ $totalStudents }}</div>
                </div>
            </div>
            <div class="stats-box">
                <div class="stats-box-inner">
                    <div class="stats-value" style="color: #28a745;">{{ $stats['passed'] }}</div>
                    <div class="stats-label">Matières Réussies</div>
                </div>
            </div>
            <div class="stats-box">
                <div class="stats-box-inner">
                    <div class="stats-value" style="color: #dc3545;">{{ $stats['failed'] }}</div>
                    <div class="stats-label">Matières Échouées</div>
                </div>
            </div>
        </div>

        {{-- Appreciation --}}
        <div class="appreciation-section">
            <div class="appreciation-title">APPRÉCIATION GÉNÉRALE DU CONSEIL DE CLASSE</div>
            <div class="appreciation-box appreciation-{{ $appreciation['class'] }}">
                {{ $appreciation['text'] }}
                @if($stats['average'] >= 10)
                    - Admis(e) à poursuivre
                @else
                    - Doit redoubler ses efforts
                @endif
            </div>
            <div style="margin-top: 10px;">
                <strong>Observations:</strong>
                <div style="border: 1px solid #ddd; min-height: 40px; padding: 5px; margin-top: 5px;">
                    _____________________________________________________________________
                </div>
            </div>
        </div>

        {{-- Grading Scale --}}
        <div class="grading-scale">
            <strong>Échelle de notation:</strong> 
            A+ (18-20): Excellent | A (16-17.99): Très Bien | B+ (14-15.99): Bien | B (12-13.99): Assez Bien | C (10-11.99): Passable | D (8-9.99): Insuffisant | E (0-7.99): Très Faible
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-title">Le Titulaire de Classe</div>
                <div class="signature-line">Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Le Parent / Tuteur</div>
                <div class="signature-line">Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Le Chef d'Établissement</div>
                <div class="signature-line">Signature & Cachet</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            Document généré le {{ $generated_at }} | {{ $school['name'] ?? 'École' }} - Année Scolaire {{ $year }}
        </div>
    </div>
</body>
</html>
