@extends('layouts.master')
@section('page_title', 'Tableau de Bord Parent')

@section('content')
{{-- En-tête de bienvenue --}}
<div class="card bg-primary text-white mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">👋 Bienvenue, {{ Auth::user()->name }}</h4>
                <p class="mb-0 opacity-75">Année scolaire {{ $year }}</p>
            </div>
            <div class="text-right">
                <span class="badge badge-light text-primary">{{ $stats['total_children'] }} enfant(s)</span>
            </div>
        </div>
    </div>
</div>

{{-- Alertes importantes --}}
@if($stats['total_balance'] > 0)
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <span><i class="icon-warning mr-2"></i> Vous avez un solde de <strong>{{ number_format($stats['total_balance'], 0, ',', ' ') }} FC</strong> à régler.</span>
    <a href="{{ route('parent.progress.index') }}" class="btn btn-sm btn-warning">Voir détails</a>
</div>
@endif

@if($stats['unread_notifications'] > 0)
<div class="alert alert-info d-flex justify-content-between align-items-center">
    <span><i class="icon-bell2 mr-2"></i> Vous avez <strong>{{ $stats['unread_notifications'] }}</strong> notification(s) non lue(s).</span>
</div>
@endif

{{-- Cartes des enfants --}}
<div class="row">
    @foreach($childrenData as $data)
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="{{ $data['info']->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                         class="rounded-circle mr-3" width="50" height="50">
                    <div>
                        <h5 class="mb-0">{{ $data['info']->user->name }}</h5>
                        <small class="text-muted">
                            {{ $data['info']->my_class->full_name ?? $data['info']->my_class->name ?? 'N/A' }}
                            @if($data['info']->section)
                                - {{ $data['info']->section->name }}
                            @endif
                        </small>
                    </div>
                </div>
                <a href="{{ route('parent.progress.show', $data['info']->user_id) }}" class="btn btn-sm btn-primary">
                    <i class="icon-stats-growth"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Notes récentes --}}
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2"><i class="icon-book mr-1"></i> Notes récentes</h6>
                        @if(count($data['grades']) > 0)
                            @foreach($data['grades'] as $grade)
                                <div class="d-flex justify-content-between mb-1">
                                    <small>{{ Str::limit($grade['subject'], 15) }}</small>
                                    <span class="badge badge-{{ $grade['status'] }}">{{ $grade['grade'] }}/20</span>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted">Aucune note</small>
                        @endif
                    </div>

                    {{-- Présences & Finance --}}
                    <div class="col-md-6">
                        {{-- Présence ce mois --}}
                        <h6 class="text-muted mb-2"><i class="icon-checkmark-circle mr-1"></i> Présence (ce mois)</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>Taux de présence</small>
                                <strong class="text-{{ $data['attendance']['rate'] >= 80 ? 'success' : ($data['attendance']['rate'] >= 60 ? 'warning' : 'danger') }}">
                                    {{ $data['attendance']['rate'] }}%
                                </strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $data['attendance']['rate'] }}%"></div>
                            </div>
                            <small class="text-muted">
                                {{ $data['attendance']['present'] }} présent(s), 
                                {{ $data['attendance']['absent'] }} absent(s),
                                {{ $data['attendance']['late'] }} retard(s)
                            </small>
                        </div>

                        {{-- Finance --}}
                        <h6 class="text-muted mb-2"><i class="icon-cash3 mr-1"></i> Finance</h6>
                        @if($data['finance']['is_up_to_date'])
                            <span class="badge badge-success">✅ À jour</span>
                        @else
                            <span class="badge badge-danger">
                                Solde: {{ number_format($data['finance']['total_balance'], 0, ',', ' ') }} FC
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    {{-- Événements à venir --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-calendar3 mr-2"></i> Événements à Venir</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingEvents as $event)
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $event->title }}</strong>
                            <br><small class="text-muted">{{ $event->event_date?->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge" style="background-color: {{ $event->color ?? '#2196F3' }}; color: white;">
                            {{ $event->event_type }}
                        </span>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                        Aucun événement prévu
                    </div>
                @endforelse
            </div>
            @if($upcomingEvents->count() > 0)
                <div class="card-footer">
                    <a href="{{ route('calendar.public') }}" class="btn btn-sm btn-info">
                        Voir le calendrier complet
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Notifications --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="card-title mb-0"><i class="icon-bell2 mr-2"></i> Notifications Récentes</h6>
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                    <div class="p-3 border-bottom {{ !$notif->is_read ? 'bg-light' : '' }}">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $notif->title }}</strong>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted">{{ Str::limit($notif->message, 80) }}</small>
                    </div>
                @empty
                    <div class="p-3 text-center text-muted">
                        <i class="icon-bell2 d-block mb-2" style="font-size: 24px;"></i>
                        Aucune notification
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Actions rapides --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-grid6 mr-2"></i> Actions Rapides</h6>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('parent.progress.index') }}" class="btn btn-lg btn-outline-primary w-100">
                    <i class="icon-stats-growth d-block mb-2" style="font-size: 24px;"></i>
                    Progression
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('parent.bulletins.index') }}" class="btn btn-lg btn-outline-success w-100">
                    <i class="icon-file-text2 d-block mb-2" style="font-size: 24px;"></i>
                    Bulletins
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('calendar.public') }}" class="btn btn-lg btn-outline-info w-100">
                    <i class="icon-calendar3 d-block mb-2" style="font-size: 24px;"></i>
                    Calendrier
                </a>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <a href="{{ route('my_children') }}" class="btn btn-lg btn-outline-secondary w-100">
                    <i class="icon-users4 d-block mb-2" style="font-size: 24px;"></i>
                    Mes Enfants
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
