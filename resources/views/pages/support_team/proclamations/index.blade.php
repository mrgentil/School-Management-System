@extends('layouts.master')
@section('page_title', 'Proclamations RDC')

@section('content')
<div class="content-wrapper">
    <div class="content-header header-elements-md-inline">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title">🏆 Proclamations RDC</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Proclamations RDC</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="content-header-right col-md-6 col-12">
            <div class="btn-group float-md-right">
                <span class="badge badge-info">Session: {{ $current_year }}</span>
            </div>
        </div>
    </div>

    <div class="content-body">
        <!-- Info Alert -->
        <div class="alert alert-info alert-dismissible mb-4">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <div class="alert-body">
                <h4 class="alert-heading">
                    <i class="icon-info22 mr-2"></i>Système de Proclamation RDC
                </h4>
                <p class="mb-2">
                    <strong>Périodes :</strong> Classement basé sur les devoirs, interrogations et TCA de chaque période (1, 2, 3, 4).
                </p>
                <p class="mb-0">
                    <strong>Semestres :</strong> Classement basé sur la moyenne des périodes + examens semestriels.
                </p>
            </div>
        </div>

        <!-- Sélection de classe et type -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="icon-search4 mr-2"></i>Sélectionner une Proclamation
                </h4>
            </div>
            <div class="card-body">
                <form id="proclamation-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Classe :</label>
                                <select id="class-selector" name="class_id" class="form-control select-search" data-placeholder="Rechercher une classe..." required>
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach($my_classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->full_name ?: $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Type de Proclamation :</label>
                                <select id="proclamation-type" name="type" class="form-control" required>
                                    <option value="">-- Choisir le type --</option>
                                    <option value="period">Par Période</option>
                                    <option value="semester">Par Semestre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">
                                    <span id="period-label" style="display: none;">Période :</span>
                                    <span id="semester-label" style="display: none;">Semestre :</span>
                                </label>
                                <select id="period-selector" name="period" class="form-control" style="display: none;">
                                    <option value="">-- Période --</option>
                                    <option value="1">Période 1</option>
                                    <option value="2">Période 2</option>
                                    <option value="3">Période 3</option>
                                    <option value="4">Période 4</option>
                                </select>
                                <select id="semester-selector" name="semester" class="form-control" style="display: none;">
                                    <option value="">-- Semestre --</option>
                                    <option value="1">Semestre 1</option>
                                    <option value="2">Semestre 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="font-weight-bold">&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="icon-calculator mr-1"></i>Calculer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Zone de résultats -->
        <div id="results-container" style="display: none;">
            <!-- Les résultats seront chargés ici via AJAX -->
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="icon-cog mr-2"></i>Actions Rapides
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="icon-calculator icon-3x text-primary mb-3"></i>
                                <h5>Recalculer une Classe</h5>
                                <p class="text-muted">Recalculer toutes les moyennes et classements pour une classe</p>
                                <button class="btn btn-outline-primary" onclick="showRecalculateModal()">
                                    <i class="icon-refresh2 mr-1"></i>Recalculer
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="icon-file-pdf icon-3x text-danger mb-3"></i>
                                <h5>Export PDF</h5>
                                <p class="text-muted">Exporter les proclamations en format PDF</p>
                                <button class="btn btn-outline-danger" disabled>
                                    <i class="icon-download mr-1"></i>Bientôt disponible
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de recalcul -->
<div class="modal fade" id="recalculateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recalculer une Classe</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="recalculate-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Classe à recalculer :</label>
                        <select name="class_id" class="form-control select-search" required>
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach($my_classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->full_name ?: $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="icon-warning22 mr-2"></i>
                        Cette opération va recalculer toutes les moyennes et classements pour la classe sélectionnée.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-refresh2 mr-1"></i>Recalculer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select-search').select2({
        placeholder: function() {
            return $(this).data('placeholder');
        },
        allowClear: true,
        width: '100%'
    });

    // Gestion du changement de type de proclamation
    $('#proclamation-type').on('change', function() {
        const type = $(this).val();
        
        if (type === 'period') {
            $('#period-label').show();
            $('#semester-label').hide();
            $('#period-selector').show().prop('required', true);
            $('#semester-selector').hide().prop('required', false);
        } else if (type === 'semester') {
            $('#period-label').hide();
            $('#semester-label').show();
            $('#period-selector').hide().prop('required', false);
            $('#semester-selector').show().prop('required', true);
        } else {
            $('#period-label, #semester-label').hide();
            $('#period-selector, #semester-selector').hide().prop('required', false);
        }
    });

    // Soumission du formulaire de proclamation
    $('#proclamation-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const type = $('#proclamation-type').val();
        
        let url = '';
        if (type === 'period') {
            url = '{{ route("proclamations.period") }}';
        } else if (type === 'semester') {
            url = '{{ route("proclamations.semester") }}';
        }
        
        if (url) {
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#results-container').html('<div class="text-center p-4"><i class="icon-spinner2 spinner mr-2"></i>Calcul en cours...</div>').show();
                },
                success: function(response) {
                    $('#results-container').html(response).show();
                },
                error: function(xhr) {
                    let errorMessage = 'Erreur lors du calcul';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#results-container').html('<div class="alert alert-danger"><i class="icon-cross2 mr-2"></i>' + errorMessage + '</div>').show();
                }
            });
        }
    });

    // Soumission du formulaire de recalcul
    $('#recalculate-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("proclamations.recalculate") }}',
            method: 'POST',
            data: formData,
            beforeSend: function() {
                $('#recalculate-form button[type="submit"]').prop('disabled', true).html('<i class="icon-spinner2 spinner mr-1"></i>Recalcul...');
            },
            success: function(response) {
                $('#recalculateModal').modal('hide');
                toastr.success('Recalcul terminé avec succès');
                
                // Recharger les résultats si une proclamation est affichée
                if ($('#results-container').is(':visible')) {
                    $('#proclamation-form').submit();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors du recalcul';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                $('#recalculate-form button[type="submit"]').prop('disabled', false).html('<i class="icon-refresh2 mr-1"></i>Recalculer');
            }
        });
    });
});

function showRecalculateModal() {
    $('#recalculateModal').modal('show');
}
</script>
@endsection

@endsection
