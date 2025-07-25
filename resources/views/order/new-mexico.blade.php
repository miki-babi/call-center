@extends('layout.new-order')

@section('content')
    <!-- Leaflet CSS -->
   


        <x-product :products="$allProducts"/>

        <x-map shope="mexico"/>
     <x-fields/>

    @endsection
