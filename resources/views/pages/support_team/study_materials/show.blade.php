@extends('layouts.master')
@section('page_title', 'Détails du Support Pédagogique')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">{{ $study_material->title }}</h6>
        <div class="header-elements">
            <div class="list-icons">
                <a href="{{ route('study-materials.edit', $study_material->id) }}" class="list-icons-item">
                    <i class="icon-pencil"></i>
                </a>
                {!! Qs::getPanelOptions() !!}
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <h5 class="mb-3">Description</h5>
                <p>{{ $study_material->description ?: 'Aucune description disponible.' }}</p>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="font-weight-semibold mb-3">Informations</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Visibilité :</strong>
                                @if($study_material->is_public)
                                    <span class="badge badge-success">📢 Public</span>
                                @else
                                    <span class="badge badge-info">🎓 Classe spécifique</span>
                                @endif
                            </li>
                            @if($study_material->myClass)
                            <li class="mb-2">
                                <strong>Classe :</strong> {{ $study_material->myClass->name }}
                            </li>
                            @endif
                            @if($study_material->subject)
                            <li class="mb-2">
                                <strong>Matière :</strong> {{ $study_material->subject->name }}
                            </li>
                            @endif
                            <li class="mb-2">
                                <strong>Téléchargé par :</strong> {{ $study_material->uploader->name }}
                            </li>
                            <li class="mb-2">
                                <strong>Date :</strong> {{ $study_material->created_at->format('d/m/Y') }}
                            </li>
                            <li>
                                <strong>Téléchargements :</strong> {{ $study_material->download_count ?? 0 }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">Fichier</h5>
                <div class="card">
                    <div class="card-body bg-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">
                                    <i class="{{ $study_material->file_icon }} mr-2 text-primary"></i>
                                    {{ $study_material->file_name }}
                                </h6>
                                <p class="text-muted mb-0">{{ $study_material->file_size_formatted }}</p>
                            </div>
                            <div>
                                <a href="{{ route('study-materials.download', $study_material->id) }}" 
                                   class="btn btn-primary">
                                    <i class="icon-download4 mr-2"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('study-materials.index') }}" class="btn btn-link">
            <i class="icon-arrow-left13 mr-2"></i>Retour à la liste
        </a>
        <a href="{{ route('study-materials.edit', $study_material->id) }}" class="btn btn-light">
            <i class="icon-pencil mr-2"></i>Modifier
        </a>
        @if(Qs::userIsSuperAdmin())
        <form method="POST" action="{{ route('study-materials.destroy', $study_material->id) }}" 
              style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce matériel ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="icon-trash mr-2"></i>Supprimer
            </button>
        </form>
        @endif
    </div>
</div>

@endsection
