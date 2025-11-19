<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats d'Examen - {{ $student_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .school-name {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .results-card {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .results-card.excellent {
            border-color: #28a745;
            background: #d4edda;
        }
        .results-card.good {
            border-color: #17a2b8;
            background: #d1ecf1;
        }
        .results-card.average {
            border-color: #ffc107;
            background: #fff3cd;
        }
        .results-card.poor {
            border-color: #dc3545;
            background: #f8d7da;
        }
        .average-score {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }
        .mention {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0;
        }
        .status {
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            margin: 10px 0;
        }
        .status.success {
            background: #28a745;
            color: white;
        }
        .status.danger {
            background: #dc3545;
            color: white;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .info-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
        }
        .encouragement {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Résultats d'Examen</h1>
            <div class="school-name">{{ $school_name }}</div>
        </div>

        <div class="content">
            <div class="student-info">
                <h2 style="margin-top: 0; color: #495057;">👨‍🎓 {{ $student_name }}</h2>
                <p style="margin: 5px 0;"><strong>Classe :</strong> {{ $class_name }}</p>
                <p style="margin: 5px 0;"><strong>Examen :</strong> {{ $exam_name }}</p>
            </div>

            @php
                $cardClass = 'average';
                if ($average >= 80) $cardClass = 'excellent';
                elseif ($average >= 60) $cardClass = 'good';
                elseif ($average < 40) $cardClass = 'poor';
            @endphp

            <div class="results-card {{ $cardClass }}">
                <div class="average-score">{{ number_format($average, 1) }}%</div>
                <div class="mention">{{ $mention }}</div>
                <div class="status {{ $status == 'ADMIS' ? 'success' : 'danger' }}">
                    {{ $status }}
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Position en Classe</div>
                    <div class="info-value">{{ $position }}{{ $position == 1 ? 'er' : 'ème' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mention Obtenue</div>
                    <div class="info-value">{{ $mention }}</div>
                </div>
            </div>

            @if($average >= 70)
                <div class="encouragement">
                    <h4 style="margin-top: 0; color: #0066cc;">🎉 Félicitations !</h4>
                    <p>Excellents résultats ! Continuez sur cette voie et maintenez cette motivation pour les prochains examens.</p>
                </div>
            @elseif($average >= 50)
                <div class="encouragement">
                    <h4 style="margin-top: 0; color: #0066cc;">👍 Bon Travail !</h4>
                    <p>Résultats satisfaisants. Avec un peu plus d'effort, vous pouvez encore améliorer vos performances.</p>
                </div>
            @else
                <div class="encouragement">
                    <h4 style="margin-top: 0; color: #cc6600;">📚 Encouragements</h4>
                    <p>Ne vous découragez pas. Avec du travail supplémentaire et du soutien, vous pouvez améliorer vos résultats. N'hésitez pas à demander de l'aide.</p>
                </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="#" class="btn">Voir le Bulletin Complet</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ $school_name }}</strong></p>
            <p>Cet email a été généré automatiquement. Pour toute question, contactez l'administration.</p>
            <p style="font-size: 12px; margin-top: 15px;">
                📧 Email envoyé le {{ date('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</body>
</html>
