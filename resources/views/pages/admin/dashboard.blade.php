@extends('layouts.master')
@section('page_title', 'Mon tableau de bord')

@section('content')
    <h2>BIENVENUE {{ Auth::user()->name }}. Ceci est votre TABLEAU DE BORD</h2>
    @endsection