
<li class="nav-item">
    <a href="<?php echo e(route('student.dashboard')); ?>" class="nav-link <?php echo e((Route::is('student.dashboard')) ? 'active' : ''); ?>">
        <i class="icon-home4"></i>
        <span>Tableau de Bord</span>
    </a>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('student.notifications.index')); ?>" class="nav-link <?php echo e((Route::is('student.notifications.*')) ? 'active' : ''); ?>">
        <i class="icon-bell2"></i>
        <span>Notifications</span>
        <?php
            $unreadNotifications = \App\Models\UserNotification::where('user_id', Auth::id())->where('is_read', false)->count();
        ?>
        <?php if($unreadNotifications > 0): ?>
            <span class="badge badge-danger badge-pill ml-auto"><?php echo e($unreadNotifications); ?></span>
        <?php endif; ?>
    </a>
</li>


<li class="nav-item nav-item-submenu <?php echo e(in_array(Route::currentRouteName(), ['student.library.index', 'student.library.show', 'student.library.search', 'student.library.requests.index', 'student.library.requests.show']) ? 'nav-item-open' : ''); ?>">
    <a href="#" class="nav-link">
        <i class="icon-books"></i>
        <span>Bibliothèque</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Bibliothèque">
        <li class="nav-item">
            <a href="<?php echo e(route('student.library.index')); ?>" class="nav-link <?php echo e((Route::is('student.library.index')) ? 'active' : ''); ?>">
                Catalogue des Livres
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.library.requests.index')); ?>" class="nav-link <?php echo e((Route::is('student.library.requests.index')) ? 'active' : ''); ?>">
                Mes Demandes
                <?php
                    $pending = \App\Models\BookRequest::where('student_id', Auth::id())->where('status', 'pending')->count();
                ?>
                <?php if($pending > 0): ?>
                    <span class="badge badge-info badge-pill ml-auto"><?php echo e($pending); ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.library.search')); ?>" class="nav-link <?php echo e((Route::is('student.library.search')) ? 'active' : ''); ?>">
                Rechercher un Livre
            </a>
        </li>
    </ul>
</li>


<li class="nav-item nav-item-submenu <?php echo e(in_array(Route::currentRouteName(), ['student.assignments.index', 'student.assignments.show', 'student.materials.index', 'student.materials.show', 'student.grades.index', 'student.grades.bulletin', 'student.progress.index']) ? 'nav-item-open' : ''); ?>">
    <a href="#" class="nav-link">
        <i class="icon-graduation2"></i>
        <span>Académique</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Académique">
        <li class="nav-item">
            <a href="<?php echo e(route('student.assignments.index')); ?>" class="nav-link <?php echo e((Route::is('student.assignments.*')) ? 'active' : ''); ?>">
                <i class="icon-clipboard3 mr-2"></i>Devoirs
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.grades.index')); ?>" class="nav-link <?php echo e(Route::is('student.grades.index') ? 'active' : ''); ?>">
                <i class="icon-certificate mr-2"></i>Mes Notes par Période
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.grades.bulletin')); ?>" class="nav-link <?php echo e(Route::is('student.grades.bulletin') ? 'active' : ''); ?>">
                <i class="icon-file-text2 mr-2"></i>Mon Bulletin
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.progress.index')); ?>" class="nav-link <?php echo e(Route::is('student.progress.index') ? 'active' : ''); ?>">
                <i class="icon-graph mr-2"></i>Ma Progression
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.materials.index')); ?>" class="nav-link <?php echo e((Route::is('student.materials.*')) ? 'active' : ''); ?>">
                <i class="icon-folder-open mr-2"></i>Matériel Pédagogique
            </a>
        </li>

        
        <li class="nav-item">
            <a href="<?php echo e(route('student.exam_schedule')); ?>" class="nav-link <?php echo e(Route::is('student.exam_schedule') ? 'active' : ''); ?>">
                <i class="icon-calendar2 mr-2"></i>Horaires d'Examens
            </a>
        </li>
    </ul>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('student.attendance.index')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['student.attendance.index', 'student.attendance.calendar']) ? 'active' : ''); ?>">
        <i class="icon-checkmark-circle"></i>
        <span>Présences</span>
    </a>
