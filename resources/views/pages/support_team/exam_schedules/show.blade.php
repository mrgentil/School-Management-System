@extends('layouts.master')
@section('page_title', 'Horaires - ' . $exam->name)
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">{{ $exam->name }} - {{ $exam->year }} (Semestre {{ $exam->semester }})</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#schedules-list" class="nav-link active" data-toggle="tab">Horaires</a></li>
                <li class="nav-item"><a href="#add-schedule" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Ajouter Horaire</a></li>
            </ul>

            <div class="tab-content">
                {{-- List of Schedules --}}
                <div class="tab-pane fade show active" id="schedules-list">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Classe</th>
                            <th>Option / Section</th>
                            <th>Matière</th>
                            <th>Salle</th>
                            <th>Surveillants</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($schedules as $sch)
                            <tr>
                                <td>
                                    @if($sch->exam_type == 'session')
                                        <span class="badge badge-danger" title="Placement automatique par performance">
                                            <i class="icon-shuffle mr-1"></i>SESSION
                                        </span>
                                    @else
                                        <span class="badge badge-info" title="Étudiants dans leurs salles habituelles">
                                            <i class="icon-home mr-1"></i>HORS SESSION
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $sch->exam_date->format('d/m/Y') }}</td>
                                <td>{{ date('H:i', strtotime($sch->start_time)) }} - {{ date('H:i', strtotime($sch->end_time)) }}</td>
                                <td>{{ $sch->my_class->name }}</td>
                                <td>
                                    @if($sch->option)
                                        <span class="badge badge-primary">
                                            {{ $sch->option->academic_section->name ?? '' }} - {{ $sch->option->name }}
                                        </span>
                                    @elseif($sch->section)
                                        <span class="badge badge-secondary">{{ $sch->section->name }}</span>
                                    @else
                                        <span class="text-muted">Toutes</span>
                                    @endif
                                </td>
                                <td>{{ $sch->subject->name }}</td>
                                <td>{{ $sch->room ?: '-' }}</td>
                                <td>
                                    @foreach($sch->supervisors as $sup)
                                        <span class="badge badge-primary">{{ $sup->teacher->name }}</span>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-icon btn-success" 
                                            data-toggle="modal" data-target="#add-supervisor-{{ $sch->id }}">
                                        <i class="icon-plus2"></i>
                                    </button>
                                </td>
                                <td>
                                    @if($sch->status == 'scheduled')
                                        <span class="badge badge-info">Planifié</span>
                                    @elseif($sch->status == 'ongoing')
                                        <span class="badge badge-warning">En cours</span>
                                    @elseif($sch->status == 'completed')
                                        <span class="badge badge-success">Terminé</span>
                                    @else
                                        <span class="badge badge-danger">Annulé</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" class="dropdown-item" data-toggle="modal" 
                                                   data-target="#edit-schedule-{{ $sch->id }}">
                                                    <i class="icon-pencil"></i> Modifier
                                                </a>
                                                @if(Qs::userIsSuperAdmin())
                                                <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cet horaire?')) { document.getElementById('delete-form-{{ $sch->id }}').submit(); }">
                                                    <i class="icon-trash"></i> Supprimer
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            {{-- Formulaire de suppression caché en dehors du dropdown --}}
                            @if(Qs::userIsSuperAdmin())
                            <form id="delete-form-{{ $sch->id }}" method="POST" action="{{ route('exam_schedules.destroy', $sch->id) }}" style="display:none;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="schedule_id" value="{{ $sch->id }}">
                            </form>
                            @endif

                            {{-- Modal Add Supervisor --}}
                            <div id="add-supervisor-{{ $sch->id }}" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ajouter un Surveillant</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form method="post" action="{{ route('exam_schedules.add_supervisor') }}">
                                            @csrf
                                            <input type="hidden" name="exam_schedule_id" value="{{ $sch->id }}">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Enseignant</label>
                                                    <select name="teacher_id" class="form-control select-search" required>
                                                        <option value="">Sélectionner...</option>
                                                        @foreach($teachers as $teacher)
                                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Rôle</label>
                                                    <select name="role" class="form-control" required>
                                                        <option value="primary">Principal</option>
                                                        <option value="assistant">Assistant</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" data-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Edit Schedule --}}
                            <div id="edit-schedule-{{ $sch->id }}" class="modal fade">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier l'Horaire</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form method="post" action="{{ route('exam_schedules.update', $sch->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="schedule_id" value="{{ $sch->id }}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Type d'Horaire</label>
                                                            <select name="exam_type" class="form-control">
                                                                <option value="hors_session" {{ $sch->exam_type == 'hors_session' ? 'selected' : '' }}>🏠 HORS SESSION</option>
                                                                <option value="session" {{ $sch->exam_type == 'session' ? 'selected' : '' }}>🔄 SESSION</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Option</label>
                                                            <select name="option_id" class="form-control select-search-edit">
                                                                <option value="">Toutes les options</option>
                                                                @if(isset($academic_sections))
                                                                    @foreach($academic_sections as $acadSection)
                                                                        <optgroup label="{{ $acadSection->name }}">
                                                                            @if($acadSection->options && $acadSection->options->count() > 0)
                                                                                @foreach($acadSection->options as $option)
                                                                                    <option value="{{ $option->id }}" {{ $sch->option_id == $option->id ? 'selected' : '' }}>
                                                                                        {{ $acadSection->name }} - {{ $option->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </optgroup>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Date <span class="text-danger">*</span></label>
                                                            <input type="date" name="exam_date" class="form-control" 
                                                                   value="{{ $sch->exam_date->format('Y-m-d') }}" required>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Heure Début</label>
                                                                    <input type="time" name="start_time" class="form-control" 
                                                                           value="{{ date('H:i', strtotime($sch->start_time)) }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Heure Fin</label>
                                                                    <input type="time" name="end_time" class="form-control" 
                                                                           value="{{ date('H:i', strtotime($sch->end_time)) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Salle</label>
                                                            <input type="text" name="room" class="form-control" 
                                                                   value="{{ $sch->room }}" placeholder="Ex: Salle A1">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Statut</label>
                                                            <select name="status" class="form-control">
                                                                <option value="scheduled" {{ $sch->status == 'scheduled' ? 'selected' : '' }}>Planifié</option>
                                                                <option value="ongoing" {{ $sch->status == 'ongoing' ? 'selected' : '' }}>En cours</option>
                                                                <option value="completed" {{ $sch->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                                                                <option value="cancelled" {{ $sch->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Instructions</label>
                                                            <textarea name="instructions" class="form-control" rows="3">{{ $sch->instructions }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-link" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Aucun horaire planifié</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Add Schedule Form --}}
                <div class="tab-pane fade" id="add-schedule">
                    <form method="post" action="{{ route('exam_schedules.store') }}">
                        @csrf
                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                        
                        <div class="alert alert-info border-0">
                            <strong><i class="icon-info22 mr-2"></i>Types d'horaires :</strong><br>
                            <span class="badge badge-info mr-2">HORS SESSION</span> Étudiants dans leurs salles habituelles (par classe)<br>
                            <span class="badge badge-danger mr-2">SESSION</span> Placement automatique par performance (mélange de classes)
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type d'Horaire <span class="text-danger">*</span></label>
                                    <select name="exam_type" id="exam_type" class="form-control select" required>
                                        <option value="">-- Choisir le type --</option>
                                        <option value="hors_session">🏠 HORS SESSION (Salle habituelle)</option>
                                        <option value="session">🔄 SESSION (Placement automatique)</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <strong>Hors Session :</strong> Chaque classe passe séparément dans sa salle<br>
                                        <strong>Session :</strong> Toutes les classes du niveau sont mélangées et placées par performance
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Classe <span class="text-danger">*</span></label>
                                    <select name="my_class_id" id="class_id" class="form-control select-search" required>
                                        <option value="">Sélectionner...</option>
                                        @foreach($my_classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Option / Section</label>
                                    <select name="option_id" id="option_id" class="form-control select-search">
                                        <option value="">Toutes les options</option>
                                        @if(isset($academic_sections))
                                            @foreach($academic_sections as $acadSection)
                                                <optgroup label="{{ $acadSection->name }}">
                                                    @if($acadSection->options && $acadSection->options->count() > 0)
                                                        @foreach($acadSection->options as $option)
                                                            <option value="{{ $option->id }}">
                                                                {{ $acadSection->name }} - {{ $option->name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option value="">{{ $acadSection->name }} (Aucune option)</option>
                                                    @endif
                                                </optgroup>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">
                                        Ex: Technique - Électronique, Technique - Mécanique
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Matière <span class="text-danger">*</span></label>
                                    <select name="subject_id" class="form-control select-search" required>
                                        <option value="">Sélectionner...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Date <span class="text-danger">*</span></label>
                                    <input type="date" name="exam_date" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Heure Début <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Heure Fin <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Salle</label>
                                    <input type="text" name="room" class="form-control" placeholder="Ex: Salle A1">
                                </div>

                                <div class="form-group">
                                    <label>Instructions</label>
                                    <textarea name="instructions" class="form-control" rows="4" 
                                              placeholder="Instructions spéciales pour l'examen..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Créer Horaire <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialiser les select2 quand un modal s'ouvre
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.select-search, .select-search-edit').select2({
            dropdownParent: $(this)
        });
    });
});
</script>
@endsection
