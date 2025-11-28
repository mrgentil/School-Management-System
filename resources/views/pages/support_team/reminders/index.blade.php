@extends('layouts.master')
@section('page_title', 'Gestion des Rappels')

@section('content')
{{-- Statistiques --}}
<div class="row">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="icon-calendar3 icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['pending_events'] }}</h3>
                <small>Événements avec rappel</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="icon-bell icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['notifications_today'] }}</h3>
                <small>Notifications aujourd'hui</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="icon-users icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                <small>Utilisateurs total</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Envoyer un rappel manuel --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="card-title mb-0">
                    <i class="icon-paperplane mr-2"></i> Envoyer un Rappel Manuel
                </h5>
            </div>
            <form action="{{ route('reminders.send_manual') }}" method="POST">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="type" value="custom">
                    
                    <div class="form-group">
                        <label><strong>Titre du rappel <span class="text-danger">*</span></strong></label>
                        <input type="text" name="title" class="form-control" 
                               placeholder="Ex: 📢 Réunion parents-profs demain !" required>
                    </div>

                    <div class="form-group">
                        <label><strong>Message <span class="text-danger">*</span></strong></label>
                        <textarea name="message" class="form-control" rows="3" 
                                  placeholder="Contenu du rappel..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Destinataires</strong></label>
                                <select name="target" class="form-control select">
                                    <option value="all">👥 Tout le monde</option>
                                    <option value="students">🎓 Élèves</option>
                                    <option value="teachers">👨‍🏫 Enseignants</option>
                                    <option value="parents">👨‍👩‍👧 Parents</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="send_whatsapp" name="send_whatsapp" value="1">
                                    <label class="custom-control-label" for="send_whatsapp">
                                        <i class="fab fa-whatsapp text-success"></i> Envoyer aussi par WhatsApp
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="icon-paperplane mr-1"></i> Envoyer le Rappel
                    </button>
                </div>
            </form>
        </div>

        {{-- Actions rapides --}}
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="icon-lightning mr-2"></i> Actions Rapides</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('reminders.payment') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-block mb-2"
                            onclick="return confirm('Envoyer rappels de paiement à tous les parents ?')">
                        💰 Rappel de Paiement (Parents)
                    </button>
                </form>
                
                <a href="{{ route('calendar.index') }}" class="btn btn-outline-primary btn-block">
                    📅 Gérer les Événements
                </a>
            </div>
        </div>
    </div>

    {{-- Événements avec rappel programmé --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="icon-alarm mr-2"></i> Événements avec Rappel
                </h5>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $event->title }}</strong>
                            <br>
                            <small class="text-muted">
                                📅 {{ $event->formatted_date }}
                                {!! $event->type_badge !!}
                            </small>
                        </div>
                        <form action="{{ route('reminders.send_event', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-info"
                                    onclick="return confirm('Envoyer rappel maintenant ?')">
                                <i class="icon-bell"></i> Rappeler
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 32px;"></i>
                        Aucun événement avec rappel programmé
                        <br>
                        <a href="{{ route('calendar.create') }}" class="btn btn-sm btn-primary mt-2">
                            Créer un événement
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Info automatisation --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="card-title mb-0"><i class="icon-cog mr-2"></i> Automatisation</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">Pour envoyer les rappels automatiquement, ajoutez cette tâche CRON :</p>
                <code class="d-block bg-light p-2 rounded">
                    * * * * * php artisan reminders:send
                </code>
                <small class="text-muted mt-2 d-block">
                    Cela enverra automatiquement les rappels J-1 et J-3 pour les événements.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
