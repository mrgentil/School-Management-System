@extends('layouts.master')
@section('page_title', 'Profil archivé')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-semibold">Profil étudiant archivé</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body text-center py-5">
            <i class="icon-user-lock icon-3x text-muted mb-3"></i>
            <h5 class="mb-2">Ce profil étudiant a été archivé ou n'est plus disponible.</h5>
            <p class="text-muted mb-4">
                Il se peut que l'étudiant ait été diplômé, supprimé ou que son enregistrement ne soit plus actif pour la session courante.
            </p>

            <a href="{{ url()->previous() }}" class="btn btn-primary">
                <i class="icon-arrow-left13 mr-1"></i>Retour
            </a>
        </div>
    </div>

@endsection
