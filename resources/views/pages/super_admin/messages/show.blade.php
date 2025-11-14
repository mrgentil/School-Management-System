@extends('layouts.master')
@section('page_title', 'Message')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline bg-primary text-white">
        <h6 class="card-title">{{ $message->subject }}</h6>
        <div class="header-elements">
            <a href="{{ route('super_admin.messages.index') }}" class="btn btn-light btn-sm">
                <i class="icon-arrow-left8 mr-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Informations du message -->
        <div class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>De:</strong> {{ $message->sender->name }}
                    </p>
                    <p class="mb-1">
                        <strong>Date:</strong> {{ $message->created_at->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1">
                        <strong>Destinataires:</strong> 
                        <span class="badge badge-primary">{{ $message->recipients->count() }} personne(s)</span>
                    </p>
                </div>
            </div>
        </div>

        <hr>

        <!-- Contenu du message -->
        <div class="message-content p-3 bg-light rounded">
            {!! nl2br(e($message->content)) !!}
        </div>

        <hr>

        <!-- Liste des destinataires -->
        <div class="mt-4">
            <h6 class="font-weight-semibold mb-3">📋 Liste des destinataires ({{ $message->recipients->count() }})</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($message->recipients as $recipient)
                        <tr>
                            <td>{{ $recipient->recipient->name }}</td>
                            <td>{{ $recipient->recipient->email }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucwords(str_replace('_', ' ', $recipient->recipient->user_type)) }}
                                </span>
                            </td>
                            <td>
                                @if($recipient->is_read)
                                    <span class="badge badge-success">✓ Lu</span>
                                @else
                                    <span class="badge badge-secondary">Non lu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
