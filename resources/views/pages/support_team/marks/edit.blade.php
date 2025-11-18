<form class="ajax-update" action="{{ route('marks.update', [$exam_id, $my_class_id, $section_id, $subject_id]) }}" method="post">
    @csrf @method('put')
    
    <div class="alert alert-info border-0 mb-3">
        <i class="icon-info22 mr-2"></i>
        <strong>Note:</strong> Saisissez les notes sur 20 pour les interrogations (T1, T2) et sur 60 pour l'examen final.
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
            <tr>
                <th width="5%">N°</th>
                <th width="30%">Nom de l'Étudiant</th>
                <th width="10%">Matricule</th>
                <th width="15%">1ère Interro (20)</th>
                <th width="15%">2ème Interro (20)</th>
                <th width="15%">Examen (60)</th>
                <th width="10%">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($marks->sortBy('user.name') as $mk)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><strong>{{ $mk->user->name }}</strong></td>
                    <td class="text-center">{{ $mk->user->student_record->adm_no }}</td>

    {{--                CA AND EXAMS --}}
                    <td><input title="1ère Interrogation" min="0" max="20" class="form-control text-center" name="t1_{{ $mk->id }}" value="{{ $mk->t1 }}" type="number" step="0.5"></td>
                    <td><input title="2ème Interrogation" min="0" max="20" class="form-control text-center" name="t2_{{ $mk->id }}" value="{{ $mk->t2 }}" type="number" step="0.5"></td>
                    <td><input title="Examen Final" min="0" max="60" class="form-control text-center" name="exm_{{ $mk->id }}" value="{{ $mk->exm }}" type="number" step="0.5"></td>
                    <td class="text-center font-weight-bold">
                        {{ ($mk->t1 ?? 0) + ($mk->t2 ?? 0) + ($mk->exm ?? 0) }}/100
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="icon-checkmark3 mr-2"></i>Enregistrer les Notes
        </button>
    </div>
</form>
