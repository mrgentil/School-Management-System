{{-- 
    Partial réutilisable pour créer un nouveau message
    Variables attendues: $recipients, $routePrefix
    Optionnel: $showBulkOptions (pour admin/super_admin)
--}}

@php
    $showBulkOptions = $showBulkOptions ?? false;
@endphp

<div class="card border-0 shadow-sm">
    <!-- En-tête -->
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route($routePrefix . '.messages.index') }}" class="btn btn-light btn-sm mr-3">
                    <i class="icon-arrow-left8"></i>
                </a>
                <div>
                    <h5 class="mb-0"><i class="icon-pencil7 text-primary mr-2"></i>Nouveau message</h5>
                    <small class="text-muted">Composez et envoyez votre message</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><i class="icon-warning mr-2"></i>Erreur</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route($routePrefix . '.messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
            @csrf
            
            @if($showBulkOptions)
                <!-- Type d'envoi amélioré -->
                <div class="form-group">
                    <label class="font-weight-semibold">
                        <i class="icon-users mr-1 text-primary"></i>
                        Type d'envoi <span class="text-danger">*</span>
                    </label>
                    <select name="recipient_type" id="recipientType" class="form-control form-control-lg" required>
                        <option value="">-- Sélectionner le type d'envoi --</option>
                        <optgroup label="Envoi en masse">
                            <option value="all" {{ old('recipient_type') == 'all' ? 'selected' : '' }}>📢 Tous les utilisateurs</option>
                            <option value="all_students" {{ old('recipient_type') == 'all_students' ? 'selected' : '' }}>🎓 Tous les étudiants</option>
                            <option value="all_parents" {{ old('recipient_type') == 'all_parents' ? 'selected' : '' }}>👨‍👩‍👧 Tous les parents</option>
                            <option value="all_teachers" {{ old('recipient_type') == 'all_teachers' ? 'selected' : '' }}>👨‍🏫 Tous les enseignants</option>
                        </optgroup>
                        <optgroup label="Envoi individuel">
                            <option value="one_student" {{ old('recipient_type') == 'one_student' ? 'selected' : '' }}>🎓 Un étudiant spécifique</option>
                            <option value="one_parent" {{ old('recipient_type') == 'one_parent' ? 'selected' : '' }}>👨‍👩‍👧 Un parent spécifique</option>
                            <option value="one_teacher" {{ old('recipient_type') == 'one_teacher' ? 'selected' : '' }}>👨‍🏫 Un enseignant spécifique</option>
                            <option value="individual" {{ old('recipient_type') == 'individual' ? 'selected' : '' }}>👥 Plusieurs destinataires (sélection libre)</option>
                        </optgroup>
                    </select>
                    <small class="form-text text-muted" id="recipientTypeHelp"></small>
                </div>

                <!-- Sélection d'UN étudiant -->
                <div class="form-group" id="oneStudentGroup" style="display: none;">
                    <label class="font-weight-semibold">
                        <i class="icon-user-check mr-1 text-warning"></i>
                        Sélectionner l'étudiant <span class="text-danger">*</span>
                    </label>
                    <select name="student_id" id="studentSelect" class="form-control select2-single">
                        <option value="">-- Rechercher un étudiant --</option>
                        @if(isset($recipients['students']))
                            @foreach($recipients['students'] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email ?? 'N/A' }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sélection d'UN parent -->
                <div class="form-group" id="oneParentGroup" style="display: none;">
                    <label class="font-weight-semibold">
                        <i class="icon-user-check mr-1 text-info"></i>
                        Sélectionner le parent <span class="text-danger">*</span>
                    </label>
                    <select name="parent_id" id="parentSelect" class="form-control select2-single">
                        <option value="">-- Rechercher un parent --</option>
                        @if(isset($recipients['parents']))
                            @foreach($recipients['parents'] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email ?? 'N/A' }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sélection d'UN enseignant -->
                <div class="form-group" id="oneTeacherGroup" style="display: none;">
                    <label class="font-weight-semibold">
                        <i class="icon-user-check mr-1 text-success"></i>
                        Sélectionner l'enseignant <span class="text-danger">*</span>
                    </label>
                    <select name="teacher_id" id="teacherSelect" class="form-control select2-single">
                        <option value="">-- Rechercher un enseignant --</option>
                        @if(isset($recipients['teachers']))
                            @foreach($recipients['teachers'] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email ?? 'N/A' }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sélection multiple (destinataires libres) -->
                <div class="form-group" id="multipleRecipientsGroup" style="display: none;">
                    <label class="font-weight-semibold">
                        <i class="icon-users4 mr-1 text-primary"></i>
                        Sélectionner les destinataires <span class="text-danger">*</span>
                    </label>
                    <select name="recipients[]" id="recipientsSelect" class="form-control select2-recipients" multiple="multiple">
                        @if(isset($recipients['students']) && $recipients['students']->count() > 0)
                            <optgroup label="🎓 Étudiants">
                                @foreach($recipients['students'] as $user)
                                    <option value="{{ $user->id }}" data-type="student">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['teachers']) && $recipients['teachers']->count() > 0)
                            <optgroup label="👨‍🏫 Enseignants">
                                @foreach($recipients['teachers'] as $user)
                                    <option value="{{ $user->id }}" data-type="teacher">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['parents']) && $recipients['parents']->count() > 0)
                            <optgroup label="👨‍👩‍👧 Parents">
                                @foreach($recipients['parents'] as $user)
                                    <option value="{{ $user->id }}" data-type="parent">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['admins']) && $recipients['admins']->count() > 0)
                            <optgroup label="👔 Administration">
                                @foreach($recipients['admins'] as $user)
                                    <option value="{{ $user->id }}" data-type="admin">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['accountants']) && $recipients['accountants']->count() > 0)
                            <optgroup label="💼 Comptables">
                                @foreach($recipients['accountants'] as $user)
                                    <option value="{{ $user->id }}" data-type="accountant">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['librarians']) && $recipients['librarians']->count() > 0)
                            <optgroup label="📚 Bibliothécaires">
                                @foreach($recipients['librarians'] as $user)
                                    <option value="{{ $user->id }}" data-type="librarian">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    <small class="form-text text-muted">
                        <i class="icon-info22 mr-1"></i>Recherchez et sélectionnez plusieurs destinataires
                    </small>
                </div>

                <!-- Compteur de destinataires -->
                <div class="alert alert-info border-0 py-2" id="recipientCounter" style="display: none;">
                    <i class="icon-users mr-2"></i>
                    <span id="recipientCountText">0 destinataire(s) sélectionné(s)</span>
                </div>
            @else
                <input type="hidden" name="recipient_type" value="individual">
                
                <!-- Sélection des destinataires pour non-admin -->
                <div class="form-group" id="recipientsGroup">
                    <label class="font-weight-semibold">
                        <i class="icon-users4 mr-1 text-primary"></i>
                        Destinataires <span class="text-danger">*</span>
                    </label>
                    <select name="recipients[]" id="recipientsSelect" class="form-control select2-recipients" multiple="multiple" required>
                        @if(isset($recipients['students']) && $recipients['students']->count() > 0)
                            <optgroup label="Étudiants">
                                @foreach($recipients['students'] as $user)
                                    <option value="{{ $user->id }}" data-type="student">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['teachers']) && $recipients['teachers']->count() > 0)
                            <optgroup label="Enseignants">
                                @foreach($recipients['teachers'] as $user)
                                    <option value="{{ $user->id }}" data-type="teacher">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['admins']) && $recipients['admins']->count() > 0)
                            <optgroup label="Administration">
                                @foreach($recipients['admins'] as $user)
                                    <option value="{{ $user->id }}" data-type="admin">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        
                        @if(isset($recipients['parents']) && $recipients['parents']->count() > 0)
                            <optgroup label="Parents">
                                @foreach($recipients['parents'] as $user)
                                    <option value="{{ $user->id }}" data-type="parent">{{ $user->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                    <small class="form-text text-muted">
                        <i class="icon-info22 mr-1"></i>Recherchez et sélectionnez un ou plusieurs destinataires
                    </small>
                </div>
            @endif
            
            <!-- Sujet -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-bookmark2 mr-1 text-primary"></i>
                    Sujet <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="subject" 
                       class="form-control form-control-lg" 
                       placeholder="Objet du message..."
                       value="{{ old('subject') }}" 
                       required>
            </div>
            
            <!-- Priorité -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-flag3 mr-1 text-primary"></i>
                    Priorité
                </label>
                <select name="priority" class="form-control">
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                </select>
            </div>
            
            <!-- Message -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-file-text2 mr-1 text-primary"></i>
                    Message <span class="text-danger">*</span>
                </label>
                
                <!-- Barre d'emojis -->
                <div class="mb-2 p-2 bg-light rounded">
                    <small class="text-muted mr-2">Emojis :</small>
                    @foreach(['😊', '👍', '🙏', '📚', '✅', '❓', '💡', '⭐', '📢', '⚠️'] as $emoji)
                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1 mb-1" data-emoji="{{ $emoji }}">{{ $emoji }}</button>
                    @endforeach
                </div>
                
                <textarea name="content" 
                          id="messageContent"
                          rows="8" 
                          class="form-control" 
                          placeholder="Écrivez votre message ici..."
                          required>{{ old('content') }}</textarea>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-muted">
                        <span id="charCount">0</span> caractères
                    </small>
                </div>
            </div>
            
            <!-- Pièces jointes -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-attachment mr-1 text-primary"></i>
                    Pièces jointes
                </label>
                <div class="custom-file">
                    <input type="file" 
                           name="attachments[]" 
                           class="custom-file-input" 
                           id="attachments" 
                           multiple
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.zip">
                    <label class="custom-file-label" for="attachments">Choisir des fichiers...</label>
                </div>
                <small class="form-text text-muted">
                    Formats acceptés: PDF, Word, Excel, Images, ZIP (max 10 Mo par fichier)
                </small>
                <div id="fileList" class="mt-2"></div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route($routePrefix . '.messages.index') }}" class="btn btn-light">
                    <i class="icon-cross2 mr-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="icon-paperplane mr-2"></i>Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script src="{{ asset('global_assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    console.log('Messagerie script loaded');
    
    // Initialiser Select2 pour sélection multiple
    if ($('.select2-recipients').length) {
        $('.select2-recipients').select2({
            placeholder: 'Rechercher des destinataires...',
            allowClear: true,
            width: '100%'
        });
    }
    
    // Initialiser Select2 pour sélection simple avec recherche
    if ($('.select2-single').length) {
        $('.select2-single').select2({
            placeholder: 'Rechercher...',
            allowClear: true,
            width: '100%'
        });
    }

    // Vérifier si on a le sélecteur de type (mode admin)
    var $recipientType = $('#recipientType');
    
    if ($recipientType.length) {
        console.log('Admin mode detected');
        
        // Messages d'aide selon le type
        var helpMessages = {
            'all': 'Le message sera envoyé à TOUS les utilisateurs du système.',
            'all_students': 'Le message sera envoyé à tous les étudiants.',
            'all_parents': 'Le message sera envoyé à tous les parents.',
            'all_teachers': 'Le message sera envoyé à tous les enseignants.',
            'one_student': 'Recherchez et sélectionnez un étudiant spécifique.',
            'one_parent': 'Recherchez et sélectionnez un parent spécifique.',
            'one_teacher': 'Recherchez et sélectionnez un enseignant spécifique.',
            'individual': 'Sélectionnez librement un ou plusieurs destinataires.'
        };

        // Fonction pour gérer l'affichage des champs
        function handleRecipientTypeChange() {
            var type = $recipientType.val();
            console.log('Type changed to:', type);
            
            // Cacher tous les groupes
            $('#oneStudentGroup').hide();
            $('#oneParentGroup').hide();
            $('#oneTeacherGroup').hide();
            $('#multipleRecipientsGroup').hide();
            $('#recipientCounter').hide();
            
            // Afficher le message d'aide
            $('#recipientTypeHelp').text(helpMessages[type] || '');
            
            // Afficher le groupe approprié selon le type
            if (type === 'one_student') {
                $('#oneStudentGroup').show();
            } else if (type === 'one_parent') {
                $('#oneParentGroup').show();
            } else if (type === 'one_teacher') {
                $('#oneTeacherGroup').show();
            } else if (type === 'individual') {
                $('#multipleRecipientsGroup').show();
            } else if (type === 'all' || type === 'all_students' || type === 'all_parents' || type === 'all_teachers') {
                $('#recipientCounter').show();
                var counts = {
                    'all': '{{ isset($recipients) ? collect($recipients)->flatten()->count() : 0 }}',
                    'all_students': '{{ isset($recipients["students"]) ? $recipients["students"]->count() : 0 }}',
                    'all_parents': '{{ isset($recipients["parents"]) ? $recipients["parents"]->count() : 0 }}',
                    'all_teachers': '{{ isset($recipients["teachers"]) ? $recipients["teachers"]->count() : 0 }}'
                };
                $('#recipientCountText').text(counts[type] + ' destinataire(s) recevront ce message');
            }
        }
        
        // Attacher l'événement change
        $recipientType.on('change', handleRecipientTypeChange);
        
        // Déclencher au chargement
        handleRecipientTypeChange();
    }

    // Insertion d'emojis
    $('.emoji-btn').click(function() {
        var emoji = $(this).data('emoji');
        var textarea = $('#messageContent');
        var cursorPos = textarea.prop('selectionStart');
        var textBefore = textarea.val().substring(0, cursorPos);
        var textAfter = textarea.val().substring(cursorPos);
        
        textarea.val(textBefore + emoji + textAfter);
        textarea.focus();
        
        var newPos = cursorPos + emoji.length;
        textarea[0].setSelectionRange(newPos, newPos);
        updateCharCount();
    });

    // Compteur de caractères
    function updateCharCount() {
        var count = $('#messageContent').val().length;
        $('#charCount').text(count);
    }
    $('#messageContent').on('input', updateCharCount);
    updateCharCount();

    // Gestion des fichiers
    $('#attachments').change(function() {
        var files = this.files;
        var fileList = $('#fileList');
        fileList.empty();
        
        if (files.length > 0) {
            $.each(files, function(index, file) {
                var size = (file.size / 1024 / 1024).toFixed(2);
                fileList.append(
                    '<div class="d-flex align-items-center p-2 bg-light rounded mb-1">' +
                        '<i class="icon-file-text mr-2"></i>' +
                        '<span class="flex-grow-1">' + file.name + '</span>' +
                        '<small>' + size + ' Mo</small>' +
                    '</div>'
                );
            });
        }
        
        var label = files.length > 1 ? files.length + ' fichiers sélectionnés' : (files.length === 1 ? files[0].name : 'Choisir des fichiers...');
        $('.custom-file-label').text(label);
    });

    // Validation avant soumission
    $('#messageForm').submit(function(e) {
        var $recipientType = $('#recipientType');
        
        if ($recipientType.length) {
            var type = $recipientType.val();
            
            if (type === 'one_student' && !$('#studentSelect').val()) {
                e.preventDefault();
                alert('Veuillez sélectionner un étudiant.');
                return false;
            }
            if (type === 'one_parent' && !$('#parentSelect').val()) {
                e.preventDefault();
                alert('Veuillez sélectionner un parent.');
                return false;
            }
            if (type === 'one_teacher' && !$('#teacherSelect').val()) {
                e.preventDefault();
                alert('Veuillez sélectionner un enseignant.');
                return false;
            }
            if (type === 'individual' && (!$('#recipientsSelect').val() || $('#recipientsSelect').val().length === 0)) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un destinataire.');
                return false;
            }
        }
        
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="icon-spinner2 spinner mr-2"></i>Envoi en cours...').prop('disabled', true);
    });
});
</script>
@endsection
