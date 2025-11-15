@extends('layouts.master')
@section('page_title', 'Sections académiques')
@section('content')
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Gérer les sections académiques</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('academic_sections.store') }}" class="mb-3">
                @csrf
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nom de la section académique <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <div class="form-group mt-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="active" value="1" class="form-check-input" checked>
                                    Active
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center">
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sections as $sec)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sec->name }}</td>
                            <td>{{ $sec->code }}</td>
                            <td>
                                @if($sec->active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form method="post" action="{{ route('academic_sections.update', $sec->id) }}" class="d-inline-block mr-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="active" value="{{ $sec->active ? 1 : 0 }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Basculer statut</button>
                                </form>
                                <form method="post" action="{{ route('academic_sections.destroy', $sec->id) }}" class="d-inline-block" onsubmit="return confirm('Supprimer cette section académique ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
