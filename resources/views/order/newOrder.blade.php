@extends('layout.app')

@section('content')

<div>
    <div>
<a role="tab" class="tab {{ request()->is('order/new/mexico') ? 'tab-active' : '' }}"
   href="{{ route('orders.new.branch', ['branch' => 'mexico']) }}">Mexico</a>

<a role="tab" class="tab {{ request()->is('order/new/ayat') ? 'tab-active' : '' }}"
   href="{{ route('orders.new.branch', ['branch' => 'ayat']) }}">Ayat</a>

    </div>
</div>

@endsection