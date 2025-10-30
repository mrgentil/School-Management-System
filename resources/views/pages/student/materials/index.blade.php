@extends('layouts.master')
@section('page_title', 'Ressources Pédagogiques')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Ressources Pédagogiques</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <!-- Search and Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher une ressource..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="subject_id" class="form-control select">
                            <option value="">Toutes les matières</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subject_id == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="file_type" class="form-control select">
                            <option value="">Tous les types</option>
                            <option value="pdf" {{ $file_type == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="doc" {{ $file_type == 'doc' ? 'selected' : '' }}>Document Word</option>
                            <option value="ppt" {{ $file_type == 'ppt' ? 'selected' : '' }}>Présentation</option>
                            <option value="video" {{ $file_type == 'video' ? 'selected' : '' }}>Vidéo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="icon-search4"></i> Rechercher</button>
                </div>
            </div>
        </form>

        <!-- Materials Grid -->
        <div class="row">
            @forelse($materials as $material)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="mr-3">
                                <i class="{{ $material->file_icon }} icon-2x text-primary"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-title font-weight-semibold">
                                    <a href="{{ route('student.materials.show', $material->id) }}">{{ $material->title }}</a>
                                </h6>
                                <p class="mb-1 text-muted">{{ $material->subject->name ?? 'Général' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $material->file_size_formatted }}</small>
                                    <a href="{{ route('student.materials.download', $material->id) }}" 
                                       class="btn btn-sm btn-success">
                                        <i class="icon-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if($material->description)
                        <p class="mt-2 text-muted small">{{ Str::limit($material->description, 100) }}</p>
                        @endif
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="icon-user"></i> {{ $material->uploader->name ?? 'Système' }} - 
                                <i class="icon-calendar"></i> {{ $material->created_at->format('d/m/Y') }} - 
                                <i class="icon-download"></i> {{ $material->download_count }} téléchargements
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="icon-file-text2 icon-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune ressource trouvée</h5>
                    <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
        <div class="d-flex justify-content-center">
            {{ $materials->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
