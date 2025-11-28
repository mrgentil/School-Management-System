@extends('layouts.master')
@section('page_title', 'Présences - ' . $child->user->name)

@section('content')
{{-- En-tête --}}
<div class="card">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $child->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                     class="rounded-circle" width="60" height="60">
            </div>
            <div class="col">
                <h4 class="mb-0">{{ $child->user->name }}</h4>
                <small class="text-muted">{{ $child->my_class->full_name ?? $child->my_class->name }}</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('parent.attendance.index') }}" class="btn btn-secondary">
                    <i class="icon-arrow-left7 mr-1"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Filtre mois --}}
<div class="card">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label>Mois</label>
                <select name="month" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label>Année</label>
                <select name="year" class="form-control">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ $yearNum == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="icon-search4 mr-1"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Statistiques du mois --}}
<div class="row">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['present'] }}</h2>
                <small>Présent(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['absent'] }}</h2>
                <small>Absent(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['late'] }}</h2>
                <small>Retard(s)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h2 class="mb-0">{{ $stats['rate'] }}%</h2>
                <small>Taux de présence</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Calendrier visuel --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-calendar3 mr-2"></i> Calendrier du mois</h6>
            </div>
            <div class="card-body">
                @php
                    $daysInMonth = Carbon\Carbon::create($yearNum, $month)->daysInMonth;
                    $firstDay = Carbon\Carbon::create($yearNum, $month, 1)->dayOfWeek;
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered text-center mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $day = 1; @endphp
                            @for($week = 0; $week < 6; $week++)
                                @if($day > $daysInMonth) @break @endif
                                <tr>
                                    @for($dow = 0; $dow < 7; $dow++)
                                        @if(($week == 0 && $dow < $firstDay) || $day > $daysInMonth)
                                            <td></td>
                                        @else
                                            @php
                                                $status = $calendarData[$day] ?? null;
                                                $bgClass = '';
                                                if ($status == 'present') $bgClass = 'bg-success text-white';
                                                elseif ($status == 'absent') $bgClass = 'bg-danger text-white';
                                                elseif ($status == 'late') $bgClass = 'bg-warning';
                                                elseif ($status == 'excused') $bgClass = 'bg-info text-white';
                                            @endphp
                                            <td class="{{ $bgClass }}" style="width: 14%;">
                                                {{ $day }}
                                            </td>
                                            @php $day++; @endphp
                                        @endif
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-center">
                    <span class="badge badge-success mr-2">✓ Présent</span>
                    <span class="badge badge-danger mr-2">✗ Absent</span>
                    <span class="badge badge-warning mr-2">⏰ Retard</span>
                    <span class="badge badge-info">📝 Excusé</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Détails --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Détail des présences</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Matière</th>
                                <th>Statut</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($att->date)->format('d/m/Y') }}</td>
                                    <td>{{ $att->subject->name ?? 'Général' }}</td>
                                    <td>
                                        @if($att->status == 'present')
                                            <span class="badge badge-success">✓ Présent</span>
                                        @elseif($att->status == 'absent')
                                            <span class="badge badge-danger">✗ Absent</span>
                                        @elseif($att->status == 'late')
                                            <span class="badge badge-warning">⏰ Retard</span>
                                        @else
                                            <span class="badge badge-info">📝 Excusé</span>
                                        @endif
                                    </td>
                                    <td><small class="text-muted">{{ $att->notes ?? '-' }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Aucune donnée pour ce mois
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
