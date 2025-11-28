@extends('layouts.master')
@section('page_title', 'Bulletins de mes enfants')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="icon-file-text mr-2"></i>Bulletins de mes enfants</h5>
    </div>
    <div class="card-body">
        @if(empty($childrenData))
            <div class="alert alert-info">Aucun enfant enregistré.</div>
        @else
            @foreach($childrenData as $data)
                @php $child = $data['student']; $bulletins = $data['publishedBulletins']; @endphp
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center">
                            <img src="{{ $child->user->photo ? asset('storage/'.$child->user->photo) : asset('global_assets/images/user.png') }}" 
                                 class="rounded-circle mr-3" width="50" height="50" alt="">
                            <div>
                                <h6 class="mb-0">{{ $child->user->name }}</h6>
                                <small class="text-muted">
                                    {{ $child->my_class->full_name ?? $child->my_class->name ?? '' }} 
                                    {{ $child->section->name ?? '' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($bulletins->isEmpty())
                            <p class="text-muted">Aucun bulletin disponible pour le moment.</p>
                        @else
                            <h6>Bulletins disponibles :</h6>
                            <div class="row">
                                @foreach($bulletins as $bulletin)
                                    <div class="col-md-4 mb-2">
                                        <a href="{{ route('parent.bulletins.show', $child->user_id) }}?type={{ $bulletin->type }}&period={{ $bulletin->period_or_semester }}&semester={{ $bulletin->period_or_semester }}" 
                                           class="btn btn-outline-primary btn-block">
                                            <i class="icon-file-eye mr-1"></i>
                                            {{ $bulletin->type == 'period' ? 'Période ' . $bulletin->period_or_semester : 'Semestre ' . $bulletin->period_or_semester }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
