@extends('layouts.master')
@section('page_title', 'Progression de mes Enfants')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-stats-growth mr-2"></i> Progression de mes Enfants
        </h5>
    </div>
    <div class="card-body">
        @if($children->count() > 0)
            <div class="row">
                @foreach($children as $child)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-left-primary shadow h-100">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <img src="{{ $child->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                                         class="rounded-circle" width="80" height="80">
                                </div>
                                <h5 class="text-center">{{ $child->user->name }}</h5>
                                <p class="text-center text-muted">
                                    <span class="badge badge-primary">{{ $child->my_class->full_name ?? $child->my_class->name ?? 'N/A' }}</span>
                                    @if($child->section)
                                        <span class="badge badge-secondary">{{ $child->section->name }}</span>
                                    @endif
                                </p>
                                <hr>
                                <div class="text-center">
                                    <a href="{{ route('parent.progress.show', $child->user_id) }}" class="btn btn-info">
                                        <i class="icon-stats-growth mr-1"></i> Voir la Progression
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center">
                <i class="icon-info22 mr-2"></i>
                Aucun enfant inscrit pour cette année scolaire.
            </div>
        @endif
    </div>
</div>
@endsection
