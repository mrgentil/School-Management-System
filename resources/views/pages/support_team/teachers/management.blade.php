@extends('layouts.master')
@section('page_title', 'Gestion des Professeurs')

@section('content')
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <h4 class="mb-0"><i class="icon-users4 mr-2"></i> Gestion des Professeurs</h4>
        <small class="opacity-75">Attribuez les classes et matières aux professeurs</small>
    </div>
</div>

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-info text-white text-center py-3">
            <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
            <small>Total Professeurs</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white text-center py-3">
            <h3 class="mb-0">{{ $stats['teachers_with_classes'] }}</h3>
            <small>Avec Classes</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white text-center py-3">
            <h3 class="mb-0">{{ $stats['teachers_without_classes'] }}</h3>
            <small>Sans Classes</small>
        </div>
    </div>
</div>

{{-- Liste des professeurs --}}
<div class="card">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-list mr-2"></i> Liste des Professeurs</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover datatable-basic">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Classes</th>
                        <th>Matières</th>
                        <th>Titulaire de</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                        @php
                            $teacherClasses = $teacher->subjects->pluck('my_class')->unique('id');
                            $titularClass = \App\Models\MyClass::where('teacher_id', $teacher->id)->first();
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ Qs::getUserPhoto($teacher->photo) }}" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $teacher->name }}</strong>
                                <br><small class="text-muted">{{ $teacher->code }}</small>
                            </td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                @if($teacherClasses->count() > 0)
                                    @foreach($teacherClasses->take(3) as $class)
                                        <span class="badge badge-info">{{ $class->name }}</span>
                                    @endforeach
                                    @if($teacherClasses->count() > 3)
                                        <span class="badge badge-secondary">+{{ $teacherClasses->count() - 3 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $teacher->subjects->count() }} matière(s)</span>
                            </td>
                            <td>
                                @if($titularClass)
                                    <span class="badge badge-success">{{ $titularClass->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('teachers.management.show', $teacher->id) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="{{ route('teachers.management.edit', $teacher->id) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier attributions">
                                    <i class="icon-pencil"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
