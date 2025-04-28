<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Gallery - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- LazySizes Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
</head>
<body class="antialiased">
    <div class="min-h-screen">
        <!-- Navigation (optional) -->
        @if (!request()->routeIs('tutorial'))
        <nav class="bg-white shadow">
            <div class="container mx-auto px-4 py-4">
                <a href="/" class="text-xl font-bold">Image Gallery</a>
            </div>
        </nav>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Footer (optional) -->
    <footer class="bg-gray-100 mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-600">
            Made with ❤️ by Rara AI
        </div>
    </footer>
</body>
</html>