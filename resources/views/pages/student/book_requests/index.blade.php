@extends('layouts.student')

@section('title') {{ __('Demandes de Livres') }} @endsection

@section('content')
    <div class="page-title">
        <h4 class="mb-3">
            {{ __('Mes Demandes de Livres') }}
            <a href="{{ route('student.book-requests.create') }}" class="btn btn-primary btn-sm float-right">
                <i class="fas fa-plus"></i> {{ __('Nouvelle Demande') }}
            </a>
        </h4>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($demandes->isEmpty())
                        <div class="alert alert-info">
                            {{ __('Vous n\'avez aucune demande de livre pour le moment.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Titre') }}</th>
                                        <th>{{ __('Auteur') }}</th>
                                        <th>{{ __('Date de demande') }}</th>
                                        <th>{{ __('Statut') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($demandes as $demande)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $demande->titre }}</td>
                                            <td>{{ $demande->auteur }}</td>
                                            <td>{{ $demande->date_demande->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge {{ $demande->badge_class }}">
                                                    {{ $demande->statut_formate }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('student.book-requests.show', $demande->id) }}" 
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
                            {{ $demandes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
