@extends('layouts.master')
@section('page_title', 'Messagerie')

@section('content')
    @php $routePrefix = 'teacher'; @endphp
    @include('partials.messages.index', [
        'messages' => $messages,
        'filter' => $filter,
        'unreadCount' => $unreadCount,
        'sentCount' => $sentCount,
        'inboxCount' => $inboxCount,
        'routePrefix' => $routePrefix
    ])
@endsection
