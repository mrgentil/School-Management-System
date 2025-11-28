<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attestation - {{ $student->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .container { max-width: 600px; margin: 0 auto; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header h2 { margin: 10px 0; font-size: 16px; color: #666; }
        .title { text-align: center; margin: 40px 0; }
        .title h3 { font-size: 20px; text-decoration: underline; margin: 0; }
        .content { line-height: 2; text-align: justify; margin: 30px 0; }
        .signature { margin-top: 60px; text-align: right; }
        .signature p { margin: 5px 0; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ccc; padding-top: 10px; }
        .border-box { border: 3px double #333; padding: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="border-box">
            <div class="header">
                <h1>{{ $schoolName }}</h1>
                <h2>{{ $schoolAddress ?? 'République Démocratique du Congo' }}</h2>
            </div>

            <div class="title">
                <h3>ATTESTATION DE SCOLARITÉ</h3>
            </div>

            <div class="content">
                <p>
                    Je soussigné(e), Directeur(trice) de <strong>{{ $schoolName }}</strong>, 
                    atteste que :
                </p>

                <p style="text-align: center; margin: 30px 0;">
                    <strong style="font-size: 16px; text-transform: uppercase;">{{ $student->name }}</strong>
                </p>

                <p>
                    @if($student->gender == 'Male')
                        Né le
                    @else
                        Née le
                    @endif
                    <strong>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d/m/Y') : '_______________' }}</strong>,
                    est régulièrement 
                    @if($student->gender == 'Male')
                        inscrit
                    @else
                        inscrite
                    @endif
                    dans notre établissement pour l'année scolaire <strong>{{ $year }}</strong>.
                </p>

                @if($record && $record->my_class)
                <p>
                    @if($student->gender == 'Male')
                        Il
                    @else
                        Elle
                    @endif
                    est actuellement en classe de <strong>{{ $record->my_class->name }}</strong>.
                </p>
                @endif

                <p>
                    Cette attestation est délivrée à 
                    @if($student->gender == 'Male')
                        l'intéressé
                    @else
                        l'intéressée
                    @endif
                    pour servir et valoir ce que de droit.
                </p>
            </div>

            <div class="signature">
                <p>Fait à _________________, le {{ $date }}</p>
                <br><br><br>
                <p><strong>Le(La) Directeur(trice)</strong></p>
                <br><br>
                <p>_________________________</p>
                <p><em>(Signature et cachet)</em></p>
            </div>
        </div>

        <div class="footer">
            Document généré le {{ now()->format('d/m/Y à H:i') }} | {{ $schoolName }}
        </div>
    </div>
</body>
</html>
