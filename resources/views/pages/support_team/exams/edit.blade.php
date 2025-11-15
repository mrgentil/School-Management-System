@extends('layouts.master')
@section('page_title', 'Edit Exam - '.$ex->name. ' ('.$ex->year.')')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Edit Exam</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form method="post" action="{{ route('exams.update', $ex->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $ex->name }}" required type="text" class="form-control" placeholder="Name of Exam">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="semester" class="col-lg-3 col-form-label font-weight-semibold">Semestre <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select data-placeholder="Sélectionner le semestre" class="form-control select-search" name="semester" id="semester" required>
                                    <option {{ $ex->semester == 1 ? 'selected' : '' }} value="1">Semestre 1 (Périodes 1 & 2)</option>
                                    <option {{ $ex->semester == 2 ? 'selected' : '' }} value="2">Semestre 2 (Périodes 3 & 4)</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class Edit Ends--}}

@endsection
