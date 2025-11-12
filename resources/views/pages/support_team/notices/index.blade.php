@extends('layouts.master')
@section('page_title', 'Tableau d\'Affichage')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Annonces et Communications</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        @if(Qs::userIsTeamSA())
        <div class="mb-3">
            <a href="{{ route('notices.create') }}" class="btn btn-primary">
                <i class="icon-plus2 mr-2"></i> Nouvelle Annonce
            </a>
        </div>
        @endif

        <div class="row">
            @forelse($notices as $notice)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-left-3 border-left-{{ $notice->type == 'urgent' ? 'danger' : ($notice->type == 'event' ? 'success' : 'primary') }}">
                    <div class="card-header bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-{{ $notice->type == 'urgent' ? 'danger' : ($notice->type == 'event' ? 'success' : 'primary') }}">
                                {{ ucfirst($notice->type) }}
                            </span>
                            <small class="text-muted">{{ $notice->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">{{ $notice->title }}</h6>
                        <p class="card-text">{{ Str::limit($notice->content, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="icon-user mr-1"></i>{{ $notice->creator->name }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('notices.show', $notice->id) }}" class="btn btn-outline-primary">
                                    <i class="icon-eye"></i> Voir
                                </a>
                                @if(Qs::userIsTeamSA())
                                <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-outline-warning">
                                    <i class="icon-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('notices.destroy', $notice->id) }}" class="d-inline">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($notice->end_date)
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="icon-calendar mr-1"></i>Expire le {{ $notice->end_date->format('d/m/Y') }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-info22 icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune annonce disponible</h5>
                    <p class="text-muted">Il n'y a actuellement aucune annonce à afficher.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{ $notices->links() }}
    </div>
</div>

@endsection
