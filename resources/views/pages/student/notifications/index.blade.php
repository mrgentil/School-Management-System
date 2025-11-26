@extends('layouts.master')
@section('page_title', 'Mes Notifications')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h6 class="card-title text-white">
            <i class="icon-bell2 mr-2"></i>Mes Notifications
            @if($unreadCount > 0)
                <span class="badge badge-light ml-2">{{ $unreadCount }} non lue(s)</span>
            @endif
        </h6>
        <div class="header-elements">
            @if($unreadCount > 0)
                <form action="{{ route('student.notifications.mark_all_read') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="icon-checkmark3 mr-1"></i>Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ !$notification->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    <span class="btn btn-{{ $notification->color }} btn-icon rounded-circle">
                                        <i class="{{ $notification->icon }}"></i>
                                    </span>
                                </div>
                                <div>
                                    <h6 class="mb-1 {{ !$notification->is_read ? 'font-weight-bold' : '' }}">
                                        {{ $notification->title }}
                                        @if(!$notification->is_read)
                                            <span class="badge badge-primary badge-pill ml-1">Nouveau</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                    <small class="text-muted">
                                        <i class="icon-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($notification->data && isset($notification->data['url']))
                                    <a href="{{ route('student.notifications.read', $notification->id) }}" class="btn btn-sm btn-outline-primary mr-2">
                                        <i class="icon-arrow-right7"></i> Voir
                                    </a>
                                @elseif(!$notification->is_read)
                                    <form action="{{ route('student.notifications.read', $notification->id) }}" method="POST" class="d-inline mr-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="icon-checkmark"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('student.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cette notification ?')">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="icon-bell2 text-muted" style="font-size: 48px;"></i>
                <p class="text-muted mt-3">Aucune notification pour le moment.</p>
            </div>
        @endif
    </div>
</div>

@if($notifications->where('is_read', true)->count() > 0)
    <div class="text-center mt-3">
        <form action="{{ route('student.notifications.clear_read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer toutes les notifications lues ?')">
                <i class="icon-trash mr-1"></i>Supprimer les notifications lues
            </button>
        </form>
    </div>
@endif

@endsection
