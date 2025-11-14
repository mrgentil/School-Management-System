@extends('layouts.master')
@section('page_title', 'Messagerie - Boîte de réception')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Boîte de réception</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('student.messages.index') }}" class="list-group-item list-group-item-action active">
                        <i class="icon-envelope mr-2"></i> Boîte de réception
                        @php
                            $unreadCount = \App\Models\MessageRecipient::where('recipient_id', Auth::id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="badge badge-primary badge-pill ml-auto">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="#" class="list-group-item list-group-item-action disabled">
                        <i class="icon-paperplane mr-2"></i> Envoyés
                        <small class="text-muted ml-2">(Bientôt)</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action disabled">
                        <i class="icon-star-full2 mr-2"></i> Favoris
                        <small class="text-muted ml-2">(Bientôt)</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action disabled">
                        <i class="icon-trash mr-2"></i> Corbeille
                        <small class="text-muted ml-2">(Bientôt)</small>
                    </a>
                </div>

                <div class="mt-3">
                    <a href="{{ route('student.messages.create') }}" class="btn btn-primary btn-block">
                        <i class="icon-pencil7 mr-2"></i> Nouveau message
                    </a>
                </div>
            </div>

            <!-- Message list -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <h6 class="card-title">Messages</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="reload"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="text-center py-5">
                            <i class="icon-envelop5 icon-2x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun message</h5>
                            <p class="text-muted">Votre boîte de réception est vide pour le moment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('.select').select2();
    });
</script>
@endpush
