@extends('layout.app')

@section('content')

<div>
    <div>
        <a role="tab" class="tab {{ request()->is('shop/orders/mexico') ? 'tab-active' : '' }} "
                href="{{ route('orders.fetch', ['name' => 'mexico']) }}">Mexico</a>
            <a role="tab" class="tab {{ request()->is('shop/orders/ayat') ? 'tab-active' : '' }} "
                href="{{ route('orders.fetch', ['name' => 'ayat']) }}">Ayat</a>
    </div>
</div>

@endsection