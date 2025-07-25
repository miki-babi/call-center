@extends('layout.new-order')

@section('content')
    <!-- Leaflet CSS -->

    <x-product :products="$allProducts" />
    <x-map shop="mexico" lat="9.03" lon="38.74" />
    <x-fields />
    
@endsection
