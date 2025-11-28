{{-- 
    Partial réutilisable pour afficher un message et son fil de discussion
    Variables attendues: $message, $conversation, $routePrefix
--}}

<div class="card border-0 shadow-sm">
    <!-- En-tête du message -->
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route($routePrefix . '.messages.index') }}" class="btn btn-light btn-sm mr-3">
                    <i class="icon-arrow-left8"></i>
                </a>
                <div>
                    <h5 class="mb-0">{{ $message->subject }}</h5>
                    <small class="text-muted">
                        {{ $conversation->count() }} message(s) dans cette conversation
                    </small>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#reply-form">
                    <i class="icon-reply mr-1"></i> Répondre
                </button>
                @if($message->sender_id == auth()->id())
                    <form action="{{ route($routePrefix . '.messages.destroy', $message->id) }}" method="POST" class="d-inline ml-2" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="icon-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Alertes -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-checkmark-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon-cancel-circle2 mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Fil de discussion -->
        <div class="conversation-thread">
            @foreach($conversation as $msg)
                @php
                    $isOwn = $msg->sender_id == auth()->id();
                @endphp
                <div class="message-bubble mb-4 {{ $isOwn ? 'own-message' : '' }}">
                    <div class="d-flex {{ $isOwn ? 'flex-row-reverse' : '' }}">
                        <!-- Avatar -->
                        <div class="{{ $isOwn ? 'ml-3' : 'mr-3' }}">
                            @if($msg->sender && $msg->sender->photo)
                                <img src="{{ $msg->sender->photo }}" class="rounded-circle shadow-sm" width="45" height="45" alt="">
                            @else
                                <div class="avatar-circle {{ $isOwn ? 'bg-primary' : 'bg-secondary' }} text-white shadow-sm">
                                    {{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Contenu -->
                        <div class="flex-grow-1" style="max-width: 80%;">
                            <div class="d-flex align-items-center mb-2 {{ $isOwn ? 'justify-content-end' : '' }}">
                                <span class="font-weight-bold {{ $isOwn ? 'order-2 ml-2' : 'mr-2' }}">
                                    {{ $msg->sender->name ?? 'Inconnu' }}
                                </span>
                                @if(isset($msg->sender->user_type) && !$isOwn)
                                    @if($msg->sender->user_type == 'teacher')
                                        <span class="badge badge-info badge-sm {{ $isOwn ? 'order-1' : '' }}">Enseignant</span>
                                    @elseif(in_array($msg->sender->user_type, ['admin', 'super_admin']))
                                        <span class="badge badge-success badge-sm {{ $isOwn ? 'order-1' : '' }}">Admin</span>
                                    @elseif($msg->sender->user_type == 'student')
                                        <span class="badge badge-warning badge-sm {{ $isOwn ? 'order-1' : '' }}">Étudiant</span>
                                    @endif
                                @endif
                                <small class="text-muted {{ $isOwn ? 'order-0 mr-2' : 'ml-auto' }}">
                                    {{ $msg->created_at->format('d/m/Y à H:i') }}
                                </small>
                            </div>
                            
                            <div class="message-content p-3 rounded {{ $isOwn ? 'bg-primary text-white' : 'bg-light' }}">
                                {!! nl2br(e($msg->content)) !!}
                            </div>
                            
                            <!-- Pièces jointes -->
                            @if($msg->attachments && $msg->attachments->count() > 0)
                                <div class="attachments mt-2">
                                    <small class="text-muted d-block mb-1">
                                        <i class="icon-attachment mr-1"></i>{{ $msg->attachments->count() }} pièce(s) jointe(s)
                                    </small>
                                    <div class="d-flex flex-wrap">
                                        @foreach($msg->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment->path) }}" 
                                               class="btn btn-sm btn-outline-secondary mr-2 mb-1" 
                                               target="_blank" download>
                                                <i class="icon-download mr-1"></i>
                                                {{ Str::limit($attachment->filename, 20) }}
                                                <span class="text-muted">({{ $attachment->formatted_size }})</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Formulaire de réponse -->
        <div class="collapse mt-4" id="reply-form">
            <div class="card border">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="icon-reply mr-2"></i>Répondre à cette conversation
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route($routePrefix . '.messages.reply', $message->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label class="font-weight-semibold">Votre réponse</label>
                            <textarea name="content" rows="5" class="form-control" 
                                      placeholder="Écrivez votre réponse ici..." required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="icon-attachment mr-1"></i>Pièces jointes (optionnel)
                            </label>
                            <div class="custom-file">
                                <input type="file" name="attachments[]" class="custom-file-input" id="attachments" multiple>
                                <label class="custom-file-label" for="attachments">Choisir des fichiers...</label>
                            </div>
                            <small class="form-text text-muted">Max 10 Mo par fichier</small>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light mr-2" data-toggle="collapse" data-target="#reply-form">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-paperplane mr-2"></i>Envoyer la réponse
                            </button>
                        </div>
                    </form>
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
    
    .message-content {
        word-wrap: break-word;
    }
    
    .own-message .message-content {
        border-radius: 18px 18px 4px 18px;
    }
    
    .message-bubble:not(.own-message) .message-content {
        border-radius: 18px 18px 18px 4px;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.2em 0.5em;
    }
    
    .custom-file-label::after {
        content: "Parcourir";
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Afficher le nom des fichiers sélectionnés
    document.getElementById('attachments')?.addEventListener('change', function() {
        var files = this.files;
        var label = this.nextElementSibling;
        if (files.length > 1) {
            label.textContent = files.length + ' fichiers sélectionnés';
        } else if (files.length === 1) {
            label.textContent = files[0].name;
        }
    });
});
</script>
