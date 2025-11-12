@extends('layouts.master')
@section('page_title', 'Mes Présences')

@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mes Présences</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Remarques</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->attendance_date->format('d/m/Y') }}</td>
                            <td>
                                @if($attendance->status == 'present')
                                    <span class="badge badge-success">Présent</span>
                                @elseif($attendance->status == 'absent')
                                    <span class="badge badge-danger">Absent</span>
                                @elseif($attendance->status == 'late')
                                    <span class="badge badge-warning">En retard</span>
                                @else
                                    <span class="badge badge-info">Autre</span>
                                @endif
                            </td>
                            <td>{{ $attendance->remarks ?? 'Aucune remarque' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Aucune présence enregistrée pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
