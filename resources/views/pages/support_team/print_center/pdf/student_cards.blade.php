<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cartes - {{ $class->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 10px; }
        .cards-container { display: flex; flex-wrap: wrap; }
        .card { 
            width: 240px; 
            height: 150px; 
            border: 2px solid #333; 
            border-radius: 8px; 
            margin: 5px; 
            padding: 10px;
            display: inline-block;
            vertical-align: top;
            page-break-inside: avoid;
        }
        .card-header { 
            text-align: center; 
            border-bottom: 1px solid #333; 
            padding-bottom: 5px; 
            margin-bottom: 8px;
        }
        .card-header h4 { margin: 0; font-size: 11px; }
        .card-body { display: flex; }
        .photo-placeholder { 
            width: 60px; 
            height: 70px; 
            border: 1px solid #999; 
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            font-size: 8px;
            color: #999;
        }
        .info { flex: 1; }
        .info p { margin: 3px 0; font-size: 9px; }
        .info strong { font-size: 10px; }
        .card-footer { 
            text-align: center; 
            margin-top: 8px; 
            padding-top: 5px; 
            border-top: 1px dashed #ccc;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    @foreach($students->chunk(6) as $chunk)
        <div class="cards-container">
            @foreach($chunk as $record)
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $schoolName }}</h4>
                        <small>CARTE D'ÉLÈVE {{ $year }}</small>
                    </div>
                    <div class="card-body">
                        <div class="photo-placeholder">
                            @if($record->user->photo)
                                <img src="{{ public_path('storage/'.$record->user->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                PHOTO
                            @endif
                        </div>
                        <div class="info">
                            <p><strong>{{ $record->user->name ?? 'N/A' }}</strong></p>
                            <p>Classe: {{ $class->name }}</p>
                            <p>Genre: {{ $record->user->gender == 'Male' ? 'M' : 'F' }}</p>
                            <p>Né(e): {{ $record->user->dob ? \Carbon\Carbon::parse($record->user->dob)->format('d/m/Y') : '-' }}</p>
                            <p>ID: {{ str_pad($record->user->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        Validité: {{ $year }}
                    </div>
                </div>
            @endforeach
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>
