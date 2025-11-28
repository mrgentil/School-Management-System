@extends('layouts.master')
@section('page_title', 'Rapport Financier par Classe')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="icon-list mr-2"></i> Rapport par Classe
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('finance.by_class') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><strong>Sélectionner une classe</strong></label>
                        <select name="class_id" class="form-control select" onchange="this.form.submit()">
                            <option value="">-- Choisir une classe --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selectedClass && $classStats)
{{-- Stats de la classe --}}
<div class="row">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $classStats['total_students'] }}</h3>
                <small>Élèves</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($classStats['total_paid'], 0, ',', ' ') }}</h3>
                <small>FC Payés</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($classStats['total_balance'], 0, ',', ' ') }}</h3>
                <small>FC Restants</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $classStats['fully_paid'] }}</h3>
                <small>À jour</small>
            </div>
        </div>
    </div>
</div>

{{-- Liste des élèves --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-users mr-2"></i> Détail par Élève</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Élève</th>
                    <th class="text-right">Total Payé</th>
                    <th class="text-right">Solde</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $item)
                    <tr>
                        <td>
                            <strong>{{ $item['student']->name ?? 'N/A' }}</strong>
                        </td>
                        <td class="text-right text-success">{{ number_format($item['total_paid'], 0, ',', ' ') }} FC</td>
                        <td class="text-right {{ $item['total_balance'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($item['total_balance'], 0, ',', ' ') }} FC
                        </td>
                        <td class="text-center">
                            @if($item['total_balance'] == 0)
                                <span class="badge badge-success">✅ À jour</span>
                            @else
                                <span class="badge badge-danger">⚠️ Solde</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="text-center mt-3">
    <a href="{{ route('finance.dashboard') }}" class="btn btn-secondary">
        <i class="icon-arrow-left7 mr-1"></i> Retour au Dashboard
    </a>
</div>
@endsection
