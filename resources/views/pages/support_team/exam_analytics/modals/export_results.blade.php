{{-- Modal Export Résultats --}}
<div class="modal fade" id="exportResultsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="icon-download mr-2"></i>Exporter les Résultats
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('exam_analytics.export_results') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <h6><i class="icon-checkmark-circle2 mr-2"></i>Options d'Export</h6>
                        <p class="mb-0">Exportez les résultats d'examens au format Excel avec mise en forme professionnelle, statistiques et graphiques intégrés.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="export_exam_id">Examen <span class="text-danger">*</span></label>
                                <select name="exam_id" id="export_exam_id" class="form-control select" required>
                                    <option value="">Sélectionner un examen</option>
                                    @foreach(\App\Models\Exam::where('year', Qs::getSetting('current_session'))->get() as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="export_class_id">Classe <span class="text-danger">*</span></label>
                                <select name="class_id" id="export_class_id" class="form-control select" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach(\App\Models\MyClass::all() as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="export_format">Format d'Export</label>
                                <select name="format" id="export_format" class="form-control">
                                    <option value="xlsx">Excel (.xlsx) - Recommandé</option>
                                    <option value="xls">Excel Ancien (.xls)</option>
                                    <option value="csv">CSV (données brutes)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="export_type">Type d'Export</label>
                                <select name="export_type" id="export_type" class="form-control">
                                    <option value="complete">Complet (avec statistiques)</option>
                                    <option value="simple">Simple (données uniquement)</option>
                                    <option value="summary">Résumé (moyennes par classe)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Options Supplémentaires</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="include_charts" name="include_charts" checked>
                            <label class="custom-control-label" for="include_charts">
                                Inclure les graphiques de performance
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="include_rankings" name="include_rankings" checked>
                            <label class="custom-control-label" for="include_rankings">
                                Inclure les classements
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="include_comments" name="include_comments">
                            <label class="custom-control-label" for="include_comments">
                                Inclure les commentaires des enseignants
                            </label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="password_protect" name="password_protect">
                            <label class="custom-control-label" for="password_protect">
                                Protéger par mot de passe
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="icon-info22 mr-2"></i>Contenu de l'Export</h6>
                        <ul class="mb-0">
                            <li><strong>Données principales :</strong> Noms, moyennes, positions, mentions</li>
                            <li><strong>Statistiques :</strong> Moyennes de classe, taux de réussite</li>
                            <li><strong>Graphiques :</strong> Distribution des notes, évolution</li>
                            <li><strong>Mise en forme :</strong> Couleurs, bordures, logos</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="icon-download mr-2"></i>Exporter Maintenant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
