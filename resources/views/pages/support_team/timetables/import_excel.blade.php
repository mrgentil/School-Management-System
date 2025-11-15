<div class="tab-pane fade" id="import-excel">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-download4 mr-2"></i>
                        Télécharger le Template Excel
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Téléchargez le template Excel pré-formaté pour faciliter l'importation de votre emploi du temps.
                        Le template contient :
                    </p>
                    <ul class="list-unstyled mb-3">
                        <li><i class="icon-checkmark3 text-success mr-2"></i>Les en-têtes de colonnes requis</li>
                        <li><i class="icon-checkmark3 text-success mr-2"></i>Des exemples de données</li>
                        <li><i class="icon-checkmark3 text-success mr-2"></i>La liste des matières disponibles</li>
                        <li><i class="icon-checkmark3 text-success mr-2"></i>Les instructions détaillées</li>
                    </ul>
                    <a href="{{ route('tt.download_template', $ttr_id) }}" class="btn btn-primary btn-block">
                        <i class="icon-download4 mr-2"></i>
                        Télécharger le Template
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-upload4 mr-2"></i>
                        Importer l'Emploi du Temps
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tt.import', $ttr_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="alert alert-info border-0">
                            <i class="icon-info22 mr-2"></i>
                            <strong>Format requis :</strong>
                            <ul class="mb-0 mt-2">
                                <li>Fichier Excel (.xlsx ou .xls)</li>
                                <li>Colonnes : Jour | Créneau Horaire | Matière</li>
                                <li>Taille maximale : 2 MB</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-semibold">Sélectionner le fichier Excel :</label>
                            <input type="file" 
                                   name="timetable_file" 
                                   class="form-control-uniform" 
                                   accept=".xlsx,.xls"
                                   required
                                   data-fouc>
                            <span class="form-text text-muted">Formats acceptés : .xlsx, .xls</span>
                        </div>

                        <div class="alert alert-warning border-0">
                            <i class="icon-warning2 mr-2"></i>
                            <strong>Attention :</strong> L'import mettra à jour les cours existants et créera les nouveaux créneaux horaires si nécessaire.
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="icon-upload4 mr-2"></i>
                            Importer l'Emploi du Temps
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="icon-file-excel mr-2"></i>
                        Exporter l'Emploi du Temps Actuel
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        Exportez l'emploi du temps actuel vers un fichier Excel pour le sauvegarder, le partager ou le modifier.
                    </p>
                    <a href="{{ route('tt.export', $ttr_id) }}" class="btn btn-info">
                        <i class="icon-file-excel mr-2"></i>
                        Exporter vers Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="icon-book mr-2"></i>
                        Guide d'utilisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h6 class="font-weight-semibold text-primary">
                                    <i class="icon-circle-small mr-1"></i>
                                    Étape 1 : Télécharger
                                </h6>
                                <p class="text-muted mb-0">
                                    Téléchargez le template Excel qui contient le format requis et les matières disponibles pour cette classe.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h6 class="font-weight-semibold text-success">
                                    <i class="icon-circle-small mr-1"></i>
                                    Étape 2 : Remplir
                                </h6>
                                <p class="text-muted mb-0">
                                    Remplissez le fichier Excel avec les informations de votre emploi du temps. Consultez l'onglet "Instructions" dans le fichier.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <h6 class="font-weight-semibold text-info">
                                    <i class="icon-circle-small mr-1"></i>
                                    Étape 3 : Importer
                                </h6>
                                <p class="text-muted mb-0">
                                    Importez le fichier rempli. Le système validera les données et créera automatiquement l'emploi du temps.
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-semibold mb-2">Format des jours :</h6>
                            <div class="d-flex flex-wrap">
                                <span class="badge badge-secondary mr-1 mb-1">Monday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Tuesday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Wednesday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Thursday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Friday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Saturday</span>
                                <span class="badge badge-secondary mr-1 mb-1">Sunday</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-semibold mb-2">Exemples de créneaux horaires :</h6>
                            <ul class="list-unstyled mb-0">
                                <li><code>08:00 AM - 09:00 AM</code></li>
                                <li><code>8:00 AM - 9:00 AM</code></li>
                                <li><code>14:00 - 15:00</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
