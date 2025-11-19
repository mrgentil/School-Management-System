<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte - Difficultés Scolaires</title>
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
            border-top: 5px solid #dc3545;
        }
        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .alert-icon {
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
        .alert-box {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .student-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .results-summary {
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            background: #fff5f5;
        }
        .average-score {
            font-size: 36px;
            font-weight: bold;
            color: #dc3545;
            margin: 10px 0;
        }
        .recommendations {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            border-radius: 0 8px 8px 0;
            padding: 20px;
            margin: 25px 0;
        }
        .recommendations h3 {
            color: #0066cc;
            margin-top: 0;
        }
        .recommendations ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        .recommendations li {
            margin: 8px 0;
            color: #495057;
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
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .contact-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .urgent {
            background: #721c24;
            color: white;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="alert-icon">⚠️</div>
            <h1>ALERTE - Difficultés Scolaires</h1>
            <div class="school-name">{{ $school_name }}</div>
        </div>

        <div class="content">
            <div class="alert-box">
                <h3 style="color: #721c24; margin-top: 0;">🚨 Attention Requise</h3>
                <p>Nous vous contactons concernant les difficultés scolaires rencontrées par votre enfant. Une intervention rapide est recommandée.</p>
            </div>

            <div class="student-info">
                <h2 style="margin-top: 0; color: #495057;">👨‍🎓 {{ $student_name }}</h2>
                <p style="margin: 5px 0;"><strong>Classe :</strong> {{ $class_name }}</p>
                <p style="margin: 5px 0;"><strong>Examen :</strong> {{ $exam_name }}</p>
                <p style="margin: 5px 0;"><strong>Position :</strong> {{ $position }}{{ $position == 1 ? 'er' : 'ème' }}</p>
            </div>

            <div class="results-summary">
                <h3 style="color: #dc3545; margin-top: 0;">Résultats Préoccupants</h3>
                <div class="average-score">{{ number_format($average, 1) }}%</div>
                <p style="color: #721c24; font-weight: bold;">Moyenne Insuffisante</p>
            </div>

            <div class="urgent">
                📞 INTERVENTION URGENTE RECOMMANDÉE
            </div>

            <div class="recommendations">
                <h3>🎯 Recommandations Immédiates</h3>
                <ul>
                    @foreach($recommendations as $recommendation)
                        <li>{{ $recommendation }}</li>
                    @endforeach
                </ul>
                
                <h4 style="color: #0066cc; margin-top: 20px;">Actions Suggérées :</h4>
                <ul>
                    <li><strong>Rendez-vous urgent :</strong> Contactez le professeur principal dans les 48h</li>
                    <li><strong>Soutien scolaire :</strong> Organisez des séances de rattrapage</li>
                    <li><strong>Suivi médical :</strong> Vérifiez s'il n'y a pas de problèmes de santé</li>
                    <li><strong>Environnement d'étude :</strong> Améliorez les conditions de travail à la maison</li>
                    <li><strong>Motivation :</strong> Encouragez et soutenez moralement votre enfant</li>
                </ul>
            </div>

            <div class="contact-info">
                <h4 style="color: #856404; margin-top: 0;">📞 Contacts Utiles</h4>
                <p><strong>Professeur Principal :</strong> Disponible du lundi au vendredi</p>
                <p><strong>Conseiller Pédagogique :</strong> Sur rendez-vous</p>
                <p><strong>Direction :</strong> Pour les cas urgents</p>
                <p><strong>Secrétariat :</strong> Pour prendre rendez-vous</p>
            </div>

            <div class="action-buttons">
                <a href="#" class="btn btn-primary">📅 Prendre Rendez-vous</a>
                <a href="#" class="btn btn-success">📋 Voir le Bulletin Complet</a>
            </div>

            <div style="background: #d1ecf1; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <h4 style="color: #0c5460; margin-top: 0;">💡 Message d'Encouragement</h4>
                <p style="color: #0c5460;">
                    Les difficultés scolaires sont temporaires et peuvent être surmontées avec le bon accompagnement. 
                    Nous sommes là pour vous aider et soutenir votre enfant dans cette période délicate. 
                    Ensemble, nous pouvons l'aider à retrouver confiance et réussite.
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ $school_name }}</strong></p>
            <p>Équipe Pédagogique - Suivi des Élèves</p>
            <p style="font-size: 12px; margin-top: 15px;">
                📧 Email d'alerte envoyé le {{ date('d/m/Y à H:i') }}
            </p>
            <p style="font-size: 12px; color: #dc3545;">
                <strong>Ceci est un message automatique nécessitant votre attention immédiate</strong>
            </p>
        </div>
    </div>
</body>
</html>
