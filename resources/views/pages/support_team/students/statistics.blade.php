@extends('layouts.master')
@section('page_title', 'Statistiques des étudiants')
@section('content')
    <div class="card">
        <div class="card-header bg-white header-elements-inline">
            <h6 class="card-title">Statistiques des étudiants par classe, division, section et option</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form action="{{ route('students.statistics') }}" method="get" class="mb-4">
                <div class="form-row">
                    <div class="col-md-3">
                        <label for="my_class_id">Classe</label>
                        <select name="my_class_id" id="my_class_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ ($filters['my_class_id'] ?? '') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id">Division</label>
                        <select name="section_id" id="section_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            @foreach($divisions as $section)
                                <option value="{{ $section->id }}" {{ ($filters['section_id'] ?? '') == $section->id ? 'selected' : '' }}>
                                    {{ $section->my_class->name }} - {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="academic_section_id">Section académique</label>
                        <select name="academic_section_id" id="academic_section_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            @foreach($academic_sections as $section)
                                <option value="{{ $section->id }}" {{ ($filters['academic_section_id'] ?? '') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="option_id">Option</label>
                        <select name="option_id" id="option_id" class="form-control select-search">
                            <option value="">Toutes</option>
                            @foreach($options as $option)
                                <option value="{{ $option->id }}" {{ ($filters['option_id'] ?? '') == $option->id ? 'selected' : '' }}>
                                    {{ $option->name }} ({{ optional($option->academic_section)->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="col-md-3">
                        <label for="session">Année scolaire</label>
                        <select name="session" id="session" class="form-control select-search">
                            <option value="">Toutes</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session }}" {{ ($filters['session'] ?? '') == $session ? 'selected' : '' }}>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-9 text-right align-self-end">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('students.statistics') }}" class="btn btn-light">Réinitialiser</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Classe</th>
                        <th>Division</th>
                        <th>Section académique</th>
                        <th>Option</th>
                        <th class="text-center">Nombre d'étudiants</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stats as $row)
                        <tr>
                            <td>{{ $row->class_name ?? '-' }}</td>
                            <td>{{ $row->division_name ?? '-' }}</td>
                            <td>{{ $row->academic_section_name ?? '-' }}</td>
                            <td>{{ $row->option_name ?? '-' }}</td>
                            <td class="text-center"><strong>{{ $row->total_students }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune donnée pour les filtres sélectionnés.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
