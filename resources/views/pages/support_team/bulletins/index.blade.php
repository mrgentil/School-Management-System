@extends('layouts.master')
@section('page_title', 'Gestion des Bulletins')

@section('content')

<div class="card">
    <div class="card-header header-elements-inline bg-primary">
        <h5 class="card-title text-white">
            <i class="icon-file-pdf mr-2"></i> Génération des Bulletins Scolaires
        </h5>
    </div>

    <div class="card-body">
        <div class="alert alert-info">
            <i class="icon-info22 mr-2"></i>
            <strong>Instructions :</strong> Sélectionnez une classe et une période/semestre pour générer les bulletins des étudiants.
        </div>

        <form action="{{ route('bulletins.students') }}" method="GET" id="bulletinForm">
            <div class="row">
                {{-- Type de bulletin --}}
                <div class="col-md-12 mb-3">
                    <label class="font-weight-bold">Type de Bulletin :</label>
                    <div class="d-flex mt-2">
                        <div class="custom-control custom-radio mr-4">
                            <input type="radio" class="custom-control-input" name="type" id="type_period" value="period" checked onchange="toggleType()">
                            <label class="custom-control-label" for="type_period">
                                <i class="icon-calendar3 mr-1"></i> Par Période
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" name="type" id="type_semester" value="semester" onchange="toggleType()">
                            <label class="custom-control-label" for="type_semester">
                                <i class="icon-bookmark mr-1"></i> Par Semestre
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
                        <label for="my_class_id" class="font-weight-bold">Classe <span class="text-danger">*</span></label>
                        <select required id="my_class_id" name="my_class_id" class="form-control select" onchange="updateSection(this)">
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach($my_classes as $c)
                                @php
                                    $classSection = $sections->where('my_class_id', $c->id)->first();
                                @endphp
                                <option value="{{ $c->id }}" data-section="{{ $classSection ? $classSection->id : '' }}">
                                    {{ $c->full_name ?: $c->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="section_id" id="section_id" value="">
                    </div>
                </div>

                {{-- Période --}}
                <div class="col-md-4" id="period_group">
                    <div class="form-group">
                        <label for="period" class="font-weight-bold">Période <span class="text-danger">*</span></label>
                        <select id="period" name="period" class="form-control select">
                            @foreach($periods as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Semestre --}}
                <div class="col-md-4" id="semester_group" style="display: none;">
                    <div class="form-group">
                        <label for="semester" class="font-weight-bold">Semestre <span class="text-danger">*</span></label>
                        <select id="semester" name="semester" class="form-control select">
                            @foreach($semesters as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group btn-block">
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-search4 mr-1"></i> Rechercher
                        </button>
                        <button type="button" class="btn btn-success" onclick="exportClass()">
                            <i class="icon-download mr-1"></i> Export ZIP
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Statistiques --}}
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="icon-users icon-2x mb-2"></i>
                <h3 class="mb-0">{{ \App\Models\StudentRecord::where('session', $year)->count() }}</h3>
                <small>Étudiants inscrits</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="icon-library icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $my_classes->count() }}</h3>
                <small>Classes</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="icon-calendar3 icon-2x mb-2"></i>
                <h3 class="mb-0">{{ $year }}</h3>
                <small>Année Scolaire</small>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function toggleType() {
    var type = document.querySelector('input[name="type"]:checked').value;
    document.getElementById('period_group').style.display = (type === 'period') ? 'block' : 'none';
    document.getElementById('semester_group').style.display = (type === 'semester') ? 'block' : 'none';
}

function updateSection(select) {
    var selectedOption = select.options[select.selectedIndex];
    var sectionId = selectedOption.getAttribute('data-section') || '';
    document.getElementById('section_id').value = sectionId;
}

function exportClass() {
    var classId = document.getElementById('my_class_id').value;
    if (!classId) {
        alert('Veuillez sélectionner une classe');
        return;
    }
    
    var type = document.querySelector('input[name="type"]:checked').value;
    var period = document.getElementById('period').value;
    var semester = document.getElementById('semester').value;
    
    var url = '{{ route("bulletins.export_class") }}?my_class_id=' + classId + '&type=' + type;
    url += '&period=' + period + '&semester=' + semester;
    
    // Afficher un message de chargement
    if (confirm('Voulez-vous exporter tous les bulletins de cette classe en ZIP ?\n\nCela peut prendre quelques minutes selon le nombre d\'étudiants.')) {
        window.location.href = url;
    }
}
</script>
@endsection
