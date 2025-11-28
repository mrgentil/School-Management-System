@extends('layouts.master')
@section('page_title', 'Gestion des Notifications')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-bell2 mr-2"></i> Gestion des Notifications
        </h5>
    </div>
</div>

{{-- Statistiques --}}
<div class="row">
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['total_sent'] }}</h3>
                <small>Total envoyées</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['today_sent'] }}</h3>
                <small>Aujourd'hui</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['unread'] }}</h3>
                <small>Non lues</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['parents_count'] }}</h3>
                <small>Parents</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['teachers_count'] }}</h3>
                <small>Enseignants</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-dark text-white">
            <div class="card-body text-center py-3">
                <h3 class="mb-0">{{ $stats['students_count'] }}</h3>
                <small>Étudiants</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Formulaire d'envoi --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-paperplane mr-2"></i> Envoyer une Notification</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('notifications.send') }}">
                    @csrf
                    
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
                            @foreach(\App\Models\User::orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->user_type }})</option>
                            @endforeach
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
                            <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="1" checked>
                            <label class="custom-control-label" for="send_email">
                                📧 Envoyer aussi par email
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-block">
                        <i class="icon-paperplane mr-2"></i> Envoyer la Notification
                    </button>
                </form>
            </div>
        </div>

        {{-- Templates rapides --}}
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

    {{-- Historique --}}
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
                            @forelse($recentNotifications as $notif)
                                <tr>
                                    <td>
                                        <small>{{ $notif->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $notif->user->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $notif->user->user_type ?? '' }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($notif->title, 30) }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($notif->message, 40) }}</small>
                                    </td>
                                    <td>
                                        @if($notif->is_read)
                                            <span class="badge badge-success">✓ Lu</span>
                                        @else
                                            <span class="badge badge-warning">Non lu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Aucune notification envoyée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
@endsection
