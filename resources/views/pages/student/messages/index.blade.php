@extends('layouts.master')
@section('page_title', 'Messagerie')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Ma messagerie</h6>
        <div class="header-elements">
            <a href="{{ route('student.messages.create') }}" class="btn btn-primary">
                <i class="icon-plus2"></i> Nouveau message
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>De</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($conversations as $message)
                    <tr class="{{ !$message->isReadBy(auth()->id()) ? 'font-weight-bold' : '' }}">
                        <td>
                            {{ $message->sender->name }}
                            @if($message->sender->user_type == 'teacher')
                                <span class="badge badge-info">Professeur</span>
                            @elseif(in_array($message->sender->user_type, ['admin', 'super_admin']))
                                <span class="badge badge-success">Administration</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.messages.show', $message->id) }}" class="text-body">
                                {{ $message->subject }}
                                @($message->attachments->count() > 0 ? '&nbsp;<i class="icon-attachment"></i>' : '')
                            </a>
                        </td>
                        <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($message->isReadBy(auth()->id()))
                                <span class="badge badge-light">Lu</span>
                            @else
                                <span class="badge badge-primary">Nouveau</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <a href="{{ route('student.messages.show', $message->id) }}" class="list-icons-item" data-popup="tooltip" title="Voir le message">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="#" class="list-icons-item text-danger" data-popup="tooltip" title="Supprimer">
                                    <i class="icon-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="p-4">
                                <i class="icon-envelop text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucun message trouvé</p>
                                <a href="{{ route('student.messages.create') }}" class="btn btn-primary mt-2">
                                    <i class="icon-plus2"></i> Écrire un message
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($conversations->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $conversations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialisation des tooltips
    $(function () {
        $('[data-popup="tooltip"]').tooltip();
    });
</script>
@endpush
