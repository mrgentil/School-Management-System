<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}"><img src="{{ Auth::user()->photo }}" width="38" height="38" class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-50">
                            <i class="icon-user font-size-sm"></i> &nbsp;{{ ucwords(str_replace('_', ' ', Auth::user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main (Non-Students) -->
                @if(!Qs::userIsStudent())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['dashboard', 'dashboard.enhanced', 'super_admin.dashboard']) ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-home4"></i> <span>Tableau de bord</span></a>
                    <ul class="nav nav-group-sub">
                        @if(Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('super_admin.dashboard') }}" class="nav-link {{ Route::is('super_admin.dashboard') ? 'active' : '' }}">
                                    <i class="icon-clipboard3"></i> Vue Générale
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                                    <i class="icon-clipboard3"></i> Vue Simple
                                </a>
                            </li>
                        @endif
                        @if(Qs::userIsTeamSA())
                            <li class="nav-item">
                                <a href="{{ route('dashboard.enhanced') }}" class="nav-link {{ Route::is('dashboard.enhanced') ? 'active' : '' }}">
                                    <i class="icon-stats-bars"></i> 📊 Statistiques
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Calendrier Scolaire (pour non-admins seulement) --}}
                @if(!Qs::userIsTeamSA())
                <li class="nav-item">
                    <a href="{{ route('calendar.public') }}" class="nav-link {{ Route::is('calendar.public') ? 'active' : '' }}">
                        <i class="icon-calendar3"></i> <span>📅 Calendrier</span>
                    </a>
                </li>
                @endif

                {{--Academics (Non-Students)--}}
                @if(Qs::userIsAcademic() && !Qs::userIsStudent())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage', 'attendance.index', 'attendance.view', 'attendance.statistics', 'study-materials.index', 'study-materials.create', 'study-materials.show', 'study-materials.edit', 'subject-grades-config.index', 'subject-grades-config.show', 'proclamations.index', 'proclamations.period', 'proclamations.semester', 'proclamations.student', 'marks.index', 'marks.manage', 'marks.bulk', 'marks.show', 'bulletins.index', 'bulletins.students', 'bulletins.preview']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span> Académique</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Gestion Académique">

                        {{--Timetables--}}
                            <li class="nav-item"><a href="{{ route('tt.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">Emplois du temps</a></li>

                        {{--Attendance--}}
                        @if(Qs::userIsTeamSAT())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['attendance.index', 'attendance.view', 'attendance.statistics']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['attendance.index', 'attendance.view', 'attendance.statistics']) ? 'active' : '' }}">✅ Présence</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item"><a href="{{ route('attendance.index') }}" class="nav-link {{ Route::is('attendance.index') ? 'active' : '' }}">Prendre la présence</a></li>
                                    <li class="nav-item"><a href="{{ route('attendance.view') }}" class="nav-link {{ Route::is('attendance.view') ? 'active' : '' }}">Consulter</a></li>
                                    <li class="nav-item"><a href="{{ route('attendance.statistics') }}" class="nav-link {{ Route::is('attendance.statistics') ? 'active' : '' }}">Statistiques</a></li>
                                </ul>
                            </li>
                        @endif

                        {{--Assignments--}}
                        @if(Qs::userIsTeamSAT())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['assignments.index', 'assignments.create', 'assignments.show', 'assignments.edit']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['assignments.index', 'assignments.create', 'assignments.show', 'assignments.edit']) ? 'active' : '' }}">📚 Devoirs</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item"><a href="{{ route('assignments.index') }}" class="nav-link {{ Route::is('assignments.index') ? 'active' : '' }}">Liste des devoirs</a></li>
                                    <li class="nav-item"><a href="{{ route('assignments.create') }}" class="nav-link {{ Route::is('assignments.create') ? 'active' : '' }}">Créer un devoir</a></li>
                                </ul>
                            </li>
                        @endif

                        {{--Marks Manage--}}
                        @if(Qs::userIsTeamSAT())
                            <li class="nav-item">
                                <a href="{{ route('marks.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index', 'marks.manage']) ? 'active' : '' }}">
                                   📝 Saisie des notes
                                </a>
                            </li>
                            
                            {{--Relevés de Notes--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.bulk') }}" 
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">
                                   📋 Relevés de Notes
                                </a>
                            </li>
                            
                            {{--Bulletins Scolaires--}}
                            <li class="nav-item">
                                <a href="{{ route('bulletins.index') }}" 
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['bulletins.index', 'bulletins.students', 'bulletins.preview']) ? 'active' : '' }}">
                                   📄 Bulletins Scolaires
                                </a>
                            </li>
                            
                            {{--Publication des Bulletins--}}
                            <li class="nav-item">
                                <a href="{{ route('bulletin_publications.index') }}" 
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['bulletin_publications.index', 'bulletin_publications.history']) ? 'active' : '' }}">
                                   📢 Publication Bulletins
                                </a>
                            </li>
                            
                            {{--Progression Élèves--}}
                            <li class="nav-item">
                                <a href="{{ route('student_progress.index') }}" 
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['student_progress.index', 'student_progress.show']) ? 'active' : '' }}">
                                   📊 Progression Élèves
                                </a>
                            </li>
                        @endif

                        {{--Subject Grades Config--}}
                        @if(Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('subject-grades-config.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['subject-grades-config.index', 'subject-grades-config.show']) ? 'active' : '' }}">
                                   🧮 Cotes par Matière
                                </a>
                            </li>
                        @endif

                        {{--Proclamations--}}
                        @if(Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('proclamations.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['proclamations.index', 'proclamations.period', 'proclamations.semester', 'proclamations.student']) ? 'active' : '' }}">
                                   🏆 Proclamations
                                </a>
                            </li>
                        @endif


                        {{--Study Materials--}}
                        @if(Qs::userIsTeamSA())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['study-materials.index', 'study-materials.create', 'study-materials.show', 'study-materials.edit']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['study-materials.index', 'study-materials.create', 'study-materials.show', 'study-materials.edit']) ? 'active' : '' }}">📖 Matériel Pédagogique</a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item"><a href="{{ route('study-materials.index') }}" class="nav-link {{ Route::is('study-materials.index') ? 'active' : '' }}">Liste des matériaux</a></li>
                                    <li class="nav-item"><a href="{{ route('study-materials.create') }}" class="nav-link {{ Route::is('study-materials.create') ? 'active' : '' }}">Ajouter un matériel</a></li>
                                </ul>
                            </li>
                        @endif
                        </ul>
                    </li>
                    @endif

                {{--Administrative--}}
                @if(Qs::userIsAdministrative())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show', 'academic_sessions.index', 'academic_sessions.create', 'academic_sessions.edit', 'academic_sessions.show']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-office"></i> <span> Administratif</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Administratif">

                            {{-- Années Scolaires --}}
                            @if(Qs::userIsTeamSA())
                            <li class="nav-item">
                                <a href="{{ route('academic_sessions.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['academic_sessions.index', 'academic_sessions.create', 'academic_sessions.edit', 'academic_sessions.show']) ? 'active' : '' }}">
                                    <i class="icon-calendar mr-1"></i> Années Scolaires
                                </a>
                            </li>
                            
                            {{-- Calendrier Scolaire --}}
                            <li class="nav-item">
                                <a href="{{ route('calendar.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['calendar.index', 'calendar.create', 'calendar.edit']) ? 'active' : '' }}">
                                    <i class="icon-calendar3 mr-1"></i> 📅 Calendrier
                                </a>
                            </li>
                            
                            {{-- Rappels --}}
                            <li class="nav-item">
                                <a href="{{ route('reminders.index') }}" class="nav-link {{ Route::is('reminders.*') ? 'active' : '' }}">
                                    <i class="icon-bell mr-1"></i> 🔔 Rappels
                                </a>
                            </li>
                            
                            {{-- Notifications --}}
                            <li class="nav-item">
                                <a href="{{ route('notifications.index') }}" class="nav-link {{ Route::is('notifications.*') ? 'active' : '' }}">
                                    <i class="icon-envelop mr-1"></i> 📧 Notifications
                                </a>
                            </li>
                            
                            {{-- Statistiques --}}
                            <li class="nav-item">
                                <a href="{{ route('statistics.index') }}" class="nav-link {{ Route::is('statistics.*') ? 'active' : '' }}">
                                    <i class="icon-stats-growth mr-1"></i> 📊 Statistiques
                                </a>
                            </li>
                            
                            {{-- Centre d'Impression --}}
                            <li class="nav-item">
                                <a href="{{ route('print.index') }}" class="nav-link {{ Route::is('print.*') ? 'active' : '' }}">
                                    <i class="icon-printer mr-1"></i> 🖨️ Centre d'Impression
                                </a>
                            </li>
                            
                            {{-- Gestion des Professeurs --}}
                            <li class="nav-item">
                                <a href="{{ route('teachers.management.index') }}" class="nav-link {{ Route::is('teachers.management.*') ? 'active' : '' }}">
                                    <i class="icon-users4 mr-1"></i> 👨‍🏫 Gestion Professeurs
                                </a>
                            </li>
                            
                            {{-- Rapports Financiers --}}
                            <li class="nav-item">
                                <a href="{{ route('finance.dashboard') }}" class="nav-link {{ Route::is('finance.*') ? 'active' : '' }}">
                                    <i class="icon-stats-bars mr-1"></i> 💰 Rapports Financiers
                                </a>
                            </li>
                            
                            {{-- Bibliothèque (accès admin) --}}
                            <li class="nav-item nav-item-submenu {{ Route::is('librarian.*') ? 'nav-item-open' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="icon-books mr-1"></i> 📚 Bibliothèque
                                </a>
                                <ul class="nav nav-group-sub">
                                    <li class="nav-item">
                                        <a href="{{ route('librarian.dashboard') }}" class="nav-link {{ Route::is('librarian.dashboard') ? 'active' : '' }}">
                                            <i class="icon-home4 mr-2"></i>Tableau de bord
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('librarian.books.index') }}" class="nav-link {{ Route::is('librarian.books.*') ? 'active' : '' }}">
                                            <i class="icon-book mr-2"></i>Gestion des Livres
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('librarian.book-requests.index') }}" class="nav-link {{ Route::is('librarian.book-requests.*') ? 'active' : '' }}">
                                            <i class="icon-clipboard3 mr-2"></i>Demandes de Prêt
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('librarian.reports.index') }}" class="nav-link {{ Route::is('librarian.reports.*') ? 'active' : '' }}">
                                            <i class="icon-stats-dots mr-2"></i>Rapports
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            @if(Qs::userIsSuperAdmin())
                            {{-- Sauvegarde (Super Admin uniquement) --}}
                            <li class="nav-item">
                                <a href="{{ route('backup.index') }}" class="nav-link {{ Route::is('backup.*') ? 'active' : '' }}">
                                    <i class="icon-database mr-1"></i> 💾 Sauvegarde
                                </a>
                            </li>
                            @endif
                            @endif

                            {{--Payments--}}
                            @if(Qs::userIsTeamAccount())
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">

                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">Paiements</a>

                                <ul class="nav nav-group-sub">
                                    <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Créer un paiement</a></li>
                                    <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">Gérer les paiements</a></li>
                                    <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">Paiements étudiants</a></li>

                                </ul>

                            </li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{--Manage Students--}}
                @if(Qs::userIsTeamSAT())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.list', 'students.edit', 'students.show', 'students.promotion', 'students.promotion_manage', 'students.graduated', 'students.assign_class']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-users"></i> <span> Étudiants</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Gestion des Étudiants">
                            {{--Admit Student--}}
                            @if(Qs::userIsTeamSA())
                                <li class="nav-item">
                                    <a href="{{ route('students.create') }}"
                                       class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">Admettre un étudiant</a>
                                </li>
                            @endif

                            {{--Assign Students to Classes--}}
                            @if(Qs::userIsTeamSA())
                                <li class="nav-item">
                                    <a href="{{ route('students.assign_class') }}"
                                       class="nav-link {{ (Route::is('students.assign_class')) ? 'active' : '' }}">Assigner aux Classes</a>
                                </li>
                            @endif

                            {{--Student Information--}}
                            <li class="nav-item">
                                <a href="{{ route('students.info') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.info', 'students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">Informations étudiants</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('students.statistics') }}" class="nav-link {{ Route::is('students.statistics') ? 'active' : '' }}">Statistiques</a>
                            </li>

                            @if(Qs::userIsTeamSA())

                            {{--Student Promotion--}}
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage']) ? 'nav-item-expanded' : '' }}"><a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage' ]) ? 'active' : '' }}">Promotion étudiants</a>
                            <ul class="nav nav-group-sub">
                                <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion']) ? 'active' : '' }}">Promouvoir les étudiants</a></li>
                                <li class="nav-item"><a href="{{ route('students.promotion_manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion_manage']) ? 'active' : '' }}">Gérer les promotions</a></li>
                            </ul>

                            </li>

                            {{--Student Graduated--}}
                            <li class="nav-item"><a href="{{ route('students.graduated') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">Diplômés</a></li>
                                @endif

                        </ul>
                    </li>
                @endif

                @if(Qs::userIsTeamSA())
                    {{--Manage Users--}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i class="icon-users4"></i> <span> Utilisateurs</span></a>
                    </li>

                    {{--Manage Classes--}}
                    <li class="nav-item">
                        <a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><i class="icon-windows2"></i> <span> Classes</span></a>
                    </li>

                    {{--Manage Dorms--}}
                    <li class="nav-item">
                        <a href="{{ route('dorms.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><i class="icon-home9"></i> <span> Dortoirs</span></a>
                    </li>

                    {{--Manage Sections (Divisions)--}}
                    <li class="nav-item">
                        <a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><i class="icon-fence"></i> <span>Divisions (A, B, C...)</span></a>
                    </li>

                    {{--Academic Sections & Options--}}
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['academic_sections.index', 'options.index']) ? 'nav-item-expanded nav-item-open' : '' }}">
                        <a href="#" class="nav-link"><i class="icon-graduation2"></i> <span>Sections & Options</span></a>
                        <ul class="nav nav-group-sub" data-submenu-title="Sections & Options">
                            <li class="nav-item"><a href="{{ route('academic_sections.index') }}" class="nav-link {{ Route::is('academic_sections.index') ? 'active' : '' }}">Sections académiques</a></li>
                            <li class="nav-item"><a href="{{ route('options.index') }}" class="nav-link {{ Route::is('options.index') ? 'active' : '' }}">Options</a></li>
                        </ul>
                    </li>

                    {{--Manage Subjects--}}
                    <li class="nav-item">
                        <a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}"><i class="icon-pin"></i> <span>Matières</span></a>
                    </li>
                @endif

                {{--Exam--}}
                @if(Qs::userIsTeamSAT())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.tabulation', 'marks.batch_fix']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="#" class="nav-link"><i class="icon-books"></i> <span> Examens</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Gestion des Examens">
                        @if(Qs::userIsTeamSA())

                        {{--Exam list--}}
                            <li class="nav-item">
                                <a href="{{ route('exams.index') }}"
                                   class="nav-link {{ (Route::is('exams.index')) ? 'active' : '' }}">Liste des examens</a>
                            </li>

                            {{--Grades list--}}
                            <li class="nav-item">
                                    <a href="{{ route('grades.index') }}"
                                       class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">Barème de notation</a>
                            </li>


                            {{--Tabulation Sheet--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.tabulation') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.tabulation']) ? 'active' : '' }}">Feuille de Tabulation</a>
                            </li>

                            {{--Marks Batch Fix--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.batch_fix') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.batch_fix']) ? 'active' : '' }}">Correction par lot</a>
                            </li>
                        @endif

                    </ul>
                </li>
                @endif


                {{--End Exam--}}

                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account (pour non-étudiants)--}}
                @if(!Qs::userIsStudent())
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i class="icon-user"></i> <span>Mon compte</span></a>
                </li>
                @endif

                </ul>
            </div>
        </div>
</div>
