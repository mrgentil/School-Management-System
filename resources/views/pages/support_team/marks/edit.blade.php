<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    
    {{-- DEBUG: Affichage des variables --}}
    <div class="alert alert-info border-0 mb-3">
        <strong>🔍 DEBUG:</strong>
        grade_config: {{ $grade_config ? 'OUI' : 'NON' }} |
        is_semester_exam: {{ isset($is_semester_exam) ? ($is_semester_exam ? 'OUI' : 'NON') : 'NON DÉFINI' }} |
        evaluation_type: {{ $evaluation_type ?? 'NON DÉFINI' }} |
        evaluation_period: {{ $evaluation_period ?? 'NON DÉFINI' }} |
        current_semester: {{ $current_semester ?? 'NON DÉFINI' }}
    </div>

    @if($grade_config)
        <div class="alert alert-success border-0 mb-3">
            <i class="icon-info22 mr-2"></i>
            <strong>Configuration RDC:</strong> 
            Cote Période: <span class="badge badge-primary">{{ $grade_config->period_max_score }}</span>
            | Cote Examen: <span class="badge badge-success">{{ $grade_config->exam_max_score }}</span>
            @if(isset($evaluation_type) && $evaluation_type === 'interrogation')
                | <strong>📋 Interrogation Période {{ $evaluation_period }}</strong>
                | <span class="badge badge-warning">Notée sur {{ $interrogation_max_score ?? 20 }}</span>
                <br><small class="text-muted ml-4">
                    <i class="icon-info3 mr-1"></i>
                    Les notes saisies sur {{ $interrogation_max_score ?? 20 }} seront converties automatiquement vers la cote RDC ({{ $grade_config->period_max_score }})
                </small>
            @elseif(isset($is_semester_exam) && $is_semester_exam)
                | <strong>📚 Examen Semestre {{ $current_semester }}</strong>
            @else
                | <strong>📝 Évaluations de Période</strong>
            @endif
        </div>
    @else
        <div class="alert alert-warning border-0 mb-3">
            <i class="icon-warning22 mr-2"></i>
            <strong>Attention:</strong> Aucune configuration de cotes RDC trouvée. Veuillez configurer les cotes pour cette classe/matière.
        </div>
    @endif
    
    <div class="table-responsive">
        @if(isset($evaluation_type) && $evaluation_type === 'interrogation' && isset($evaluation_period))
            {{-- Vue SIMPLIFIÉE pour INTERROGATIONS par période --}}
            <table class="table table-bordered table-striped">
                <thead class="bg-info text-white">
                <tr>
                    <th width="5%">N°</th>
                    <th width="30%">Nom de l'Étudiant</th>
                    <th width="15%">Matricule</th>
                    <th width="20%">Interrogation P{{ $evaluation_period }} (/{{ $interrogation_max_score ?? 20 }})</th>
                    <th width="15%">Pourcentage</th>
                    <th width="15%">Points/20</th>
                </tr>
                </thead>
                <tbody>
                @foreach($marks->sortBy('user.name') as $mk)
                    @php
                        // Utiliser la colonne appropriée selon la période
                        $periodColumn = 't' . $evaluation_period;
                        $periodScore = $mk->$periodColumn ?? 0;
                        $interrogationMax = $interrogation_max_score ?? 20;
                        $rdcMaxScore = $grade_config ? $grade_config->period_max_score : 20;
                        // Convertir la note de l'interrogation vers la cote RDC
                        $percentage = $interrogationMax > 0 ? ($periodScore / $interrogationMax) * 100 : 0;
                        $pointsOn20 = ($percentage / 100) * 20;
                        $pointsOnRDC = ($percentage / 100) * $rdcMaxScore;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $mk->user->name }}</strong></td>
                        <td class="text-center">{{ $mk->user->student_record->adm_no ?? 'N/A' }}</td>
                        <td>
                            <input 
                                title="Interrogation Période {{ $evaluation_period }}" 
                                min="0" 
                                max="{{ $interrogationMax }}" 
                                class="form-control text-center period-input" 
                                name="{{ $periodColumn }}_{{ $mk->id }}" 
                                value="{{ $periodScore }}" 
                                type="number" 
                                step="0.25"
                                data-student-id="{{ $mk->id }}"
                                data-max-score="{{ $interrogationMax }}"
                                data-rdc-max="{{ $rdcMaxScore }}">
                        </td>
                        <td class="text-center">
                            <span class="percentage-display badge badge-info" data-student-id="{{ $mk->id }}">
                                {{ number_format($percentage, 2) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="points-display badge badge-primary" data-student-id="{{ $mk->id }}">
                                {{ number_format($pointsOn20, 2) }}/20
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @elseif(isset($is_semester_exam) && $is_semester_exam && $grade_config)
            {{-- Vue SIMPLIFIÉE pour EXAMENS semestriels --}}
            <table class="table table-bordered table-striped">
                <thead class="bg-success text-white">
                <tr>
                    <th width="5%">N°</th>
                    <th width="30%">Nom de l'Étudiant</th>
                    <th width="15%">Matricule</th>
                    <th width="20%">Examen S{{ $current_semester }} (/{{ $grade_config->exam_max_score }})</th>
                    <th width="15%">Pourcentage</th>
                    <th width="15%">Points/20</th>
                </tr>
                </thead>
                <tbody>
                @foreach($marks->sortBy('user.name') as $mk)
                    @php
                        $examColumn = $current_semester == 1 ? 's1_exam' : 's2_exam';
                        $examScore = $mk->$examColumn ?? 0;
                        $maxScore = $grade_config->exam_max_score ?? 80;
                        $percentage = $maxScore > 0 ? ($examScore / $maxScore) * 100 : 0;
                        $pointsOn20 = ($percentage / 100) * 20;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $mk->user->name }}</strong></td>
                        <td class="text-center">{{ $mk->user->student_record->adm_no ?? 'N/A' }}</td>
                        <td>
                            <input 
                                title="Examen Semestre {{ $current_semester }}" 
                                min="0" 
                                max="{{ $maxScore }}" 
                                class="form-control text-center exam-input" 
                                name="{{ $examColumn }}_{{ $mk->id }}" 
                                value="{{ $examScore }}" 
                                type="number" 
                                step="0.25"
                                data-student-id="{{ $mk->id }}"
                                data-max-score="{{ $maxScore }}">
                        </td>
                        <td class="text-center">
                            <span class="percentage-display badge badge-info" data-student-id="{{ $mk->id }}">
                                {{ number_format($percentage, 2) }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="points-display badge badge-primary" data-student-id="{{ $mk->id }}">
                                {{ number_format($pointsOn20, 2) }}/20
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            {{-- Vue pour évaluations de période (système RDC complet) --}}
            <table class="table table-bordered table-striped">
                <thead class="bg-primary text-white">
                <tr>
                    <th width="3%">N°</th>
                    <th width="20%">Nom de l'Étudiant</th>
                    <th width="8%">Matricule</th>
                    <th width="8%">T1 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">T2 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">T3 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">T4 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">TCA ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">TEX1 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">TEX2 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="8%">TEX3 ({{ $grade_config ? $grade_config->period_max_score : 20 }})</th>
                    <th width="5%">%</th>
                </tr>
                </thead>
                <tbody>
                @foreach($marks->sortBy('user.name') as $mk)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $mk->user->name }}</strong></td>
                        <td class="text-center">{{ $mk->user->student_record->adm_no ?? 'N/A' }}</td>
                        
                        {{-- Devoirs par période --}}
                        <td><input title="Devoir Période 1" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 2" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="t2_{{ $mk->id }}" value="{{ $mk->t2 }}" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 3" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="t3_{{ $mk->id }}" value="{{ $mk->t3 }}" type="number" step="0.25"></td>
                        <td><input title="Devoir Période 4" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="t4_{{ $mk->id }}" value="{{ $mk->t4 }}" type="number" step="0.25"></td>
                        
                        {{-- TCA et TEX --}}
                        <td><input title="TCA - Travaux Continus" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="tca_{{ $mk->id }}" value="{{ $mk->tca }}" type="number" step="0.25"></td>
                        <td><input title="TEX1 - Travaux Expression 1" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="tex1_{{ $mk->id }}" value="{{ $mk->tex1 }}" type="number" step="0.25"></td>
                        <td><input title="TEX2 - Travaux Expression 2" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="tex2_{{ $mk->id }}" value="{{ $mk->tex2 }}" type="number" step="0.25"></td>
                        <td><input title="TEX3 - Travaux Expression 3" min="0" max="{{ $grade_config ? $grade_config->period_max_score : 20 }}" class="form-control text-center period-input" name="tex3_{{ $mk->id }}" value="{{ $mk->tex3 }}" type="number" step="0.25"></td>
                        
                        {{-- Pourcentage calculé --}}
                        <td class="text-center">
                            <span class="badge badge-info period-percentage" data-student-id="{{ $mk->id }}">
                                --
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="icon-checkmark3 mr-2"></i>Enregistrer les Notes
        </button>
    </div>
