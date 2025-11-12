@extends('layouts.master')
@section('page_title', 'Détails de la Demande')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Demande #{{ $demande->id }}</h6>
        <div class="header-elements">
            <a href="{{ route('student.library.requests.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left13 mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Informations du livre -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="font-weight-semibold">Informations du Livre</h6>
                <ul class="list list-unstyled mb-0">
                    <li><strong>Titre :</strong> {{ $demande->book->name ?? 'N/A' }}</li>
                    <li><strong>Auteur :</strong> {{ $demande->book->author ?? 'N/A' }}</li>
                    @if($demande->book && $demande->book->book_type)
                        <li><strong>Type :</strong> {{ $demande->book->book_type }}</li>
                    @endif
                </ul>
            </div>

            <div class="col-md-6">
                <h6 class="font-weight-semibold">Détails de la Demande</h6>
                <ul class="list list-unstyled mb-0">
                    <li><strong>Date de demande :</strong> {{ $demande->request_date ? \Carbon\Carbon::parse($demande->request_date)->format('d/m/Y') : 'N/A' }}</li>
                    <li><strong>Statut :</strong> 
                        @if($demande->status == 'pending')
                            <span class="badge badge-warning">En Attente</span>
                        @elseif($demande->status == 'approved')
                            <span class="badge badge-success">Approuvée</span>
                        @elseif($demande->status == 'borrowed')
                            <span class="badge badge-info">Emprunté</span>
                        @elseif($demande->status == 'returned')
                            <span class="badge badge-primary">Retourné</span>
                        @elseif($demande->status == 'rejected')
                            <span class="badge badge-danger">Rejetée</span>
                        @endif
                    </li>
                    @if($demande->expected_return_date)
                        <li><strong>Date de retour prévue :</strong> {{ \Carbon\Carbon::parse($demande->expected_return_date)->format('d/m/Y') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <hr>

        <!-- Statut de la demande -->
        @if($demande->status == 'rejected')
            <div class="alert alert-danger">
                <h5 class="alert-heading"><i class="icon-cross-circle mr-2"></i> Demande Rejetée</h5>
                <p class="mb-0">
                    <strong>Raison du refus :</strong><br>
                    {{ $demande->remarks ?? 'Aucune précision supplémentaire.' }}
                </p>
            </div>
        @elseif($demande->status == 'approved')
            <div class="alert alert-success">
                <h5 class="alert-heading"><i class="icon-checkmark-circle mr-2"></i> Demande Approuvée</h5>
                <p class="mb-0">
                    Votre demande a été approuvée ! Vous pouvez récupérer le livre à la bibliothèque.
                </p>
                @if($demande->expected_return_date)
                    <p class="mb-0 mt-2">
                        <strong>Date de retour prévue :</strong> {{ \Carbon\Carbon::parse($demande->expected_return_date)->format('d/m/Y') }}
                    </p>
                @endif
                @if($demande->remarks)
                    <p class="mb-0 mt-2">
                        <strong>Remarques :</strong> {{ $demande->remarks }}
                    </p>
                @endif
            </div>
        @elseif($demande->status == 'borrowed')
            <div class="alert alert-info">
                <h5 class="alert-heading"><i class="icon-book mr-2"></i> Livre Emprunté</h5>
                <p class="mb-0">
                    Vous avez actuellement ce livre en votre possession.
                </p>
                @if($demande->expected_return_date)
                    <p class="mb-0 mt-2">
                        <strong>Date de retour prévue :</strong> {{ \Carbon\Carbon::parse($demande->expected_return_date)->format('d/m/Y') }}
                    </p>
                    @if(\Carbon\Carbon::parse($demande->expected_return_date)->isPast())
                        <p class="mb-0 mt-2 text-danger">
                            <strong><i class="icon-warning"></i> Attention :</strong> Ce livre est en retard ! Veuillez le retourner au plus vite.
                        </p>
                    @endif
                @endif
            </div>
        @elseif($demande->status == 'returned')
            <div class="alert alert-primary">
                <h5 class="alert-heading"><i class="icon-undo2 mr-2"></i> Livre Retourné</h5>
                <p class="mb-0">
                    Vous avez retourné ce livre.
                </p>
                @if($demande->actual_return_date)
                    <p class="mb-0 mt-2">
                        <strong>Date de retour :</strong> {{ \Carbon\Carbon::parse($demande->actual_return_date)->format('d/m/Y') }}
                    </p>
                @endif
            </div>
        @else
            <div class="alert alert-warning">
                <h5 class="alert-heading"><i class="icon-hour-glass2 mr-2"></i> En Attente de Traitement</h5>
                <p class="mb-0">
                    Votre demande est en cours de traitement par le bibliothécaire.
                </p>
            </div>
        @endif

        @if($demande->book && $demande->book->description)
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6 class="font-weight-semibold">Description du Livre</h6>
                    <p>{{ $demande->book->description }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection