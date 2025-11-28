{{-- Tableau de Bord --}}
<li class="nav-item">
    <a href="{{ route('accountant.dashboard') }}" class="nav-link {{ (Route::is('accountant.dashboard')) ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>Tableau de Bord</span>
    </a>
</li>

{{-- Gestion des Paiements --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.show', 'payments.manage']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-cash3"></i>
        <span>Gestion des Paiements</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Paiements">
        <li class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link {{ (Route::is('payments.index')) ? 'active' : '' }}">
                Tous les Paiements
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payments.create') }}" class="nav-link {{ (Route::is('payments.create')) ? 'active' : '' }}">
                Nouveau Type de Paiement
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payments.manage') }}" class="nav-link {{ (Route::is('payments.manage')) ? 'active' : '' }}">
                Enregistrer Paiement
                @php
                    $overdueCount = \App\Models\PaymentRecord::where('balance', '>', 0)->count();
                @endphp
                @if($overdueCount > 0)
                    <span class="badge badge-warning badge-pill ml-auto">{{ $overdueCount }}</span>
                @endif
            </a>
        </li>
    </ul>
</li>

{{-- Reçus et Factures --}}
<li class="nav-item nav-item-submenu">
    <a href="#" class="nav-link">
        <i class="icon-file-text2"></i>
        <span>Reçus & Factures</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Documents">
        <li class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link">
                Générer Reçu
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('payments.index') }}" class="nav-link">
                Voir Factures
            </a>
        </li>
    </ul>
</li>

{{-- Rapports Financiers --}}
<li class="nav-item nav-item-submenu {{ Route::is('finance.*') ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-stats-dots"></i>
        <span>📊 Rapports</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Rapports">
        <li class="nav-item">
            <a href="{{ route('finance.dashboard') }}" class="nav-link {{ Route::is('finance.dashboard') ? 'active' : '' }}">
                <i class="icon-stats-bars mr-2"></i>Dashboard Financier
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('finance.by_class') }}" class="nav-link {{ Route::is('finance.by_class') ? 'active' : '' }}">
                <i class="icon-list mr-2"></i>Par Classe
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('finance.export') }}" class="nav-link">
                <i class="icon-download mr-2"></i>Export CSV
            </a>
        </li>
    </ul>
</li>

{{-- Mon Compte --}}
<li class="nav-item">
    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}">
        <i class="icon-user"></i>
        <span>Mon Compte</span>
    </a>
</li>