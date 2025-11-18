@extends('layouts.master')
@section('page_title', 'Fix Mark Errors')
@section('content')

    {{-- Alerte d'information --}}
    <div class="alert alert-warning border-0">
        <div class="d-flex align-items-center">
            <i class="icon-warning mr-3 icon-2x"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Correction en Masse des Notes</h6>
                <p class="mb-0">Cette fonctionnalité recalcule automatiquement les grades, positions et moyennes pour une classe entière.</p>
            </div>
        </div>
    </div>

    {{-- Menu Rapide --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <a href="{{ route('marks.index') }}" class="btn btn-primary btn-block">
                <i class="icon-pencil5 mr-2"></i>Retour à la Saisie
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('marks.tabulation') }}" class="btn btn-info btn-block">
                <i class="icon-table2 mr-2"></i>Tabulation
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('exam_analytics.index') }}" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analytics
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-wrench mr-2"></i> Batch Fix </h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form class="ajax-update" method="post" action="{{ route('marks.batch_update') }}">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-10">
                        <fieldset>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                        <select required id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select">
                                            @foreach($exams as $ex)
                                                <option {{ $selected && $exam_id == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                        <select required onchange="getClassSections(this.value)" id="my_class_id" name="my_class_id" class="form-control select">
                                            <option value="">Select Class</option>
                                            @foreach($my_classes as $c)
                                                <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                        <select required id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select">
                                            @if($selected)
                                                @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                                    <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            </div>

                        </fieldset>
                    </div>

                    <div class="col-md-2 mt-4">
                        <div class="text-right mt-1">
                            <button type="submit" class="btn btn-danger">Fix Errors <i class="icon-wrench2 ml-2"></i></button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
