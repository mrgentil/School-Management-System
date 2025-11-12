@extends('layouts.master')
@section('page_title', 'Inventaire - Rapports')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary-400">
        <h6 class="card-title text-white">
            <i class="icon-database2 mr-2"></i>
            Rapport d'Inventaire de la Bibliothèque
        </h6>
        <div class="header-elements">
            <a href="{{ route('librarian.reports.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour aux rapports
            </a>
        </div>
    </div>
</div>

<!-- Statistiques globales -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="icon-books icon-3x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $inventory['total_books'] }}</h3>
                        <span class="text-muted font-size-sm">Titres différents</span>
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
                        <i class="icon-stack2 icon-3x text-info"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $inventory['total_copies'] }}</h3>
                        <span class="text-muted font-size-sm">Exemplaires totaux</span>
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
                        <i class="icon-checkmark3 icon-3x text-success"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $inventory['available_copies'] }}</h3>
                        <span class="text-muted font-size-sm">Disponibles</span>
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
                        <i class="icon-book icon-3x text-warning"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $inventory['borrowed_copies'] }}</h3>
                        <span class="text-muted font-size-sm">Empruntés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribution par type de livre -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">
            <i class="icon-pie-chart mr-2"></i>
            Distribution par Type de Livre
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="40%">Type de livre</th>
                    <th width="20%" class="text-center">Nombre de titres</th>
                    <th width="20%" class="text-center">Total exemplaires</th>
                    <th width="20%">Visualisation</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalTitles = $inventory['by_category']->sum('count');
                @endphp
                @forelse($inventory['by_category'] as $category)
                <tr>
                    <td>
                        <strong>{{ ucfirst($category->book_type) ?: 'Non classifié' }}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-primary badge-pill">{{ $category->count }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info badge-pill">{{ $category->total }}</span>
                    </td>
                    <td>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-primary" 
                                 role="progressbar" 
                                 style="width: {{ $totalTitles > 0 ? ($category->count / $totalTitles) * 100 : 0 }}%">
                                {{ round(($category->count / $totalTitles) * 100, 1) }}%
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <i class="icon-info22 icon-2x text-muted d-block mb-2"></i>
                        <span class="text-muted">Aucune catégorie disponible</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Livres en rupture de stock -->
<div class="card mt-3">
    <div class="card-header bg-danger-100">
        <h6 class="card-title text-danger">
            <i class="icon-cross2 mr-2"></i>
            Livres en Rupture de Stock ({{ $inventory['out_of_stock']->count() }})
        </h6>
    </div>
    @if($inventory['out_of_stock']->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Type</th>
                    <th class="text-center">Total exemplaires</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory['out_of_stock'] as $book)
                <tr>
                    <td>
                        <div class="font-weight-semibold">{{ $book->name }}</div>
                        <small class="text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $book->author }}</td>
                    <td>
                        <span class="badge badge-secondary">{{ ucfirst($book->book_type) ?? 'Non classifié' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-danger">{{ $book->total_copies }} (tous empruntés)</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card-body text-center py-4">
        <i class="icon-checkmark-circle icon-3x text-success d-block mb-2"></i>
        <p class="text-muted mb-0">Aucun livre en rupture de stock</p>
    </div>
    @endif
</div>

<!-- Livres à faible stock -->
<div class="card mt-3">
    <div class="card-header bg-warning-100">
        <h6 class="card-title text-warning">
            <i class="icon-warning mr-2"></i>
            Livres à Faible Stock (≤ 2 exemplaires) - {{ $inventory['low_stock']->count() }}
        </h6>
    </div>
    @if($inventory['low_stock']->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Type</th>
                    <th class="text-center">Disponibles / Total</th>
                    <th class="text-center">État</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory['low_stock'] as $book)
                <tr>
                    <td>
                        <div class="font-weight-semibold">{{ $book->name }}</div>
                        <small class="text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $book->author }}</td>
                    <td>
                        <span class="badge badge-secondary">{{ ucfirst($book->book_type) ?? 'Non classifié' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">{{ $book->available_copies }} / {{ $book->total_copies }}</span>
                    </td>
                    <td class="text-center">
                        @if($book->available_copies == 0)
                            <span class="badge badge-danger">Rupture temporaire</span>
                        @elseif($book->available_copies == 1)
                            <span class="badge badge-warning">Stock critique</span>
                        @else
                            <span class="badge badge-warning">Stock faible</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card-body text-center py-4">
        <i class="icon-checkmark-circle icon-3x text-success d-block mb-2"></i>
        <p class="text-muted mb-0">Aucun livre à faible stock</p>
    </div>
    @endif
</div>

@section('styles')
<style>
    .bg-primary-400 {
        background-color: #42a5f5 !important;
    }
    
    .bg-danger-100 {
        background-color: #ffebee !important;
    }
    
    .bg-warning-100 {
        background-color: #fff8e1 !important;
    }
</style>
@endsection

@endsection
