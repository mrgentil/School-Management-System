
<div class="modal fade" id="notifyParentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="icon-mail5 mr-2"></i>Notifier les Parents
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('exam_analytics.send_parent_notifications')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6><i class="icon-info22 mr-2"></i>Notifications Automatiques</h6>
                        <p class="mb-0">Envoyez automatiquement des notifications par email aux parents concernant les résultats de leurs enfants.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notify_exam_id">Examen <span class="text-danger">*</span></label>
                                <select name="exam_id" id="notify_exam_id" class="form-control select" required>
                                    <option value="">Sélectionner un examen</option>
                                    <?php $__currentLoopData = \App\Models\Exam::where('year', Qs::getSetting('current_session'))->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($exam->id); ?>"><?php echo e($exam->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notification_type">Type de Notification <span class="text-danger">*</span></label>
                                <select name="type" id="notification_type" class="form-control" required>
                                    <option value="">Choisir le type</option>
                                    <option value="results_published">📊 Résultats Publiés (Tous les parents)</option>
                                    <option value="excellent_performance">🏆 Performance Excellente (≥80%)</option>
                                    <option value="struggling_student">⚠️ Élève en Difficulté (<40%)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Filtres Supplémentaires</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="class_filter" id="class_filter" class="form-control">
                                    <option value="">Toutes les classes</option>
                                    <?php $__currentLoopData = \App\Models\MyClass::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="form-text text-muted">Filtrer par classe (optionnel)</small>
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="min_average" class="form-control" placeholder="Moyenne minimale" min="0" max="100">
                                <small class="form-text text-muted">Moyenne minimale pour notification</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Options de Notification</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="include_bulletin" name="include_bulletin" checked>
                            <label class="custom-control-label" for="include_bulletin">
                                Inclure le bulletin en pièce jointe (PDF)
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="include_recommendations" name="include_recommendations" checked>
                            <label class="custom-control-label" for="include_recommendations">
                                Inclure des recommandations personnalisées
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="schedule_notification" name="schedule_notification">
                            <label class="custom-control-label" for="schedule_notification">
                                Programmer l'envoi (au lieu d'envoyer immédiatement)
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="schedule_options" style="display: none;">
                        <label>Date et Heure d'Envoi</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="date" name="schedule_date" class="form-control" min="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                            <div class="col-md-6">
                                <input type="time" name="schedule_time" class="form-control" value="08:00">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="custom_message">Message Personnalisé (Optionnel)</label>
                        <textarea name="custom_message" id="custom_message" class="form-control" rows="3" 
                                  placeholder="Ajoutez un message personnalisé qui sera inclus dans l'email..."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="icon-warning22 mr-2"></i>Aperçu des Destinataires</h6>
                        <div id="recipients_preview">
                            <p class="mb-0 text-muted">Sélectionnez un examen et un type pour voir l'aperçu</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-outline-info" id="preview_notifications">
                        <i class="icon-eye mr-2"></i>Aperçu
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="icon-mail5 mr-2"></i>Envoyer les Notifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Afficher/masquer les options de programmation
document.getElementById('schedule_notification').addEventListener('change', function() {
    const scheduleOptions = document.getElementById('schedule_options');
    scheduleOptions.style.display = this.checked ? 'block' : 'none';
});

// Aperçu des destinataires
document.getElementById('preview_notifications').addEventListener('click', function() {
    const examId = document.getElementById('notify_exam_id').value;
    const notificationType = document.getElementById('notification_type').value;
    const classFilter = document.getElementById('class_filter').value;
    
    if (!examId || !notificationType) {
        alert('Veuillez sélectionner un examen et un type de notification');
        return;
    }
    
    // Ici vous pouvez faire un appel AJAX pour récupérer l'aperçu
    const previewDiv = document.getElementById('recipients_preview');
    previewDiv.innerHTML = '<p class="text-info"><i class="icon-spinner2 spinner mr-2"></i>Chargement de l\'aperçu...</p>';
    
    // Simulation d'un aperçu (remplacez par un vrai appel AJAX)
    setTimeout(() => {
        let message = '';
        switch(notificationType) {
            case 'results_published':
                message = '<span class="text-success">📊 Tous les parents avec email valide recevront la notification</span>';
                break;
            case 'excellent_performance':
                message = '<span class="text-success">🏆 Parents des élèves avec moyenne ≥ 80% recevront la notification</span>';
                break;
            case 'struggling_student':
                message = '<span class="text-warning">⚠️ Parents des élèves avec moyenne < 40% recevront la notification</span>';
                break;
        }
        previewDiv.innerHTML = message;
    }, 1000);
});

// Validation du formulaire
document.querySelector('#notifyParentsModal form').addEventListener('submit', function(e) {
    const examId = document.getElementById('notify_exam_id').value;
    const notificationType = document.getElementById('notification_type').value;
    
    if (!examId || !notificationType) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires');
        return;
    }
    
    if (!confirm('Êtes-vous sûr de vouloir envoyer ces notifications ? Cette action ne peut pas être annulée.')) {
        e.preventDefault();
    }
});
</script>
<?php /**PATH C:\laragon\www\eschool\resources\views/pages/support_team/exam_analytics/modals/notify_parents.blade.php ENDPATH**/ ?>