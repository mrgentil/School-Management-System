

<?php
    $showBulkOptions = $showBulkOptions ?? false;
?>

<div class="card border-0 shadow-sm">
    <!-- En-tête -->
    <div class="card-header bg-white border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="<?php echo e(route($routePrefix . '.messages.index')); ?>" class="btn btn-light btn-sm mr-3">
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
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong><i class="icon-warning mr-2"></i>Erreur</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route($routePrefix . '.messages.store')); ?>" method="POST" enctype="multipart/form-data" id="messageForm">
            <?php echo csrf_field(); ?>
            
            <?php if($showBulkOptions): ?>
                <!-- Type d'envoi amélioré -->
                <div class="form-group">
                    <label class="font-weight-semibold">
                        <i class="icon-users mr-1 text-primary"></i>
                        Type d'envoi <span class="text-danger">*</span>
                    </label>
                    <select name="recipient_type" id="recipientType" class="form-control form-control-lg" required>
                        <option value="">-- Sélectionner le type d'envoi --</option>
                        <optgroup label="Envoi en masse">
                            <option value="all" <?php echo e(old('recipient_type') == 'all' ? 'selected' : ''); ?>>📢 Tous les utilisateurs</option>
                            <option value="all_students" <?php echo e(old('recipient_type') == 'all_students' ? 'selected' : ''); ?>>🎓 Tous les étudiants</option>
                            <option value="all_parents" <?php echo e(old('recipient_type') == 'all_parents' ? 'selected' : ''); ?>>👨‍👩‍👧 Tous les parents</option>
                            <option value="all_teachers" <?php echo e(old('recipient_type') == 'all_teachers' ? 'selected' : ''); ?>>👨‍🏫 Tous les enseignants</option>
                        </optgroup>
                        <optgroup label="Envoi individuel">
                            <option value="one_student" <?php echo e(old('recipient_type') == 'one_student' ? 'selected' : ''); ?>>🎓 Un étudiant spécifique</option>
                            <option value="one_parent" <?php echo e(old('recipient_type') == 'one_parent' ? 'selected' : ''); ?>>👨‍👩‍👧 Un parent spécifique</option>
                            <option value="one_teacher" <?php echo e(old('recipient_type') == 'one_teacher' ? 'selected' : ''); ?>>👨‍🏫 Un enseignant spécifique</option>
                            <option value="individual" <?php echo e(old('recipient_type') == 'individual' ? 'selected' : ''); ?>>👥 Plusieurs destinataires (sélection libre)</option>
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
                        <?php if(isset($recipients['students'])): ?>
                            <?php $__currentLoopData = $recipients['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email ?? 'N/A'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
                        <?php if(isset($recipients['parents'])): ?>
                            <?php $__currentLoopData = $recipients['parents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email ?? 'N/A'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
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
                        <?php if(isset($recipients['teachers'])): ?>
                            <?php $__currentLoopData = $recipients['teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email ?? 'N/A'); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Sélection multiple (destinataires libres) -->
                <div class="form-group" id="multipleRecipientsGroup" style="display: none;">
                    <label class="font-weight-semibold">
                        <i class="icon-users4 mr-1 text-primary"></i>
                        Sélectionner les destinataires <span class="text-danger">*</span>
                    </label>
                    <select name="recipients[]" id="recipientsSelect" class="form-control select2-recipients" multiple="multiple">
                        <?php if(isset($recipients['students']) && $recipients['students']->count() > 0): ?>
                            <optgroup label="🎓 Étudiants">
                                <?php $__currentLoopData = $recipients['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="student"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['teachers']) && $recipients['teachers']->count() > 0): ?>
                            <optgroup label="👨‍🏫 Enseignants">
                                <?php $__currentLoopData = $recipients['teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="teacher"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['parents']) && $recipients['parents']->count() > 0): ?>
                            <optgroup label="👨‍👩‍👧 Parents">
                                <?php $__currentLoopData = $recipients['parents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="parent"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['admins']) && $recipients['admins']->count() > 0): ?>
                            <optgroup label="👔 Administration">
                                <?php $__currentLoopData = $recipients['admins']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="admin"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['accountants']) && $recipients['accountants']->count() > 0): ?>
                            <optgroup label="💼 Comptables">
                                <?php $__currentLoopData = $recipients['accountants']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="accountant"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['librarians']) && $recipients['librarians']->count() > 0): ?>
                            <optgroup label="📚 Bibliothécaires">
                                <?php $__currentLoopData = $recipients['librarians']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="librarian"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
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
            <?php else: ?>
                <input type="hidden" name="recipient_type" value="individual">
                
                <!-- Sélection des destinataires pour non-admin -->
                <div class="form-group" id="recipientsGroup">
                    <label class="font-weight-semibold">
                        <i class="icon-users4 mr-1 text-primary"></i>
                        Destinataires <span class="text-danger">*</span>
                    </label>
                    <select name="recipients[]" id="recipientsSelect" class="form-control select2-recipients" multiple="multiple" required>
                        <?php if(isset($recipients['students']) && $recipients['students']->count() > 0): ?>
                            <optgroup label="Étudiants">
                                <?php $__currentLoopData = $recipients['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="student"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['teachers']) && $recipients['teachers']->count() > 0): ?>
                            <optgroup label="Enseignants">
                                <?php $__currentLoopData = $recipients['teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="teacher"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['admins']) && $recipients['admins']->count() > 0): ?>
                            <optgroup label="Administration">
                                <?php $__currentLoopData = $recipients['admins']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="admin"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if(isset($recipients['parents']) && $recipients['parents']->count() > 0): ?>
                            <optgroup label="Parents">
                                <?php $__currentLoopData = $recipients['parents']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-type="parent"><?php echo e($user->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                    <small class="form-text text-muted">
                        <i class="icon-info22 mr-1"></i>Recherchez et sélectionnez un ou plusieurs destinataires
                    </small>
                </div>
            <?php endif; ?>
            
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
                       value="<?php echo e(old('subject')); ?>" 
                       required>
            </div>
            
            <!-- Priorité -->
            <div class="form-group">
                <label class="font-weight-semibold">
                    <i class="icon-flag3 mr-1 text-primary"></i>
                    Priorité
                </label>
                <select name="priority" class="form-control">
                    <option value="normal" <?php echo e(old('priority') == 'normal' ? 'selected' : ''); ?>>Normale</option>
                    <option value="low" <?php echo e(old('priority') == 'low' ? 'selected' : ''); ?>>Basse</option>
                    <option value="high" <?php echo e(old('priority') == 'high' ? 'selected' : ''); ?>>Haute</option>
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
                    <?php $__currentLoopData = ['😊', '👍', '🙏', '📚', '✅', '❓', '💡', '⭐', '📢', '⚠️']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emoji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" class="btn btn-sm btn-light emoji-btn mr-1 mb-1" data-emoji="<?php echo e($emoji); ?>"><?php echo e($emoji); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <textarea name="content" 
                          id="messageContent"
                          rows="8" 
                          class="form-control" 
                          placeholder="Écrivez votre message ici..."
                          required><?php echo e(old('content')); ?></textarea>
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
                <a href="<?php echo e(route($routePrefix . '.messages.index')); ?>" class="btn btn-light">
                    <i class="icon-cross2 mr-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="icon-paperplane mr-2"></i>Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('global_assets/js/plugins/select2/js/select2.full.min.js')); ?>"></script>
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
                    'all': '<?php echo e(isset($recipients) ? collect($recipients)->flatten()->count() : 0); ?>',
                    'all_students': '<?php echo e(isset($recipients["students"]) ? $recipients["students"]->count() : 0); ?>',
                    'all_parents': '<?php echo e(isset($recipients["parents"]) ? $recipients["parents"]->count() : 0); ?>',
                    'all_teachers': '<?php echo e(isset($recipients["teachers"]) ? $recipients["teachers"]->count() : 0); ?>'
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
<?php $__env->stopSection(); ?>
<?php /**PATH D:\laragon\www\eschool\resources\views/partials/messages/create.blade.php ENDPATH**/ ?>