</li>


<li class="nav-item nav-item-submenu <?php echo e(in_array(Route::currentRouteName(), ['student.timetable.index', 'student.timetable.calendar']) ? 'nav-item-open' : ''); ?>">
    <a href="#" class="nav-link">
        <i class="icon-calendar"></i>
        <span>Emploi du Temps</span>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Emploi du Temps">
        <li class="nav-item">
            <a href="<?php echo e(route('student.timetable.index')); ?>" class="nav-link <?php echo e((Route::is('student.timetable.index')) ? 'active' : ''); ?>">
                <i class="icon-list mr-2"></i>Vue Liste
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.timetable.calendar')); ?>" class="nav-link <?php echo e((Route::is('student.timetable.calendar')) ? 'active' : ''); ?>">
                <i class="icon-calendar3 mr-2"></i>Vue Calendrier
            </a>
        </li>
    </ul>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('student.messages.index')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['student.messages.index', 'student.messages.show', 'student.messages.create']) ? 'active' : ''); ?>">
        <i class="icon-envelop"></i>
        <span>Messagerie</span>
        <?php
            $unreadMsgCount = \App\Models\MessageRecipient::where('recipient_id', Auth::id())
                ->where('is_read', false)
                ->count();
        ?>
        <?php if($unreadMsgCount > 0): ?>
            <span class="badge badge-danger badge-pill ml-auto"><?php echo e($unreadMsgCount); ?></span>
        <?php endif; ?>
    </a>
</li>


<li class="nav-item nav-item-submenu <?php echo e(in_array(Route::currentRouteName(), ['student.finance.dashboard', 'student.finance.payments', 'student.finance.receipts', 'student.finance.receipt']) ? 'nav-item-open' : ''); ?>">
    <a href="#" class="nav-link">
        <i class="icon-cash3"></i>
        <span>Finance</span>
        <?php
            $unpaid = \App\Models\PaymentRecord::where('student_id', Auth::id())->where('balance', '>', 0)->count();
        ?>
        <?php if($unpaid > 0): ?>
            <span class="badge badge-warning badge-pill ml-2"><?php echo e($unpaid); ?></span>
        <?php endif; ?>
    </a>
    <ul class="nav nav-group-sub" data-submenu-title="Finance">
        <li class="nav-item">
            <a href="<?php echo e(route('student.finance.payments')); ?>" class="nav-link <?php echo e((Route::is('student.finance.payments')) ? 'active' : ''); ?>">
                Mes Paiements
            </a>
        </li>
        <li class="nav-item">
            <a href="<?php echo e(route('student.finance.receipts')); ?>" class="nav-link <?php echo e((Route::is('student.finance.receipts')) ? 'active' : ''); ?>">
                Mes Reçus
            </a>
        </li>
    </ul>
</li>


<li class="nav-item">
    <?php
        $userId = Auth::user()->id;
        $hashedId = Qs::hash($userId);
        \Log::info('Menu Notes Debug', [
            'user_id' => $userId,
            'hashed_id' => $hashedId,
            'route_url' => route('marks.year_selector', ['id' => $hashedId])
        ]);
    ?>
    <a href="<?php echo e(route('marks.year_selector', ['id' => $hashedId])); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['marks.show', 'marks.year_selector', 'pins.enter']) ? 'active' : ''); ?>">
        <i class="icon-book"></i> 
        <span>Mes Notes</span>
        
    </a>
</li>


<li class="nav-item">
    <a href="<?php echo e(route('my_account')); ?>" class="nav-link <?php echo e(in_array(Route::currentRouteName(), ['my_account']) ? 'active' : ''); ?>">
        <i class="icon-user"></i>
        <span>Mon Compte</span>
    </a>
</li>
<?php /**PATH D:\laragon\www\eschool\resources\views/pages/student/menu.blade.php ENDPATH**/ ?>