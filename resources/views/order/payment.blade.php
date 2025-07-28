@extends('layout.new-order')

@section('content')
    <div class="flex justify-center items-center min-h-screen bg-gray-100">
        <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-md text-center">
            <h1 class="text-2xl font-bold mb-4 text-blue-700">Payment</h1>
            <div class="mb-4">
                <span class="block text-lg font-semibold text-gray-700">Order Number:</span>
                <span class="text-xl text-gray-900">{{ $order_number }}</span>
            </div>
            <div class="mb-6">
                <span class="block text-lg font-semibold text-gray-700">Total Amount:</span>
                <span class="text-2xl text-green-600 font-bold">{{ $total_amount }} ETB</span>
            </div>
            <p class="mb-2 text-gray-600">Click the button below to make a payment:</p>
            <a href="{{ $payment_url }}" target="_blank" rel="noopener noreferrer"
               class="inline-block w-full py-3 px-6 mb-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-lg shadow transition duration-150">Proceed to Chapa</a>
            <div class="mb-2 text-gray-500 text-sm break-all">{{ $payment_url }}</div>
            <button onclick="copyToClipboard()" class="mt-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-gray-700 font-medium">Copy Payment Link</button>
        </div>
        <script>
            function copyToClipboard() {
                const paymentUrl = "{{ $payment_url }}";
                navigator.clipboard.writeText(paymentUrl).then(() => {
                    alert('Payment link copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            }
        </script>
    </div>
@endsection
