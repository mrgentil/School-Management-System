@extends('layouts.master')
@section('page_title', 'Barèmes de Notation')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Gestion des Barèmes de Notation</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-grades" class="nav-link active" data-toggle="tab">Barèmes Existants</a></li>
                <li class="nav-item"><a href="#new-grade" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Ajouter un Barème</a></li>
                <li class="nav-item"><a href="#custom-remarks" class="nav-link" data-toggle="tab"><i class="icon-cog3"></i> Mentions Personnalisées</a></li>
            </ul>

            <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-grades">
                        <table class="table datatable-button-html5-columns">
                            <thead>
                            <tr>
                                <th>N°</th>
                                <th>Grade</th>
                                <th>Type de Classe</th>
                                <th>Intervalle</th>
                                <th>Mention</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($grades as $gr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $gr->name }}</td>
                                    <td>{{ $gr->class_type_id ? $class_types->where('id', $gr->class_type_id)->first()->name : ''}}</td>
                                    <td>{{ $gr->mark_from.' - '.$gr->mark_to }}</td>
                                    <td>{{ $gr->remark }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if(Qs::userIsTeamSA())
                                                    {{--Edit--}}
                                                    <a href="{{ route('grades.edit', $gr->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Modifier</a>
                                                   @endif
                                                    @if(Qs::userIsSuperAdmin())
                                                    {{--Delete--}}
                                                    <a id="{{ $gr->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Supprimer</a>
                                                    <form method="post" id="item-delete-{{ $gr->id }}" action="{{ route('grades.destroy', $gr->id) }}" class="hidden">@csrf @method('delete')</form>
                                                        @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                <div class="tab-pane fade" id="new-grade">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info border-0 alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                                <span><strong>Instructions :</strong> Si le barème s'applique à tous les types de classe, sélectionnez <strong>NON APPLICABLE</strong>. Sinon, choisissez le type de classe spécifique concerné par ce barème.</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <form method="post" action="{{ route('grades.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Grade <span class="text-danger">*</span></label>
                                    <div class="col-lg-9">
                                        <input name="name" value="{{ old('name') }}" required type="text" class="form-control text-uppercase" placeholder="Ex. A1, B2, C3">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="class_type_id" class="col-lg-3 col-form-label font-weight-semibold">Type de Classe</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="class_type_id" id="class_type_id">
                                            <option value="">Non Applicable</option>
                                         @foreach($class_types as $ct)
                                                <option {{ old('class_type_id') == $ct->id ? 'selected' : '' }} value="{{ $ct->id }}">{{ $ct->name }}</option>
                                             @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Note Minimum <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input min="0" max="20" name="mark_from" value="{{ old('mark_from') }}" required type="number" step="0.01" class="form-control" placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Note Maximum <span class="text-danger">*</span></label>
                                    <div class="col-lg-3">
                                        <input min="0" max="20" name="mark_to" value="{{ old('mark_to') }}" required type="number" step="0.01" class="form-control" placeholder="20">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="remark" class="col-lg-3 col-form-label font-weight-semibold">Mention</label>
                                    <div class="col-lg-9">
                                        <select class="form-control select" name="remark" id="remark">
                                            <option value="">Sélectionner une mention...</option>
                                            @foreach(Mk::getRemarks() as $rem)
                                                <option {{ old('remark') == $rem ? 'selected' : '' }} value="{{ $rem }}">{{ $rem }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Enregistrer le Barème <i class="icon-paperplane ml-2"></i></button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title"><i class="icon-info22 mr-2"></i>Barèmes Suggérés (Système RDC)</h6>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-primary">Barème Standard sur 20 :</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>Grade</th>
                                                    <th>Intervalle</th>
                                                    <th>Mention</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-success text-white">
                                                    <td><strong>A1</strong></td>
                                                    <td>18 - 20</td>
                                                    <td>Excellent</td>
                                                </tr>
                                                <tr class="bg-info text-white">
                                                    <td><strong>A2</strong></td>
                                                    <td>16 - 17.99</td>
                                                    <td>Très Bien</td>
                                                </tr>
                                                <tr class="bg-primary text-white">
                                                    <td><strong>B1</strong></td>
                                                    <td>14 - 15.99</td>
                                                    <td>Bien</td>
                                                </tr>
                                                <tr class="bg-secondary text-white">
                                                    <td><strong>B2</strong></td>
                                                    <td>12 - 13.99</td>
                                                    <td>Assez Bien</td>
                                                </tr>
                                                <tr class="bg-warning text-dark">
                                                    <td><strong>C</strong></td>
                                                    <td>10 - 11.99</td>
                                                    <td>Passable</td>
                                                </tr>
                                                <tr class="bg-danger text-white">
                                                    <td><strong>D</strong></td>
                                                    <td>8 - 9.99</td>
                                                    <td>Insuffisant</td>
                                                </tr>
                                                <tr class="bg-dark text-white">
                                                    <td><strong>E</strong></td>
                                                    <td>0 - 7.99</td>
                                                    <td>Très Insuffisant</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6 class="text-success">Conseils :</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Utilisez des intervalles sans chevauchement</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>La note de passage est généralement 10/20</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Adaptez selon votre établissement</li>
                                            <li><i class="icon-checkmark-circle text-success mr-2"></i>Créez des barèmes spécifiques par niveau si nécessaire</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Onglet Mentions Personnalisées --}}
                <div class="tab-pane fade" id="custom-remarks">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title"><i class="icon-list mr-2"></i>Mentions Personnalisées</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>N°</th>
                                                <th>Mention</th>
                                                <th>Description</th>
                                                <th>Ordre</th>
                                                <th>Statut</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(\App\Helpers\Mk::getCustomRemarks() as $remark)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><strong>{{ $remark->name }}</strong></td>
                                                    <td>{{ $remark->description ?: '-' }}</td>
                                                    <td>{{ $remark->sort_order }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $remark->active ? 'success' : 'secondary' }}">
                                                            {{ $remark->active ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" onclick="editRemark({{ $remark->id }}, '{{ $remark->name }}', '{{ $remark->description }}', {{ $remark->sort_order }}, {{ $remark->active ? 'true' : 'false' }})">
                                                            <i class="icon-pencil"></i>
                                                        </button>
                                                        @if(Qs::userIsSuperAdmin())
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteRemark({{ $remark->id }})">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        <i class="icon-info22 mr-2"></i>Aucune mention personnalisée créée
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title"><i class="icon-plus2 mr-2"></i>Ajouter une Mention</h6>
                                </div>
                                <div class="card-body">
                                    <form id="custom-remark-form" method="post" action="{{ route('custom-remarks.store') }}">
                                        @csrf
                                        <input type="hidden" id="remark-id" name="id">
                                        <input type="hidden" id="form-method" name="_method">

                                        <div class="form-group">
                                            <label>Mention <span class="text-danger">*</span></label>
                                            <input type="text" id="remark-name" name="name" class="form-control" placeholder="Ex. Très Satisfaisant" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea id="remark-description" name="description" class="form-control" rows="2" placeholder="Description optionnelle..."></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Ordre d'affichage</label>
                                            <input type="number" id="remark-order" name="sort_order" class="form-control" value="0" min="0">
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" id="remark-active" name="active" class="form-check-input" checked>
                                                <label class="form-check-label" for="remark-active">Mention active</label>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="button" id="cancel-edit" class="btn btn-light" onclick="resetForm()" style="display: none;">Annuler</button>
                                            <button type="submit" id="submit-btn" class="btn btn-primary">Ajouter <i class="icon-plus2 ml-2"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Mentions par défaut --}}
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title"><i class="icon-info22 mr-2"></i>Mentions par Défaut</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-2">Ces mentions sont toujours disponibles :</p>
                                    <div class="row">
                                        @foreach(\App\Helpers\Mk::getDefaultRemarks() as $remark)
                                            <div class="col-6 mb-1">
                                                <span class="badge badge-light">{{ $remark }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Gestion des Barèmes de Notation Terminée--}}

