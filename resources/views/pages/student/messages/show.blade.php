@extends('layouts.master')
@section('page_title', 'Message')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{ $message->subject }}</h5>
        <div class="header-elements">
            <a href="{{ route('student.messages.index') }}" class="btn btn-light">
                <i class="icon-arrow-left8"></i> Retour aux messages
            </a>
            <a href="#reply-form" class="btn btn-primary ml-2" data-toggle="collapse" aria-expanded="false" aria-controls="reply-form">
                <i class="icon-reply"></i> Répondre
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Fil de discussion -->
        <div class="timeline">
            @foreach($conversation as $msg)
            <div class="timeline-item">
                <div class="timeline-item-date">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                
                <div class="timeline-item-content">
                    <div class="d-flex align-items-center mb-2">
                        <div class="mr-3">
                            <img src="{{ $msg->sender->photo }}" width="40" height="40" class="rounded-circle" alt="">
                        </div>
                        <div>
                            <h6 class="mb-0">
                                {{ $msg->sender->name }}
                                @if($msg->sender->user_type == 'teacher')
                                    <span class="badge badge-info ml-1">Professeur</span>
                                @elseif(in_array($msg->sender->user_type, ['admin', 'super_admin']))
                                    <span class="badge badge-success ml-1">Administration</span>
                                @endif
                            </h6>
                            <span class="text-muted">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        {!! nl2br(e($msg->content)) !!}
                    </div>
                    
                    @if($msg->attachments->count() > 0)
                    <div class="mb-3">
                        <h6>Pièces jointes :</h6>
                        <div class="list-group">
                            @foreach($msg->attachments as $attachment)
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <i class="icon-file-{{ $attachment->getFileTypeIcon() }} mr-2"></i>
                                    <div class="mr-auto">{{ $attachment->filename }}</div>
                                    <a href="{{ route('student.messages.download', $attachment->id) }}" class="btn btn-sm btn-light ml-2">
                                        <i class="icon-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <!-- /fil de discussion -->

        <!-- Formulaire de réponse -->
        <div class="collapse mt-4" id="reply-form">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Répondre</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.messages.reply', $message->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="content" rows="5" class="form-control" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="attachments">Pièces jointes (optionnel)</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            <span class="form-text text-muted">Vous pouvez sélectionner plusieurs fichiers (max 10 Mo par fichier)</span>
                        </div>
                        
                        <div class="text-right">
                            <button type="reset" class="btn btn-light" data-toggle="collapse" data-target="#reply-form">
                                Annuler
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-paperplane mr-2"></i> Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /formulaire de réponse -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Faire défiler jusqu'au dernier message
    $(document).ready(function() {
        $('html, body').animate({
            scrollTop: $(document).height()
        }, 'slow');
    });
</script>
@endpush
