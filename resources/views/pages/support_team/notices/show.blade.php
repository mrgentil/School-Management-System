@extends('layouts.master')
@section('page_title', $notice->title)
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">{{ $notice->title }}</h6>
        <div class="header-elements">
            <span class="badge badge-{{ $notice->type == 'urgent' ? 'danger' : ($notice->type == 'event' ? 'success' : 'primary') }} mr-2">
                {{ ucfirst($notice->type) }}
            </span>
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="icon-user mr-1"></i>Publié par : <strong>{{ $notice->creator->name }}</strong>
                    </small>
                </div>
                <div class="col-md-6 text-right">
                    <small class="text-muted">
                        <i class="icon-calendar mr-1"></i>{{ $notice->created_at->format('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <div class="content-divider text-muted mx-0">
            <span>Contenu de l'annonce</span>
        </div>

        <div class="mt-3">
            {!! nl2br(e($notice->content)) !!}
        </div>

        @if($notice->end_date)
        <div class="alert alert-info mt-4">
            <i class="icon-info22 mr-2"></i>
            Cette annonce expire le <strong>{{ $notice->end_date->format('d/m/Y à H:i') }}</strong>
        </div>
        @endif

        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="icon-users mr-1"></i>Public cible : 
                        <span class="badge badge-light">{{ ucfirst($notice->target_audience) }}</span>
                    </small>
                </div>
                <div class="col-md-6 text-right">
                    <small class="text-muted">
                        Statut : 
                        <span class="badge badge-{{ $notice->is_active ? 'success' : 'secondary' }}">
                            {{ $notice->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <a href="{{ route('notices.index') }}" class="btn btn-link">
                <i class="icon-arrow-left8 mr-2"></i> Retour à la liste
            </a>
            
            @if(Qs::userIsTeamSA())
            <div>
                <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-warning">
                    <i class="icon-pencil mr-2"></i> Modifier
                </a>
                <form method="post" action="{{ route('notices.destroy', $notice->id) }}" class="d-inline ml-2">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?')">
                        <i class="icon-trash mr-2"></i> Supprimer
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
