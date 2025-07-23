<div>
    <h1>Payment</h1>
    <p>Click the link below to make a payment:</p>
    <a href="{{ $payment_url }}">
pay
    </a>
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
