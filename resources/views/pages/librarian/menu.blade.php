{{--Library Management--}}
<li class="nav-item nav-item-submenu">
    <a href="#" class="nav-link"><i class="icon-books"></i> <span>Bibliothèque</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Gestion Bibliothèque">
        <li class="nav-item"><a href="{{ route('books.index') }}" class="nav-link">Gestion des Livres</a></li>
        <li class="nav-item"><a href="{{ route('book-requests.index') }}" class="nav-link">Demandes d'Emprunt</a></li>
        <li class="nav-item"><a href="{{ route('study-materials.index') }}" class="nav-link">Supports Pédagogiques</a></li>
    </ul>
</li>

{{--Notices & Events--}}
<li class="nav-item nav-item-submenu">
    <a href="#" class="nav-link"><i class="icon-megaphone"></i> <span>Communications</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Communications">
        <li class="nav-item"><a href="{{ route('notices.index') }}" class="nav-link">Tableau d'Affichage</a></li>
        <li class="nav-item"><a href="{{ route('events.index') }}" class="nav-link">Événements</a></li>
    </ul>
</li>