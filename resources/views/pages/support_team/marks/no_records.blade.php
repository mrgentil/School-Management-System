@extends('layouts.master')
@section('page_title', 'Mes Notes')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="icon-book mr-2"></i>Mes Notes</h5>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="icon-file-empty icon-3x text-muted"></i>
                    </div>
                    <h4 class="mb-3">Aucune note disponible</h4>
                    <p class="text-muted mb-4">
                        Vous n'avez pas encore de notes enregistrées dans le système.<br>
                        Les notes seront disponibles une fois que vos enseignants les auront saisies.
                    </p>
                    
                    <div class="alert alert-info border-left-info">
                        <div class="d-flex align-items-start">
                            <i class="icon-info22 icon-2x mr-3"></i>
                            <div class="text-left">
                                <h6 class="alert-heading mb-2">Informations</h6>
                                <ul class="mb-0">
                                    <li>Vos notes seront visibles ici après chaque évaluation</li>
                                    <li>Vous pourrez consulter vos bulletins par année scolaire</li>
                                    <li>Un système de notification vous alertera dès qu'une nouvelle note sera disponible</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                            <i class="icon-arrow-left13 mr-2"></i>Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations sur votre profil -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="icon-user mr-2"></i>Vos informations</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom :</strong> {{ Auth::user()->name }}</p>
                            <p><strong>Matricule :</strong> {{ Auth::user()->student_record->adm_no ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Classe :</strong> {{ Auth::user()->student_record->my_class->name ?? 'N/A' }}</p>
                            <p><strong>Section :</strong> {{ Auth::user()->student_record->section->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