</form>

@if($grade_config)
<script>
$(document).ready(function() {
    const maxPoints = {{ $grade_config->period_max_score ?? 20 }};
    const examMaxPoints = {{ $grade_config->exam_max_score ?? 40 }};
    const isExam = {{ isset($is_semester_exam) && $is_semester_exam ? 'true' : 'false' }};
    const isInterrogation = {{ isset($evaluation_type) && $evaluation_type === 'interrogation' ? 'true' : 'false' }};
    
    // Calcul automatique pour les INTERROGATIONS (une seule colonne avec cote flexible)
    if (isInterrogation) {
        $('.period-input').on('input', function() {
            const studentId = $(this).data('student-id');
            const score = parseFloat($(this).val()) || 0;
            const interrogationMax = parseFloat($(this).data('max-score')); // Cote de l'interrogation (ex: 10)
            const rdcMax = parseFloat($(this).data('rdc-max')); // Cote RDC configurée (ex: 20)
            
            if (score > interrogationMax) {
                $(this).val(interrogationMax);
                return;
            }
            
            // Calculer le pourcentage par rapport à la cote de l'interrogation
            const percentage = interrogationMax > 0 ? (score / interrogationMax) * 100 : 0;
            const pointsOn20 = (percentage / 100) * 20;
            const pointsOnRDC = (percentage / 100) * rdcMax;
            
            $(`[data-student-id="${studentId}"].percentage-display`).text(percentage.toFixed(2) + '%');
            $(`[data-student-id="${studentId}"].points-display`).text(pointsOn20.toFixed(2) + '/20');
            
            // Optionnel: afficher aussi la note convertie en cote RDC
            console.log(`Étudiant ${studentId}: ${score}/${interrogationMax} = ${percentage.toFixed(2)}% = ${pointsOnRDC.toFixed(2)}/${rdcMax}`);
        });
        
        // Calcul initial
        $('.period-input').trigger('input');
    }
    // Calcul automatique pour les EXAMENS semestriels
    else if (isExam) {
        $('.exam-input').on('input', function() {
            const studentId = $(this).data('student-id');
            const score = parseFloat($(this).val()) || 0;
            
            if (score > examMaxPoints) {
                $(this).val(examMaxPoints);
                return;
            }
            
            const percentage = examMaxPoints > 0 ? (score / examMaxPoints) * 100 : 0;
            const points = (percentage / 100) * 20;
            
            $(`[data-student-id="${studentId}"].percentage-display`).text(percentage.toFixed(2) + '%');
            $(`[data-student-id="${studentId}"].points-display`).text(points.toFixed(2) + '/20');
        });
        
        // Calcul initial
        $('.exam-input').trigger('input');
    } 
    // Calcul automatique pour les évaluations de période (ancien système)
    else {
        // Calcul automatique pour les périodes (système RDC)
        $('.period-input').on('input', function() {
            const row = $(this).closest('tr');
            const studentId = row.find('.period-percentage').data('student-id');
            
            // Validation de la note
            const inputMax = parseFloat($(this).attr('max'));
            const score = parseFloat($(this).val()) || 0;
            
            if (score > inputMax) {
                $(this).val(inputMax);
                return;
            }
            
            // Calcul de la moyenne pondérée RDC
            const t1 = parseFloat(row.find('input[name^="t1_"]').val()) || 0;
            const t2 = parseFloat(row.find('input[name^="t2_"]').val()) || 0;
            const t3 = parseFloat(row.find('input[name^="t3_"]').val()) || 0;
            const t4 = parseFloat(row.find('input[name^="t4_"]').val()) || 0;
            const tca = parseFloat(row.find('input[name^="tca_"]').val()) || 0;
            const tex1 = parseFloat(row.find('input[name^="tex1_"]').val()) || 0;
            const tex2 = parseFloat(row.find('input[name^="tex2_"]').val()) || 0;
            const tex3 = parseFloat(row.find('input[name^="tex3_"]').val()) || 0;
            
            // Pondération RDC
            const testsAvg = (t1 + t2 + t3 + t4) / 4; // Moyenne des devoirs
            const tcaWeight = 0.3;
            const tex1Weight = 0.1;
            const tex2Weight = 0.05;
            const tex3Weight = 0.05;
            const testsWeight = 0.5;
            
            const weightedAvg = (testsAvg * testsWeight) + (tca * tcaWeight) + 
                               (tex1 * tex1Weight) + (tex2 * tex2Weight) + (tex3 * tex3Weight);
            
            const percentage = maxPoints > 0 ? (weightedAvg / maxPoints) * 100 : 0;
            
            row.find('.period-percentage').text(percentage.toFixed(1) + '%');
        });
        
        // Calcul initial
        $('.period-input').trigger('input');
    }
});
</script>
@endif
