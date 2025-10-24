@extends('layouts.master')
@section('page_title', 'Tableau de Bord - Bibliothécaire')
@section('content')

<div class="row">
    <!-- Statistiques de la bibliothèque -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-blue-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\Book::count() }}</h3>
                    <span class="text-uppercase font-size-xs font-weight-bold">Total Livres</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-books icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\Book::where('status', 'available')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Livres Disponibles</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-checkmark3 icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-warning-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\BookRequest::where('status', 'borrowed')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Livres Empruntés</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-reading icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger-400 has-bg-image">
            <div class="media">
                <div class="media-body">
                    <h3 class="mb-0">{{ \App\Models\BookRequest::where('status', 'overdue')->count() }}</h3>
                    <span class="text-uppercase font-size-xs">Retards</span>
                </div>
                <div class="ml-3 align-self-center">
                    <i class="icon-alarm icon-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Demandes récentes -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Demandes d'Emprunt Récentes</h6>
                <div class="header-elements">
                    <a href="{{ route('book-requests.index') }}" class="btn btn-link btn-sm">Voir tout</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Livre</th>
                                <th>Date Demande</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\BookRequest::with(['user', 'book'])->latest()->take(5)->get() as $request)
                            <tr>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ $request->book->title }}</td>
                                <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->status == 'pending')
                                    <a href="{{ route('book-requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary">
                                        Traiter
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucune demande récente</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('books.create') }}" class="list-group-item list-group-item-action">
                        <i class="icon-plus2 mr-3"></i>Ajouter un Livre
                    </a>
                    <a href="{{ route('books.index') }}" class="list-group-item list-group-item-action">
                        <i class="icon-books mr-3"></i>Gérer les Livres
                    </a>
                    <a href="{{ route('book-requests.index') }}" class="list-group-item list-group-item-action">
                        <i class="icon-reading mr-3"></i>Demandes d'Emprunt
                    </a>
                    <a href="{{ route('study-materials.index') }}" class="list-group-item list-group-item-action">
                        <i class="icon-file-pdf mr-3"></i>Supports Pédagogiques
                    </a>
                </div>
            </div>
        </div>

        <!-- Livres populaires -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Livres Populaires</h6>
            </div>
            <div class="card-body">
                @forelse(\App\Models\Book::withCount('requests')->orderBy('requests_count', 'desc')->take(5)->get() as $book)
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <img src="{{ $book->cover_image ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                             alt="Cover" class="rounded" style="width: 40px; height: 50px; object-fit: cover;">
                    </div>
                    <div class="flex-1">
                        <h6 class="mb-0">{{ Str::limit($book->title, 30) }}</h6>
                        <small class="text-muted">{{ $book->author }}</small>
                        <div>
                            <small class="text-success">{{ $book->requests_count }} demandes</small>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center">Aucune donnée disponible</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
