@extends('layouts.master')
@section('page_title', 'Étudiants Actifs - Rapports')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-info-400">
        <h6 class="card-title text-white">
            <i class="icon-users mr-2"></i>
            Étudiants les Plus Actifs
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
        <form method="GET" action="{{ route('librarian.reports.active-students') }}" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Du :</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Au :</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}">
            </div>
            <button type="submit" class="btn btn-info">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Statistiques globales -->
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-users icon-3x text-info-400"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $activeStudents->count() }}</h3>
                        <span class="text-muted">Étudiants actifs</span>
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
                        <i class="icon-book icon-3x text-success-400"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $activeStudents->sum('book_requests_count') }}</h3>
                        <span class="text-muted">Total emprunts</span>
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
                        <i class="icon-stats-growth icon-3x text-warning-400"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $activeStudents->count() > 0 ? round($activeStudents->sum('book_requests_count') / $activeStudents->count(), 1) : 0 }}</h3>
                        <span class="text-muted">Moyenne par étudiant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des étudiants actifs -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Top 50 des Étudiants les Plus Actifs</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="5%">Rang</th>
                    <th width="30%">Étudiant</th>
                    <th width="20%">Classe</th>
                    <th width="15%" class="text-center">Emprunts</th>
                    <th width="15%" class="text-center">En cours</th>
                    <th width="15%" class="text-center">Retournés</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeStudents as $index => $student)
                <tr>
                    <td>
                        <span class="badge badge-flat 
                            @if($index == 0) badge-info 
                            @elseif($index == 1) badge-primary 
                            @elseif($index == 2) badge-warning 
                            @else badge-secondary @endif 
                            badge-pill" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <strong>{{ $index + 1 }}</strong>
                        </span>
                    </td>
                    <td>
                        @if($student->user)
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <div class="bg-info-400 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white font-weight-bold">{{ strtoupper(substr($student->user->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="font-weight-semibold">{{ $student->user->name }}</div>
                                    <small class="text-muted">{{ $student->user->email }}</small>
                                </div>
                            </div>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($student->myClass)
                            <span class="badge badge-primary">{{ $student->myClass->name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info badge-pill font-size-lg">
                            <i class="icon-book mr-1"></i>
                            {{ $student->book_requests_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $borrowed = $student->bookRequests->where('status', 'borrowed')->count();
                        @endphp
                        @if($borrowed > 0)
                            <span class="badge badge-warning badge-pill">{{ $borrowed }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $returned = $student->bookRequests->where('status', 'returned')->count();
                        @endphp
                        @if($returned > 0)
                            <span class="badge badge-success badge-pill">{{ $returned }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="icon-info22 icon-3x text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Aucune donnée disponible</h5>
                        <p class="text-muted">Aucun étudiant n'a effectué d'emprunt durant cette période.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<style>
    .bg-info-400 {
        background-color: #29b6f6 !important;
    }
    
    .text-info-400 {
        color: #29b6f6 !important;
    }
    
    .text-success-400 {
        color: #66bb6a !important;
    }
    
    .text-warning-400 {
        color: #ffa726 !important;
    }
</style>
@endsection

@endsection
