@extends('layouts.master')
@section('page_title', 'Correction en Lot')
@section('content')

    {{-- Alerte d'information --}}
    <div class="alert alert-info border-0">
        <div class="d-flex align-items-center">
            <i class="icon-info22 mr-3 icon-2x"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Correction en Lot des Notes (Système académique)</h6>
                <p class="mb-0">Recalcule automatiquement les moyennes de période, grades, positions et classements pour une classe entière.</p>
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
        <div class="card-header header-elements-inline bg-light">
            <h5 class="card-title"><i class="icon-wrench mr-2"></i> Correction en Lot </h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <form class="ajax-update" method="post" action="{{ route('marks.batch_update') }}">
                @csrf @method('PUT')
                
                <div class="row">
                    {{-- Type de correction --}}
                    <div class="col-md-12 mb-3">
                        <label class="font-weight-bold">Type de correction :</label>
                        <div class="d-flex flex-wrap">
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" id="type_period" name="correction_type" value="period" class="custom-control-input" checked onchange="toggleCorrectionType()">
                                <label class="custom-control-label" for="type_period">
                                    <i class="icon-calendar3 mr-1"></i> Période (P1, P2, P3, P4)
                                </label>
                            </div>
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" id="type_semester" name="correction_type" value="semester" class="custom-control-input" onchange="toggleCorrectionType()">
                                <label class="custom-control-label" for="type_semester">
                                    <i class="icon-book mr-1"></i> Semestre (S1, S2)
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="type_exam" name="correction_type" value="exam" class="custom-control-input" onchange="toggleCorrectionType()">
                                <label class="custom-control-label" for="type_exam">
                                    <i class="icon-graduation2 mr-1"></i> Examen
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    {{-- Classe --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="my_class_id" class="col-form-label font-weight-bold">Classe :</label>
                            <select required id="my_class_id" name="my_class_id" class="form-control select" onchange="updateSectionFromClass(this)">
                                <option value="">-- Sélectionner une classe --</option>
                                @foreach($my_classes as $c)
                                    @php
                                        // Trouver la première section de cette classe
                                        $classSection = $sections->where('my_class_id', $c->id)->first();
                                    @endphp
                                    <option value="{{ $c->id }}" data-section="{{ $classSection ? $classSection->id : '' }}">
                                        {{ $c->full_name ?: $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="section_id" name="section_id" value="">
                        </div>
                    </div>

                    {{-- Période (visible par défaut) --}}
                    <div class="col-md-3" id="period_select">
                        <div class="form-group">
                            <label for="period" class="col-form-label font-weight-bold">Période :</label>
                            <select id="period" name="period" class="form-control select">
                                <option value="">-- Toutes les périodes --</option>
                                <option value="1">Période 1</option>
                                <option value="2">Période 2</option>
                                <option value="3">Période 3</option>
                                <option value="4">Période 4</option>
                            </select>
                        </div>
                    </div>

                    {{-- Semestre (caché par défaut) --}}
                    <div class="col-md-3" id="semester_select" style="display: none;">
                        <div class="form-group">
                            <label for="semester" class="col-form-label font-weight-bold">Semestre :</label>
                            <select id="semester" name="semester" class="form-control select">
                                <option value="">-- Tous les semestres --</option>
                                <option value="1">Semestre 1</option>
                                <option value="2">Semestre 2</option>
                            </select>
                        </div>
                    </div>

                    {{-- Examen (caché par défaut) --}}
                    <div class="col-md-3" id="exam_select" style="display: none;">
                        <div class="form-group">
                            <label for="exam_id" class="col-form-label font-weight-bold">Examen :</label>
                            <select id="exam_id" name="exam_id" class="form-control select">
                                <option value="">-- Sélectionner --</option>
                                @foreach($exams as $ex)
                                    <option {{ $selected && $exam_id == $ex->id ? 'selected' : '' }} value="{{ $ex->id }}">{{ $ex->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Bouton --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="icon-wrench2 mr-2"></i> Corriger
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Légende des corrections --}}
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0"><i class="icon-info3 mr-2"></i> Ce qui sera recalculé</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="media">
                        <i class="icon-calendar3 icon-2x text-primary mr-3"></i>
                        <div class="media-body">
                            <h6 class="font-weight-bold">Période</h6>
                            <ul class="mb-0 pl-3">
                                <li>Moyennes de période (p1_avg, p2_avg...)</li>
                                <li>Classement par période</li>
                                <li>Pourcentages de période</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media">
                        <i class="icon-book icon-2x text-info mr-3"></i>
                        <div class="media-body">
                            <h6 class="font-weight-bold">Semestre</h6>
                            <ul class="mb-0 pl-3">
                                <li>Moyennes P1+P2 ou P3+P4</li>
                                <li>Notes d'examen semestriel</li>
                                <li>Classement semestriel</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="media">
                        <i class="icon-graduation2 icon-2x text-success mr-3"></i>
                        <div class="media-body">
                            <h6 class="font-weight-bold">Examen</h6>
                            <ul class="mb-0 pl-3">
                                <li>Grades (A, B, C, D, E, F)</li>
                                <li>Positions finales</li>
                                <li>Totaux et moyennes générales</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
function toggleCorrectionType() {
    var type = document.querySelector('input[name="correction_type"]:checked').value;
    
    document.getElementById('period_select').style.display = (type === 'period') ? 'block' : 'none';
    document.getElementById('semester_select').style.display = (type === 'semester') ? 'block' : 'none';
    document.getElementById('exam_select').style.display = (type === 'exam') ? 'block' : 'none';
}

function updateSectionFromClass(select) {
    var selectedOption = select.options[select.selectedIndex];
    var sectionId = selectedOption.getAttribute('data-section') || '';
    document.getElementById('section_id').value = sectionId;
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', function() {
    var classSelect = document.getElementById('my_class_id');
    if (classSelect && classSelect.selectedIndex > 0) {
        updateSectionFromClass(classSelect);
    }
});
</script>
@endsection
