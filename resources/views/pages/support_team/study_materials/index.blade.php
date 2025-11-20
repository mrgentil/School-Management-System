@extends('layouts.master')
@section('page_title', 'Supports Pédagogiques')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Bibliothèque de Supports Pédagogiques</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Filtres et recherche -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="class_id" class="form-control select">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class ? ($class->full_name ?: $class->name) : 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="subject_id" class="form-control select">
                        <option value="">Toutes les matières</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search4 mr-2"></i>Filtrer
                    </button>
                    @if(Qs::userIsTeamSAT())
                    <a href="{{ route('study-materials.create') }}" class="btn btn-success ml-2">
                        <i class="icon-plus2 mr-2"></i>Ajouter
                    </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Liste des supports -->
        <div class="row">
            @forelse($materials as $material)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <i class="{{ $material->file_icon }} icon-2x text-primary"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="mb-1">{{ $material->title }}</h6>
                                <p class="text-muted mb-2 small">{{ \Illuminate\Support\Str::limit($material->description, 80) }}</p>
                                
                                <div class="mb-2">
                                    @if($material->myClass)
                                    <span class="badge badge-light mr-1">{{ $material->myClass->name }}</span>
                                    @endif
                                    @if($material->subject)
                                    <span class="badge badge-primary mr-1">{{ $material->subject->name }}</span>
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span>{{ $material->file_size_formatted }}</span>
                                    <span>{{ $material->download_count }} téléchargements</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Par {{ $material->uploader->name }}
                            </small>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('study-materials.download', $material->id) }}" 
                                   class="btn btn-outline-success" title="Télécharger">
                                    <i class="icon-download4"></i>
                                </a>
                                <a href="{{ route('study-materials.show', $material->id) }}" 
                                   class="btn btn-outline-primary" title="Voir détails">
                                    <i class="icon-eye"></i>
                                </a>
                                @if(Qs::userIsTeamSAT())
                                <a href="{{ route('study-materials.edit', $material->id) }}" 
                                   class="btn btn-outline-warning" title="Modifier">
                                    <i class="icon-pencil"></i>
                                </a>
                                <form method="post" action="{{ route('study-materials.destroy', $material->id) }}" class="d-inline">
                                    @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Êtes-vous sûr ?')" title="Supprimer">
                                        <i class="icon-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-file-empty icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun support pédagogique</h5>
                    <p class="text-muted">Il n'y a actuellement aucun support pédagogique disponible.</p>
                    @if(Qs::userIsTeamSAT())
                    <a href="{{ route('study-materials.create') }}" class="btn btn-primary">
                        <i class="icon-plus2 mr-2"></i>Ajouter le premier support
                    </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{ $materials->appends(request()->query())->links() }}
    </div>
</div>

@endsection
