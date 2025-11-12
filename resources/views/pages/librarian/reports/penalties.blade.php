@extends('layouts.master')
@section('page_title', 'Retards et Pénalités - Rapports')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-danger-400">
        <h6 class="card-title text-white">
            <i class="icon-alarm mr-2"></i>
            Rapport des Retards et Pénalités
        </h6>
        <div class="header-elements">
            <a href="{{ route('librarian.reports.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux rapports
            </a>
        </div>
    </div>
</div>

<!-- Filtres de période -->
<div class="card mt-3">
    <div class="card-body">
        <form method="GET" action="{{ route('librarian.reports.penalties') }}" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Du :</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Au :</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}">
            </div>
            <button type="submit" class="btn btn-danger">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques des retards -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-alarm icon-3x text-warning"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $penalties->count() }}</h3>
                        <span class="text-muted font-size-sm">Retours en retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-calendar icon-3x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $totalDaysLate }}</h3>
                        <span class="text-muted font-size-sm">Total jours de retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-stats-bars icon-3x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $penalties->count() > 0 ? round($totalDaysLate / $penalties->count(), 1) : 0 }}</h3>
                        <span class="text-muted font-size-sm">Moyenne jours/retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des pénalités -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Détail des Retards et Pénalités</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="25%">Étudiant</th>
                    <th width="30%">Livre</th>
                    <th width="15%" class="text-center">Date retour prévue</th>
                    <th width="15%" class="text-center">Date retour réel</th>
                    <th width="15%" class="text-center">Jours de retard</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penalties as $penalty)
                <tr>
                    <td>
                        @if($penalty->student && $penalty->student->user)
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="bg-danger-400 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <span class="text-white font-weight-bold font-size-sm">{{ strtoupper(substr($penalty->student->user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-semibold">{{ $penalty->student->user->name }}</div>
                                    <small class="text-muted">{{ $penalty->student->user->email ?? '' }}</small>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($penalty->book)
                            <div class="font-weight-semibold">{{ Str::limit($penalty->book->name, 40) }}</div>
                            <small class="text-muted">{{ $penalty->book->author ?? '' }}</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($penalty->expected_return_date)
                            {{ \Carbon\Carbon::parse($penalty->expected_return_date)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($penalty->actual_return_date)
                            {{ \Carbon\Carbon::parse($penalty->actual_return_date)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge 
                            @if($penalty->days_late > 30) badge-danger
                            @elseif($penalty->days_late > 14) badge-warning
                            @else badge-info
                            @endif badge-pill font-size-lg">
                            {{ $penalty->days_late ?? 0 }} jour{{ ($penalty->days_late ?? 0) > 1 ? 's' : '' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="icon-checkmark-circle icon-3x text-success d-block mb-3"></i>
                        <h5 class="text-muted">Aucun retard enregistré</h5>
                        <p class="text-muted">Tous les livres ont été retournés à temps durant cette période.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($penalties->count() > 0)
    <div class="card-footer bg-light">
        <div class="row">
            <div class="col-md-4">
                <strong>Total retours en retard : </strong>
                <span class="text-danger font-size-lg">{{ $penalties->count() }}</span>
            </div>
            <div class="col-md-4 text-center">
                <strong>Total jours de retard : </strong>
                <span class="text-warning font-size-lg">{{ $totalDaysLate }}</span>
            </div>
            <div class="col-md-4 text-right">
                <strong>Moyenne jours/retard : </strong>
                <span class="text-info font-size-lg">{{ round($totalDaysLate / $penalties->count(), 1) }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Graphique de distribution -->
@if($penalties->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Analyse des Retards</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-info mb-2">{{ $penalties->where('days_late', '<=', 7)->count() }}</h4>
                    <p class="text-muted mb-0">Retards ≤ 7 jours</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-warning mb-2">{{ $penalties->whereBetween('days_late', [8, 14])->count() }}</h4>
                    <p class="text-muted mb-0">Retards 8-14 jours</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 border rounded">
                    <h4 class="text-danger mb-2">{{ $penalties->where('days_late', '>', 14)->count() }}</h4>
                    <p class="text-muted mb-0">Retards > 14 jours</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@section('styles')
<style>
    .bg-danger-400 {
        background-color: #ef5350 !important;
    }
</style>
@endsection

@endsection
