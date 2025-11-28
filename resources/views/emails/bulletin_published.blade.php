<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin Disponible</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .highlight {
            background: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Bulletin Scolaire Disponible</h1>
        <p>{{ $schoolName }}</p>
    </div>
    
    <div class="content">
        <p>Cher Parent/Tuteur,</p>
        
        <p>Nous avons le plaisir de vous informer que le bulletin de notes de votre enfant est maintenant disponible.</p>
        
        <div class="highlight">
            <p><strong>Élève :</strong> {{ $studentName }}</p>
            <p><strong>Classe :</strong> {{ $className }}</p>
            <p><strong>Période :</strong> {{ $typeLabel }}</p>
            <p><strong>Année scolaire :</strong> {{ $year }}</p>
        </div>
        
        <p>Vous pouvez consulter le bulletin en cliquant sur le bouton ci-dessous :</p>
        
        <p style="text-align: center;">
            <a href="{{ $url }}" class="btn">Voir le Bulletin</a>
        </p>
        
        <p>Si vous avez des questions concernant les résultats de votre enfant, n'hésitez pas à contacter l'établissement.</p>
        
        <p>Cordialement,<br>
        L'Administration de {{ $schoolName }}</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        <p>© {{ date('Y') }} {{ $schoolName }} - Tous droits réservés</p>
    </div>
</body>
</html>
