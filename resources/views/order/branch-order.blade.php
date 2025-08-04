@extends('layout.app')

@section('content')
    <div x-data="{ tab: 'all' }" class="space-y-4 w-full mx-auto mt-8">
        <!-- Radio Buttons as Styled Buttons -->
        <div class="flex flex-wrap gap-2 justify-center">
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'all' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="all" x-model="tab" class="hidden" />
                All
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'pending' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="pending" x-model="tab" class="hidden" />
                Pending
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'completed' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="completed" x-model="tab" class="hidden" />
                Completed
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'processing' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="processing" x-model="tab" class="hidden" />
                Processing
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'onhold' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="onhold" x-model="tab" class="hidden" />
                On-Hold
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'failed' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="failed" x-model="tab" class="hidden" />
                Failed
            </label>
            <label class="px-4 py-2 rounded-lg border cursor-pointer shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 hover:shadow-md"
                :class="tab === 'shipmentReady' ? 'bg-blue-600 text-white border-blue-700 scale-105 font-semibold' : 'bg-gray-100 text-gray-800'">
                <input type="radio" name="tabs" value="shipmentReady" x-model="tab" class="hidden" />
                Shipment-ready
            </label>
        </div>

        <!-- Tab Content -->
        <div class="  shadow-lg p-6 mt-4">
            <div x-show="tab === 'all'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-gray-800">All Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                    <input type="text" id="allsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are all orders from all branches and statuses:</p>
                    <ul class="space-y-4">
                        @foreach (array_merge(
                            $pendingOrders->toArray(),
                            $completedOrders->toArray(),
                            $processingOrders->toArray(),
                            $onHoldOrders->toArray(),
                            $failedOrders->toArray(),
                            $shipmentReadyOrders->toArray()
                        ) as $order)
                            <li class="all-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700">{{ ucfirst($order['status']) }}</span>
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
                    <script>
                        const allSearchInput = document.getElementById('allsearch');
                        const allListItems = document.querySelectorAll('.all-order-item');
                        allSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            allListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'pending'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-blue-700 flex items-center gap-2"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Pending Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                    <input type="text" id="pendingsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($pendingOrders as $order)
                            <li class="pending-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700">Pending</span>
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
                    <script>
                        const pendingSearchInput = document.getElementById('pendingsearch');
                        const pendingListItems = document.querySelectorAll('.pending-order-item');
                        pendingSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            pendingListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'completed'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-green-700 flex items-center gap-2"><svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Completed Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                    <input type="text" id="completedsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($completedOrders as $order)
                            <li class="completed-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">Completed</span>
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
                    <script>
                        const completedSearchInput = document.getElementById('completedsearch');
                        const completedListItems = document.querySelectorAll('.completed-order-item');
                        completedSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            completedListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'processing'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-yellow-700 flex items-center gap-2"><svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Processing Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                    <input type="text" id="processingsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($processingOrders as $order)
                            <li class="processing-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">Processing</span>
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
                    <script>
                        const processingSearchInput = document.getElementById('processingsearch');
                        const processingListItems = document.querySelectorAll('.processing-order-item');
                        processingSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            processingListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'onhold'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-orange-700 flex items-center gap-2"><svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>On-Hold Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                <input type="text" id="onholdsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($onHoldOrders as $order)
                            <li class="onhold-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700">On-Hold</span>
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
                    <script>
                        const onholdSearchInput = document.getElementById('onholdsearch');
                        const onholdListItems = document.querySelectorAll('.onhold-order-item');
                        onholdSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            onholdListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'failed'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-red-700 flex items-center gap-2"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Failed Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                <input type="text" id="failedsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($failedOrders as $order)
                            <li class="failed-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">Failed</span>
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
                    <script>
                        const failedSearchInput = document.getElementById('failedsearch');
                        const failedListItems = document.querySelectorAll('.failed-order-item');
                        failedSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            failedListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
            <div x-show="tab === 'shipmentReady'" class="p-2 border rounded bg-gray-50">
                <h2 class="text-xl font-bold mb-2 text-indigo-700 flex items-center gap-2"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h2l1 2h13a1 1 0 01.9 1.45l-3 6A1 1 0 0116 20H7a1 1 0 01-.9-1.45L9 14H5a1 1 0 01-1-1v-2a1 1 0 011-1z"/></svg>Shipment Ready Orders</h2>
                <div class="relative mb-4 max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg></span>
                <input type="text" id="shipmentsearch" placeholder="Search orders..."
                        class="pl-10 p-2 border border-gray-300 rounded-lg w-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm transition-all duration-150">
                </div>
                <div class="bg-grey-400 text-white p-4 rounded-xl shadow-md mt-4 ">
                    <h1 class="text-2xl font-bold mb-4">Orders</h1>
                    <p class="mb-4 text-black">Here are the orders from all branches:</p>
                    <ul class="space-y-4">
                        @foreach ($shipmentReadyOrders as $order)
                            <li class="shipment-order-item bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold">Order #{{ $order['number'] }}</span>
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700">Shipment Ready</span>
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
                    <script>
                        const shipmentSearchInput = document.getElementById('shipmentsearch');
                        const shipmentListItems = document.querySelectorAll('.shipment-order-item');
                        shipmentSearchInput.addEventListener('input', function() {
                            const searchValue = this.value.toLowerCase();
                            shipmentListItems.forEach(item => {
                                const text = item.textContent.toLowerCase();
                                item.style.display = text.includes(searchValue) ? 'flex' : 'none';
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
