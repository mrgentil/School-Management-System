@extends('layouts.master')
@section('page_title', 'Liste des élèves')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5 class="mb-0"><i class="icon-users mr-2"></i>Liste des élèves</h5>
        <a href="{{ route('teacher.bulletins.index') }}" class="btn btn-light btn-sm">
            <i class="icon-arrow-left5 mr-1"></i> Retour
        </a>
    </div>
    <div class="card-body">
        @if($students->isEmpty())
            <div class="alert alert-info">Aucun élève trouvé dans cette classe.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>N° Matricule</th>
                            <th>Section</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $sr)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <img src="{{ $sr->user->photo ? asset('storage/'.$sr->user->photo) : asset('global_assets/images/user.png') }}" 
                                         class="rounded-circle" width="40" height="40" alt="">
                                </td>
                                <td>{{ $sr->user->name }}</td>
                                <td>{{ $sr->adm_no }}</td>
                                <td>{{ $sr->section->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('teacher.bulletins.preview', $sr->user_id) }}?type={{ $type }}&period={{ $period }}&semester={{ $semester }}" 
                                       class="btn btn-info btn-sm" target="_blank">
                                        <i class="icon-file-eye mr-1"></i> Voir Bulletin
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
