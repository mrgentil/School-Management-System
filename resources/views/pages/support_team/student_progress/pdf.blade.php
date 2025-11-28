<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport de Progression - {{ $student->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        .container { padding: 20px; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2196F3; padding-bottom: 15px; }
        .header h1 { color: #2196F3; font-size: 18px; margin-bottom: 5px; }
        .header p { color: #666; }
        
        .student-info { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px; }
        .student-info .label { font-weight: bold; width: 120px; }
        
        .stats-row { margin-bottom: 20px; }
        .stats-row table { width: 100%; }
        .stats-row td { width: 25%; text-align: center; padding: 10px; }
        .stat-box { background: #e3f2fd; padding: 10px; border-radius: 5px; }
        .stat-box.success { background: #e8f5e9; }
        .stat-box.warning { background: #fff3e0; }
        .stat-value { font-size: 18px; font-weight: bold; color: #1976D2; }
        .stat-label { font-size: 10px; color: #666; }
        
        .section { margin-bottom: 20px; }
        .section-title { background: #2196F3; color: white; padding: 8px 12px; font-weight: bold; margin-bottom: 10px; }
        
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 6px; text-align: center; }
        table.data th { background: #f5f5f5; font-weight: bold; }
        
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 10px; }
        .badge-success { background: #4CAF50; color: white; }
        .badge-danger { background: #f44336; color: white; }
        .badge-warning { background: #FF9800; color: white; }
        
        .trend { padding: 10px; text-align: center; border-radius: 5px; margin-bottom: 15px; }
        .trend.up { background: #e8f5e9; color: #2e7d32; }
        .trend.down { background: #ffebee; color: #c62828; }
        .trend.stable { background: #e3f2fd; color: #1565c0; }
        
        .footer { text-align: center; margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 10px; color: #666; }
        
        .strengths-weaknesses { margin-bottom: 15px; }
        .strengths-weaknesses td { width: 50%; vertical-align: top; padding: 10px; }
        .sw-box { border: 1px solid #ddd; padding: 10px; border-radius: 5px; min-height: 80px; }
        .sw-box.strengths { border-color: #4CAF50; }
        .sw-box.weaknesses { border-color: #f44336; }
        .sw-title { font-weight: bold; margin-bottom: 8px; }
        .sw-title.strengths { color: #4CAF50; }
        .sw-title.weaknesses { color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        {{-- En-tête --}}
        <div class="header">
            <h1>📊 RAPPORT DE PROGRESSION SCOLAIRE</h1>
            <p>{{ $school_name }} - Année {{ $year }}</p>
        </div>

        {{-- Infos élève --}}
        <div class="student-info">
            <table>
                <tr>
                    <td class="label">Nom de l'élève:</td>
                    <td><strong>{{ $student->user->name }}</strong></td>
                    <td class="label">N° Matricule:</td>
                    <td>{{ $student->adm_no }}</td>
                </tr>
                <tr>
                    <td class="label">Classe:</td>
                    <td>{{ $student->my_class->full_name ?? $student->my_class->name ?? 'N/A' }}</td>
                    <td class="label">Section:</td>
                    <td>{{ $student->section->name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        {{-- Tendance --}}
        <div class="trend {{ $trend['status'] }}">
            <strong>Tendance: {{ $trend['message'] }}</strong>
        </div>

        {{-- Statistiques --}}
        <div class="stats-row">
            <table>
                <tr>
                    <td>
                        <div class="stat-box">
                            <div class="stat-value">{{ $stats['general_average'] }}/20</div>
                            <div class="stat-label">Moyenne Générale</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-box success">
                            <div class="stat-value">{{ $stats['class_rank'] }}e/{{ $stats['class_total'] }}</div>
                            <div class="stat-label">Rang dans la Classe</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-box">
                            <div class="stat-value">{{ $stats['total_subjects'] }}</div>
                            <div class="stat-label">Matières Évaluées</div>
                        </div>
                    </td>
                    <td>
                        <div class="stat-box warning">
                            <div class="stat-value">{{ count($stats['strengths']) }}/{{ count($stats['weaknesses']) }}</div>
                            <div class="stat-label">Forces / Faiblesses</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Points forts / faibles --}}
        <table class="strengths-weaknesses">
            <tr>
                <td>
                    <div class="sw-box strengths">
                        <div class="sw-title strengths">✓ Points Forts (≥14/20)</div>
                        @forelse($stats['strengths'] as $subject)
                            • {{ $subject }}<br>
                        @empty
                            <em>Aucun point fort identifié</em>
                        @endforelse
                    </div>
                </td>
                <td>
                    <div class="sw-box weaknesses">
                        <div class="sw-title weaknesses">✗ À Améliorer (<10/20)</div>
                        @forelse($stats['weaknesses'] as $subject)
                            • {{ $subject }}<br>
                        @empty
                            <em>Aucune faiblesse identifiée</em>
                        @endforelse
                    </div>
                </td>
            </tr>
        </table>

        {{-- Évolution par période --}}
        <div class="section">
            <div class="section-title">📈 Évolution par Période</div>
            <table class="data">
                <thead>
                    <tr>
                        <th>Période 1</th>
                        <th>Période 2</th>
                        <th>Période 3</th>
                        <th>Période 4</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($progressData['averages'] as $avg)
                            <td>
                                @if($avg !== null)
                                    <span class="badge badge-{{ $avg >= 10 ? 'success' : 'danger' }}">{{ $avg }}/20</span>
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Détail par matière --}}
        <div class="section">
            <div class="section-title">📚 Détail par Matière</div>
            <table class="data">
                <thead>
                    <tr>
                        <th style="text-align: left;">Matière</th>
                        <th>P1</th>
                        <th>P2</th>
                        <th>P3</th>
                        <th>P4</th>
                        <th>Moyenne</th>
                        <th>Évolution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjectData as $subject)
                        @php
                            $evolution = 0;
                            $periods = [$subject['p1'], $subject['p2'], $subject['p3'], $subject['p4']];
                            $validPeriods = array_filter($periods, fn($v) => $v !== null);
                            if (count($validPeriods) >= 2) {
                                $values = array_values($validPeriods);
                                $evolution = end($values) - $values[0];
                            }
                        @endphp
                        <tr>
                            <td style="text-align: left;"><strong>{{ $subject['subject'] }}</strong></td>
                            <td>{{ $subject['p1'] ?? '-' }}</td>
                            <td>{{ $subject['p2'] ?? '-' }}</td>
                            <td>{{ $subject['p3'] ?? '-' }}</td>
                            <td>{{ $subject['p4'] ?? '-' }}</td>
                            <td><span class="badge badge-{{ $subject['average'] >= 10 ? 'success' : 'danger' }}">{{ $subject['average'] }}</span></td>
                            <td>
                                @if($evolution > 0.5)
                                    ↑ +{{ round($evolution, 1) }}
                                @elseif($evolution < -0.5)
                                    ↓ {{ round($evolution, 1) }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pied de page --}}
        <div class="footer">
            <p>Document généré le {{ $generated_at }} | {{ $school_name }}</p>
            <p>Ce rapport est un outil d'aide au suivi scolaire</p>
        </div>
    </div>
</body>
</html>
