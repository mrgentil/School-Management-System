@extends('layouts.master')
@section('page_title', '📧 Messagerie Admin')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h6 class="card-title">📧 Messages envoyés</h6>
        <div class="header-elements">
            <a href="{{ route('super_admin.messages.create') }}" class="btn btn-light btn-sm">
                <i class="icon-plus2 mr-2"></i> Nouveau message
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sujet</th>
                        <th>Destinataires</th>
                        <th>Date d'envoi</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr>
                        <td>
                            <strong>{{ $message->subject }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($message->content, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge badge-primary">
                                {{ $message->recipients->count() }} destinataire(s)
                            </span>
                        </td>
                        <td>
                            {{ $message->created_at->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                        </td>
                        <td class="text-center">
                            <div class="list-icons">
                                <a href="{{ route('super_admin.messages.show', $message->id) }}" 
                                   class="list-icons-item text-primary" 
                                   data-popup="tooltip" 
                                   title="Voir le message">
                                    <i class="icon-eye"></i>
                                </a>
                                <form action="{{ route('super_admin.messages.destroy', $message->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="list-icons-item text-danger border-0 bg-transparent" 
                                            data-popup="tooltip" 
                                            title="Supprimer">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            <div class="p-4">
                                <i class="icon-envelop text-muted" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucun message envoyé</p>
                                <a href="{{ route('super_admin.messages.create') }}" class="btn btn-primary mt-2">
                                    <i class="icon-plus2 mr-2"></i> Envoyer votre premier message
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('[data-popup="tooltip"]').tooltip();
});
</script>
@endpush
