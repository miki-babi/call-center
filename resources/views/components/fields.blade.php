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
       <button type="submit" class="p-4 bg-blue-500 text-white rounded-sm ">
           Orders
       </button>
   </div>

</form>