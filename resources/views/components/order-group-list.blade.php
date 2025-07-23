{{-- resources/views/components/order-group-list.blade.php --}}
@props(['label', 'orders', 'badgeColor', 'itemClass'])

<h3 class="font-bold mt-4 mb-2 {{ $badgeColor }}">{{ $label }}</h3>
<ul class="space-y-4">
    @forelse ($orders as $order)
        <li class="{{ $itemClass }} bg-white text-gray-900 rounded-lg shadow hover:shadow-lg transition-all duration-150 p-4 flex flex-col gap-2">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="font-semibold">Order #{{ $order['number'] }}</span>
                @php
                    $status = $order['status'];
                    $statusMap = [
                        'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                        'processing' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                        'on-hold' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                        'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-700'],
                        'shipment-ready' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700'],
                    ];
                    $badge = $statusMap[$status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-700'];
                @endphp
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $badge['bg'] }} {{ $badge['text'] }}">{{ ucfirst($status) }}</span>
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
    @empty
        <li class="text-gray-400 italic">No orders for this period.</li>
    @endforelse
</ul>