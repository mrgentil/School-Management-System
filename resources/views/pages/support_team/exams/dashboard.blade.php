@extends('layouts.master')
@section('page_title', 'Tableau de Bord - Examens')
@section('content')

<div class="row">
    <div class="col-md-12">
        {{-- Header --}}
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-1"><i class="icon-book mr-2"></i>Gestion Complète des Examens</h3>
                        <p class="mb-0">Administrez tous les aspects des examens - De la création à la publication des résultats</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('exams.index') }}" class="btn btn-light btn-lg">
                            <i class="icon-list mr-2"></i>Liste des Examens
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Rapide - Fonctionnalités Principales --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="icon-folder-open mr-2"></i>Fonctionnalités Principales</h5>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-left-3 border-left-primary">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="icon-file-text2 mr-2"></i>Examens & Notes</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('exams.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-plus2 mr-2 text-success"></i>Créer un Examen
                        </a>
                        <a href="{{ route('marks.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-pencil5 mr-2 text-primary"></i>Saisir les Notes
                        </a>
                        <a href="{{ route('marks.batch_fix') }}" class="list-group-item list-group-item-action">
                            <i class="icon-wrench mr-2 text-warning"></i>Corriger les Notes (Batch)
                        </a>
                        <a href="{{ route('marks.tabulation') }}" class="list-group-item list-group-item-action">
                            <i class="icon-table2 mr-2 text-info"></i>Tabulation des Notes
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-left-3 border-left-success">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="icon-calendar mr-2"></i>Calendrier & Planning</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('exam_schedules.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-list mr-2 text-primary"></i>Tous les Calendriers
                        </a>
                        <a href="{{ route('exam_schedules.calendar') }}" class="list-group-item list-group-item-action">
                            <i class="icon-calendar2 mr-2 text-success"></i>Vue Calendrier
                        </a>
                        <a href="{{ route('exam_schedules.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-plus2 mr-2 text-success"></i>Planifier un Examen
                        </a>
                        <a href="{{ route('exam_schedules.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-user-tie mr-2 text-info"></i>Gérer les Surveillants
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-left-3 border-left-warning">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="icon-stats-dots mr-2"></i>Analytics & Rapports</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('exam_analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-graph mr-2 text-primary"></i>Vue d'Ensemble
                        </a>
                        @if($exams->count() > 0)
                        <a href="{{ route('exam_analytics.overview', $exams->first()->id) }}" class="list-group-item list-group-item-action">
                            <i class="icon-pie-chart mr-2 text-success"></i>Analyse Détaillée
                        </a>
                        @endif
                        <a href="{{ route('exam_analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-library2 mr-2 text-warning"></i>Statistiques par Classe
                        </a>
                        <a href="{{ route('exam_analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="icon-download mr-2 text-info"></i>Exporter les Résultats
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Publication & Communication --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="icon-megaphone mr-2"></i>Publication & Communication</h5>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-left-3 border-left-danger">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="icon-eye mr-2"></i>Publication des Résultats</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Contrôlez la visibilité des résultats et publiez-les progressivement</p>
                        <div class="list-group">
                            @forelse($exams->take(5) as $exam)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $exam->name }}</strong>
                                    <br><small class="text-muted">{{ $exam->year }} - S{{ $exam->semester }}</small>
                                </div>
                                <div>
                                    @if($exam->results_published)
                                        <span class="badge badge-success mr-2">Publié</span>
                                    @else
                                        <span class="badge badge-secondary mr-2">Non publié</span>
                                    @endif
                                    <a href="{{ route('exam_publication.show', $exam->id) }}" class="btn btn-sm btn-primary">
                                        <i class="icon-cog"></i> Gérer
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-info mb-0">
                                <i class="icon-info22 mr-2"></i>Aucun examen disponible
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card border-left-3 border-left-info">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="icon-bell mr-2"></i>Notifications</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Informez les étudiants des événements importants</p>
                        @if($exams->count() > 0)
                        <a href="{{ route('exam_publication.show', $exams->first()->id) }}" class="btn btn-info btn-block mb-2">
                            <i class="icon-mail5 mr-2"></i>Envoyer une Notification
                        </a>
                        @endif
                        <div class="alert alert-light mb-0">
                            <h6 class="mb-2">Types de notifications disponibles:</h6>
                            <ul class="mb-0">
                                <li>Publication de calendrier</li>
                                <li>Publication de résultats</li>
                                <li>Rappels d'examens</li>
                                <li>Modifications</li>
                                <li>Annulations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques Rapides --}}
        <div class="row">
            <div class="col-md-12">
                <h5 class="mb-3"><i class="icon-stats-bars2 mr-2"></i>Statistiques Rapides</h5>
            </div>

            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $stats['total_exams'] }}</h2>
                        <p class="mb-0">Examens cette année</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $stats['published'] }}</h2>
                        <p class="mb-0">Résultats publiés</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $stats['scheduled'] }}</h2>
                        <p class="mb-0">Horaires planifiés</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $stats['upcoming'] }}</h2>
                        <p class="mb-0">Examens à venir</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aide Rapide --}}
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="icon-question-circle mr-2"></i>Aide Rapide</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">📝 Créer un Examen</h6>
                        <ol class="mb-3">
                            <li>Aller à "Liste des Examens"</li>
                            <li>Cliquer "Add Exam"</li>
                            <li>Renseigner nom, semestre, année</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">📅 Planifier un Horaire</h6>
                        <ol class="mb-3">
                            <li>Sélectionner un examen</li>
                            <li>Cliquer "Calendrier"</li>
                            <li>Ajouter date, heure, salle</li>
                            <li>Assigner des surveillants</li>
                        </ol>
                    </div>
                    <div class="col-md-4">
                        <h6 class="font-weight-bold">✅ Publier des Résultats</h6>
                        <ol class="mb-3">
                            <li>Saisir toutes les notes</li>
                            <li>Aller à "Publication"</li>
                            <li>Vérifier la progression</li>
                            <li>Cliquer "Publier"</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
