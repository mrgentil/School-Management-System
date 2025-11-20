@extends('layouts.master')
@section('page_title', 'Détails de la Demande')
@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Demande #{{ $request->id }}</h6>
                <div class="header-elements">
                    <a href="{{ route('librarian.book-requests.index') }}" class="btn btn-light btn-sm">
                        <i class="icon-arrow-left13 mr-2"></i> Retour
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Informations de la demande -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-semibold">Informations Étudiant</h6>
                        <ul class="list list-unstyled mb-0">
                            <li><strong>Nom :</strong> {{ $request->student->user->name ?? 'N/A' }}</li>
                            <li><strong>Email :</strong> {{ $request->student->user->email ?? 'N/A' }}</li>
                            @if($request->student && $request->student->my_class)
                                <li><strong>Classe :</strong> {{ $request->student->my_class ? ($request->student->my_class->full_name ?: $request->student->my_class->name) : 'N/A' }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h6 class="font-weight-semibold">Informations Livre</h6>
                        <ul class="list list-unstyled mb-0">
                            <li><strong>Titre :</strong> {{ $request->book->name ?? 'N/A' }}</li>
                            <li><strong>Auteur :</strong> {{ $request->book->author ?? 'N/A' }}</li>
                            <li><strong>Disponible :</strong> 
                                @if($request->book && $request->book->available)
                                    <span class="badge badge-success">Oui</span>
                                @else
                                    <span class="badge badge-danger">Non</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <hr>

                <!-- Détails de la demande -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="font-weight-semibold">Détails de la Demande</h6>
                        <ul class="list list-unstyled mb-0">
                            <li><strong>Date de demande :</strong> {{ $request->request_date ? \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') : 'N/A' }}</li>
                            <li><strong>Statut :</strong> 
                                @if($request->status == 'pending')
                                    <span class="badge badge-warning">En Attente</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge badge-success">Approuvée</span>
                                @elseif($request->status == 'borrowed')
                                    <span class="badge badge-info">Emprunté</span>
                                @elseif($request->status == 'returned')
                                    <span class="badge badge-primary">Retourné</span>
                                @elseif($request->status == 'rejected')
                                    <span class="badge badge-danger">Rejetée</span>
                                @endif
                            </li>
                            @if($request->expected_return_date)
                                <li><strong>Date de retour prévue :</strong> {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}</li>
                            @endif
                        </ul>
                    </div>

                    @if($request->remarks)
                    <div class="col-md-6">
                        <h6 class="font-weight-semibold">Remarques</h6>
                        <p>{{ $request->remarks }}</p>
                    </div>
                    @endif
                </div>

                @if($request->approved_by)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="font-weight-semibold">Informations de Traitement</h6>
                        <ul class="list list-unstyled mb-0">
                            <li><strong>Traité par :</strong> ID {{ $request->approved_by }}</li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions</h6>
            </div>
            <div class="card-body">
                @if($request->status == 'pending')
                    <!-- Approuver -->
                    <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveModal">
                        <i class="icon-checkmark-circle mr-2"></i> Approuver
                    </button>

                    <!-- Rejeter -->
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                        <i class="icon-cross-circle mr-2"></i> Rejeter
                    </button>
                @endif

                @if($request->status == 'approved')
                    <!-- Marquer comme emprunté -->
                    <form action="{{ route('librarian.book-requests.mark-borrowed', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info btn-block" onclick="return confirm('Confirmer que le livre a été remis à l\'étudiant ?')">
                            <i class="icon-book mr-2"></i> Marquer comme Emprunté
                        </button>
                    </form>
                @endif

                @if($request->status == 'borrowed')
                    <!-- Marquer comme retourné -->
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#returnModal">
                        <i class="icon-undo2 mr-2"></i> Marquer comme Retourné
                    </button>

                    <!-- Envoyer un rappel -->
                    <form action="{{ route('librarian.book-requests.send-reminder', $request->id) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="icon-mail5 mr-2"></i> Envoyer un Rappel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Approuver -->
<div id="approveModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('librarian.book-requests.approve', $request->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approuver la Demande</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date de retour prévue <span class="text-danger">*</span></label>
                        <input type="date" name="expected_return_date" class="form-control" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="form-group">
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Remarques ou instructions..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="icon-checkmark-circle mr-2"></i> Approuver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rejeter -->
<div id="rejectModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('librarian.book-requests.reject', $request->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Rejeter la Demande</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Raison du rejet <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Expliquez pourquoi cette demande est rejetée..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="icon-cross-circle mr-2"></i> Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Retourner -->
<div id="returnModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('librarian.book-requests.mark-returned', $request->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Marquer comme Retourné</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>État du livre <span class="text-danger">*</span></label>
                        <select name="condition" class="form-control" required>
                            <option value="">Sélectionner...</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Bon</option>
                            <option value="fair">Moyen</option>
                            <option value="damaged">Endommagé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (optionnel)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Remarques sur l'état du livre..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-undo2 mr-2"></i> Confirmer le Retour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
