@extends('layouts.design_ecommerce.frontmain')
@section('metatag')
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
@endsection
@php
    $address_tenant = '';
    $sinpe_name = '';
    switch ($tenantinfo->tenant) {
        case 'abril7cr':
        case 'aycfashion':
            $address_tenant = '';
            $sinpe_name = 'Ariel Valdivia';
            break;

        default:
            break;
    }
@endphp
@section('content')
    
@endsection
@section('scripts')
@endsection
