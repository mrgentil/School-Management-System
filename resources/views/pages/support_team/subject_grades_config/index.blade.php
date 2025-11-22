@extends('layouts.master')
@section('page_title', 'Configuration des Cotes par Matière')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-calculator mr-2"></i> Configuration des Cotes par Matière</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="list-icons-item" data-action="collapse"></a>
                <a class="list-icons-item" data-action="reload"></a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="alert alert-info border-0">
            <div class="d-flex align-items-center">
                <i class="icon-info22 mr-3 icon-2x"></i>
                <div>
                    <strong>Configuration Système RDC</strong>
                    <p class="mb-0">
                        Configurez les cotes maximales pour chaque matière par classe. 
                        <strong>Période</strong> : devoirs et interrogations. 
                        <strong>Examen</strong> : examens semestriels.
                    </p>
                </div>
            </div>
        </div>

        <!-- Sélection de classe -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">Sélectionner une Classe :</label>
                    <select id="class-selector" class="form-control select-search" data-placeholder="Rechercher et choisir une classe...">
                        <option value="">-- Rechercher une classe --</option>
                        @foreach($my_classes as $class)
                            <option value="{{ $class->id }}" {{ $selected_class && $selected_class->id == $class->id ? 'selected' : '' }}>
                                {{ $class->full_name ?: $class->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">
                        <i class="icon-info22 mr-1"></i>
                        Tapez pour rechercher parmi les classes disponibles
                    </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">Année Académique :</label>
                    <input type="text" class="form-control" value="{{ $current_year }}" readonly>
                </div>
            </div>
        </div>

        @if($selected_class)
        <!-- Configuration pour la classe sélectionnée -->
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="icon-graduation2 mr-2"></i>
                    Configuration pour : {{ $selected_class->full_name ?: $selected_class->name }}
                </h6>
            </div>
            <div class="card-body">
                
                <!-- Actions rapides -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="btn-group" role="group">
                            <a href="{{ route('subject-grades-config.initialize-defaults', $selected_class->id) }}" 
                               class="btn btn-success btn-sm">
                                <i class="icon-plus mr-1"></i>Initialiser par Défaut
                            </a>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#duplicateModal">
                                <i class="icon-copy mr-1"></i>Dupliquer depuis une autre classe
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="resetAllToDefaults()">
                                <i class="icon-reset mr-1"></i>Réinitialiser Tout
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de configuration -->
                <form action="{{ route('subject-grades-config.store') }}" method="POST" id="config-form">
                    @csrf
                    <input type="hidden" name="my_class_id" value="{{ $selected_class->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th width="40%">Matière</th>
                                    <th width="20%" class="text-center">Cote Période</th>
                                    <th width="20%" class="text-center">Cote Examen</th>
                                    <th width="15%" class="text-center">Ratio</th>
                                    <th width="5%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class_subjects as $index => $subject)
                                @php
                                    $config = $configs->where('subject_id', $subject->id)->first();
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $subject->name }}</strong>
                                        <input type="hidden" name="configs[{{ $index }}][subject_id]" value="{{ $subject->id }}">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="configs[{{ $index }}][period_max_points]" 
                                                   class="form-control text-center period-input" 
                                                   value="{{ $config ? $config->period_max_points : 20 }}"
                                                   min="1" max="999" step="0.01" required
                                                   onchange="calculateRatio({{ $index }})">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pts</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="configs[{{ $index }}][exam_max_points]" 
                                                   class="form-control text-center exam-input" 
                                                   value="{{ $config ? $config->exam_max_points : 40 }}"
                                                   min="1" max="999" step="0.01" required
                                                   onchange="calculateRatio({{ $index }})">
                                            <div class="input-group-append">
                                                <span class="input-group-text">pts</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info ratio-display" id="ratio-{{ $index }}">
                                            1:{{ $config ? round($config->exam_max_points / $config->period_max_points, 1) : '2.0' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="resetToDefault({{ $index }})" title="Réinitialiser">
                                            <i class="icon-reset"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="icon-info22 mr-2"></i>
                                        Aucune matière trouvée pour cette classe. 
                                        Vérifiez que les matières sont bien assignées à cette classe.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($class_subjects->count() > 0)
                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="icon-checkmark mr-2"></i>Sauvegarder la Configuration
                        </button>
                    </div>
                    @endif
                </form>

                <!-- Configurations existantes -->
                @if($configs->count() > 0)
                <div class="mt-4">
                    <h6 class="font-weight-bold">Configurations Actuelles :</h6>
                    <div class="row">
                        @foreach($configs as $config)
                        <div class="col-md-4 mb-2">
                            <div class="card border-left-primary">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $config->subject->name }}</strong><br>
                                            <small class="text-muted">
                                                Période: {{ $config->period_max_points }}pts | 
                                                Examen: {{ $config->exam_max_points }}pts
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-success">
                                                {{ round(($config->exam_max_points / $config->period_max_points), 1) }}x
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de duplication -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dupliquer Configuration</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('subject-grades-config.duplicate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="target_class_id" value="{{ $selected_class ? $selected_class->id : '' }}">
                    
                    <div class="form-group">
                        <label>Dupliquer depuis la classe :</label>
                        <select name="source_class_id" class="form-control select-search" data-placeholder="Rechercher une classe source..." required>
                            <option value="">-- Rechercher une classe source --</option>
                            @foreach($my_classes as $class)
                                @if(!$selected_class || $class->id != $selected_class->id)
                                <option value="{{ $class->id }}">{{ $class->full_name ?: $class->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            Recherchez la classe dont vous voulez copier la configuration
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="icon-info22 mr-2"></i>
                        Seules les matières communes aux deux classes seront dupliquées.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Dupliquer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Attendre que jQuery soit chargé
$(document).ready(function() {
    console.log('Subject Grade Config Interface loaded');
    
    // Initialiser Select2 pour la recherche de classes
    $('.select-search').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Aucune classe trouvée";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            inputTooShort: function() {
                return "Tapez pour rechercher";
            }
        }
    });
    
    // Sélection de classe
    $('#class-selector').on('change', function() {
        if (this.value) {
            window.location.href = '{{ route("subject-grades-config.index") }}/' + this.value;
        } else {
            window.location.href = '{{ route("subject-grades-config.index") }}';
        }
    });
    
    // Initialiser les ratios au chargement
    $('.period-input').each(function(index) {
        calculateRatio(index);
    });
});