<script>
function editRemark(id, name, description, sortOrder, active) {
    // Remplir le formulaire avec les données de la mention
    document.getElementById('remark-id').value = id;
    document.getElementById('remark-name').value = name;
    document.getElementById('remark-description').value = description || '';
    document.getElementById('remark-order').value = sortOrder;
    document.getElementById('remark-active').checked = active;
    
    // Changer l'action du formulaire pour la mise à jour
    document.getElementById('custom-remark-form').action = '/custom-remarks/' + id;
    document.getElementById('form-method').value = 'PUT';
    
    // Changer le texte du bouton
    document.getElementById('submit-btn').innerHTML = 'Mettre à Jour <i class="icon-pencil ml-2"></i>';
    document.getElementById('cancel-edit').style.display = 'inline-block';
    
    // Changer le titre de la carte
    document.querySelector('#custom-remarks .card-header h6').innerHTML = '<i class="icon-pencil mr-2"></i>Modifier la Mention';
}

function resetForm() {
    // Réinitialiser le formulaire
    document.getElementById('custom-remark-form').reset();
    document.getElementById('remark-id').value = '';
    document.getElementById('form-method').value = '';
    
    // Remettre l'action du formulaire pour la création
    document.getElementById('custom-remark-form').action = '/custom-remarks';
    
    // Remettre le texte du bouton
    document.getElementById('submit-btn').innerHTML = 'Ajouter <i class="icon-plus2 ml-2"></i>';
    document.getElementById('cancel-edit').style.display = 'none';
    
    // Remettre le titre de la carte
    document.querySelector('#custom-remarks .card-header h6').innerHTML = '<i class="icon-plus2 mr-2"></i>Ajouter une Mention';
}

function deleteRemark(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette mention personnalisée ?')) {
        // Créer un formulaire de suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/custom-remarks/' + id;
        
        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Soumettre le formulaire
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
