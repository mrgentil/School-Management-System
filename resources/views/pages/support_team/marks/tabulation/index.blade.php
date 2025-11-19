@extends('layouts.master')
@section('page_title', 'Feuille de Tabulation')
@section('content')

    {{-- Menu Rapide --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <a href="{{ route('exam_analytics.index') }}" class="btn btn-success btn-block">
                <i class="icon-stats-dots mr-2"></i>Analyses & Rapports
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('exam_schedules.index') }}" class="btn btn-primary btn-block">
                <i class="icon-calendar mr-2"></i>Calendrier d'Examens
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('marks.index') }}" class="btn btn-info btn-block">
                <i class="icon-pencil5 mr-2"></i>Saisir les Notes
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('exams.dashboard') }}" class="btn btn-warning btn-block">
                <i class="icon-grid mr-2"></i>Tableau de Bord Examens
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title"><i class="icon-table2 mr-2"></i> Feuille de Tabulation</h5>
            {!! Qs::getPanelOptions() !!}
        </div>

        <div class="card-body">
            <div class="alert alert-info">
                <h6><i class="icon-info22 mr-2"></i>Comment utiliser la Feuille de Tabulation :</h6>
                <ul class="mb-0">
                    <li><strong>Sélectionnez un examen</strong> - Choisissez l'examen pour lequel vous voulez voir les résultats</li>
                    <li><strong>Choisissez une classe</strong> - Sélectionnez la classe concernée</li>
                    <li><strong>Sélectionnez une section</strong> - Choisissez la section/division de la classe</li>
                    <li><strong>Cliquez sur "Afficher la Feuille"</strong> - Le tableau avec toutes les notes apparaîtra</li>
                </ul>
                <small class="text-muted"><strong>Note :</strong> Des notes doivent avoir été saisies au préalable pour que la feuille s'affiche.</small>
            </div>
            
        <form method="post" action="{{ route('marks.tabulation_select') }}">
                    @csrf
                    <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exam_id" class="col-form-label font-weight-bold">Examen :</label>
                                            <select required id="exam_id" name="exam_id" class="form-control select" data-placeholder="Sélectionner un examen">
                                                @foreach($exams as $exm)
                                                    <option {{ ($selected && $exam_id == $exm->id) ? 'selected' : '' }} value="{{ $exm->id }}">{{ $exm->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="my_class_id" class="col-form-label font-weight-bold">Classe :</label>
                                            <select onchange="getClassSections(this.value)" required id="my_class_id" name="my_class_id" class="form-control select" data-placeholder="Sélectionner une classe">
                                                <option value=""></option>
                                                @foreach($my_classes as $c)
                                                    <option {{ ($selected && $my_class_id == $c->id) ? 'selected' : '' }} value="{{ $c->id }}">{{ $c->full_name ?: $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id" class="col-form-label font-weight-bold">Section :</label>
                                <select required id="section_id" name="section_id" data-placeholder="Sélectionner d'abord une classe" class="form-control select">
                                    @if($selected)
                                        @foreach($sections->where('my_class_id', $my_class_id) as $s)
                                            <option {{ $section_id == $s->id ? 'selected' : '' }} value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>


                        <div class="col-md-2 mt-4">
                            <div class="text-right mt-1">
                                <button type="submit" class="btn btn-primary">Afficher la Feuille <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </div>

                    </div>

                </form>
        </div>
    </div>

    {{--Si une sélection a été faite --}}

    @if($selected)
        <div class="card">
            <div class="card-header">
                <h6 class="card-title font-weight-bold">Feuille de Tabulation pour {{ ($my_class->full_name ?: $my_class->name).' '.$section->name.' - '.$ex->name.' ('.$year.')' }}</h6>
            </div>
            <div class="card-body">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMS DES ÉTUDIANTS</th>
                       @foreach($subjects as $sub)
                       <th title="{{ $sub->name }}" rowspan="2">{{ strtoupper($sub->slug ?: $sub->name) }}</th>
                       @endforeach
                        {{--@if($ex->term == 3)
                        <th>TOTAL 1ER SEMESTRE</th>
                        <th>TOTAL 2ÈME SEMESTRE</th>
                        <th>TOTAL 3ÈME SEMESTRE</th>
                        <th style="color: darkred">TOTAL CUMULÉ</th>
                        <th style="color: darkblue">MOYENNE CUMULÉE</th>
                        @endif--}}
                        <th style="color: darkred">TOTAL</th>
                        <th style="color: darkblue">MOYENNE</th>
                        <th style="color: darkgreen">POSITION</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="text-align: center">{{ $s->user->name }}</td>
                            @foreach($subjects as $sub)
                            <td>{{ $marks->where('student_id', $s->user_id)->where('subject_id', $sub->id)->first()->$tex ?? '-' ?: '-' }}</td>
                            @endforeach

                            {{--@if($ex->term == 3)
                                --}}{{--1st term Total--}}{{--
                            <td>{{ Mk::getTermTotal($s->user_id, 1, $year) ?? '-' }}</td>
                            --}}{{--2nd Term Total--}}{{--
                            <td>{{ Mk::getTermTotal($s->user_id, 2, $year) ?? '-' }}</td>
                            --}}{{--3rd Term total--}}{{--
                            <td>{{ Mk::getTermTotal($s->user_id, 3, $year) ?? '-' }}</td>
                            @endif--}}

                            <td style="color: darkred">{{ $exr->where('student_id', $s->user_id)->first()->total ?: '-' }}</td>
                            <td style="color: darkblue">{{ $exr->where('student_id', $s->user_id)->first()->ave ?: '-' }}</td>
                            <td style="color: darkgreen">{!! Mk::getSuffix($exr->where('student_id', $s->user_id)->first()->pos) ?: '-' !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{--Bouton d'impression--}}
                <div class="text-center mt-4">
                    <a target="_blank" href="{{  route('marks.print_tabulation', [$exam_id, $my_class_id, $section_id]) }}" class="btn btn-danger btn-lg"><i class="icon-printer mr-2"></i> Imprimer la Feuille de Tabulation</a>
                </div>
            </div>
        </div>
    @endif
@endsection
