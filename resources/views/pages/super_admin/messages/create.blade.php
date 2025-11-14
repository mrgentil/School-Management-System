@extends('layouts.master')
@section('page_title', '✉️ Nouveau message')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h6 class="card-title">✉️ Envoyer un message</h6>
        <div class="header-elements">
            <a href="{{ route('super_admin.messages.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>⚠️ Erreur :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super_admin.messages.store') }}" method="POST" id="messageForm">
            @csrf

            <!-- Type de destinataire -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-users mr-1 text-primary"></i>
                    Type de destinataire <span class="text-danger">*</span>
                </label>
                <select name="recipient_type" id="recipientType" class="form-control" required>
                    <option value="">-- Sélectionner --</option>
                    <option value="all">📢 Tous les utilisateurs</option>
                    <option value="role">👥 Par rôle</option>
                    <option value="individual">👤 Utilisateurs spécifiques</option>
                </select>
            </div>

            <!-- Sélection du rôle -->
            <div class="form-group" id="roleGroup" style="display: none;">
                <label class="font-weight-semibold">
                    <i class="icon-user-tie mr-1 text-success"></i>
                    Rôle <span class="text-danger">*</span>
                </label>
                <select name="role" class="form-control">
                    <option value="">-- Sélectionner un rôle --</option>
                    <option value="student">👨‍🎓 Étudiants</option>
                    <option value="teacher">👨‍🏫 Enseignants</option>
                    <option value="parent">👨‍👩‍👧 Parents</option>
                    <option value="admin">👔 Administrateurs</option>
                    <option value="librarian">📚 Bibliothécaires</option>
                    <option value="accountant">💼 Comptables</option>
                </select>
            </div>

            <!-- Sélection individuelle -->
            <div class="form-group" id="individualGroup" style="display: none;">
                <label class="font-weight-semibold">
                    <i class="icon-users4 mr-1 text-info"></i>
                    Destinataires <span class="text-danger">*</span>
                </label>
                <select name="recipients[]" class="form-control select2-recipients" multiple="multiple">
                    @foreach($users as $userType => $userList)
                        <optgroup label="{{ ucwords(str_replace('_', ' ', $userType)) }}">
                            @foreach($userList as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <small class="form-text text-muted">Vous pouvez sélectionner plusieurs destinataires</small>
            </div>

            <!-- Sujet -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-bookmark2 mr-1 text-warning"></i>
                    Sujet <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="subject" 
                       class="form-control form-control-lg" 
                       placeholder="📝 Objet du message..."
                       value="{{ old('subject') }}"
                       required>
            </div>

            <!-- Message -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-paragraph-justify mr-1 text-danger"></i>
                    Message <span class="text-danger">*</span>
                </label>
                
                <!-- Barre d'emojis -->
                <div class="mb-2">
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="😊">😊</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="👍">👍</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="📚">📚</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="✅">✅</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="⚠️">⚠️</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="📢">📢</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="🎓">🎓</button>
                    <button type="button" class="btn btn-light btn-sm emoji-btn" data-emoji="⭐">⭐</button>
                </div>

                <textarea name="content" 
                          id="messageContent" 
                          class="form-control" 
                          rows="8" 
                          placeholder="✍️ Écrivez votre message ici..."
                          required>{{ old('content') }}</textarea>
                
                <div class="d-flex justify-content-between mt-2">
                    <small class="text-muted">
                        <span id="charCount">0</span> caractères
                    </small>
                </div>
            </div>

            <!-- Boutons -->
            <div class="text-right">
                <a href="{{ route('super_admin.messages.index') }}" class="btn btn-light">
                    <i class="icon-cross2 mr-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-paperplane mr-2"></i> Envoyer le message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2-recipients').select2({
        placeholder: 'Rechercher et sélectionner des destinataires...',
        allowClear: true,
        width: '100%'
    });

    // Gestion du type de destinataire
    $('#recipientType').change(function() {
        var type = $(this).val();
        
        $('#roleGroup').hide();
        $('#individualGroup').hide();
        
        if (type === 'role') {
            $('#roleGroup').show();
        } else if (type === 'individual') {
            $('#individualGroup').show();
        }
    });

    // Insertion d'emojis
    $('.emoji-btn').click(function() {
        var emoji = $(this).data('emoji');
        var textarea = $('#messageContent');
        var cursorPos = textarea.prop('selectionStart');
        var textBefore = textarea.val().substring(0, cursorPos);
        var textAfter = textarea.val().substring(cursorPos);
        
        textarea.val(textBefore + emoji + textAfter);
        textarea.focus();
        
        // Repositionner le curseur
        var newPos = cursorPos + emoji.length;
        textarea[0].setSelectionRange(newPos, newPos);
        
        updateCharCount();
    });

    // Compteur de caractères
    function updateCharCount() {
        var count = $('#messageContent').val().length;
        $('#charCount').text(count);
        
        if (count > 1000) {
            $('#charCount').addClass('text-danger');
        } else if (count > 500) {
            $('#charCount').addClass('text-warning').removeClass('text-danger');
        } else {
            $('#charCount').removeClass('text-warning text-danger');
        }
    }

    $('#messageContent').on('input', updateCharCount);
    updateCharCount();

    // Validation
    $('#messageForm').submit(function(e) {
        var recipientType = $('#recipientType').val();
        
        if (!recipientType) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner un type de destinataire');
            return false;
        }
        
        if (recipientType === 'role' && !$('select[name="role"]').val()) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner un rôle');
            return false;
        }
        
        if (recipientType === 'individual' && $('.select2-recipients').val().length === 0) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner au moins un destinataire');
            return false;
        }
        
        $(this).find('button[type="submit"]')
            .html('<i class="icon-spinner2 spinner mr-2"></i> Envoi en cours...')
            .prop('disabled', true);
    });
});
</script>
@endpush
