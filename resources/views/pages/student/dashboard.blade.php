@extends('layouts.master')

@section('page_title', 'Tableau de Bord Étudiant')

@section('content')
<div class="container-fluid">
    <!-- En-tête de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-1">
                        <i class="icon-user"></i> Bienvenue, {{ auth()->user()->name }}!
                    </h3>
                    <p class="mb-0">
                        <i class="icon-calendar"></i> {{ Carbon\Carbon::now()->isoFormat('dddd D MMMM YYYY') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Devoirs en attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_assignments'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-book text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('student.assignments.index') }}" class="small text-primary">
                        Voir tous les devoirs <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Taux de présence
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['attendance_rate'] }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-checkmark-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('student.attendance.index') }}" class="small text-success">
                        Voir les présences <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Livres empruntés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['borrowed_books'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-books text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('student.library.requests.index') }}" class="small text-info">
                        Voir mes demandes <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Messages non lus
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['unread_messages'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="icon-mail text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('student.messages.index') }}" class="small text-warning">
                        Voir les messages <i class="icon-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-lg-8">
            <!-- Devoirs à venir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="icon-clipboard"></i> Devoirs à venir
                    </h6>
                    <a href="{{ route('student.assignments.index') }}" class="btn btn-sm btn-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($upcomingAssignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matière</th>
                                        <th>Titre</th>
                                        <th>Date limite</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAssignments as $assignment)
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $assignment->subject->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($assignment->title, 30) }}</td>
                                            <td>
                                                <span class="text-{{ Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'danger' : 'muted' }}">
                                                    <i class="icon-calendar"></i>
                                                    {{ Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($assignment->submissions_count > 0)
                                                    <span class="badge badge-success">
                                                        <i class="icon-checkmark"></i> Soumis
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="icon-clock"></i> En attente
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                                   class="btn btn-sm btn-info">
                                                    <i class="icon-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="icon-checkmark-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucun devoir en attente pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Livres empruntés -->
            @if($borrowedBooks->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="icon-books"></i> Mes livres empruntés
                    </h6>
                    <a href="{{ route('student.library.requests.index') }}" class="btn btn-sm btn-info">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th>Auteur</th>
                                    <th>Date de retour</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowedBooks as $request)
                                    <tr>
                                        <td>{{ $request->book->name ?? 'N/A' }}</td>
                                        <td>{{ $request->book->author ?? 'N/A' }}</td>
                                        <td>
                                            @if($request->expected_return_date)
                                                <span class="text-{{ Carbon\Carbon::parse($request->expected_return_date)->isPast() ? 'danger' : 'muted' }}">
                                                    <i class="icon-calendar"></i>
                                                    {{ Carbon\Carbon::parse($request->expected_return_date)->format('d/m/Y') }}
                                                    @if(Carbon\Carbon::parse($request->expected_return_date)->isPast())
                                                        <br><small class="text-danger">En retard!</small>
                                                    @else
                                                        <br><small>({{ Carbon\Carbon::parse($request->expected_return_date)->diffForHumans() }})</small>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">Non définie</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $request->badge_class }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Supports pédagogiques récents -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="icon-folder"></i> Supports pédagogiques récents
                    </h6>
                    <a href="{{ route('student.materials.index') }}" class="btn btn-sm btn-success">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    @if($recentMaterials->count() > 0)
                        <div class="list-group">
                            @foreach($recentMaterials as $material)
                                <a href="{{ route('student.materials.show', $material->id) }}"
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="icon-file-text"></i> {{ $material->title }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $material->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="mb-1 text-muted small">
                                        {{ Str::limit($material->description, 80) }}
                                    </p>
                                    <small>
                                        <span class="badge badge-primary">
                                            {{ $material->subject->name ?? 'Général' }}
                                        </span>
                                        <span class="text-muted">
                                            Par {{ $material->user->name ?? 'N/A' }}
                                        </span>
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="icon-folder text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucun support pédagogique disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-4">
            <!-- Résumé financier -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="icon-credit-card"></i> Situation financière
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total à payer:</span>
                            <strong>{{ number_format($financialSummary['total_amount'], 0, ',', ' ') }} FC</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Montant payé:</span>
                            <strong class="text-success">{{ number_format($financialSummary['total_paid'], 0, ',', ' ') }} FC</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Solde restant:</span>
                            <strong class="text-{{ $financialSummary['total_balance'] > 0 ? 'danger' : 'success' }}">
                                {{ number_format($financialSummary['total_balance'], 0, ',', ' ') }} FC
                            </strong>
                        </div>
                    </div>

                    @if($financialSummary['total_balance'] > 0)
                        <div class="alert alert-warning mb-3">
                            <i class="icon-warning"></i>
                            <small>Vous avez un solde impayé</small>
                        </div>
                    @else
                        <div class="alert alert-success mb-3">
                            <i class="icon-checkmark-circle"></i>
                            <small>Tous vos paiements sont à jour!</small>
                        </div>
                    @endif

                    <a href="{{ route('student.finance.payments') }}" class="btn btn-warning btn-block">
                        <i class="icon-eye"></i> Voir les détails
                    </a>
                </div>
            </div>

            <!-- Statistiques de présence -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="icon-calendar"></i> Présences ce mois
                    </h6>
                </div>
                <div class="card-body">
                    @if($attendanceStats['total'] > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success">
                                    <i class="icon-checkmark-circle"></i> Présent
                                </span>
                                <strong>{{ $attendanceStats['present'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger">
                                    <i class="icon-close-circle"></i> Absent
                                </span>
                                <strong>{{ $attendanceStats['absent'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-warning">
                                    <i class="icon-clock"></i> Retard
                                </span>
                                <strong>{{ $attendanceStats['late'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-info">
                                    <i class="icon-info"></i> Excusé
                                </span>
                                <strong>{{ $attendanceStats['excused'] }}</strong>
                            </div>
                        </div>

                        <div class="progress mb-3" style="height: 25px;">
                            @php
                                $presentPercent = $attendanceStats['total'] > 0 ? ($attendanceStats['present'] / $attendanceStats['total']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $presentPercent }}%"
                                 aria-valuenow="{{ $presentPercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ round($presentPercent, 1) }}%
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="icon-calendar text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Aucune donnée de présence ce mois</p>
                        </div>
                    @endif

                    <a href="{{ route('student.attendance.index') }}" class="btn btn-success btn-block">
                        <i class="icon-eye"></i> Voir l'historique
                    </a>
                </div>
            </div>

            <!-- Notifications récentes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="icon-bell"></i> Notifications récentes
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($recentNotifications->count() > 0)
                        <div class="list-group">
                            @foreach($recentNotifications as $notification)
                                <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-primary">
                                            <i class="icon-bell"></i>
                                        </small>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="mb-1 small">
                                        {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="icon-bell text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Aucune notification</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
</style>
@endsection
