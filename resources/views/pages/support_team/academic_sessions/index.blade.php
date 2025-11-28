@extends('layouts.master')
@section('page_title', 'Gestion des Années Scolaires')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline bg-dark text-white">
        <h5 class="card-title"><i class="icon-calendar mr-2"></i> Années Scolaires</h5>
        <div class="header-elements">
            <a href="{{ route('academic_sessions.create') }}" class="btn btn-success btn-sm">
                <i class="icon-plus2 mr-1"></i> Nouvelle Année
            </a>
            <a href="{{ route('academic_sessions.prepare_new_year') }}" class="btn btn-warning btn-sm ml-2">
                <i class="icon-forward mr-1"></i> Préparer Nouvelle Année
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($sessions->isEmpty())
            <div class="alert alert-info">
                <i class="icon-info22 mr-2"></i>
                Aucune année scolaire enregistrée. 
                <a href="{{ route('academic_sessions.create') }}" class="alert-link">Créez-en une maintenant</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>Année</th>
                            <th>Dates</th>
                            <th class="text-center">Élèves</th>
                            <th class="text-center">Classes</th>
                            <th class="text-center">Moyenne</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                            <tr class="{{ $session->is_current ? 'table-primary' : '' }}">
                                <td>
                                    <strong>{{ $session->name }}</strong>
                                    @if($session->is_current)
                                        <span class="badge badge-primary ml-2">📍 Courante</span>
                                    @endif
                                    @if($session->label && $session->label != 'Année Scolaire ' . $session->name)
                                        <br><small class="text-muted">{{ $session->label }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($session->start_date && $session->end_date)
                                        <small>
                                            {{ $session->start_date->format('d/m/Y') }} 
                                            <i class="icon-arrow-right7 mx-1"></i>
                                            {{ $session->end_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <small class="text-muted">Non défini</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $session->total_students }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary">{{ $session->total_classes }}</span>
                                </td>
                                <td class="text-center">
                                    @if($session->average_score)
                                        <span class="badge badge-{{ $session->average_score >= 10 ? 'success' : 'warning' }}">
                                            {{ number_format($session->average_score, 1) }}/20
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{!! $session->status_badge !!}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('academic_sessions.show', $session) }}" 
                                           class="btn btn-info btn-sm" title="Détails">
                                            <i class="icon-eye"></i>
                                        </a>
                                        <a href="{{ route('academic_sessions.edit', $session) }}" 
                                           class="btn btn-primary btn-sm" title="Modifier">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        
                                        @if(!$session->is_current)
                                            <form action="{{ route('academic_sessions.set_current', $session) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Définir comme courante"
                                                        onclick="return confirm('Définir {{ $session->name }} comme année courante ?')">
                                                    <i class="icon-checkmark"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($session->status != 'archived' && !$session->is_current)
                                            <form action="{{ route('academic_sessions.archive', $session) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm" 
                                                        title="Archiver"
                                                        onclick="return confirm('Archiver cette année ?')">
                                                    <i class="icon-drawer"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('academic_sessions.copy_structure', $session) }}" 
                                           class="btn btn-warning btn-sm" title="Copier vers nouvelle année">
                                            <i class="icon-copy"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Légende --}}
<div class="card mt-3">
    <div class="card-body py-2">
        <div class="d-flex flex-wrap align-items-center">
            <span class="mr-4"><strong>Légende:</strong></span>
            <span class="badge badge-success mr-2">Active</span> En cours
            <span class="badge badge-secondary mx-2">Archivée</span> Terminée
            <span class="badge badge-info mx-2">À venir</span> Prochaine année
            <span class="badge badge-primary mx-2">📍 Courante</span> Année de travail actuelle
        </div>
    </div>
</div>
@endsection
