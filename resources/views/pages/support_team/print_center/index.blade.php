@extends('layouts.master')
@section('page_title', 'Centre d\'Impression')

@section('content')
<div class="card bg-primary text-white mb-3">
    <div class="card-body py-3">
        <h4 class="mb-0"><i class="icon-printer mr-2"></i> Centre d'Impression</h4>
        <small class="opacity-75">
            @if($userType == 'teacher')
                Imprimez les documents de vos classes
            @elseif($userType == 'accountant')
                Imprimez les documents financiers
            @else
                Générez et imprimez tous vos documents scolaires
            @endif
        </small>
    </div>
</div>

<div class="row">
    {{-- Documents Académiques (Admin + Enseignant) --}}
    @if(in_array($userType, ['super_admin', 'admin', 'teacher']))
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0"><i class="icon-book mr-2"></i> Documents Académiques</h6>
            </div>
            <div class="card-body">
                {{-- Liste de classe --}}
                <div class="mb-4">
                    <h6><i class="icon-users mr-1"></i> Liste des Élèves</h6>
                    <form action="{{ route('print.class_list') }}" method="POST" target="_blank">
                        @csrf
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Fiche de notes --}}
                <div class="mb-4">
                    <h6><i class="icon-pencil mr-1"></i> Fiche de Notes</h6>
                    <form action="{{ route('print.grade_sheet') }}" method="POST" target="_blank">
                        @csrf
                        <select name="class_id" class="form-control mb-2" required id="grade-class">
                            <option value="">-- Classe --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group">
                            <select name="subject_id" class="form-control" required>
                                <option value="">-- Matière --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Emploi du temps --}}
                <div>
                    <h6><i class="icon-calendar mr-1"></i> Emploi du Temps</h6>
                    <form action="{{ route('print.timetable') }}" method="POST" target="_blank">
                        @csrf
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Documents Administratifs (Admin seulement) --}}
    @if(in_array($userType, ['super_admin', 'admin']))
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="card-title mb-0"><i class="icon-file-text mr-2"></i> Documents Administratifs</h6>
            </div>
            <div class="card-body">
                {{-- Attestation de scolarité --}}
                <div class="mb-4">
                    <h6><i class="icon-certificate mr-1"></i> Attestation de Scolarité</h6>
                    <form action="{{ route('print.certificate') }}" method="POST" target="_blank">
                        @csrf
                        <div class="input-group">
                            <select name="student_id" class="form-control select-search" required>
                                <option value="">-- Élève --</option>
                                @foreach(\App\Models\User::where('user_type', 'student')->orderBy('name')->get() as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Cartes d'élèves --}}
                <div class="mb-4">
                    <h6><i class="icon-vcard mr-1"></i> Cartes d'Élèves</h6>
                    <form action="{{ route('print.student_cards') }}" method="POST" target="_blank">
                        @csrf
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Récapitulatif général --}}
                <div>
                    <h6><i class="icon-stats-bars mr-1"></i> Récapitulatif Général</h6>
                    <form action="{{ route('print.summary') }}" method="POST" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="icon-printer mr-1"></i> Imprimer le Récapitulatif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Documents Financiers (Admin + Comptable) --}}
    @if(in_array($userType, ['super_admin', 'admin', 'accountant']))
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h6 class="card-title mb-0"><i class="icon-coins mr-2"></i> Documents Financiers</h6>
            </div>
            <div class="card-body">
                {{-- État de paiement --}}
                <div class="mb-4">
                    <h6><i class="icon-list mr-1"></i> État de Paiement</h6>
                    <form action="{{ route('print.payment_status') }}" method="POST" target="_blank">
                        @csrf
                        <div class="input-group">
                            <select name="class_id" class="form-control" required>
                                <option value="">-- Classe --</option>
                                @foreach(\App\Models\MyClass::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-warning"><i class="icon-printer"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Liens rapides --}}
                <div class="mt-4 pt-3 border-top">
                    <h6 class="text-muted">Liens Rapides</h6>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-warning btn-sm mb-2 btn-block">
                        <i class="icon-clipboard3 mr-1"></i> Gérer les Paiements
                    </a>
                    @if(in_array($userType, ['super_admin', 'admin', 'accountant']))
                    <a href="{{ route('finance.dashboard') }}" class="btn btn-outline-warning btn-sm btn-block">
                        <i class="icon-stats-bars mr-1"></i> Rapports Financiers
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Aide --}}
<div class="card mt-3">
    <div class="card-header bg-light">
        <h6 class="card-title mb-0"><i class="icon-question3 mr-2"></i> Aide</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6 class="text-info"><i class="icon-book mr-1"></i> Documents Académiques</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>Liste des élèves</strong> - Tous les élèves d'une classe</li>
                    <li>• <strong>Fiche de notes</strong> - Notes par matière</li>
                    <li>• <strong>Emploi du temps</strong> - Planning hebdomadaire</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-success"><i class="icon-file-text mr-1"></i> Documents Administratifs</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>Attestation</strong> - Certificat de scolarité</li>
                    <li>• <strong>Cartes d'élèves</strong> - Badges avec photo</li>
                    <li>• <strong>Récapitulatif</strong> - Stats de l'école</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-warning"><i class="icon-coins mr-1"></i> Documents Financiers</h6>
                <ul class="list-unstyled text-muted">
                    <li>• <strong>État de paiement</strong> - Situation par classe</li>
                    <li>• Les reçus individuels sont dans la section Paiements</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
