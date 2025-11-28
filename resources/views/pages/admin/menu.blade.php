{{--Messagerie Admin--}}
<li class="nav-item">
    <a href="{{ route('super_admin.messages.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['super_admin.messages.index', 'super_admin.messages.create', 'super_admin.messages.show']) ? 'active' : '' }}">
        <i class="icon-envelop"></i> <span>Messagerie</span>
        @php
            $unreadMsgCount = \App\Models\MessageRecipient::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();
        @endphp
        @if($unreadMsgCount > 0)
            <span class="badge badge-danger badge-pill ml-auto">{{ $unreadMsgCount }}</span>
        @endif
    </a>
</li>
