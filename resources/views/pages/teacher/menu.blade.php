{{--Tableau de Bord--}}
<li class="nav-item">
    <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ Route::is('teacher.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i> <span>🏠 Tableau de Bord</span>
    </a>
</li>

{{--Messagerie Enseignant--}}
<li class="nav-item">
    <a href="{{ route('teacher.messages.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['teacher.messages.index', 'teacher.messages.create', 'teacher.messages.show']) ? 'active' : '' }}">
        <i class="icon-envelop"></i> <span>💬 Messagerie</span>
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

{{--Calendrier Scolaire--}}
<li class="nav-item">
    <a href="{{ route('calendar.public') }}" class="nav-link {{ Route::is('calendar.public') ? 'active' : '' }}">
        <i class="icon-calendar3"></i> <span>📅 Calendrier</span>
    </a>
</li>
