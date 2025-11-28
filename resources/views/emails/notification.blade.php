<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .message {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #2196F3;
            color: #fff;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px 0;
        }
        .footer {
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-info { background: #E3F2FD; color: #1976D2; }
        .badge-success { background: #E8F5E9; color: #388E3C; }
        .badge-warning { background: #FFF3E0; color: #F57C00; }
        .badge-danger { background: #FFEBEE; color: #D32F2F; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $schoolName ?? 'École' }}</h1>
            <p>Système de Gestion Scolaire</p>
        </div>
        
        <div class="content">
            <p class="greeting">Bonjour {{ $user->name }},</p>
            
            <div class="message">
                @if(isset($data['type']))
                    @switch($data['type'])
                        @case('grade')
                            <span class="badge badge-info">📝 Note</span>
                            @break
                        @case('absence')
                            <span class="badge badge-danger">⚠️ Absence</span>
                            @break
                        @case('event')
                            <span class="badge badge-success">📅 Événement</span>
                            @break
                        @case('payment')
                            <span class="badge badge-warning">💰 Paiement</span>
                            @break
                        @case('message')
                            <span class="badge badge-info">📩 Message</span>
                            @break
                        @case('bulletin')
                            <span class="badge badge-success">📄 Bulletin</span>
                            @break
                    @endswitch
                    <br><br>
                @endif
                
                {!! nl2br(e($messageContent)) !!}
            </div>
            
            <p style="text-align: center;">
                <a href="{{ config('app.url') }}" class="button">Accéder à l'application</a>
            </p>
            
            <p style="color: #666; font-size: 14px;">
                Cordialement,<br>
                L'équipe {{ $schoolName ?? 'de l\'école' }}
            </p>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par le système de gestion scolaire.</p>
            <p>© {{ date('Y') }} {{ $schoolName ?? 'École' }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
