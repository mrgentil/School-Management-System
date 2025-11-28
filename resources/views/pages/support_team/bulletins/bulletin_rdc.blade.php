<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Bulletin de Notes - {{ $student->user->name }}</title>
    @php
        // Détecter si on est en mode PDF (pas de request disponible en PDF)
        $isPdf = !isset($_SERVER['HTTP_HOST']) || (isset($pdf_mode) && $pdf_mode);
        
        // Convertir les images en base64 pour PDF
        $flagPath = public_path('rdc.jpg');
        $emblemPath = public_path('citoyennete.jpg');
        
        $flagBase64 = '';
        $emblemBase64 = '';
        
        if (file_exists($flagPath)) {
            $flagBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($flagPath));
        }
        if (file_exists($emblemPath)) {
            $emblemBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($emblemPath));
        }
    @endphp
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10px;
            line-height: 1.2;
            background: white;
            color: #000;
        }
        
        .bulletin-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 8mm;
            background: white;
        }
        
        /* En-tête */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }
        
        .header-left {
            width: 60px;
            text-align: center;
        }
        
        .header-left img {
            width: 50px;
            height: auto;
        }
        
        .flag-placeholder {
            width: 50px;
            height: 35px;
            border: 1px solid #000;
            display: flex;
            flex-direction: column;
        }
        
        .flag-blue { background: #007FFF; height: 33.33%; }
        .flag-yellow { background: #F7D618; height: 33.33%; }
        .flag-red { background: #CE1126; height: 33.33%; }
        
        .header-center {
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }
        
        .header-center h1 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        
        .header-center h2 {
            font-size: 10px;
            font-weight: normal;
            margin-bottom: 2px;
        }
        
        .header-right {
            width: 60px;
            text-align: center;
        }
        
        .header-right img {
            width: 50px;
            height: auto;
        }
        
        .emblem-placeholder {
            width: 50px;
            height: 50px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
        }
        
        /* Info section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 9px;
        }
        
        .info-left, .info-right {
            width: 48%;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 80px;
        }
        
        .info-value {
            flex: 1;
            border-bottom: 1px dotted #000;
            padding-left: 5px;
        }
        
        .id-box {
            border: 1px solid #000;
            padding: 3px 8px;
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block;
        }
        
        /* Titre du bulletin */
        .bulletin-title {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin: 10px 0;
            padding: 5px;
            background: #f0f0f0;
            border: 1px solid #000;
        }
        
        /* Tableau principal */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 10px;
        }
        
        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        
        .grades-table th {
            background: #e0e0e0;
            font-weight: bold;
        }
        
        .grades-table .branch-name {
            text-align: left;
            padding-left: 5px;
            font-weight: normal;
        }
        
        .grades-table .maxima-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        .grades-table .total-row {
            background: #d0d0d0;
            font-weight: bold;
        }
        
        .semester-header {
            background: #c0c0c0 !important;
        }
        
        .sub-header {
            font-size: 7px;
        }
        
        /* Section décisions */
        .decisions-section {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 9px;
        }
        
        .decision-item {
            display: flex;
            align-items: center;
            margin: 3px 0;
        }
        
        .decision-checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
        }
        
        /* Signatures */
        .signatures-section {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 9px;
        }
        
        .signature-block {
            text-align: center;
            width: 30%;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 5px;
        }
        
        /* Pied de page */
        .footer {
            margin-top: 15px;
            font-size: 8px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
        .footer p {
            margin: 2px 0;
        }
        
        .mention-biffer {
            font-style: italic;
        }
        
        .code-ref {
            text-align: right;
            font-weight: bold;
            margin-top: 5px;
        }
        
        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .bulletin-container {
                padding: 5mm;
            }
            
            .no-print {
                display: none;
            }
        }
        
        /* Boutons d'action */
        .action-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }
        
        .action-buttons button {
            padding: 8px 15px;
            margin-left: 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .btn-print {
            background: #28a745;
            color: white;
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    @if(!$isPdf)
    <!-- Boutons d'action (masqués en PDF) -->
    <div class="action-buttons no-print">
        <button class="btn-back" onclick="history.back()">← Retour</button>
        <button class="btn-print" onclick="window.print()">🖨️ Imprimer</button>
    </div>
    @endif

    <div class="bulletin-container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                @if($flagBase64)
                    <img src="{{ $flagBase64 }}" alt="Drapeau RDC" style="width: 60px; height: auto;">
                @else
                    <div class="flag-placeholder">
                        <div class="flag-blue"></div>
                        <div class="flag-yellow"></div>
                        <div class="flag-red"></div>
                    </div>
                @endif
            </div>
            <div class="header-center">
                <h1>REPUBLIQUE DEMOCRATIQUE DU CONGO</h1>
                <h2>MINISTERE DE L'ENSEIGNEMENT PRIMAIRE, SECONDAIRE ET TECHNIQUE</h2>
                <h2>INITIATION A LA NOUVELLE CITOYENNETE</h2>
            </div>
            <div class="header-right">
                @if($emblemBase64)
                    <img src="{{ $emblemBase64 }}" alt="Armoiries" style="width: 60px; height: auto;">
                @else
                    <div class="emblem-placeholder">Armoiries</div>
                @endif
            </div>
        </div>

        <!-- N° ID -->
        <div class="id-box">N° ID. {{ $studentRecord->adm_no ?? '' }}</div>

        <!-- Informations école/élève -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-row">
                    <span class="info-label">PROVINCE :</span>
                    <span class="info-value">{{ $school['province'] ?? 'KINSHASA' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">VILLE :</span>
                    <span class="info-value">{{ $school['city'] ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">COMMUNE :</span>
                    <span class="info-value">{{ $school['commune'] ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ECOLE :</span>
                    <span class="info-value">{{ $school['name'] ?? config('app.name') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">CODE :</span>
                    <span class="info-value">{{ $school['code'] ?? '' }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-row">
                    <span class="info-label">ELEVE :</span>
                    <span class="info-value">{{ $student->user->name ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">SEXE :</span>
                    <span class="info-value">{{ $student->user->gender == 'Male' ? 'M' : ($student->user->gender == 'Female' ? 'F' : ($student->user->gender ?? '')) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NE(E) A :</span>
                    <span class="info-value"></span>
                </div>
                <div class="info-row">
                    <span class="info-label">LE :</span>
                    <span class="info-value">{{ $student->user->dob ? \Carbon\Carbon::parse($student->user->dob)->format('d/m/Y') : '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">CLASSE :</span>
                    <span class="info-value">{{ $studentRecord->my_class->full_name ?? $studentRecord->my_class->name ?? '' }} {{ $studentRecord->section->name ?? '' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">N° PERM. :</span>
                    <span class="info-value">{{ $studentRecord->adm_no ?? '' }}</span>
                </div>
            </div>
        </div>

        <!-- Titre du bulletin -->
        <div class="bulletin-title">
            BULLETIN DE {{ $type == 'semester' ? 'SEMESTRE ' . $semester : 'PERIODE ' . $period }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            ANNEE SCOLAIRE {{ $year }}
        </div>

        <!-- Tableau des notes -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th rowspan="3" style="width: 120px;">BRANCHES</th>
                    <th colspan="4" class="semester-header">PREMIER SEMESTRE</th>
                    <th colspan="4" class="semester-header">SECOND SEMESTRE</th>
                    <th rowspan="3" style="width: 35px;">T.G.</th>
                    <th colspan="2">EXAMEN DE</th>
                </tr>
                <tr>
                    <th colspan="2" class="sub-header">TR. JOURNAL.</th>
                    <th rowspan="2" style="width: 30px;">EXAM</th>
                    <th rowspan="2" style="width: 30px;">TOT.</th>
                    <th colspan="2" class="sub-header">TR. JOURNAL.</th>
                    <th rowspan="2" style="width: 30px;">EXAM</th>
                    <th rowspan="2" style="width: 30px;">TOT.</th>
                    <th colspan="2">REPECHAGE</th>
                </tr>
                <tr>
                    <th class="sub-header" style="width: 25px;">1<sup>ère</sup> P</th>
                    <th class="sub-header" style="width: 25px;">2<sup>ème</sup> P</th>
                    <th class="sub-header" style="width: 25px;">3<sup>ème</sup> P</th>
                    <th class="sub-header" style="width: 25px;">4<sup>ème</sup> P</th>
                    <th class="sub-header" style="width: 25px;">%</th>
                    <th class="sub-header" style="width: 40px;">SIGN.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalMaxP1 = 0;
                    $totalMaxP2 = 0;
                    $totalMaxExam1 = 0;
                    $totalMaxS1 = 0;
                    $totalMaxP3 = 0;
                    $totalMaxP4 = 0;
                    $totalMaxExam2 = 0;
                    $totalMaxS2 = 0;
                    $totalMaxTG = 0;
                    
                    $totalP1 = 0;
                    $totalP2 = 0;
                    $totalExam1 = 0;
                    $totalS1 = 0;
                    $totalP3 = 0;
                    $totalP4 = 0;
                    $totalExam2 = 0;
                    $totalS2 = 0;
                    $totalTG = 0;
                @endphp
                
                @foreach($bulletinData as $data)
                    @php
                        // Utiliser les vraies configurations de cotes
                        $maxPerPeriod = $data['period_max'] ?? 20;
                        $maxExam = $data['exam_max'] ?? $maxPerPeriod;
                        $maxSemester = ($maxPerPeriod * 2) + $maxExam;
                        $maxTG = $maxSemester * 2;
                        
                        // Mise à jour des totaux max
                        $totalMaxP1 += $maxPerPeriod;
                        $totalMaxP2 += $maxPerPeriod;
                        $totalMaxExam1 += $maxExam;
                        $totalMaxS1 += $maxSemester;
                        $totalMaxP3 += $maxPerPeriod;
                        $totalMaxP4 += $maxPerPeriod;
                        $totalMaxExam2 += $maxExam;
                        $totalMaxS2 += $maxSemester;
                        $totalMaxTG += $maxTG;
                        
                        // Notes selon la période/semestre sélectionné
                        // On remplit uniquement les colonnes correspondant au filtre
                        $p1 = '';
                        $p2 = '';
                        $exam1 = '';
                        $s1Total = '';
                        $p3 = '';
                        $p4 = '';
                        $exam2 = '';
                        $s2Total = '';
                        $tg = '';
                        
                        if ($type == 'period') {
                            // Période 1: seulement P1
                            if ($period >= 1) {
                                $p1 = $data['p1_avg'] ?? ($data['total_obtained'] ?? '');
                            }
                            // Période 2: P1 + P2
                            if ($period >= 2) {
                                $p2 = $data['p2_avg'] ?? '';
                            }
                            // Période 3: P1 + P2 + Exam1 + S1 + P3
                            if ($period >= 3) {
                                $exam1 = $data['s1_exam'] ?? '';
                                $s1Total = $data['s1_total'] ?? '';
                                $p3 = $data['p3_avg'] ?? ($data['total_obtained'] ?? '');
                            }
                            // Période 4: tout sauf Exam2 et S2
                            if ($period >= 4) {
                                $p4 = $data['p4_avg'] ?? '';
                            }
                        } else {
                            // Semestre 1: P1, P2, Exam1, S1 Total
                            if ($semester >= 1) {
                                $p1 = $data['p1_avg'] ?? '';
                                $p2 = $data['p2_avg'] ?? '';
                                $exam1 = $data['s1_exam'] ?? ($data['exam_average'] ?? '');
                                $s1Total = $data['s1_total'] ?? ($data['total_obtained'] ?? '');
                            }
                            // Semestre 2: tout
                            if ($semester >= 2) {
                                $p3 = $data['p3_avg'] ?? '';
                                $p4 = $data['p4_avg'] ?? '';
                                $exam2 = $data['s2_exam'] ?? ($data['exam_average'] ?? '');
                                $s2Total = $data['s2_total'] ?? ($data['total_obtained'] ?? '');
                                $tg = $data['total_general'] ?? '';
                            }
                        }
                        
                        // Mise à jour des totaux
                        if (is_numeric($p1)) $totalP1 += $p1;
                        if (is_numeric($p2)) $totalP2 += $p2;
                        if (is_numeric($exam1)) $totalExam1 += $exam1;
                        if (is_numeric($s1Total)) $totalS1 += $s1Total;
                        if (is_numeric($p3)) $totalP3 += $p3;
                        if (is_numeric($p4)) $totalP4 += $p4;
                        if (is_numeric($exam2)) $totalExam2 += $exam2;
                        if (is_numeric($s2Total)) $totalS2 += $s2Total;
                        if (is_numeric($tg)) $totalTG += $tg;
                    @endphp
                    <tr>
                        <td class="branch-name">{{ $data['subject'] ?? 'Matière' }}</td>
                        <td>{{ $p1 !== '' && is_numeric($p1) ? number_format($p1, 1) : '' }}</td>
                        <td>{{ $p2 !== '' && is_numeric($p2) ? number_format($p2, 1) : '' }}</td>
                        <td>{{ $exam1 !== '' && is_numeric($exam1) ? number_format($exam1, 1) : '' }}</td>
                        <td>{{ $s1Total !== '' && is_numeric($s1Total) ? number_format($s1Total, 1) : '' }}</td>
                        <td>{{ $p3 !== '' && is_numeric($p3) ? number_format($p3, 1) : '' }}</td>
                        <td>{{ $p4 !== '' && is_numeric($p4) ? number_format($p4, 1) : '' }}</td>
                        <td>{{ $exam2 !== '' && is_numeric($exam2) ? number_format($exam2, 1) : '' }}</td>
                        <td>{{ $s2Total !== '' && is_numeric($s2Total) ? number_format($s2Total, 1) : '' }}</td>
                        <td>{{ $tg !== '' && is_numeric($tg) ? number_format($tg, 1) : '' }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{-- Ligne MAXIMA par matière selon le filtre --}}
                    <tr style="font-size: 7px; background: #f5f5f5;">
                        <td class="branch-name" style="font-style: italic;">MAXIMA</td>
                        <td>{{ ($type == 'period' && $period >= 1) || $type == 'semester' ? $maxPerPeriod : '' }}</td>
                        <td>{{ ($type == 'period' && $period >= 2) || $type == 'semester' ? $maxPerPeriod : '' }}</td>
                        <td>{{ ($type == 'period' && $period >= 3) || $type == 'semester' ? $maxExam : '' }}</td>
                        <td>{{ ($type == 'period' && $period >= 3) || $type == 'semester' ? $maxSemester : '' }}</td>
                        <td>{{ ($type == 'period' && $period >= 3) || ($type == 'semester' && $semester >= 2) ? $maxPerPeriod : '' }}</td>
                        <td>{{ ($type == 'period' && $period >= 4) || ($type == 'semester' && $semester >= 2) ? $maxPerPeriod : '' }}</td>
                        <td>{{ $type == 'semester' && $semester >= 2 ? $maxExam : '' }}</td>
                        <td>{{ $type == 'semester' && $semester >= 2 ? $maxSemester : '' }}</td>
                        <td>{{ $type == 'semester' && $semester >= 2 ? $maxTG : '' }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                
                @php
                    // Déterminer quelles colonnes afficher selon le filtre
                    $showP1 = ($type == 'period' && $period >= 1) || ($type == 'semester');
                    $showP2 = ($type == 'period' && $period >= 2) || ($type == 'semester');
                    $showExam1 = ($type == 'period' && $period >= 3) || ($type == 'semester');
                    $showS1 = ($type == 'period' && $period >= 3) || ($type == 'semester');
                    $showP3 = ($type == 'period' && $period >= 3) || ($type == 'semester' && $semester >= 2);
                    $showP4 = ($type == 'period' && $period >= 4) || ($type == 'semester' && $semester >= 2);
                    $showExam2 = ($type == 'semester' && $semester >= 2);
                    $showS2 = ($type == 'semester' && $semester >= 2);
                    $showTG = ($type == 'semester' && $semester >= 2);
                @endphp
                
                <!-- Ligne MAXIMA GENERAUX -->
                <tr class="maxima-row">
                    <td class="branch-name">MAXIMA GENERAUX</td>
                    <td>{{ $showP1 ? $totalMaxP1 : '' }}</td>
                    <td>{{ $showP2 ? $totalMaxP2 : '' }}</td>
                    <td>{{ $showExam1 ? $totalMaxExam1 : '' }}</td>
                    <td>{{ $showS1 ? $totalMaxS1 : '' }}</td>
                    <td>{{ $showP3 ? $totalMaxP3 : '' }}</td>
                    <td>{{ $showP4 ? $totalMaxP4 : '' }}</td>
                    <td>{{ $showExam2 ? $totalMaxExam2 : '' }}</td>
                    <td>{{ $showS2 ? $totalMaxS2 : '' }}</td>
                    <td>{{ $showTG ? $totalMaxTG : '' }}</td>
                    <td></td>
                    <td></td>
                </tr>
                
                <!-- Ligne TOTAUX -->
                <tr class="total-row">
                    <td class="branch-name">TOTAUX</td>
                    <td>{{ $showP1 && $totalP1 > 0 ? $totalP1 : '' }}</td>
                    <td>{{ $showP2 && $totalP2 > 0 ? $totalP2 : '' }}</td>
                    <td>{{ $showExam1 && $totalExam1 > 0 ? $totalExam1 : '' }}</td>
                    <td>{{ $showS1 && $totalS1 > 0 ? $totalS1 : '' }}</td>
                    <td>{{ $showP3 && $totalP3 > 0 ? $totalP3 : '' }}</td>
                    <td>{{ $showP4 && $totalP4 > 0 ? $totalP4 : '' }}</td>
                    <td>{{ $showExam2 && $totalExam2 > 0 ? $totalExam2 : '' }}</td>
                    <td>{{ $showS2 && $totalS2 > 0 ? $totalS2 : '' }}</td>
                    <td>{{ $showTG && $totalTG > 0 ? $totalTG : '' }}</td>
                    <td></td>
                    <td></td>
                </tr>
                
                <!-- Ligne POURCENTAGE - Utiliser le pourcentage déjà calculé par le service -->
                <tr class="total-row">
                    <td class="branch-name">POURCENTAGE</td>
                    {{-- Pour période 1, utiliser le pourcentage global déjà calculé --}}
                    <td>{{ $showP1 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showP2 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showExam1 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showS1 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showP3 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showP4 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showExam2 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showS2 ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td>{{ $showTG ? number_format($stats['average'] ?? 0, 1) . '%' : '' }}</td>
                    <td></td>
                    <td></td>
                </tr>
                
                <!-- Ligne PLACE/NBRE ELEVES - Toujours afficher pour la période sélectionnée -->
                <tr>
                    <td class="branch-name">PLACE/NBRE ELEVES</td>
                    @if($type == 'period')
                        {{-- Pour les périodes, afficher la place dans la colonne de la période sélectionnée --}}
                        <td>{{ $period == 1 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td>{{ $period == 2 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td></td>
                        <td>{{ $period >= 3 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td>{{ $period == 3 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td>{{ $period == 4 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @else
                        {{-- Pour les semestres --}}
                        <td colspan="4">{{ $semester >= 1 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td colspan="4">{{ $semester >= 2 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                        <td>{{ $semester >= 2 ? ($rank ?? '-') . ' / ' . ($totalStudents ?? '-') : '' }}</td>
                    @endif
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <!-- Section Application/Conduite -->
        <table class="grades-table" style="width: 50%; margin-bottom: 10px;">
            <tr>
                <td class="branch-name" style="width: 120px;">APPLICATION</td>
                <td style="width: 100px;"></td>
                <td rowspan="3" style="vertical-align: top; padding: 5px; text-align: left; font-size: 9px;">
                    <div style="margin-bottom: 5px;">
                        <span style="display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px;"></span>
                        Passe (1)
                    </div>
                    <div style="margin-bottom: 5px;">
                        <span style="display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px;"></span>
                        Double (1)
                    </div>
                    <div>
                        <span style="display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 5px;"></span>
                        A échoué (1)
                    </div>
                </td>
            </tr>
            <tr>
                <td class="branch-name">CONDUITE</td>
                <td></td>
            </tr>
            <tr>
                <td class="branch-name">SIGN. RESPONSABLE</td>
                <td></td>
            </tr>
        </table>

        <!-- Signatures -->
        <div class="signatures-section">
            <div class="signature-block">
                <p>Le Titulaire de Classe</p>
                <div class="signature-line">
                    <p>Signature</p>
                </div>
            </div>
            <div class="signature-block">
                <p>Le Parent / Tuteur</p>
                <div class="signature-line">
                    <p>Signature</p>
                </div>
            </div>
            <div class="signature-block">
                <p>Le Chef d'Établissement</p>
                <div class="signature-line">
                    <p>Signature & Cachet</p>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>- L'élève ne pourra passer dans la classe supérieure s'il ne subit avec succès un examen de repêchage en ................................ (1)</p>
            <p>- L'élève passe dans la classe supérieure (1)</p>
            <p>- L'élève double sa classe (1)</p>
            <p>- L'élève a échoué et orienté vers ................................ (1)</p>
            <br>
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <div>
                    <p>Signature de l'élève</p>
                    <br><br>
                    <p>_______________________</p>
                </div>
                <div style="text-align: center;">
                    <p>Sceau de l'école</p>
                    <br><br>
                    <p></p>
                </div>
                <div style="text-align: right;">
                    <p>Fait à {{ $school['city'] ?? '..................' }}, le {{ now()->format('d/m/Y') }}</p>
                    <p>Le Chef d'Établissement,</p>
                    <br>
                    <p>Nom et signature</p>
                </div>
            </div>
            <br>
            <p class="mention-biffer">(1) Biffer la mention inutile</p>
            <p><strong>Note importante :</strong> Le bulletin est sans valeur s'il est raturé ou surchargé</p>
            <p class="code-ref">Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>

    <script>
        // Auto-print si demandé
        @if(request()->has('print'))
            window.onload = function() {
                window.print();
            };
        @endif
    </script>
</body>
</html>
