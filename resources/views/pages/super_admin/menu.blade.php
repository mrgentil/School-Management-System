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

{{--Paramètres--}}
<li class="nav-item">
    <a href="{{ route('settings') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['settings',]) ? 'active' : '' }}">
        <i class="icon-gear"></i> <span>⚙️ Paramètres</span>
    </a>
</li>

{{--Codes PIN--}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['pins.create', 'pins.index']) ? 'nav-item-expanded nav-item-open' : '' }} ">
    <a href="#" class="nav-link"><i class="icon-lock2"></i> <span>🔐 Codes PIN</span></a>

    <ul class="nav nav-group-sub" data-submenu-title="Gestion des Codes PIN">
        {{--Générer des codes--}}
        <li class="nav-item">
            <a href="{{ route('pins.create') }}"
               class="nav-link {{ (Route::is('pins.create')) ? 'active' : '' }}">Générer des codes</a>
        </li>

        {{--Voir les codes--}}
        <li class="nav-item">
            <a href="{{ route('pins.index') }}"
               class="nav-link {{ (Route::is('pins.index')) ? 'active' : '' }}">Voir les codes</a>
        </li>
    </ul>
</li>