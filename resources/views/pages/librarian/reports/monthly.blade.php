@extends('layouts.master')
@section('page_title', 'Rapport Mensuel - Rapports')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-warning-400">
        <h6 class="card-title text-white">
            <i class="icon-calendar mr-2"></i>
            Rapport Mensuel de la Bibliothèque
        </h6>
        <div class="header-elements">
            <a href="{{ route('librarian.reports.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux rapports
            </a>
        </div>
    </div>
</div>

<!-- Sélection de période -->
<div class="card mt-3">
    <div class="card-body">
        <form method="GET" action="{{ route('librarian.reports.monthly') }}" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Mois :</label>
                <select name="month" class="form-control">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('fr')->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Année :</label>
                <select name="year" class="form-control">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-warning">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques mensuelles -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-stack2 icon-2x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_requests'] }}</h3>
                        <span class="text-muted font-size-sm">Total demandes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-checkmark-circle icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        <span class="text-muted font-size-sm">Approuvées</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-book icon-2x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['borrowed'] }}</h3>
                        <span class="text-muted font-size-sm">Empruntés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-undo2 icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['returned'] }}</h3>
                        <span class="text-muted font-size-sm">Retournés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-cross-circle icon-2x text-danger"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                        <span class="text-muted font-size-sm">Rejetées</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-alarm icon-2x text-danger"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['overdue'] }}</h3>
                        <span class="text-muted font-size-sm">En retard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-plus-circle2 icon-2x text-teal"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['new_books'] }}</h3>
                        <span class="text-muted font-size-sm">Nouveaux livres ajoutés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-checkmark3 icon-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_requests'] > 0 ? round(($stats['returned'] / $stats['total_requests']) * 100, 1) : 0 }}%</h3>
                        <span class="text-muted font-size-sm">Taux de retour</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphique des demandes journalières -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Évolution des Demandes par Jour</h6>
    </div>
    <div class="card-body">
        @if($dailyStats->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Demandes</th>
                        <th>Graphique</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $maxCount = $dailyStats->max('count');
                    @endphp
                    @foreach($dailyStats as $stat)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($stat->date)->locale('fr')->translatedFormat('d F Y') }}</td>
                        <td><strong>{{ $stat->count }}</strong></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-warning" 
                                     role="progressbar" 
                                     style="width: {{ $maxCount > 0 ? ($stat->count / $maxCount) * 100 : 0 }}%"
                                     aria-valuenow="{{ $stat->count }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $maxCount }}">
                                    {{ $stat->count }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="icon-info22 icon-3x text-muted d-block mb-3"></i>
            <p class="text-muted">Aucune donnée disponible pour ce mois.</p>
        </div>
        @endif
    </div>
</div>

@section('styles')
<style>
    .bg-warning-400 {
        background-color: #ffa726 !important;
    }
    
    .text-teal {
        color: #26a69a !important;
    }
</style>
@endsection

@endsection
