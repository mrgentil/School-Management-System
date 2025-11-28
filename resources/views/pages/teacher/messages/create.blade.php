@extends('layouts.master')
@section('page_title', 'Nouveau message')

@section('content')
    @php $routePrefix = 'teacher'; @endphp
    @include('partials.messages.create', [
        'recipients' => $recipients,
        'routePrefix' => $routePrefix,
        'showBulkOptions' => false
    ])
@endsection
