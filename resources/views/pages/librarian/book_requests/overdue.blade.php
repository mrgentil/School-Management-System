@extends('layouts.master')
@section('page_title', 'Livres en Retard')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-danger-400">
        <h6 class="card-title text-white">
            <i class="icon-alarm mr-2"></i>
            Livres en Retard - Gestion des Retours
        </h6>
        <div class="header-elements">
            <div class="badge badge-light badge-pill font-size-lg px-3 py-2">
                <strong>{{ $overdueRequests->total() }}</strong> livre{{ $overdueRequests->total() > 1 ? 's' : '' }} en retard
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($overdueRequests->isEmpty())
            <div class="alert alert-success border-success alert-styled-left alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                <span class="font-weight-semibold">Excellent !</span> Aucun livre en retard actuellement.
            </div>
        @else
            <div class="row mb-3">
                @php
                    $criticalCount = $overdueRequests->filter(function($r) { 
                        return \Carbon\Carbon::parse($r->expected_return_date)->diffInDays(now()) > 14; 
                    })->count();
                    $warningCount = $overdueRequests->filter(function($r) { 
                        $days = \Carbon\Carbon::parse($r->expected_return_date)->diffInDays(now());
                        return $days > 7 && $days <= 14; 
                    })->count();
                    $recentCount = $overdueRequests->filter(function($r) { 
                        return \Carbon\Carbon::parse($r->expected_return_date)->diffInDays(now()) <= 7; 
                    })->count();
                @endphp
                
                <div class="col-md-4">
                    <div class="card bg-danger text-white mb-0">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mr-3 align-self-center">
                                    <i class="icon-warning2 icon-3x opacity-75"></i>
                                </div>
                                <div>
                                    <h3 class="font-weight-semibold mb-0">{{ $criticalCount }}</h3>
                                    <div class="text-uppercase font-size-sm opacity-75">+ de 14 jours</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning text-white mb-0">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mr-3 align-self-center">
                                    <i class="icon-alarm icon-3x opacity-75"></i>
                                </div>
                                <div>
                                    <h3 class="font-weight-semibold mb-0">{{ $warningCount }}</h3>
                                    <div class="text-uppercase font-size-sm opacity-75">7-14 jours</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-info text-white mb-0">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mr-3 align-self-center">
                                    <i class="icon-hour-glass2 icon-3x opacity-75"></i>
                                </div>
                                <div>
                                    <h3 class="font-weight-semibold mb-0">{{ $recentCount }}</h3>
                                    <div class="text-uppercase font-size-sm opacity-75">Moins de 7 jours</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($overdueRequests->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-danger-400">
                    <tr>
                        <th class="text-center" width="60">#</th>
                        <th width="20%">Étudiant</th>
                        <th width="25%">Livre</th>
                        <th class="text-center" width="120">Emprunté le</th>
                        <th class="text-center" width="120">Retour prévu</th>
                        <th class="text-center" width="140">Retard</th>
                        <th class="text-center" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueRequests as $request)
                        @php
                            $daysOverdue = \Carbon\Carbon::parse($request->expected_return_date)->diffInDays(now());
                            $severity = 'secondary';
                            $icon = 'icon-alarm';
                            if ($daysOverdue > 14) {
                                $severity = 'danger';
                                $icon = 'icon-warning2';
                            } elseif ($daysOverdue > 7) {
                                $severity = 'warning';
                                $icon = 'icon-alarm';
                            } elseif ($daysOverdue > 3) {
                                $severity = 'info';
                                $icon = 'icon-hour-glass2';
                            }
                        @endphp
                        <tr class="border-left-3 border-left-{{ $severity }}">
                            <td class="text-center">
                                <span class="badge badge-flat border-{{ $severity }} text-{{ $severity }}">{{ $request->id }}</span>
                            </td>
                            <td>
                                @if($request->student && $request->student->user)
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="bg-{{ $severity }}-400 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="text-white font-weight-bold">{{ strtoupper(substr($request->student->user->name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-semibold text-dark">{{ $request->student->user->name }}</div>
                                            <div class="text-muted">
                                                <small><i class="icon-mail5 mr-1"></i>{{ $request->student->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($request->book)
                                    <div class="media align-items-center">
                                        <div class="mr-3">
                                            <i class="icon-book3 icon-2x text-{{ $severity }}"></i>
                                        </div>
                                        <div class="media-body">
                                            <div class="font-weight-semibold text-dark">{{ $request->book->name }}</div>
                                            @if($request->book->author)
                                                <div class="text-muted">
                                                    <small><i class="icon-user mr-1"></i>{{ $request->book->author }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="text-muted">
                                    <i class="icon-calendar3 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="badge badge-flat border-danger text-danger-600">
                                    <i class="icon-alarm mr-1"></i>
                                    {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="badge badge-{{ $severity }} badge-pill px-3 py-2">
                                    <i class="{{ $icon }} mr-1"></i>
                                    <strong>{{ $daysOverdue }}</strong> jour{{ $daysOverdue > 1 ? 's' : '' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="list-icons list-icons-extended">
                                    <a href="{{ route('librarian.book-requests.show', $request->id) }}" 
                                       class="list-icons-item btn btn-sm btn-outline-primary" 
                                       data-toggle="tooltip" 
                                       title="Voir les détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                    
                                    <button type="button"
                                            class="list-icons-item btn btn-sm btn-outline-success ml-1" 
                                            data-toggle="modal"
                                            data-target="#returnModal{{ $request->id }}"
                                            title="Marquer comme retourné">
                                        <i class="icon-checkmark-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $overdueRequests->links() }}
        </div>
    @endif
</div>

<!-- Modals pour marquer comme retourné -->
@foreach($overdueRequests as $request)
<div id="returnModal{{ $request->id }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('librarian.book-requests.mark-returned', $request->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Marquer comme Retourné</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Livre :</strong> {{ $request->book->name ?? 'N/A' }}<br>
                        <strong>Étudiant :</strong> {{ $request->student->user->name ?? 'N/A' }}<br>
                        <strong>Retard :</strong> {{ \Carbon\Carbon::parse($request->expected_return_date)->diffInDays(now()) }} jours
                    </div>
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
@endforeach

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
