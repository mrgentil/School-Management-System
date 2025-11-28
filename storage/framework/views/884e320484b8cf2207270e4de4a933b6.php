
<?php $__env->startSection('page_title', 'Gestion des Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-bell2 mr-2"></i> Gestion des Notifications
        </h5>
    </div>
</div>

<?php
    $mailConfigured = !empty(config('mail.mailers.smtp.host')) 
        && config('mail.mailers.smtp.host') !== 'mailpit' 
        && config('mail.mailers.smtp.host') !== 'localhost'
        && !in_array(config('mail.default'), ['log', 'array']);
?>

<?php if(!$mailConfigured): ?>
<div class="alert alert-info d-flex align-items-center">
    <i class="icon-info22 mr-3" style="font-size: 24px;"></i>
    <div>
        <strong>📧 Email non configuré</strong><br>
        Les notifications seront envoyées uniquement <strong>dans l'application</strong>. 
        Pour activer les emails, configurez SMTP dans le fichier <code>.env</code>
    </div>
</div>
<?php else: ?>
<div class="alert alert-success d-flex align-items-center">
    <i class="icon-checkmark-circle mr-3" style="font-size: 24px;"></i>
    <div>
        <strong>✅ Email configuré</strong><br>
        Les notifications seront envoyées par email ET dans l'application.
    </div>
</div>
<?php endif; ?>


<div class="row">
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['total_sent']); ?></h3>
                <small>Total envoyées</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['today_sent']); ?></h3>
                <small>Aujourd'hui</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['unread']); ?></h3>
                <small>Non lues</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['parents_count']); ?></h3>
                <small>Parents</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['teachers_count']); ?></h3>
                <small>Enseignants</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-dark text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0"><?php echo e($stats['students_count']); ?></h3>
                <small>Étudiants</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-paperplane mr-2"></i> Envoyer une Notification</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('notifications.send')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label><strong>Destinataires</strong> <span class="text-danger">*</span></label>
                        <select name="target" class="form-control" id="target-select" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="all">📢 Tous les utilisateurs</option>
                            <option value="parents">👨‍👩‍👧 Tous les parents</option>
                            <option value="teachers">👨‍🏫 Tous les enseignants</option>
                            <option value="students">🎓 Tous les étudiants</option>
                            <option value="user">👤 Utilisateur spécifique</option>
                        </select>
                    </div>

                    <div class="form-group" id="user-select-group" style="display: none;">
                        <label><strong>Utilisateur</strong></label>
                        <select name="user_id" class="form-control select-search">
                            <option value="">-- Sélectionner --</option>
                            <?php $__currentLoopData = \App\Models\User::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->user_type); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><strong>Sujet</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" required placeholder="Ex: Information importante">
                    </div>

                    <div class="form-group">
                        <label><strong>Message</strong> <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" required placeholder="Votre message..."></textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="1" 
                                <?php echo e($mailConfigured ? 'checked' : 'disabled'); ?>>
                            <label class="custom-control-label" for="send_email">
                                📧 Envoyer aussi par email
                                <?php if(!$mailConfigured): ?>
                                    <small class="text-muted">(non configuré)</small>
                                <?php endif; ?>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">
                        <i class="icon-paperplane mr-2"></i> Envoyer la Notification
                    </button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-bookmark mr-2"></i> Templates Rapides</h6>
            </div>
            <div class="card-body p-0">
                <a href="#" class="d-block p-3 border-bottom text-dark template-btn" 
                   data-subject="📅 Rappel: Réunion des parents" 
                   data-message="Chers parents, nous vous rappelons la réunion des parents prévue prochainement. Votre présence est vivement souhaitée.">
                    <strong>Réunion des parents</strong>
                </a>
                <a href="#" class="d-block p-3 border-bottom text-dark template-btn" 
                   data-subject="📚 Rentrée scolaire" 
                   data-message="La rentrée scolaire aura lieu bientôt. Merci de préparer les fournitures nécessaires.">
                    <strong>Rentrée scolaire</strong>
                </a>
                <a href="#" class="d-block p-3 border-bottom text-dark template-btn" 
                   data-subject="🎉 Vacances scolaires" 
                   data-message="Nous vous informons que les vacances scolaires débutent prochainement. Bonnes vacances à tous!">
                    <strong>Vacances scolaires</strong>
                </a>
                <a href="#" class="d-block p-3 text-dark template-btn" 
                   data-subject="💰 Rappel de paiement" 
                   data-message="Nous vous rappelons que les frais scolaires doivent être réglés avant la date limite. Merci de régulariser votre situation.">
                    <strong>Rappel de paiement</strong>
                </a>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-history mr-2"></i> Dernières Notifications Envoyées</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px;">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Destinataire</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <small><?php echo e($notif->created_at->format('d/m/Y H:i')); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo e($notif->user->name ?? 'N/A'); ?></strong>
                                        <br><small class="text-muted"><?php echo e($notif->user->user_type ?? ''); ?></small>
                                    </td>
                                    <td>
                                        <strong><?php echo e(Str::limit($notif->title, 30)); ?></strong>
                                        <br><small class="text-muted"><?php echo e(Str::limit($notif->message, 40)); ?></small>
                                    </td>
                                    <td>
                                        <?php if($notif->is_read): ?>
                                            <span class="badge badge-success">✓ Lu</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Non lu</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Aucune notification envoyée
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Afficher/masquer le sélecteur d'utilisateur
    document.getElementById('target-select').addEventListener('change', function() {
        const userGroup = document.getElementById('user-select-group');
        userGroup.style.display = this.value === 'user' ? 'block' : 'none';
    });

    // Templates rapides
    document.querySelectorAll('.template-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('input[name="subject"]').value = this.dataset.subject;
            document.querySelector('textarea[name="message"]').value = this.dataset.message;
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/notifications/index.blade.php ENDPATH**/ ?>