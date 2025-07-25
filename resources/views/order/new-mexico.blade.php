@extends('layout.new-order')

@section('content')
    <!-- Leaflet CSS -->
   


        <x-product :products="$allProducts"/>

        <x-map shop="mexico"/>
     <x-fields/>

    @endsection
