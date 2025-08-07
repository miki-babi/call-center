@props(['branch'])
<form method="POST" action="{{ route('orders.place.new') }}">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block font-medium">First Name:</label>
            <input type="text" name="first_name" required class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Last Name:</label>
            <input type="text" name="last_name" required class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Email:</label>
            <input type="email" name="email" required class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Phone:</label>
            <input type="text" name="phone" class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Address:</label>
            <input type="text" name="address_1" id="address_1" required
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">City:</label>
            <input type="text" name="city" id="city" required
                class="w-full border border-gray-300 rounded-lg p-2" value="Addis Ababa">
        </div>
        <div>
            <label class="block font-medium">State:</label>
            <input type="text" name="state" id="state" value="notset"
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Postcode:</label>
            <input type="text" name="postcode" id="postcode" required
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>
        <div>
            <label class="block font-medium">Country:</label>
            <select name="country" required class="w-full border border-gray-300 rounded-lg p-2">
                <option value="ET" selected>Ethiopia</option>
            </select>
        </div>
        <div>
            <label class="block font-medium">Delivery Method:</label>
            <select name="delivery_method" id="delivery_method" required class="w-full border border-gray-300 rounded-lg p-2">
                <option value="self_pickup">Self Pickup</option>
                <option value="same_day_delivery">Same Day Delivery</option>
                <option value="next_day_delivery">Next Day Delivery</option>
            </select>
        </div>
        <input type="hidden" name="delivery_price" id="delivery_price" value="">
        <input type="hidden" name="products" id="products" value="">
        <input type="hidden" name="branch" id="brach" value="{{ $branch }}">

        <button type="submit" class="p-4 bg-blue-500 text-white rounded-sm ">
            Place order
        </button>
    </div>

</form>

<script>
    // Handle delivery method change
    document.getElementById('delivery_method').addEventListener('change', function() {
        const deliveryMethod = this.value;
        
        if (deliveryMethod === 'self_pickup') {
            // Set delivery price to 0 for self pickup
            window.deliveryCost = 0;
            
            // Update the hidden input
            document.getElementById('delivery_price').value = '0';
            
            // Update cart display if it exists
            if (typeof renderCart === 'function') {
                renderCart();
            }
            
            // Show notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
            notification.textContent = 'Self pickup selected - No delivery charge';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    });
</script>
