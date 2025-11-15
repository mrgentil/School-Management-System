@extends('layouts.master')
@section('page_title', 'Gérer l\'Emploi du Temps')
@section('content')

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title font-weight-bold">{{ $ttr->name.' ('.$my_class->name.')'.' '.$ttr->year }}</h6>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#manage-ts" class="nav-link active" data-toggle="tab">⏰ Gérer les Créneaux Horaires</a></li>
                <li class="nav-item"><a href="#add-sub" class="nav-link" data-toggle="tab">➕ Ajouter une Matière</a></li>
                <li class="nav-item"><a href="#edit-subs" class="nav-link " data-toggle="tab">✏️ Modifier les Matières</a></li>
                <li class="nav-item"><a href="#import-excel" class="nav-link" data-toggle="tab">📥 Import/Export Excel</a></li>
                <li class="nav-item"><a target="_blank" href="{{ route('ttr.show', $ttr->id) }}" class="nav-link" >👁️ Voir l'Emploi du Temps</a></li>
            </ul>

            <div class="tab-content">
                {{--Add Time Slots--}}
                @include('pages.support_team.timetables.time_slots.index')
                {{--Add Subject--}}
                @include('pages.support_team.timetables.subjects.add')
                {{--Edit Subject--}}
                @include('pages.support_team.timetables.subjects.edit')
                {{--Import/Export Excel--}}
                @include('pages.support_team.timetables.import_excel')
            </div>
        </div>
    </div>

    {{--TimeTable Manage Ends--}}

@endsection
