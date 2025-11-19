@extends('layouts.master')
@section('page_title', 'Étudiants en Difficulté - Détection Automatique')
@section('content')

<div class="content">
    {{-- En-tête avec alerte --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-left-3 border-left-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="icon-warning22 mr-2"></i>⚠️ Système de Détection Automatique des Difficultés Scolaires
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2">
                                <strong>{{ $total_at_risk }}</strong> étudiants nécessitent une attention particulière selon nos critères d'analyse.
                            </p>
                            <p class="text-muted mb-0">
                                Le système analyse automatiquement les performances, tendances et présences pour identifier les élèves en difficulté.
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#criteriaModal">
                                <i class="icon-info22 mr-2"></i>Voir les Critères
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $total_at_risk }}</h3>
                            <span class="font-size-sm">Étudiants à Risque</span>
                        </div>
                        <i class="icon-user-minus icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ collect($struggling_students)->where('risk_score', '>=', 5)->count() }}</h3>
                            <span class="font-size-sm">Risque Élevé</span>
                        </div>
                        <i class="icon-warning22 icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ collect($struggling_students)->where('risk_score', 3)->count() }}</h3>
                            <span class="font-size-sm">Risque Modéré</span>
                        </div>
                        <i class="icon-user-check icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ \App\Models\StudentRecord::where('session', Qs::getSetting('current_session'))->count() - $total_at_risk }}</h3>
                            <span class="font-size-sm">Étudiants Stables</span>
                        </div>
                        <i class="icon-user-check icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">⚡ Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#notifyParentsModal">
                                <i class="icon-mail5 mr-2"></i>Notifier Tous les Parents
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-warning btn-block" data-toggle="modal" data-target="#scheduleInterventionModal">
                                <i class="icon-calendar22 mr-2"></i>Programmer Interventions
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.export_results') }}" class="btn btn-info btn-block">
                                <i class="icon-download mr-2"></i>Exporter Rapport
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('exam_analytics.dashboard') }}" class="btn btn-success btn-block">
                                <i class="icon-stats-dots mr-2"></i>Retour Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste détaillée des étudiants en difficulté --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">📋 Liste Détaillée des Étudiants Nécessitant une Attention</h6>
                    <div class="header-elements">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="autoRefresh">
                            <label class="form-check-label" for="autoRefresh">
                                Actualisation automatique
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($struggling_students) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Priorité</th>
                                        <th>Étudiant</th>
                                        <th>Classe</th>
                                        <th>Moyenne Récente</th>
                                        <th>Facteurs de Risque</th>
                                        <th>Score de Risque</th>
                                        <th>Dernière Évaluation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($struggling_students as $student_data)
                                    <tr class="{{ $student_data['risk_score'] >= 5 ? 'table-danger' : 'table-warning' }}">
                                        <td>
                                            @if($student_data['risk_score'] >= 5)
                                                <span class="badge badge-danger">URGENT</span>
                                            @else
                                                <span class="badge badge-warning">MODÉRÉ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $student_data['student']->user->photo ?? asset('global_assets/images/placeholders/placeholder.jpg') }}" 
                                                     class="rounded-circle mr-2" width="32" height="32" alt="">
                                                <div>
                                                    <div class="font-weight-semibold">{{ $student_data['student']->user->name }}</div>
                                                    <div class="text-muted font-size-sm">{{ $student_data['student']->adm_no }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $student_data['student']->my_class->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger font-size-sm">{{ $student_data['recent_average'] }}%</span>
                                        </td>
                                        <td>
                                            @foreach($student_data['risk_factors'] as $factor)
                                                <span class="badge badge-outline-danger mr-1 mb-1">{{ $factor }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-fill mr-2" style="height: 6px;">
                                                    <div class="progress-bar bg-danger" style="width: {{ ($student_data['risk_score'] / 7) * 100 }}%"></div>
                                                </div>
                                                <span class="font-weight-bold">{{ $student_data['risk_score'] }}/7</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted font-size-sm">{{ $student_data['last_exam_date']->diffForHumans() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('exam_analytics.student_progress_chart', $student_data['student']->user_id) }}" 
                                                   class="btn btn-outline-primary" title="Voir Progression">
                                                    <i class="icon-graph"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-info" 
                                                        onclick="showRecommendations({{ json_encode($student_data['recommendations']) }})"
                                                        title="Voir Recommandations">
                                                    <i class="icon-lightbulb"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-warning" 
                                                        onclick="contactParent('{{ $student_data['student']->user->name }}')"
                                                        title="Contacter Parent">
                                                    <i class="icon-phone"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="icon-checkmark-circle2 icon-3x text-success mb-3"></i>
                            <h4 class="text-success">Excellente Nouvelle !</h4>
                            <p class="text-muted">Aucun étudiant ne présente actuellement de difficultés majeures selon nos critères d'analyse.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Critères --}}
<div class="modal fade" id="criteriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">📊 Critères de Détection des Difficultés</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6>🎯 Critères d'Analyse Automatique :</h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Moyenne Faible :</strong> < {{ $criteria['low_average'] }}%
                                <span class="badge badge-danger ml-2">+3 points</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Tendance Déclinante :</strong> Baisse sur {{ $criteria['declining_trend'] }} examens
                                <span class="badge badge-warning ml-2">+2 points</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Absences Fréquentes :</strong> > {{ $criteria['frequent_absence'] }}%
                                <span class="badge badge-info ml-2">+2 points</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>📈 Échelle de Risque :</h6>
                        <div class="progress-stacked mb-2">
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 40%">Faible (0-2)</div>
                                <div class="progress-bar bg-warning" style="width: 20%">Modéré (3-4)</div>
                                <div class="progress-bar bg-danger" style="width: 40%">Élevé (5+)</div>
                            </div>
                        </div>
                        <small class="text-muted">Score ≥ 3 déclenche une alerte</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Recommandations --}}
<div class="modal fade" id="recommendationsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">💡 Recommandations Personnalisées</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="recommendationsList"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script>
// Actualisation automatique
let autoRefreshInterval;
document.getElementById('autoRefresh').addEventListener('change', function() {
    if (this.checked) {
        autoRefreshInterval = setInterval(() => {
            location.reload();
        }, 60000); // Actualiser toutes les minutes
    } else {
        clearInterval(autoRefreshInterval);
    }
});

// Afficher les recommandations
function showRecommendations(recommendations) {
    const list = document.getElementById('recommendationsList');
    list.innerHTML = '<ul class="list-group list-group-flush">';
    recommendations.forEach(rec => {
        list.innerHTML += `<li class="list-group-item"><i class="icon-checkmark mr-2 text-success"></i>${rec}</li>`;
    });
    list.innerHTML += '</ul>';
    $('#recommendationsModal').modal('show');
}

// Contacter parent
function contactParent(studentName) {
    if (confirm(`Voulez-vous envoyer une notification d'alerte au parent de ${studentName} ?`)) {
        // Ici vous pouvez implémenter l'envoi de notification
        alert('Notification envoyée avec succès !');
    }
}

// Notification toast pour les actions
function showToast(message, type = 'success') {
    const toast = `
        <div class="toast" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div class="toast-header bg-${type} text-white">
                <strong class="mr-auto">Notification</strong>
                <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body">${message}</div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', toast);
    $('.toast').toast('show');
}

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    $('.alert').fadeOut();
}, 5000);
</script>
@endsection