// Calcul du ratio
function calculateRatio(index) {
    const periodInput = document.querySelector(`input[name="configs[${index}][period_max_points]"]`);
    const examInput = document.querySelector(`input[name="configs[${index}][exam_max_points]"]`);
    const ratioDisplay = document.getElementById(`ratio-${index}`);
    
    if (!periodInput || !examInput || !ratioDisplay) return;
    
    const periodValue = parseFloat(periodInput.value) || 1;
    const examValue = parseFloat(examInput.value) || 1;
    const ratio = (examValue / periodValue).toFixed(1);
    
    ratioDisplay.textContent = `1:${ratio}`;
    
    // Changer la couleur selon le ratio
    ratioDisplay.className = 'badge ';
    if (ratio < 1.5) {
        ratioDisplay.className += 'badge-warning';
    } else if (ratio > 3) {
        ratioDisplay.className += 'badge-danger';
    } else {
        ratioDisplay.className += 'badge-info';
    }
}

// Réinitialiser à la valeur par défaut
function resetToDefault(index) {
    const periodInput = document.querySelector(`input[name="configs[${index}][period_max_points]"]`);
    const examInput = document.querySelector(`input[name="configs[${index}][exam_max_points]"]`);
    
    if (periodInput) periodInput.value = 20;
    if (examInput) examInput.value = 40;
    calculateRatio(index);
}

// Réinitialiser tout
function resetAllToDefaults() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser toutes les valeurs par défaut ?')) {
        const periodInputs = document.querySelectorAll('.period-input');
        const examInputs = document.querySelectorAll('.exam-input');
        
        periodInputs.forEach(input => input.value = 20);
        examInputs.forEach(input => input.value = 40);
        
        // Recalculer tous les ratios
        for (let i = 0; i < periodInputs.length; i++) {
            calculateRatio(i);
        }
    }
}
</script>
@endsection

@endsection
