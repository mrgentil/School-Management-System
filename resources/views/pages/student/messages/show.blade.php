@extends('layouts.master')
@section('page_title', 'Message - ' . $message->subject)

@section('content')
    @php $routePrefix = 'student'; @endphp
    @include('partials.messages.show', [
        'message' => $message,
        'conversation' => $conversation,
        'routePrefix' => $routePrefix
    ])
@endsection
