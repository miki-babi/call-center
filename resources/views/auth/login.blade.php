<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Agent Login')</title>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"> --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="container mx-auto max-w-md p-6 bg-white shadow-md rounded-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
    <form action="{{ route('auth.login') }}" method="POST" class="space-y-4">
        @csrf
        <div class="form-group">
            <label for="phone" class="block text-sm font-medium text-black">Phone:</label>
            <input type="tel" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="password" class="block text-sm font-medium text-black">Password:</label>
            <input type="password" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="password" name="password" required>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Login</button>
    </form>
</div>
</body>
</html>
