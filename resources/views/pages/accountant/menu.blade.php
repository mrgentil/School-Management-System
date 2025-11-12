{{--Financial Management--}}
<li class="nav-item nav-item-submenu">
    <a href="#" class="nav-link"><i class="icon-cash3"></i> <span>Gestion Financière</span></a>
    <ul class="nav nav-group-sub" data-submenu-title="Gestion Financière">
        <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link">Créer Paiement</a></li>
        <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link">Gérer Paiements</a></li>
        <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link">Paiements Étudiants</a></li>
    </ul>
</li>

{{--Reports--}}
<li class="nav-item">
    <a href="#" class="nav-link" onclick="generateFinancialReport()"><i class="icon-file-stats"></i> <span>Rapports Financiers</span></a>
</li>