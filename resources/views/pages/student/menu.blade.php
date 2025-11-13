{{-- Tableau de Bord --}}
<li class="nav-item">
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ (Route::is('student.dashboard')) ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>Tableau de Bord</span>
    </a>
</li>

{{-- Bibliothèque --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.library.index', 'student.library.show', 'student.library.search', 'student.library.requests.index', 'student.library.requests.show']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-books"></i>
        <span>Bibliothèque</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Bibliothèque">
        <li class="nav-item">
            <a href="{{ route('student.library.index') }}" class="nav-link {{ (Route::is('student.library.index')) ? 'active' : '' }}">
                Catalogue des Livres
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.library.requests.index') }}" class="nav-link {{ (Route::is('student.library.requests.index')) ? 'active' : '' }}">
                Mes Demandes
                @php
                    $pending = \App\Models\BookRequest::where('student_id', Auth::id())->where('status', 'pending')->count();
                @endphp
                @if($pending > 0)
                    <span class="badge badge-info badge-pill ml-auto">{{ $pending }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.library.search') }}" class="nav-link {{ (Route::is('student.library.search')) ? 'active' : '' }}">
                Rechercher un Livre
            </a>
        </li>
    </ul>
</li>

{{-- Académique --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.assignments.index', 'student.assignments.show', 'student.materials.index', 'student.materials.show']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-graduation2"></i>
        <span>Académique</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Académique">
        <li class="nav-item">
            <a href="{{ route('student.assignments.index') }}" class="nav-link {{ (Route::is('student.assignments.*')) ? 'active' : '' }}">
                <i class="icon-clipboard3 mr-2"></i>Devoirs
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.materials.index') }}" class="nav-link {{ (Route::is('student.materials.*')) ? 'active' : '' }}">
                <i class="icon-folder-open mr-2"></i>Matériel Pédagogique
            </a>
        </li>
    </ul>
</li>

{{-- Présences --}}
<li class="nav-item">
    <a href="{{ route('student.attendance.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student.attendance.index', 'student.attendance.calendar']) ? 'active' : '' }}">
        <i class="icon-checkmark-circle"></i>
        <span>Présences</span>
    </a>
</li>

{{-- Emploi du Temps --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.timetable.index', 'student.timetable.calendar']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-calendar"></i>
        <span>Emploi du Temps</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Emploi du Temps">
        <li class="nav-item">
            <a href="{{ route('student.timetable.index') }}" class="nav-link {{ (Route::is('student.timetable.index')) ? 'active' : '' }}">
                <i class="icon-list mr-2"></i>Vue Liste
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.timetable.calendar') }}" class="nav-link {{ (Route::is('student.timetable.calendar')) ? 'active' : '' }}">
                <i class="icon-calendar3 mr-2"></i>Vue Calendrier
            </a>
        </li>
    </ul>
</li>

{{-- Messagerie --}}
<li class="nav-item">
    <a href="{{ route('student.messages.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['student.messages.index', 'student.messages.show', 'student.messages.create']) ? 'active' : '' }}">
        <i class="icon-bubbles4"></i>
        <span>Messagerie</span>
        @php
            $unreadMessages = \App\Models\Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
        @endphp
        @if($unreadMessages > 0)
            <span class="badge badge-danger badge-pill ml-2">{{ $unreadMessages }}</span>
        @endif
    </a>
</li>

{{-- Finance & Paiements --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['student.finance.dashboard', 'student.finance.payments', 'student.finance.receipts', 'student.finance.receipt']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-cash3"></i>
        <span>Finance</span>
        @php
            $unpaid = \App\Models\PaymentRecord::where('student_id', Auth::id())->where('balance', '>', 0)->count();
        @endphp
        @if($unpaid > 0)
            <span class="badge badge-warning badge-pill ml-2">{{ $unpaid }}</span>
        @endif
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Finance">
        <li class="nav-item">
            <a href="{{ route('student.finance.payments') }}" class="nav-link {{ (Route::is('student.finance.payments')) ? 'active' : '' }}">
                Mes Paiements
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('student.finance.receipts') }}" class="nav-link {{ (Route::is('student.finance.receipts')) ? 'active' : '' }}">
                Mes Reçus
            </a>
        </li>
    </ul>
</li>

{{-- Notes & Bulletins --}}
<li class="nav-item">
    @php
        $userId = Auth::user()->id;
        $hashedId = Qs::hash($userId);
        \Log::info('Menu Notes Debug', [
            'user_id' => $userId,
            'hashed_id' => $hashedId,
            'route_url' => route('marks.year_selector', ['id' => $hashedId])
        ]);
    @endphp
    <a href="{{ route('marks.year_selector', ['id' => $hashedId]) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.show', 'marks.year_selector', 'pins.enter']) ? 'active' : '' }}">
        <i class="icon-book"></i> 
        <span>Mes Notes</span>
        {{-- Debug: ID={{ $userId }}, Hash={{ $hashedId }} --}}
    </a>
</li>

{{-- Mon Compte --}}
<li class="nav-item">
    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}">
        <i class="icon-user"></i>
        <span>Mon Compte</span>
    </a>
</li>
