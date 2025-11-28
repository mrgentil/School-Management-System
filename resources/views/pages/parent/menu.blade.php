{{--Tableau de Bord--}}
<li class="nav-item">
    <a href="{{ route('parent.dashboard') }}" class="nav-link {{ Route::is('parent.dashboard') ? 'active' : '' }}">
        <i class="icon-home4"></i> <span>🏠 Tableau de Bord</span>
    </a>
</li>

{{--Mes Enfants--}}
<li class="nav-item">
    <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children']) ? 'active' : '' }}">
        <i class="icon-users4"></i> 👶 Mes Enfants
    </a>
</li>

{{--Progression--}}
<li class="nav-item">
    <a href="{{ route('parent.progress.index') }}" class="nav-link {{ Route::is('parent.progress.*') ? 'active' : '' }}">
        <i class="icon-stats-growth"></i> 📊 Progression
    </a>
</li>

{{--Bulletins--}}
<li class="nav-item">
    <a href="{{ route('parent.bulletins.index') }}" class="nav-link {{ Route::is('parent.bulletins.*') ? 'active' : '' }}">
        <i class="icon-file-text2"></i> 📄 Bulletins
    </a>
</li>

{{--Présences--}}
<li class="nav-item">
    <a href="{{ route('parent.attendance.index') }}" class="nav-link {{ Route::is('parent.attendance.*') ? 'active' : '' }}">
        <i class="icon-checkmark-circle"></i> ✅ Présences
    </a>
</li>

{{--Messagerie--}}
<li class="nav-item">
    <a href="{{ route('parent.messages.index') }}" class="nav-link {{ Route::is('parent.messages.*') ? 'active' : '' }}">
        <i class="icon-envelop"></i> 💬 Messagerie
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

{{--Calendrier--}}
<li class="nav-item">
    <a href="{{ route('calendar.public') }}" class="nav-link {{ Route::is('calendar.public') ? 'active' : '' }}">
        <i class="icon-calendar3"></i> 📅 Calendrier
    </a>
</li>