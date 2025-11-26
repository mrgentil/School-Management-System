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
                <li class="nav-item">
                    @if(Qs::userIsSuperAdmin())
                        <a href="{{ route('super_admin.dashboard') }}" class="nav-link {{ (Route::is('super_admin.dashboard')) ? 'active' : '' }}">
                            <i class="icon-home4"></i>
                            <span>📊 Tableau de bord</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="nav-link {{ (Route::is('dashboard')) ? 'active' : '' }}">
                            <i class="icon-home4"></i>
                            <span>Tableau de bord</span>
                        </a>
                    @endif
                </li>
                @endif

                {{--Academics (Non-Students)--}}
                @if(Qs::userIsAcademic() && !Qs::userIsStudent())
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage', 'attendance.index', 'attendance.view', 'attendance.statistics', 'study-materials.index', 'study-materials.create', 'study-materials.show', 'study-materials.edit', 'subject-grades-config.index', 'subject-grades-config.show', 'proclamations.index', 'proclamations.period', 'proclamations.semester', 'proclamations.student', 'marks.index', 'marks.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
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
                        @endif

                        {{--Subject Grades Config (RDC System)--}}
                        @if(Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('subject-grades-config.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['subject-grades-config.index', 'subject-grades-config.show']) ? 'active' : '' }}">
                                   🧮 Cotes par Matière (RDC)
                                </a>
                            </li>
                        @endif

                        {{--Proclamations RDC--}}
                        @if(Qs::userIsSuperAdmin())
                            <li class="nav-item">
                                <a href="{{ route('proclamations.index') }}"
                                   class="nav-link {{ in_array(Route::currentRouteName(), ['proclamations.index', 'proclamations.period', 'proclamations.semester', 'proclamations.student']) ? 'active' : '' }}">
                                   🏆 Proclamations RDC
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
                    <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                        <a href="#" class="nav-link"><i class="icon-office"></i> <span> Administratif</span></a>

                        <ul class="nav nav-group-sub" data-submenu-title="Administratif">

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
                            <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                                <a href="#" class="nav-link {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">Informations étudiants</a>
                                <ul class="nav nav-group-sub">
                                    @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                        <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link ">{{ $c->name }}</a></li>
                                    @endforeach
                                </ul>
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
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.index', 'marks.manage', 'marks.bulk', 'marks.tabulation', 'marks.show', 'marks.batch_fix', 'bulletins.index', 'bulletins.students', 'bulletins.preview']) ? 'nav-item-expanded nav-item-open' : '' }} ">
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

                            {{--Bulletins PDF--}}
                            <li class="nav-item">
                                <a href="{{ route('bulletins.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['bulletins.index', 'bulletins.students', 'bulletins.preview']) ? 'active' : '' }}">
                                    <i class="icon-file-pdf text-danger"></i> Bulletins PDF
                                </a>
                            </li>
                        @endif

                        @if(Qs::userIsTeamSAT())
                            {{--Marksheet--}}
                            <li class="nav-item">
                                <a href="{{ route('marks.bulk') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">Bulletin de notes</a>
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
