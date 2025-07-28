@extends('layout.new-order')

@section('content')
    <div>
        <h1>Payment</h1>
        <p><strong>Order Number:</strong> {{ $order_number }}</p>
        <p><strong>Total Amount:</strong> {{ $total_amount }} ETB</p>
        <p>Click the link below to make a payment:</p>
        <a href="{{ $payment_url }}">
            pay
        </a>
        <br>
        <a href="{{ $payment_url }}" class="btn btn-primary" style="display:inline-block;padding:10px 20px;background:#2563eb;color:#fff;border-radius:5px;text-decoration:none;margin-top:10px;">Proceed to Chapa</a>
        <br>
        {{ $payment_url }}
        <button onclick="copyToClipboard()">Copy Link</button>

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
