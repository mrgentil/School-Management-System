@extends('layouts.master')
@section('page_title', 'Bulletins des élèves')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="icon-file-text mr-2"></i>Bulletins des élèves</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('teacher.bulletins.students') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label>Classe</label>
                    <select name="my_class_id" class="form-control select" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->full_name ?? $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Type</label>
                    <select name="type" class="form-control" id="type_select">
                        <option value="period">Période</option>
                        <option value="semester">Semestre</option>
                    </select>
                </div>
                <div class="col-md-2" id="period_div">
                    <label>Période</label>
                    <select name="period" class="form-control">
                        <option value="1">Période 1</option>
                        <option value="2">Période 2</option>
                        <option value="3">Période 3</option>
                        <option value="4">Période 4</option>
                    </select>
                </div>
                <div class="col-md-2 d-none" id="semester_div">
                    <label>Semestre</label>
                    <select name="semester" class="form-control">
                        <option value="1">Semestre 1</option>
                        <option value="2">Semestre 2</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search4 mr-1"></i> Afficher les élèves
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#type_select').change(function() {
        if ($(this).val() == 'semester') {
            $('#period_div').addClass('d-none');
            $('#semester_div').removeClass('d-none');
        } else {
            $('#period_div').removeClass('d-none');
            $('#semester_div').addClass('d-none');
        }
    });
</script>
@endsection
