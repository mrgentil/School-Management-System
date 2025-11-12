@extends('layouts.master')
@section('page_title', 'Livres en Retard')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Livres en Retard</h6>
        <div class="header-elements">
            <span class="badge badge-danger badge-pill">{{ $overdueRequests->total() }} en retard</span>
        </div>
    </div>

    <div class="card-body">
        @if($overdueRequests->isEmpty())
            <div class="alert alert-success">
                <i class="icon-checkmark-circle mr-2"></i>
                Aucun livre en retard actuellement !
            </div>
        @else
            <div class="alert alert-warning">
                <i class="icon-warning mr-2"></i>
                <strong>{{ $overdueRequests->total() }}</strong> livre(s) sont actuellement en retard.
            </div>
        @endif
    </div>

    @if($overdueRequests->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Livre</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour prévue</th>
                        <th>Jours de retard</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overdueRequests as $request)
                        @php
                            $daysOverdue = \Carbon\Carbon::parse($request->expected_return_date)->diffInDays(now());
                        @endphp
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>
                                @if($request->student && $request->student->user)
                                    <div class="font-weight-semibold">{{ $request->student->user->name }}</div>
                                    <div class="text-muted">
                                        <small>{{ $request->student->user->email ?? '' }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($request->book)
                                    <div class="font-weight-semibold">{{ $request->book->name }}</div>
                                    @if($request->book->author)
                                        <div class="text-muted">
                                            <small>{{ $request->book->author }}</small>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($request->request_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-danger font-weight-semibold">
                                    {{ \Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                @if($daysOverdue > 7)
                                    <span class="badge badge-danger">{{ $daysOverdue }} jours</span>
                                @elseif($daysOverdue > 3)
                                    <span class="badge badge-warning">{{ $daysOverdue }} jours</span>
                                @else
                                    <span class="badge badge-secondary">{{ $daysOverdue }} jours</span>
                                @endif
                            </td>
                            <td>
                                <div class="list-icons">
                                    <a href="{{ route('librarian.book-requests.show', $request->id) }}" 
                                       class="list-icons-item text-primary" 
                                       data-toggle="tooltip" 
                                       title="Voir les détails">
                                        <i class="icon-eye"></i>
                                    </a>
                                    
                                    <form action="{{ route('librarian.book-requests.mark-returned', $request->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Marquer ce livre comme retourné ?')">
                                        @csrf
                                        <button type="submit" 
                                                class="list-icons-item text-success border-0 bg-transparent" 
                                                data-toggle="tooltip" 
                                                title="Marquer comme retourné">
                                            <i class="icon-checkmark-circle"></i>
                                        </button>
                                    </form>
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

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
