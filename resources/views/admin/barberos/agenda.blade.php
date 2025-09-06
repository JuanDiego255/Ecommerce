@extends('layouts.admin')

@section('metatag')
    {!! SEOMeta::generate() !!}{!! OpenGraph::generate() !!}
@endsection

@section('content')
    @include('admin.barberos.partials._excepciones', ['barbero' => $barbero])
    @include('admin.barberos.partials._bloques', ['barbero' => $barbero])
@endsection
