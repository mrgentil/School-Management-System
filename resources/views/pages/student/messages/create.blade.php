@extends('layouts.master')
@section('page_title', '✉️ Nouveau message')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <!-- En-tête moderne -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">✉️ Nouveau message</h4>
                            <small class="opacity-75">Composez votre message</small>
                        </div>
                        <a href="{{ route('student.messages.index') }}" class="btn btn-light btn-sm">
                            <i class="icon-arrow-left8 mr-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulaire moderne -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <i class="icon-checkmark-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>⚠️ Erreur :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
                        @csrf
                        
                        <!-- Destinataires avec Select2 moderne -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="icon-users mr-1 text-primary"></i>
                                Destinataires <span class="text-danger">*</span>
                            </label>
                            <select name="recipients[]" class="form-control select2-recipients" multiple="multiple" required>
                                <optgroup label="👨‍🏫 Enseignants">
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" data-type="teacher">
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="👔 Administration">
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" data-type="admin">
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <small class="form-text text-muted">
                                <i class="icon-info22"></i> Recherchez et sélectionnez un ou plusieurs destinataires
                            </small>
                        </div>
                        
                        <!-- Sujet -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="icon-bookmark2 mr-1 text-primary"></i>
                                Sujet <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="subject" 
                                   class="form-control form-control-lg" 
                                   placeholder="📝 Ex: Question sur le cours de mathématiques..."
                                   value="{{ old('subject') }}" 
                                   required>
                        </div>
                        
                        <!-- Message avec éditeur riche -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="icon-file-text2 mr-1 text-primary"></i>
                                Message <span class="text-danger">*</span>
                            </label>
                            
                            <!-- Barre d'outils emojis -->
                            <div class="card bg-light mb-2">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <small class="text-muted mr-3">Emojis rapides:</small>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="😊">😊</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="👍">👍</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="🙏">🙏</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="📚">📚</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="✅">✅</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="❓">❓</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="💡">💡</button>
                                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1" data-emoji="⭐">⭐</button>
                                    </div>
                                </div>
                            </div>
                            
                            <textarea name="content" 
                                      id="messageContent"
                                      rows="10" 
                                      class="form-control" 
                                      placeholder="✍️ Écrivez votre message ici... Vous pouvez utiliser les emojis ci-dessus !"
                                      required>{{ old('content') }}</textarea>
                            <small class="form-text text-muted">
                                <span id="charCount">0</span> caractères
                            </small>
                        </div>
                        
                        <!-- Pièces jointes stylées -->
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
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                <label class="custom-file-label" for="attachments">
                                    📎 Choisir des fichiers...
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="icon-info22"></i> Formats acceptés: PDF, Word, Images (max 10 Mo par fichier)
                            </small>
                            <div id="fileList" class="mt-2"></div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <button type="reset" class="btn btn-light">
                                <i class="icon-undo2 mr-2"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="icon-paperplane mr-2"></i> Envoyer le message 🚀
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('global_assets/js/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('global_assets/js/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('global_assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // ===== Select2 avec recherche et tags =====
    $('.select2-recipients').select2({
        theme: 'bootstrap',
        placeholder: '🔍 Recherchez un destinataire...',
        width: '100%',
        allowClear: true,
        closeOnSelect: false,
        tags: false,
        templateResult: formatRecipient,
        templateSelection: formatRecipientSelection
    });

    // Format personnalisé pour les résultats
    function formatRecipient(recipient) {
        if (!recipient.id) return recipient.text;
        
        var type = $(recipient.element).data('type');
        var icon = type === 'teacher' ? '👨‍🏫' : '👔';
        var badge = type === 'teacher' ? 
            '<span class="badge badge-info ml-2">Enseignant</span>' : 
            '<span class="badge badge-success ml-2">Admin</span>';
        
        return $('<span>' + icon + ' ' + recipient.text + badge + '</span>');
    }

    // Format pour les sélections
    function formatRecipientSelection(recipient) {
        if (!recipient.id) return recipient.text;
        
        var type = $(recipient.element).data('type');
        var icon = type === 'teacher' ? '👨‍🏫' : '👔';
        
        return icon + ' ' + recipient.text;
    }

    // ===== Gestion des emojis =====
    $('.emoji-btn').click(function() {
        var emoji = $(this).data('emoji');
        var textarea = $('#messageContent');
        var cursorPos = textarea.prop('selectionStart');
        var textBefore = textarea.val().substring(0, cursorPos);
        var textAfter = textarea.val().substring(cursorPos);
        
        textarea.val(textBefore + emoji + textAfter);
        textarea.focus();
        
        // Repositionner le curseur après l'emoji
        var newPos = cursorPos + emoji.length;
        textarea.prop('selectionStart', newPos);
        textarea.prop('selectionEnd', newPos);
        
        // Mettre à jour le compteur
        updateCharCount();
        
        // Animation du bouton
        $(this).addClass('btn-primary').removeClass('btn-light');
        setTimeout(() => {
            $(this).removeClass('btn-primary').addClass('btn-light');
        }, 200);
    });

    // ===== Compteur de caractères =====
    function updateCharCount() {
        var count = $('#messageContent').val().length;
        $('#charCount').text(count);
        
        if (count > 1000) {
            $('#charCount').addClass('text-danger font-weight-bold');
        } else if (count > 500) {
            $('#charCount').addClass('text-warning').removeClass('text-danger font-weight-bold');
        } else {
            $('#charCount').removeClass('text-warning text-danger font-weight-bold');
        }
    }

    $('#messageContent').on('input', updateCharCount);
    updateCharCount();

    // ===== Gestion des fichiers =====
    $('#attachments').change(function() {
        var files = this.files;
        var fileList = $('#fileList');
        fileList.empty();
        
        if (files.length > 0) {
            fileList.append('<div class="alert alert-info border-0 mt-2"><strong>📎 Fichiers sélectionnés:</strong></div>');
            
            $.each(files, function(index, file) {
                var size = (file.size / 1024 / 1024).toFixed(2);
                var icon = getFileIcon(file.name);
                var sizeClass = size > 10 ? 'text-danger' : 'text-success';
                
                fileList.append(
                    '<div class="d-flex align-items-center mb-2 p-2 bg-light rounded">' +
                        '<span class="mr-2" style="font-size: 1.5rem;">' + icon + '</span>' +
                        '<div class="flex-grow-1">' +
                            '<div class="font-weight-semibold">' + file.name + '</div>' +
                            '<small class="' + sizeClass + '">' + size + ' Mo</small>' +
                        '</div>' +
                    '</div>'
                );
            });
        }
        
        // Mettre à jour le label
        var label = files.length > 1 ? 
            '📎 ' + files.length + ' fichiers sélectionnés' : 
            '📎 ' + files[0].name;
        $('.custom-file-label').text(label);
    });

    function getFileIcon(filename) {
        var ext = filename.split('.').pop().toLowerCase();
        var icons = {
            'pdf': '📄',
            'doc': '📝',
            'docx': '📝',
            'jpg': '🖼️',
            'jpeg': '🖼️',
            'png': '🖼️',
            'gif': '🖼️'
        };
        return icons[ext] || '📎';
    }

    // ===== Validation avant envoi =====
    $('#messageForm').submit(function(e) {
        var recipients = $('.select2-recipients').val();
        var subject = $('input[name="subject"]').val();
        var content = $('#messageContent').val();
        
        console.log('Form submit - Recipients:', recipients);
        console.log('Form submit - Subject:', subject);
        console.log('Form submit - Content:', content);
        
        if (!recipients || recipients.length === 0) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner au moins un destinataire');
            return false;
        }
        
        if (!subject || !subject.trim()) {
            e.preventDefault();
            alert('⚠️ Veuillez saisir un sujet');
            return false;
        }
        
        if (!content || !content.trim()) {
            e.preventDefault();
            alert('⚠️ Veuillez saisir un message');
            return false;
        }
        
        // Animation du bouton d'envoi
        var submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html('<i class="icon-spinner2 spinner mr-2"></i> Envoi en cours...')
                 .prop('disabled', true);
        
        // Permettre la soumission
        return true;
    });

    // ===== Réinitialisation =====
    $('button[type="reset"]').click(function() {
        $('.select2-recipients').val(null).trigger('change');
        $('#fileList').empty();
        $('.custom-file-label').text('📎 Choisir des fichiers...');
        updateCharCount();
    });
});
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .emoji-btn {
        transition: all 0.2s;
        font-size: 1.2rem;
        padding: 0.25rem 0.5rem;
        border: 1px solid #e0e0e0;
    }
    
    .emoji-btn:hover {
        transform: scale(1.2);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .select2-container--bootstrap .select2-selection--multiple {
        min-height: 45px;
        border-radius: 0.25rem;
    }
    
    .select2-container--bootstrap .select2-selection--multiple .select2-selection__choice {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
        padding: 5px 10px;
        margin: 3px;
        border-radius: 15px;
    }
    
    .select2-container--bootstrap .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    
    .custom-file-label::after {
        content: "Parcourir";
    }
    
    #messageContent {
        border-radius: 0.25rem;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    #messageContent:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endpush
