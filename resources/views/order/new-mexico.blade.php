@extends('layout.new-order')

@section('content')
    <!-- Leaflet CSS -->
   


        <x-product :products="$allProducts"/>

        <x-map/>
     <x-fields/>

    @endsection
