{{-- 
    Partial réutilisable pour l'index de la messagerie
    Variables attendues: $messages, $filter, $unreadCount, $sentCount, $inboxCount, $routePrefix
--}}

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="row no-gutters">
            <!-- Sidebar gauche - Menu de navigation -->
            <div class="col-lg-3 border-right" style="background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);">
                <div class="p-3">
                    <!-- Bouton Nouveau message -->
                    <a href="{{ route($routePrefix . '.messages.create') }}" class="btn btn-primary btn-block mb-4 py-2 shadow-sm">
                        <i class="icon-pencil7 mr-2"></i> Nouveau message
                    </a>

                    <!-- Menu de navigation -->
                    <div class="nav flex-column">
                        <a href="{{ route($routePrefix . '.messages.index', ['filter' => 'all']) }}" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 {{ $filter == 'all' ? 'active bg-primary text-white' : 'text-dark' }}">
                            <i class="icon-stack mr-3"></i>
                            <span>Tous les messages</span>
                            <span class="badge {{ $filter == 'all' ? 'badge-light' : 'badge-secondary' }} ml-auto">{{ $inboxCount + $sentCount }}</span>
                        </a>
                        
                        <a href="{{ route($routePrefix . '.messages.index', ['filter' => 'inbox']) }}" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 {{ $filter == 'inbox' ? 'active bg-primary text-white' : 'text-dark' }}">
                            <i class="icon-inbox mr-3"></i>
                            <span>Boîte de réception</span>
                            @if($unreadCount > 0)
                                <span class="badge badge-danger ml-auto">{{ $unreadCount }}</span>
                            @else
                                <span class="badge {{ $filter == 'inbox' ? 'badge-light' : 'badge-secondary' }} ml-auto">{{ $inboxCount }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route($routePrefix . '.messages.index', ['filter' => 'sent']) }}" 
                           class="nav-link d-flex align-items-center py-2 px-3 rounded mb-1 {{ $filter == 'sent' ? 'active bg-primary text-white' : 'text-dark' }}">
                            <i class="icon-paperplane mr-3"></i>
                            <span>Messages envoyés</span>
                            <span class="badge {{ $filter == 'sent' ? 'badge-light' : 'badge-secondary' }} ml-auto">{{ $sentCount }}</span>
                        </a>
                    </div>

                    <!-- Statistiques -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-muted mb-3"><i class="icon-stats-dots mr-2"></i>Statistiques</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Non lus</span>
                            <span class="font-weight-bold text-primary">{{ $unreadCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Reçus</span>
                            <span class="font-weight-bold">{{ $inboxCount }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Envoyés</span>
                            <span class="font-weight-bold">{{ $sentCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone principale - Liste des messages -->
            <div class="col-lg-9">
                <div class="p-3">
                    <!-- Alertes -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-checkmark-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <i class="icon-cancel-circle2 mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <!-- En-tête avec titre et actions -->
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="mb-0">
                            @if($filter == 'inbox')
                                <i class="icon-inbox text-primary mr-2"></i>Boîte de réception
                            @elseif($filter == 'sent')
                                <i class="icon-paperplane text-success mr-2"></i>Messages envoyés
                            @else
                                <i class="icon-stack text-secondary mr-2"></i>Tous les messages
                            @endif
                        </h5>
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm" onclick="location.reload()" title="Actualiser">
                                <i class="icon-reload-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Liste des messages -->
                    <div class="messages-list">
                        @forelse($messages as $message)
                            @php
                                $isSent = $message->sender_id == auth()->id();
                                $isUnread = !$message->isReadBy(auth()->id());
                            @endphp
                            <div class="message-item d-flex align-items-start p-3 border-bottom {{ $isUnread && !$isSent ? 'bg-light' : '' }}" 
                                 style="cursor: pointer; transition: all 0.2s;"
                                 onclick="window.location='{{ route($routePrefix . '.messages.show', $message->id) }}'">
                                
                                <!-- Avatar -->
                                <div class="mr-3">
                                    @if($isSent)
                                        <div class="avatar-circle bg-success text-white">
                                            <i class="icon-paperplane"></i>
                                        </div>
                                    @else
                                        @if($message->sender && $message->sender->photo)
                                            <img src="{{ $message->sender->photo }}" class="rounded-circle" width="45" height="45" alt="">
                                        @else
                                            <div class="avatar-circle bg-primary text-white">
                                                {{ strtoupper(substr($message->sender->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                
                                <!-- Contenu du message -->
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="font-weight-bold text-truncate {{ $isUnread && !$isSent ? 'text-dark' : 'text-secondary' }}">
                                            @if($isSent)
                                                <span class="text-muted">À: </span>
                                                @foreach($message->recipients->take(2) as $recipient)
                                                    {{ $recipient->recipient->name ?? 'Inconnu' }}@if(!$loop->last), @endif
                                                @endforeach
                                                @if($message->recipients->count() > 2)
                                                    <span class="text-muted">+{{ $message->recipients->count() - 2 }}</span>
                                                @endif
                                            @else
                                                {{ $message->sender->name ?? 'Inconnu' }}
                                                @if(isset($message->sender->user_type))
                                                    @if($message->sender->user_type == 'teacher')
                                                        <span class="badge badge-info badge-sm ml-1">Enseignant</span>
                                                    @elseif(in_array($message->sender->user_type, ['admin', 'super_admin']))
                                                        <span class="badge badge-success badge-sm ml-1">Admin</span>
                                                    @elseif($message->sender->user_type == 'student')
                                                        <span class="badge badge-warning badge-sm ml-1">Étudiant</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <small class="text-muted text-nowrap ml-2">
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-1 {{ $isUnread && !$isSent ? 'font-weight-bold' : '' }}">
                                        {{ $message->subject }}
                                        @if($message->attachments && $message->attachments->count() > 0)
                                            <i class="icon-attachment text-muted ml-1" title="{{ $message->attachments->count() }} pièce(s) jointe(s)"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="text-muted small text-truncate">
                                        {{ Str::limit($message->content, 80) }}
                                    </div>
                                </div>

                                <!-- Indicateurs -->
                                <div class="ml-3 text-right">
                                    @if($isUnread && !$isSent)
                                        <span class="badge badge-primary badge-pill">Nouveau</span>
                                    @elseif($isSent)
                                        <span class="badge badge-light"><i class="icon-checkmark"></i></span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="icon-envelop text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">Aucun message</h5>
                                <p class="text-muted">
                                    @if($filter == 'inbox')
                                        Votre boîte de réception est vide
                                    @elseif($filter == 'sent')
                                        Vous n'avez pas encore envoyé de messages
                                    @else
                                        Commencez une conversation !
                                    @endif
                                </p>
                                <a href="{{ route($routePrefix . '.messages.create') }}" class="btn btn-primary mt-2">
                                    <i class="icon-plus2 mr-2"></i>Écrire un message
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($messages->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $messages->appends(['filter' => $filter])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
    
    .message-item:hover {
        background-color: #f8f9fa !important;
    }
    
    .nav-link {
        transition: all 0.2s;
    }
    
    .nav-link:hover:not(.active) {
        background-color: #e9ecef;
    }
    
    .min-width-0 {
        min-width: 0;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.2em 0.5em;
    }
</style>
