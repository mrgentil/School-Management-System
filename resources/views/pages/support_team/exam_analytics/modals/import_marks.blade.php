{{-- Modal Import Notes Excel --}}
<div class="modal fade" id="importMarksModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="icon-upload mr-2"></i>Importer Notes depuis Excel
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('exam_analytics.import_marks') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6><i class="icon-info22 mr-2"></i>Instructions d'Import</h6>
                        <ul class="mb-0">
                            <li>Le fichier Excel doit contenir les colonnes : <code>nom_etudiant</code>, <code>numero_matricule</code>, <code>matiere</code></li>
                            <li>Pour le système RDC : <code>periode_1</code>, <code>periode_2</code>, <code>periode_3</code>, <code>periode_4</code></li>
                            <li>Examens : <code>examen_semestre_1</code>, <code>examen_semestre_2</code></li>
                            <li>Notes sur 20 pour les périodes, sur 100 pour les anciens systèmes</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="import_exam_id">Examen <span class="text-danger">*</span></label>
                                <select name="exam_id" id="import_exam_id" class="form-control select" required>
                                    <option value="">Sélectionner un examen</option>
                                    @foreach(\App\Models\Exam::where('year', Qs::getSetting('current_session'))->get() as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="import_class_id">Classe <span class="text-danger">*</span></label>
                                <select name="class_id" id="import_class_id" class="form-control select" required onchange="getClassSections(this.value, 'import_section_id')">
                                    <option value="">Sélectionner une classe</option>
                                    @foreach(\App\Models\MyClass::all() as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="import_section_id">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="import_section_id" class="form-control select" required>
                                    <option value="">Sélectionner d'abord une classe</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="excel_file">Fichier Excel <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" name="excel_file" id="excel_file" class="custom-file-input" accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="excel_file">Choisir un fichier...</label>
                        </div>
                        <small class="form-text text-muted">Formats acceptés : Excel (.xlsx, .xls) ou CSV</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="overwrite_existing">
                            <label class="custom-control-label" for="overwrite_existing">
                                Écraser les notes existantes
                            </label>
                        </div>
                        <small class="form-text text-muted">Si coché, les notes existantes seront remplacées</small>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="icon-warning22 mr-2"></i>Modèle Excel</h6>
                        <p class="mb-2">Téléchargez le modèle Excel pour vous assurer du bon format :</p>
                        <a href="{{ asset('templates/import_notes_template.xlsx') }}" class="btn btn-sm btn-outline-primary">
                            <i class="icon-download mr-1"></i>Télécharger le Modèle
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-upload mr-2"></i>Importer les Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mise à jour du nom du fichier sélectionné
document.getElementById('excel_file').addEventListener('change', function(e) {
    var fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier...';
    document.querySelector('.custom-file-label').textContent = fileName;
});
</script>
