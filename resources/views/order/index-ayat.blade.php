@extends('layout.app')
@section('title', 'order list')
@section('content')
    {{-- <div class="max-w-7xl mx-auto px-4 py-8"> --}}
    <div>
        <div>
            <!-- Mexico Button -->
            <a href="{{ route('order.index.mexico', ['page' => 1]) }}"
                {{-- class="px-4 py-2 rounded text-md border bg-sky-500 text-white cursor-default"> --}}
                class="px-4 py-2 rounded text-md border-sky-500 bg-white text-sky-500 cursor-default">

                Mexico
            </a>
            <!-- Ayat Button -->
            <a href="{{ route('order.index.ayat', ['page' => 1]) }}"
                {{-- class="px-4 py-2 rounded text-md border-sky-500 bg-white text-sky-500 cursor-default"> --}}
                class="px-4 py-2 rounded text-md border bg-sky-500 text-white cursor-default">

                Ayat
            </a>
        </div>
        <div class="flex justify-end m-4">
            <div>
                <a class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded text-md"
                    href= "{{ route('order.create.mexico') }}">
                    + new order
                </a>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">pending List</h2>
        </div>

        @if (count($pendings) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($pendings as $order)
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
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">processing List</h2>
        </div>

        @if (count($processings) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($processings as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $order['id'] }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order['billing']['first_name'] ?? 'Guest' }}
                                {{ $order['billing']['last_name'] ?? '' }}
                                {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                {{ $order['billing']['phone'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">cancelled List</h2>
        </div>

        @if (count($cancelleds) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($cancelleds as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $order['id'] }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order['billing']['first_name'] ?? 'Guest' }}
                                {{ $order['billing']['last_name'] ?? '' }}
                                {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                {{ $order['billing']['phone'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">failed List</h2>
        </div>

        @if (count($faileds) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($failds as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $order['id'] }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order['billing']['first_name'] ?? 'Guest' }}
                                {{ $order['billing']['last_name'] ?? '' }}
                                {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                {{ $order['billing']['phone'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">on-hold List</h2>
        </div>

        @if (count($on_holds) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($on_holds as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $order['id'] }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order['billing']['first_name'] ?? 'Guest' }}
                                {{ $order['billing']['last_name'] ?? '' }}
                                {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                {{ $order['billing']['phone'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    <div class="bg-white rounded-md shadow-sm border m-4">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">completed  List</h2>
        </div>

        @if (count($completeds) > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment
                            link </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($completeds as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $order['id'] }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order['billing']['first_name'] ?? 'Guest' }}
                                {{ $order['billing']['last_name'] ?? '' }}
                                {{-- {{ $order['billing']['phone'] ?? '' }} --}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{-- {{ $order['billing']['first_name'] ?? 'Guest' }} {{ $order['billing']['last_name'] ?? '' }} --}}
                                {{ $order['billing']['phone'] ?? '' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
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
                                {{-- <button onclick="copyToClipboard()" class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Copy Link
                                </button> --}}
                                <a href="{{ $order['payment_url'] }}" target="_blank"
                                    class="ml-2 px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
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
    

    {{-- </div> --}}
@endsection
