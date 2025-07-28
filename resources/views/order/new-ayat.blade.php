@extends('layout.new-order')

@section('content')

    <x-product :products="$allProducts" />
    <x-map shop="Ayat-Store" lat="9.023810040605607" lon="38.897535922518266" :delivery-options="$deliveryOptions"/>
    <x-fields :delivery-options="$deliveryOptions" branch="ayat"/>

@endsection
