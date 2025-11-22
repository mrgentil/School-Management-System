<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            <i class="icon-trophy mr-2"></i>
            Proclamation Période {{ $period }} - {{ $selected_class->full_name ?: $selected_class->name }}
        </h4>
        <div class="card-header-elements">
            <span class="badge badge-primary">{{ $rankings['total_students'] }} étudiants</span>
        </div>
    </div>
    <div class="card-body">
        @if($rankings['total_students'] > 0)
            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ collect($rankings['rankings'])->where('mention', 'Très Bien')->count() }}</h3>
                            <small>Très Bien (≥80%)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ collect($rankings['rankings'])->where('mention', 'Bien')->count() }}</h3>
                            <small>Bien (70-79%)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ collect($rankings['rankings'])->where('mention', 'Assez Bien')->count() }}</h3>
                            <small>Assez Bien (60-69%)</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ collect($rankings['rankings'])->whereIn('mention', ['Passable', 'Insuffisant'])->count() }}</h3>
                            <small>En difficulté (<60%)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" width="80">Rang</th>
                            <th>Étudiant</th>
                            <th class="text-center" width="120">Pourcentage</th>
                            <th class="text-center" width="100">Points/20</th>
                            <th class="text-center" width="120">Mention</th>
                            <th class="text-center" width="100">Matières</th>
                            <th class="text-center" width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings['rankings'] as $ranking)
                            <tr class="{{ $ranking['rank'] <= 3 ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    @if($ranking['rank'] == 1)
                                        <span class="badge badge-warning">🥇 {{ $ranking['rank'] }}er</span>
                                    @elseif($ranking['rank'] == 2)
                                        <span class="badge badge-light">🥈 {{ $ranking['rank'] }}ème</span>
                                    @elseif($ranking['rank'] == 3)
                                        <span class="badge badge-secondary">🥉 {{ $ranking['rank'] }}ème</span>
                                    @else
                                        <span class="badge badge-outline-secondary">{{ $ranking['rank'] }}ème</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $ranking['student_name'] }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="font-weight-bold text-primary">
                                        {{ number_format($ranking['percentage'], 2) }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ number_format($ranking['points'], 2) }}/20
                                </td>
                                <td class="text-center">
                                    @php
                                        $mentionClass = '';
                                        switch($ranking['mention']) {
                                            case 'Très Bien': $mentionClass = 'badge-success'; break;
                                            case 'Bien': $mentionClass = 'badge-info'; break;
                                            case 'Assez Bien': $mentionClass = 'badge-warning'; break;
                                            case 'Passable': $mentionClass = 'badge-secondary'; break;
                                            default: $mentionClass = 'badge-danger'; break;
                                        }
                                    @endphp
                                    <span class="badge {{ $mentionClass }}">{{ $ranking['mention'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-outline-primary">{{ $ranking['subject_count'] }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info" 
                                            onclick="showStudentDetail({{ $ranking['student_id'] }}, '{{ $ranking['student_name'] }}', 'period', {{ $period }})">
                                        <i class="icon-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <button class="btn btn-outline-primary" onclick="exportToPDF('period', {{ $period }})">
                        <i class="icon-file-pdf mr-1"></i>Exporter en PDF
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <small class="text-muted">
                        Calculé le {{ date('d/m/Y à H:i') }}
                    </small>
                </div>
            </div>

        @else
            <div class="alert alert-warning">
                <i class="icon-warning22 mr-2"></i>
                Aucun étudiant trouvé avec des notes pour cette période.
                <br>
                <small>Vérifiez que les notes ont été saisies et que les cotes sont configurées pour cette classe.</small>
            </div>
        @endif
    </div>
</div>

<!-- Modal détail étudiant -->
<div class="modal fade" id="studentDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détail Étudiant</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="student-detail-content">
                <!-- Contenu chargé via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function showStudentDetail(studentId, studentName, type, periodOrSemester) {
    $('#studentDetailModal .modal-title').text('Détail - ' + studentName);
    
    const params = {
        student_id: studentId,
        class_id: {{ $selected_class->id }},
        year: '{{ $year }}'
    };
    
    if (type === 'period') {
        params.period = periodOrSemester;
    } else {
        params.semester = periodOrSemester;
    }
    
    $.ajax({
        url: '{{ route("proclamations.student") }}',
        method: 'GET',
        data: params,
        beforeSend: function() {
            $('#student-detail-content').html('<div class="text-center p-4"><i class="icon-spinner2 spinner mr-2"></i>Chargement...</div>');
            $('#studentDetailModal').modal('show');
        },
        success: function(response) {
            $('#student-detail-content').html(response);
        },
        error: function() {
            $('#student-detail-content').html('<div class="alert alert-danger">Erreur lors du chargement des détails</div>');
        }
    });
}

function exportToPDF(type, periodOrSemester) {
    toastr.info('Fonctionnalité d\'export PDF en cours de développement');
}
</script>
