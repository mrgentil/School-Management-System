{{-- Menu Bibliothécaire --}}
<li class="nav-item">
    <a href="{{ route('librarian.dashboard') }}" class="nav-link {{ (Route::is('librarian.dashboard')) ? 'active' : '' }}">
        <i class="icon-home4"></i>
        <span>Tableau de Bord</span>
    </a>
</li>

{{-- Gestion des Livres --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['librarian.books.index', 'librarian.books.create', 'librarian.books.edit', 'librarian.books.show']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-books"></i>
        <span>Gestion des Livres</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Livres">
        <li class="nav-item">
            <a href="{{ route('librarian.books.index') }}" class="nav-link {{ (Route::is('librarian.books.index')) ? 'active' : '' }}">
                Tous les Livres
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.books.create') }}" class="nav-link {{ (Route::is('librarian.books.create')) ? 'active' : '' }}">
                Ajouter un Livre
            </a>
        </li>
    </ul>
</li>

{{-- Demandes d'Emprunt --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['librarian.book-requests.index', 'librarian.book-requests.show', 'librarian.book-requests.overdue']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-reading"></i>
        <span>Demandes d'Emprunt</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Demandes">
        <li class="nav-item">
            <a href="{{ route('librarian.book-requests.index') }}" class="nav-link {{ (Route::is('librarian.book-requests.index')) ? 'active' : '' }}">
                Toutes les Demandes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.book-requests.index', ['status' => 'pending']) }}" class="nav-link">
                En Attente
                @php
                    $pending = \App\Models\BookRequest::where('status', 'pending')->count();
                @endphp
                @if($pending > 0)
                    <span class="badge badge-warning badge-pill ml-auto">{{ $pending }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.book-requests.overdue') }}" class="nav-link {{ (Route::is('librarian.book-requests.overdue')) ? 'active' : '' }}">
                Livres en Retard
                @php
                    $overdue = \App\Models\BookRequest::where('status', 'borrowed')->where('expected_return_date', '<', now())->count();
                @endphp
                @if($overdue > 0)
                    <span class="badge badge-danger badge-pill ml-auto">{{ $overdue }}</span>
                @endif
            </a>
        </li>
    </ul>
</li>

{{-- Rapports --}}
<li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['librarian.reports.index', 'librarian.reports.popular-books', 'librarian.reports.active-students', 'librarian.reports.monthly', 'librarian.reports.inventory', 'librarian.reports.penalties']) ? 'nav-item-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="icon-stats-dots"></i>
        <span>Rapports</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Rapports">
        <li class="nav-item">
            <a href="{{ route('librarian.reports.index') }}" class="nav-link {{ (Route::is('librarian.reports.index')) ? 'active' : '' }}">
                Vue d'Ensemble
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.reports.inventory') }}" class="nav-link {{ (Route::is('librarian.reports.inventory')) ? 'active' : '' }}">
                Inventaire
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.reports.monthly') }}" class="nav-link {{ (Route::is('librarian.reports.monthly')) ? 'active' : '' }}">
                Rapport Mensuel
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.reports.popular-books') }}" class="nav-link {{ (Route::is('librarian.reports.popular-books')) ? 'active' : '' }}">
                Livres Populaires
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.reports.active-students') }}" class="nav-link {{ (Route::is('librarian.reports.active-students')) ? 'active' : '' }}">
                Étudiants Actifs
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('librarian.reports.penalties') }}" class="nav-link {{ (Route::is('librarian.reports.penalties')) ? 'active' : '' }}">
                Pénalités
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