@extends('layouts.master')

@section('title') {{ __('Gestion des Demandes de Livres') }} @endsection

@section('content')
    <div class="page-title">
        <h4 class="mb-3">
            {{ __('Gestion des Demandes de Livres') }}
        </h4>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('book-requests.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="statut">{{ __('Statut') }}</label>
                                    <select name="statut" id="statut" class="form-control">
                                        <option value="">{{ __('Tous les statuts') }}</option>
                                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                                        <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>{{ __('Approuvé') }}</option>
                                        <option value="refuse" {{ request('statut') == 'refuse' ? 'selected' : '' }}>{{ __('Refusé') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">{{ __('Rechercher') }}</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           value="{{ request('search') }}" placeholder="{{ __('Titre, auteur, ISBN, étudiant...') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> {{ __('Filtrer') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($demandes->isEmpty())
                        <div class="alert alert-info">
                            {{ __('Aucune demande de livre trouvée.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Titre') }}</th>
                                        <th>{{ __('Auteur') }}</th>
                                        <th>{{ __('Étudiant') }}</th>
                                        <th>{{ __('Date de demande') }}</th>
                                        <th>{{ __('Statut') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($demandes as $demande)
                                        <tr>
                                            <td>{{ $loop->iteration + (($demandes->currentPage() - 1) * $demandes->perPage()) }}</td>
                                            <td>{{ $demande->titre }}</td>
                                            <td>{{ $demande->auteur }}</td>
                                            <td>{{ $demande->etudiant->name ?? 'N/A' }}</td>
                                            <td>{{ $demande->date_demande->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $demande->badge_class }}">
                                                    {{ $demande->statut_formate }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('book-requests.show', $demande->id) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="{{ __('Voir les détails') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $demandes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
