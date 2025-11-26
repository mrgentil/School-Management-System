
<li class="nav-item">
    <a href="<?php echo e(route('super_admin.messages.index')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['super_admin.messages.index', 'super_admin.messages.create']) ? 'active' : ''); ?>">
        <i class="icon-envelop"></i> <span>📧 Messagerie Admin</span>
    </a>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('settings')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['settings',]) ? 'active' : ''); ?>">
        <i class="icon-gear"></i> <span>⚙️ Paramètres</span>
    </a>
</li>


<li class="nav-item nav-item-submenu <?php echo e(in_array(Route::currentRouteName(), ['pins.create', 'pins.index']) ? 'nav-item-expanded nav-item-open' : ''); ?> ">
    <a href="#" class="nav-link"><i class="icon-lock2"></i> <span>🔐 Codes PIN</span></a>

    <ul class="nav nav-group-sub" data-submenu-title="Gestion des Codes PIN">
        
        <li class="nav-item">
            <a href="<?php echo e(route('pins.create')); ?>"
               class="nav-link <?php echo e((Route::is('pins.create')) ? 'active' : ''); ?>">Générer des codes</a>
        </li>

        
        <li class="nav-item">
            <a href="<?php echo e(route('pins.index')); ?>"
               class="nav-link <?php echo e((Route::is('pins.index')) ? 'active' : ''); ?>">Voir les codes</a>
        </li>
    </ul>
</li><?php /**PATH D:\laragon\www\eschool\resources\views/pages/super_admin/menu.blade.php ENDPATH**/ ?>