<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WooCommerce Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-sm text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold">Orders</h1>
            {{-- You can add user info or logout here --}}
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-md shadow-sm border">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Order List</h2>
            </div>

            @if (count($orders) > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment link </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    #{{ $order['id'] }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }}
                                    {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                    {{ $order['billing']['phone'] ?? '' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $order['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($order['status']) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order['currency'] }} {{ number_format($order['total'], 2) }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($order['date_created'])->format('M d, Y H:i') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:underline">
                                    <a href="#">View</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @php
                                        $shortLink = Str::limit($order['payment_url'] ?? '', 20, '...');
                                    @endphp
                                    <span title="{{ $order['payment_url'] ?? '' }}">{{ $shortLink }}</span>
                                    
                                    <a href="{{ $order['payment_url']  }}" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        proceed to payment
                                    </a>
                                    

                                    <script>
                                        function copyToClipboard() {
                                            const paymentUrl = "{{ $order['payment_url'] ?? '' }}";
                                            navigator.clipboard.writeText(paymentUrl).then(() => {
                                                alert('Payment link copied to clipboard!');
                                            }).catch(err => {
                                                console.error('Failed to copy: ', err);
                                            });
                                        }
                                    </script>
                                </td>



                                
    
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    <p class="text-gray-500">No orders found.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- Footer (Optional) -->
    <footer class="text-center text-xs text-gray-400 py-4">
        &copy; {{ date('Y') }} WooClone. All rights reserved.
    </footer>

</body>
</html>
