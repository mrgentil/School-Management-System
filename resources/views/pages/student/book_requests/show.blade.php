@extends('layouts.student')

@section('title') {{ __('Détails de la Demande') }} @endsection

@section('content')
    <div class="page-title">
        <h4 class="mb-3">
            {{ __('Détails de la Demande') }}
            <a href="{{ route('student.book-requests.index') }}" class="btn btn-secondary btn-sm float-right">
                <i class="fas fa-arrow-left"></i> {{ __('Retour à la liste') }}
            </a>
        </h4>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ $demande->titre }}
                        <span class="badge {{ $demande->badge_class }} float-right">
                            {{ $demande->statut_formate }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">{{ __('Auteur') }}</h6>
                            <p>{{ $demande->auteur }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">{{ __('Date de la demande') }}</h6>
                            <p>{{ $demande->date_demande->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($demande->isbn)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted">{{ __('ISBN') }}</h6>
                                <p>{{ $demande->isbn }}</p>
                            </div>
                        </div>
                    @endif

                    @if($demande->description)
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-muted">{{ __('Description') }}</h6>
                                <p>{!! nl2br(e($demande->description)) !!}</p>
                            </div>
                        </div>
                    @endif

                    @if($demande->statut !== 'en_attente')
                        <div class="row">
                            <div class="col-12">
                                <div class="alert {{ $demande->statut === 'approuve' ? 'alert-success' : 'alert-danger' }}">
                                    <h6 class="alert-heading">
                                        @if($demande->statut === 'approuve')
                                            <i class="fas fa-check-circle"></i> {{ __('Demande approuvée') }}
                                        @else
                                            <i class="fas fa-times-circle"></i> {{ __('Demande refusée') }}
                                        @endif
                                    </h6>
                                    <p class="mb-0">
                                        <strong>{{ $demande->statut === 'approuve' ? 'Réponse' : 'Raison du refus' }} :</strong><br>
                                        {{ $demande->reponse ?? 'Aucune précision supplémentaire.' }}
                                    </p>
                                    @if($demande->date_traitement)
                                        <p class="mb-0 mt-2">
                                            <small class="text-muted">
                                                Traité le {{ $demande->date_traitement->format('d/m/Y à H:i') }}
                                                @if($demande->bibliothecaire)
                                                    par {{ $demande->bibliothecaire->name }}
                                                @endif
                                            </small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer text-muted">
                    <a href="{{ route('student.book-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Retour') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
