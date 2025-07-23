@extends('layout.app')

@section('content')
    <div class="w-full w-full mx-auto mt-8">
        <div class="flex flex-col gap-4">
           <div class="bg-white text-gray-900 p-6 rounded-2xl shadow-lg border border-gray-200 mt-4">
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                    <input type="text" id="search" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <h1 class="text-3xl font-extrabold mb-6 text-blue-700">Orders</h1>
                <p class="mb-6 text-gray-500">Here are the orders from all branches:</p>
                @foreach ($allOrders as $shopOrders)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold mb-4 px-2 py-1 bg-blue-50 rounded-lg inline-block border border-blue-100 shadow-sm">{{ $shopOrders['shop'] }}</h2>
                        <ul class="space-y-4">
                            @foreach ($shopOrders['orders'] as $order)
                                @php
                                    $status = $order['status'];
                                    $statusColors = [
                                        'completed' => 'bg-green-100 text-green-700',
                                        'processing' => 'bg-yellow-100 text-yellow-700',
                                        'pending' => 'bg-blue-100 text-blue-700',
                                        'on-hold' => 'bg-orange-100 text-orange-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        'shipment-ready' => 'bg-indigo-100 text-indigo-700',
                                    ];
                                    $badgeClass = $statusColors[$status] ?? 'bg-gray-200 text-gray-700';
                                @endphp
                                <li class="order-item bg-white text-gray-900 rounded-xl shadow hover:shadow-lg transition-all duration-150 p-6 flex flex-col gap-2 border border-gray-100">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="font-semibold text-lg">Order #{{ $order['number'] }}</span>
                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $badgeClass }} capitalize">{{ str_replace('-', ' ', $order['status']) }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                                        <div><span class="font-semibold">Customer:</span> {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }}</div>
                                        <div><span class="font-semibold">Phone:</span> {{ $order['billing']['phone'] ?? '-' }}</div>
                                        <div><span class="font-semibold">Date:</span> {{ \Carbon\Carbon::parse($order['date_created'])->format('M d, Y H:i') }}</div>
                                        <div><span class="font-semibold">Total:</span> {{ $order['currency'] ?? 'ETB' }} {{ number_format($order['total'], 2) }}</div>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Products:</span>
                                        <ul class="list-disc list-inside ml-2">
                                            @foreach ($order['line_items'] as $item)
                                                <li>{{ $item['name'] }} <span class="text-xs text-gray-500">x{{ $item['quantity'] }}</span></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if (!$loop->last)
                        <hr class="my-8 border-t border-gray-200">
                    @endif
                @endforeach
            </div>
        </div>
        <script>
            const searchInput = document.getElementById('search');
            const listItems = document.querySelectorAll('.order-item');
            searchInput.addEventListener('input', function() {
                const searchValue = this.value.toLowerCase();
                listItems.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                });
            });
        </script>
    </div>
@endsection
