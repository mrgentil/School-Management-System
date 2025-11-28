@extends('layouts.master')
@section('page_title', 'Nouveau Message')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-envelop mr-2"></i> Nouveau Message
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('parent.messages.store') }}" method="POST">
            @csrf

            {{-- Destinataire --}}
            <div class="form-group">
                <label><strong>Destinataire</strong> <span class="text-danger">*</span></label>
                <select name="recipient_id" class="form-control select-search" required>
                    <option value="">-- Sélectionner --</option>
                    
                    @if($teachers->count() > 0)
                        <optgroup label="👨‍🏫 Enseignants">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if($admins->count() > 0)
                        <optgroup label="👔 Administration">
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }} ({{ ucfirst($admin->user_type) }})</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
            </div>

            {{-- Concernant (enfant) --}}
            @if($children->count() > 1)
            <div class="form-group">
                <label><strong>Concernant</strong> (optionnel)</label>
                <select name="child_id" class="form-control">
                    <option value="">-- Général --</option>
                    @foreach($children as $child)
                        <option value="{{ $child->user_id }}">
                            {{ $child->user->name }} - {{ $child->my_class->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Sujet --}}
            <div class="form-group">
                <label><strong>Sujet</strong> <span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control" required 
                       placeholder="Ex: Question sur les devoirs de mon enfant">
            </div>

            {{-- Message --}}
            <div class="form-group">
                <label><strong>Message</strong> <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="6" required
                          placeholder="Écrivez votre message ici..."></textarea>
            </div>

            {{-- Actions --}}
            <div class="text-right">
                <a href="{{ route('parent.messages.index') }}" class="btn btn-secondary">
                    <i class="icon-arrow-left7 mr-1"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="icon-paperplane mr-1"></i> Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Conseils --}}
<div class="alert alert-info">
    <h6 class="alert-heading"><i class="icon-info22 mr-2"></i> Conseils</h6>
    <ul class="mb-0">
        <li>Soyez précis dans votre sujet pour faciliter le suivi</li>
        <li>Indiquez le nom de votre enfant si le message le concerne</li>
        <li>Vous recevrez une notification quand l'enseignant répondra</li>
    </ul>
</div>
@endsection
