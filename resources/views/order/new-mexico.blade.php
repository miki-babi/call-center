@extends('layout.new-order')

@section('content')

    <x-product :products="$allProducts" :delivery-options="$deliveryOptions"/>
    <x-map shop="mexico" lat="9.009907758314755" lon="38.75883353263652" :delivery-options="$deliveryOptions" />
    <x-fields :delivery-options="$deliveryOptions" branch="mexico"/>



@endsection