@extends('layouts.master')
@section('page_title', 'Modifier la Classe - '.$c->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Modifier la Classe</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form class="ajax-update" data-reload="#page-header" method="post" action="{{ route('classes.update', $c->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Nom <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input name="name" value="{{ $c->name }}" required type="text" class="form-control" placeholder="Nom de la classe">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="teacher_id" class="col-lg-3 col-form-label font-weight-semibold">Titulaire</label>
                            <div class="col-lg-9">
                                <select data-placeholder="Sélectionner le titulaire" class="form-control select-search" name="teacher_id" id="teacher_id">
                                    <option value="">-- Choisir un titulaire --</option>
                                    @foreach($teachers as $t)
                                        <option {{ $c->teacher_id == $t->id ? 'selected' : '' }} value="{{ $t->id }}">{{ $t->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Le professeur responsable/garant de cette classe</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Type de Classe</label>
                            <div class="col-lg-9">
                                <input class="form-control" disabled="disabled" value="{{ $c->class_type->name }}" title="Type de Classe" type="text">
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary mr-2">
                                <i class="icon-arrow-left7 mr-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">Enregistrer <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="card-title">📋 Informations</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Classe :</strong> {{ $c->full_name ?: $c->name }}</p>
                            <p><strong>Type :</strong> {{ $c->class_type->name }}</p>
                            <p><strong>Titulaire actuel :</strong> 
                                @if($c->teacher)
                                    <span class="badge badge-primary">{{ $c->teacher->name }}</span>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
