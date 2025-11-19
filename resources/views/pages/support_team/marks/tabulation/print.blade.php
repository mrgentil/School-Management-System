<html>
<head>
    <title>Feuille de Tabulation - {{ ($my_class->full_name ?: $my_class->name).' '.$section->name.' - '.$ex->name.' ('.$year.')' }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/print_tabulation.css') }}" />
</head>
<body>
<div class="container">
    <div id="print" xmlns:margin-top="http://www.w3.org/1999/xhtml">
        {{--    Logo et détails de l'école--}}
        <table width="100%">
            <tr>
                {{--<td><img src="{{ $s['logo'] }}" style="max-height : 100px;"></td>--}}

                <td >
                    <strong><span style="color: #1b0c80; font-size: 25px;">{{ strtoupper(Qs::getSetting('system_name')) }}</span></strong><br/>
                    {{-- <strong><span style="color: #1b0c80; font-size: 20px;">KINSHASA, RDC</span></strong><br/>--}}
                    <strong><span
                                style="color: #000; font-size: 15px;"><i>{{ ucwords($s['address']) }}</i></span></strong><br/>
                    <strong><span style="color: #000; font-size: 15px;"> FEUILLE DE TABULATION POUR {{ strtoupper(($my_class->full_name ?: $my_class->name).' '.$section->name.' - '.$ex->name.' ('.$year.')' ) }}
                    </span></strong>
                </td>
            </tr>
        </table>
        <br/>

        {{--Background Logo--}}
        <div style="position: relative;  text-align: center; ">
            <img src="{{ $s['logo'] }}"
                 style="max-width: 500px; max-height:600px; margin-top: 60px; position:absolute ; opacity: 0.2; margin-left: auto;margin-right: auto; left: 0; right: 0;" />
        </div>

        {{-- Début de la Tabulation --}}
        <table style="width:100%; border-collapse:collapse; border: 1px solid #000; margin: 10px auto;" border="1">
            <thead>
            <tr>
                <th>#</th>
                <th>NOMS DES ÉTUDIANTS DE LA CLASSE</th>
                @foreach($subjects as $sub)
                    <th rowspan="2">{{ strtoupper($sub->slug ?: $sub->name) }}</th>
                @endforeach
             {{--   @if($ex->term == 3)
                    <th>TOTAL 1ER SEMESTRE</th>
                    <th>TOTAL 2ÈME SEMESTRE</th>
                    <th>TOTAL 3ÈME SEMESTRE</th>
                    <th style="color: darkred">TOTAL CUMULÉ</th>
                    <th style="color: darkblue">MOYENNE CUMULÉE</th>
                @endif--}}
                <th style="color: darkred">TOTAL</th>
                <th style="color: darkblue">MOYENNE</th>
                <th style="color: darkgreen">POSITION</th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: center">{{ $s->user->name }}</td>
                    @foreach($subjects as $sub)
                        <td>{{ $marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->$tex ?? '-' ?: '-' }}</td>
                    @endforeach

                    {{--@if($ex->term == 3)
                        --}}{{--1st term Total--}}{{--
                        <td>{{ Mk::getTermTotal($s->user_id, 1, $year) ?: '-' }}</td>
                        --}}{{--2nd Term Total--}}{{--
                        <td>{{ Mk::getTermTotal($s->user_id, 2, $year) ?: '-' }}</td>
                        --}}{{--3rd Term total--}}{{--
                        <td>{{ Mk::getTermTotal($s->user_id, 3, $year) ?: '-' }}</td>
                    @endif--}}

                    <td style="color: darkred">{{ $exr->where('student_id', $s->user_id)->first()->total ?: '-' }}</td>
                    <td style="color: darkblue">{{ $exr->where('student_id', $s->user_id)->first()->ave ?: '-' }}</td>
                    <td style="color: darkgreen">{!! Mk::getSuffix($exr->where('student_id', $s->user_id)->first()->pos) ?: '-' !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    window.print();
</script>
</body>
</html>
