<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-cafe-gradient min-h-screen font-sans antialiased text-gray-800">
    <div class="flex min-h-screen flex-col md:flex-row">
    <aside class="w-64 bg-white shadow-md flex-shrink-0 hidden md:block">
         @include('layouts.admin')
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-blue-50">
        @include('layouts.navigation')

        <main class="p-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>
</div>
    @stack('scripts')
</body>
</html>