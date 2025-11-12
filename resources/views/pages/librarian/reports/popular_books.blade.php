@extends('layouts.master')
@section('page_title', 'Livres Populaires - Rapports')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-success-400">
        <h6 class="card-title text-white">
            <i class="icon-stars mr-2"></i>
            Livres les Plus Populaires
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
        <form method="GET" action="{{ route('librarian.reports.popular-books') }}" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2">Du :</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2">Au :</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="icon-filter3 mr-2"></i> Filtrer
            </button>
        </form>
    </div>
</div>

<!-- Tableau des livres populaires -->
<div class="card mt-3">
    <div class="card-header">
        <h6 class="card-title">Top 20 des Livres les Plus Empruntés</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="bg-light">
                <tr>
                    <th width="5%">Rang</th>
                    <th width="10%">Couverture</th>
                    <th width="30%">Titre</th>
                    <th width="20%">Auteur</th>
                    <th width="15%">Catégorie</th>
                    <th width="10%" class="text-center">Emprunts</th>
                    <th width="10%" class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularBooks as $index => $book)
                <tr>
                    <td>
                        <span class="badge badge-flat 
                            @if($index == 0) badge-success 
                            @elseif($index == 1) badge-primary 
                            @elseif($index == 2) badge-warning 
                            @else badge-secondary @endif 
                            badge-pill" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                            <strong>{{ $index + 1 }}</strong>
                        </span>
                    </td>
                    <td>
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" 
                                 alt="{{ $book->name }}" 
                                 class="rounded shadow-sm" 
                                 style="width: 50px; height: 70px; object-fit: cover;">
                        @else
                            <div class="bg-success-400 rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 70px;">
                                <i class="icon-book3 text-white icon-2x"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="font-weight-semibold">{{ $book->name }}</div>
                        <small class="text-muted">ISBN: {{ $book->isbn ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $book->author }}</td>
                    <td>
                        <span class="badge badge-info">{{ $book->category ?? 'Non classifié' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success badge-pill font-size-lg">
                            <i class="icon-reading mr-1"></i>
                            {{ $book->requests_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($book->available_copies > 0)
                            <span class="badge badge-success">{{ $book->available_copies }} / {{ $book->total_copies }}</span>
                        @else
                            <span class="badge badge-danger">Épuisé</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="icon-info22 icon-3x text-muted d-block mb-3"></i>
                        <h5 class="text-muted">Aucune donnée disponible</h5>
                        <p class="text-muted">Aucun livre n'a été emprunté durant cette période.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('styles')
<style>
    .bg-success-400 {
        background-color: #66bb6a !important;
    }
</style>
@endsection

@endsection
