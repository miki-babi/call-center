@extends('layout.new-order')

@section('content')

    <x-product :products="$allProducts" />
    <x-map shop="mexico" lat="9.009907758314755" lon="38.75883353263652" />
    <x-fields />

@endsection