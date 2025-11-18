@extends('layouts.master')
@section('page_title', 'Publication - ' . $exam->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline bg-primary text-white">
            <h6 class="card-title">{{ $exam->name }} - {{ $exam->year }} (Semestre {{ $exam->semester }})</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            {{-- Statut actuel --}}
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-left-3 border-left-{{ $exam->results_published ? 'success' : 'warning' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        Statut: 
                                        @if($exam->results_published)
                                            <span class="badge badge-success">Publié</span>
                                        @else
                                            <span class="badge badge-warning">Non Publié</span>
                                        @endif
                                    </h5>
                                    @if($exam->published_at)
                                        <p class="text-muted mb-0">Publié le: {{ $exam->published_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($exam->results_published)
                                        <form method="post" action="{{ route('exam_publication.unpublish', $exam->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" onclick="return confirm('Annuler la publication ?')">
                                                <i class="icon-cross"></i> Dépublier
                                            </button>
                                        </form>
                                    @else
                                        <form method="post" action="{{ route('exam_publication.publish', $exam->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Publier les résultats ?')">
                                                <i class="icon-checkmark"></i> Publier Résultats
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#notification-modal">
                                        <i class="icon-bell"></i> Envoyer Notification
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques par classe --}}
            <h6 class="font-weight-bold">Progression de la Notation par Classe</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr class="bg-light">
                        <th>Classe</th>
                        <th>Total Étudiants</th>
                        <th>Notes Saisies</th>
                        <th>Progression</th>
                        <th>Statut</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($my_classes as $class)
                        @if(isset($class_stats[$class->id]))
                        <tr>
                            <td><strong>{{ $class->name }}</strong></td>
                            <td>{{ $class_stats[$class->id]['total_students'] }}</td>
                            <td>{{ $class_stats[$class->id]['graded'] }}</td>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $class_stats[$class->id]['percentage'] == 100 ? 'success' : 'primary' }}" 
                                         style="width: {{ $class_stats[$class->id]['percentage'] }}%">
                                        {{ $class_stats[$class->id]['percentage'] }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($class_stats[$class->id]['percentage'] == 100)
                                    <span class="badge badge-success">Complet</span>
                                @else
                                    <span class="badge badge-warning">En cours</span>
                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Notification --}}
    <div id="notification-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Envoyer une Notification</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" action="{{ route('exam_publication.notify', $exam->id) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type de Notification</label>
                            <select name="type" class="form-control" required>
                                <option value="results_published">Résultats Publiés</option>
                                <option value="schedule_published">Calendrier Publié</option>
                                <option value="reminder">Rappel</option>
                                <option value="modification">Modification</option>
                                <option value="cancellation">Annulation</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="title" class="form-control" 
                                   value="Notification - {{ $exam->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Classes Concernées (laissez vide pour tous)</label>
                            <select name="classes[]" class="form-control select-search" multiple>
                                @foreach($my_classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Envoyer Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
