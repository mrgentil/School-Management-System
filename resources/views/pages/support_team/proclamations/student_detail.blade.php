@if(!empty($student_details))
    <div class="row">
        <!-- Résumé général -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ number_format($student_details['overall_percentage'], 2) }}%</h3>
                    <p class="mb-1">Moyenne Générale</p>
                    <small>{{ number_format($student_details['overall_points'], 2) }}/20</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $student_details['subject_count'] }}</h3>
                    <p class="mb-1">Matières</p>
                    <small>Évaluées</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    @php
                        $percentage = $student_details['overall_percentage'];
                        if ($percentage >= 80) $mention = 'Très Bien';
                        elseif ($percentage >= 70) $mention = 'Bien';
                        elseif ($percentage >= 60) $mention = 'Assez Bien';
                        elseif ($percentage >= 50) $mention = 'Passable';
                        else $mention = 'Insuffisant';
                    @endphp
                    <h3 class="mb-1">{{ $mention }}</h3>
                    <p class="mb-1">Mention</p>
                    <small>
                        @if(isset($type) && $type === 'period')
                            Période {{ $period }}
                        @else
                            Semestre {{ $semester }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail par matière -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="icon-list mr-2"></i>Détail par Matière
            </h5>
        </div>
        <div class="card-body">
            @if(isset($type) && $type === 'semester')
                <!-- Vue semestre avec périodes + examens -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Matière</th>
                                <th class="text-center">Moy. Périodes</th>
                                <th class="text-center">Moy. Examen</th>
                                <th class="text-center">Moy. Semestre</th>
                                <th class="text-center">Points/20</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student_details['subject_averages'] as $subjectId => $average)
                                <tr>
                                    <td><strong>{{ $average['subject_name'] }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge badge-outline-info">
                                            {{ number_format($average['period_average'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-outline-warning">
                                            {{ number_format($average['exam_average'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">
                                            {{ number_format($average['semester_percentage'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($average['semester_points'], 2) }}/20
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <small>
                        <i class="icon-info22 mr-1"></i>
                        <strong>Calcul semestre :</strong> Moyenne périodes (40%) + Moyenne examens (60%)
                    </small>
                </div>
            @else
                <!-- Vue période simple -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Matière</th>
                                <th class="text-center">Pourcentage</th>
                                <th class="text-center">Points/20</th>
                                <th class="text-center">Appréciation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student_details['subject_averages'] as $subjectId => $average)
                                <tr>
                                    <td><strong>{{ $average['subject_name'] }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge badge-primary">
                                            {{ number_format($average['percentage'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($average['points'], 2) }}/20
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $subjectPercentage = $average['percentage'];
                                            if ($subjectPercentage >= 80) {
                                                $appreciation = 'Excellent';
                                                $badgeClass = 'badge-success';
                                            } elseif ($subjectPercentage >= 70) {
                                                $appreciation = 'Bien';
                                                $badgeClass = 'badge-info';
                                            } elseif ($subjectPercentage >= 60) {
                                                $appreciation = 'Assez Bien';
                                                $badgeClass = 'badge-warning';
                                            } elseif ($subjectPercentage >= 50) {
                                                $appreciation = 'Passable';
                                                $badgeClass = 'badge-secondary';
                                            } else {
                                                $appreciation = 'Insuffisant';
                                                $badgeClass = 'badge-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $appreciation }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Graphique de performance (optionnel) -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="icon-stats-bars mr-2"></i>Performance par Matière
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($student_details['subject_averages'] as $subjectId => $average)
                    @php
                        $percentage = isset($average['semester_percentage']) ? $average['semester_percentage'] : $average['percentage'];
                        $progressClass = '';
                        if ($percentage >= 80) $progressClass = 'bg-success';
                        elseif ($percentage >= 70) $progressClass = 'bg-info';
                        elseif ($percentage >= 60) $progressClass = 'bg-warning';
                        elseif ($percentage >= 50) $progressClass = 'bg-secondary';
                        else $progressClass = 'bg-danger';
                    @endphp
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="font-weight-bold">{{ $average['subject_name'] }}</small>
                            <small>{{ number_format($percentage, 1) }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar {{ $progressClass }}" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@else
    <div class="alert alert-warning">
        <i class="icon-warning22 mr-2"></i>
        Aucune donnée trouvée pour cet étudiant.
    </div>
@endif
