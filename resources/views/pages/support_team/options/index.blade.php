@extends('layouts.master')
@section('page_title', 'Options')
@section('content')
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Gérer les options</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form method="post" action="{{ route('options.store') }}" class="mb-3">
                @csrf
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Section académique <span class="text-danger">*</span></label>
                            <select name="academic_section_id" class="form-control select-search" required>
                                <option value=""></option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nom de l'option <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                </div>
                <div class="form-row">
                    <div class="col-md-2 offset-md-10 text-right">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Option</th>
                        <th>Section académique</th>
                        <th>Code</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($options as $opt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $opt->name }}</td>
                            <td>{{ optional($opt->academic_section)->name }}</td>
                            <td>{{ $opt->code }}</td>
                            <td>
                                @if($opt->active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form method="post" action="{{ route('options.update', $opt->id) }}" class="d-inline-block mr-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="academic_section_id" value="{{ $opt->academic_section_id }}">
                                    <input type="hidden" name="name" value="{{ $opt->name }}">
                                    <input type="hidden" name="code" value="{{ $opt->code }}">
                                    <input type="hidden" name="active" value="{{ $opt->active ? 1 : 0 }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Basculer statut</button>
                                </form>
                                <form method="post" action="{{ route('options.destroy', $opt->id) }}" class="d-inline-block" onsubmit="return confirm('Supprimer cette option ?');">
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
