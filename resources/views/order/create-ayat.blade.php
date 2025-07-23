@extends('layout.app')
@section('title', 'new order')
@section('content')
    <div>
        <div>
            <!-- Mexico Button -->
            <a href="{{ route('order.create.mexico') }}"
                class="px-4 py-2 rounded text-md border-sky-500 bg-white text-sky-500 cursor-default">
                Mexico
            </a>
            <!-- Ayat Button -->
            <a href="{{ route('order.create.ayat') }}"
                class="px-4 py-2 rounded text-md border bg-sky-500 text-white cursor-default">
                Ayat
            </a>
        </div>

        <div class="container mx-auto p-6">
            <h2 class="text-2xl font-bold mb-6">Create New Order</h2>
            <form action="{{ route('AY_order') }}" method="post" class="bg-white p-6 rounded-lg shadow-md">
                @csrf
                <!-- Billing Info -->
                <h3 class="text-xl font-semibold mb-4">Billing Info</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">First Name:</label>
                        <input type="text" name="first_name" required
                            class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block font-medium">Last Name:</label>
                        <input type="text" name="last_name" required
                            class="w-full border border-gray-300 rounded-lg p-2">
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
                        <input type="text" name="address_1" required
                            class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block font-medium">City:</label>
                        <input type="text" name="city" required class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block font-medium">State:</label>
                        <input type="text" name="state" value="notset"
                            class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block font-medium">Postcode:</label>
                        <input type="text" name="postcode" required class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block font-medium">Country:</label>
                        <select name="country" required class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="SZ">Eswatini</option>
                            <option value="ET" selected>Ethiopia</option>
                        </select>
                    </div>
                </div>

                <!-- Order Details -->
                <h3 class="text-xl font-semibold mt-6 mb-4">Order Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">Payment Method:</label>
                        <select name="payment_method" id="payment_method" required onchange="updatePaymentMethodTitle()"
                            class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="Chapa" selected>Chapa</option>
                            <option value="kacha">Kacha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium">Payment Method Title:</label>
                        <select name="payment_method_title" id="payment_method_title" required
                            class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="Chapa payment method" selected>Chapa</option>
                            <option value="Kacha payment method">Kacha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium">Set Paid:</label>
                        <select name="set_paid" required class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="true">Yes</option>
                            <option value="false" selected>No</option>
                        </select>
                    </div>
                </div>

                <!-- Line Items -->
                <h3 class="text-xl font-semibold mt-6 mb-4">Line Items</h3>
                <button type="button" onclick="openModal()"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Search & Add Product</button>

                <table id="productsTable" class="w-full mt-4 border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Product Name</th>
                            <th class="p-2 border">Product ID</th>
                            <th class="p-2 border">Quantity</th>
                            <th class="p-2 border">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamically added rows go here -->
                    </tbody>
                </table>

                <button type="submit" class="mt-6 bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">Create
                    Order</button>
            </form>
        </div>



    </div>
   <!-- Product Search Modal -->
    <div id="searchProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white w-1/2 p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Search Product</h3>
            <form id="searchProductForm" onsubmit="return false;">
                <label for="productName" class="block font-medium">Product Name:</label>
                <input type="text" id="productName" name="product_name" required oninput="debouncedSearch()"
                    class="w-full border border-gray-300 rounded-lg p-2 mt-2 mb-4">
                <button type="button" onclick="closeModal()"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Close</button>
            </form>
            <div id="searchResults" class="mt-4"></div>
        </div>
    </div>


    <script>
        function openModal() {
            document.getElementById('searchProductModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('searchProductModal').style.display = 'none';
            document.getElementById('productName').value = '';
            document.getElementById('searchResults').innerHTML = '';
        }

        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            }
        }

        function searchProduct() {
            const productName = document.getElementById('productName').value.trim();

            if (!productName) {
                document.getElementById('searchResults').innerHTML = '';
                return;
            }

            document.getElementById('searchResults').innerHTML = '<p>Loading...</p>';

            fetch(`{{ route('AY_product.search', '') }}/${encodeURIComponent(productName)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not OK');
                    return response.json();
                })
                .then(data => {
                    const resultsContainer = document.getElementById('searchResults');

                    if (!data || data.length === 0 || data.message === 'No products found') {
                        resultsContainer.innerHTML = `<p>No products found for "<strong>${productName}</strong>".</p>`;
                    } else {
                        let resultHTML =
                            `<p>Results for "<strong>${productName}</strong>":</p><ul style="list-style:none; padding:0;">`;

                        data.forEach(product => {
                            const imageUrl = product.images.length ? product.images[0].src :
                                'https://via.placeholder.com/100?text=No+Image';
                            const priceHtml = product.price_html || 'Price not available';

                            resultHTML += `
  <li style="margin-bottom:15px; display:flex; align-items:center; justify-content:space-between;">
<div style="display:flex; align-items:center;">
  <img src="${imageUrl}" alt="${product.name}" style="max-width:80px; margin-right:10px; border:1px solid #ccc; padding:4px;">
  <div>
    <strong>${product.name}</strong><br>
    <span>${priceHtml}</span>
  </div>
</div>
<button type="button" onclick="addProductToOrder(${product.id}, '${product.name}')" 
    style="margin-left:10px; padding:6px 12px; background-color:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">
  Select
</button>
  </li>
`;
                        });

                        resultHTML += '</ul>';
                        resultsContainer.innerHTML = resultHTML;
                    }
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    document.getElementById('searchResults').innerHTML =
                        '<p style="color:red;">Error fetching products. Please try again later.</p>';
                });
        }

        const debouncedSearch = debounce(searchProduct, 1000);

        // Add product to order table
        function addProductToOrder(productId, productName) {
            const tableBody = document.querySelector('#productsTable tbody');

            // Check if product already added
            const existingRow = tableBody.querySelector(`tr[data-product-id="${productId}"]`);
            if (existingRow) {
                alert('Product already added!');
                return;
            }

            const row = document.createElement('tr');
            row.setAttribute('data-product-id', productId);

            row.innerHTML = `
<td>${productName}</td>
<td>
${productId}
<input type="hidden" name="product_id[]" value="${productId}">
</td>
<td>
<input type="number" name="quantity[]" value="1" min="1" style="width:60px;" required>
</td>
<td>
<button type="button" onclick="removeProduct(this)" 
        style="background-color:red; color:white; border:none; padding:6px 12px; border-radius:4px;">Remove</button>
</td>
`;

            tableBody.appendChild(row);
            closeModal();
        }

        function removeProduct(button) {
            const row = button.closest('tr');
            row.remove();
        }
    </script>
    </div>
@endsection
