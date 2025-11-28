
<?php $__env->startSection('page_title', 'Test WhatsApp'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fab fa-whatsapp mr-2"></i> Test WhatsApp API
        </h5>
    </div>
    <div class="card-body">
        
        <div class="alert <?php echo e($isConfigured ? 'alert-success' : 'alert-danger'); ?>">
            <?php if($isConfigured): ?>
                <i class="icon-checkmark3 mr-2"></i>
                <strong>WhatsApp est configuré !</strong> Vous pouvez envoyer des messages.
            <?php else: ?>
                <i class="icon-warning mr-2"></i>
                <strong>WhatsApp n'est pas configuré.</strong><br>
                Ajoutez ces variables dans votre fichier <code>.env</code> :
                <pre class="mt-2 bg-light p-2">WHATSAPP_TOKEN=votre_token
WHATSAPP_PHONE_NUMBER_ID=votre_phone_number_id</pre>
            <?php endif; ?>
        </div>

        <div class="row">
            
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="icon-bubble mr-2"></i> Envoyer un message simple</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('whatsapp.test.send')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label><strong>Numéro de téléphone</strong></label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="Ex: 243812345678 ou 0812345678" required>
                                <small class="text-muted">Format: avec ou sans indicatif pays (243 pour RDC)</small>
                            </div>
                            <div class="form-group">
                                <label><strong>Message</strong></label>
                                <textarea name="message" class="form-control" rows="3" 
                                          placeholder="Votre message..." required>Ceci est un test de l'API WhatsApp depuis ESchool ! 🎓</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" <?php echo e(!$isConfigured ? 'disabled' : ''); ?>>
                                <i class="fab fa-whatsapp mr-2"></i> Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="icon-file-text mr-2"></i> Tester notification bulletin</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('whatsapp.test.bulletin')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label><strong>Numéro de téléphone</strong></label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="Ex: 243812345678" required>
                            </div>
                            <div class="alert alert-info">
                                <strong>Aperçu du message :</strong><br>
                                <small>
                                    📋 <strong>BULLETIN SCOLAIRE DISPONIBLE</strong><br>
                                    🏫 <?php echo e(Qs::getSetting('system_name') ?? 'Mon École'); ?><br>
                                    👤 Élève: <strong>Jean Test</strong><br>
                                    📚 Classe: 6ème A<br>
                                    📅 Période: Période 1<br>
                                    🗓️ Année: <?php echo e(Qs::getCurrentSession()); ?>

                                </small>
                            </div>
                            <button type="submit" class="btn btn-success btn-block" <?php echo e(!$isConfigured ? 'disabled' : ''); ?>>
                                <i class="icon-paperplane mr-2"></i> Envoyer notification test
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="icon-info22 mr-2"></i> Instructions importantes</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>⚠️ Mode Test (Sandbox)</strong></h6>
                        <ul>
                            <li>Vous devez d'abord <strong>enregistrer le numéro de destination</strong> sur Facebook Developers</li>
                            <li>Maximum <strong>5 numéros</strong> en mode test</li>
                            <li>Le destinataire doit envoyer un message au numéro WhatsApp Business d'abord</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><strong>📱 Comment ajouter un numéro de test</strong></h6>
                        <ol>
                            <li>Allez sur <a href="https://developers.facebook.com/" target="_blank">developers.facebook.com</a></li>
                            <li>Ouvrez votre app → WhatsApp → Configuration</li>
                            <li>Section "À" → Cliquez "Gérer la liste des numéros"</li>
                            <li>Ajoutez le numéro que vous voulez tester</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\eschool\resources\views/pages/support_team/whatsapp_test.blade.php ENDPATH**/ ?>