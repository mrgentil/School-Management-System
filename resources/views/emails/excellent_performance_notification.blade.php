<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Félicitations - Performance Excellente</title>
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
            border-top: 5px solid #28a745;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .celebration-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .congratulations-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
        }
        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .results-highlight {
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%);
        }
        .average-score {
            font-size: 48px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .mention {
            font-size: 20px;
            font-weight: bold;
            color: #155724;
            text-transform: uppercase;
            margin: 10px 0;
        }
        .achievements {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 0 8px 8px 0;
            padding: 20px;
            margin: 25px 0;
        }
        .achievements h3 {
            color: #856404;
            margin-top: 0;
        }
        .achievements ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .achievements li {
            margin: 8px 0;
            color: #495057;
        }
        .encouragements {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            border-radius: 0 8px 8px 0;
            padding: 20px;
            margin: 25px 0;
        }
        .encouragements h3 {
            color: #0066cc;
            margin-top: 0;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        .stat-item.highlight {
            border-color: #28a745;
            background: #d4edda;
        }
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
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
        .celebration-banner {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #333;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="celebration-icon">🎉</div>
            <h1>FÉLICITATIONS !</h1>
            <div class="school-name">{{ $school_name }}</div>
        </div>

        <div class="content">
            <div class="congratulations-box">
                <h2 style="color: #155724; margin-top: 0;">🏆 Performance Exceptionnelle !</h2>
                <p style="font-size: 16px; color: #155724;">
                    Nous avons le plaisir de vous informer des excellents résultats obtenus par votre enfant.
                </p>
            </div>

            <div class="student-info">
                <h2 style="margin-top: 0; color: #495057;">👨‍🎓 {{ $student_name }}</h2>
                <p style="margin: 5px 0;"><strong>Classe :</strong> {{ $class_name }}</p>
                <p style="margin: 5px 0;"><strong>Examen :</strong> {{ $exam_name }}</p>
            </div>

            <div class="results-highlight">
                <h3 style="color: #155724; margin-top: 0;">Résultats Remarquables</h3>
                <div class="average-score">{{ number_format($average, 1) }}%</div>
                <div class="mention">{{ $mention }}</div>
            </div>

            <div class="stats-grid">
                <div class="stat-item highlight">
                    <div class="stat-label">Position en Classe</div>
                    <div class="stat-value">{{ $position }}{{ $position == 1 ? 'er' : 'ème' }}</div>
                </div>
                <div class="stat-item highlight">
                    <div class="stat-label">Mention Obtenue</div>
                    <div class="stat-value">{{ $mention }}</div>
                </div>
            </div>

            <div class="celebration-banner">
                🌟 ÉLÈVE D'EXCELLENCE - RÉSULTATS EXCEPTIONNELS 🌟
            </div>

            <div class="achievements">
                <h3>🎯 Réalisations Remarquables</h3>
                <ul>
                    <li><strong>Performance académique exceptionnelle</strong> avec une moyenne de {{ number_format($average, 1) }}%</li>
                    <li><strong>Classement excellent</strong> - {{ $position }}{{ $position == 1 ? 'er' : 'ème' }} position</li>
                    <li><strong>Mention {{ $mention }}</strong> - Reconnaissance du niveau d'excellence</li>
                    <li><strong>Exemple pour les autres élèves</strong> de la classe</li>
                    <li><strong>Démonstration de sérieux et de persévérance</strong> dans le travail</li>
                </ul>
            </div>

            <div class="encouragements">
                <h3>💪 Messages d'Encouragement</h3>
                <ul>
                    @foreach($encouragements as $encouragement)
                        <li>{{ $encouragement }}</li>
                    @endforeach
                </ul>
                
                <h4 style="color: #0066cc; margin-top: 20px;">Conseils pour Maintenir l'Excellence :</h4>
                <ul>
                    <li><strong>Continuité :</strong> Maintenez le même rythme de travail</li>
                    <li><strong>Équilibre :</strong> Gardez un bon équilibre entre études et loisirs</li>
                    <li><strong>Partage :</strong> Aidez vos camarades en difficulté</li>
                    <li><strong>Objectifs :</strong> Fixez-vous de nouveaux défis stimulants</li>
                    <li><strong>Humilité :</strong> Restez humble malgré vos succès</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="#" class="btn btn-success">🏆 Voir le Bulletin Complet</a>
                <a href="#" class="btn btn-primary">📊 Historique des Performances</a>
            </div>

            <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 8px; padding: 25px; margin: 25px 0; text-align: center;">
                <h4 style="color: #1565c0; margin-top: 0;">🎓 Message de l'Équipe Pédagogique</h4>
                <p style="color: #1565c0; font-style: italic; font-size: 16px;">
                    "{{ $student_name }} fait preuve d'une excellence remarquable dans son parcours scolaire. 
                    Ses résultats témoignent de son sérieux, de sa motivation et de son potentiel exceptionnel. 
                    Nous sommes fiers de l'accompagner dans sa réussite et l'encourageons à poursuivre sur cette voie d'excellence."
                </p>
                <p style="color: #1565c0; font-weight: bold; margin-top: 15px;">
                    - L'Équipe Pédagogique de {{ $school_name }}
                </p>
            </div>

            <div style="background: #fff8e1; border: 2px solid #ffc107; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <h4 style="color: #f57f17; margin-top: 0;">🎁 Reconnaissance Spéciale</h4>
                <p style="color: #f57f17;">
                    En reconnaissance de ces excellents résultats, {{ $student_name }} sera mentionné(e) 
                    au tableau d'honneur de l'établissement et recevra un certificat d'excellence.
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ $school_name }}</strong></p>
            <p>Équipe Pédagogique - Suivi de l'Excellence</p>
            <p style="font-size: 12px; margin-top: 15px;">
                📧 Email de félicitations envoyé le {{ date('d/m/Y à H:i') }}
            </p>
            <p style="font-size: 12px; color: #28a745;">
                <strong>Continuez à briller ! 🌟</strong>
            </p>
        </div>
    </div>
</body>
</html>
