<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Covid New Case Rate</title>
    <link rel="stylesheet" href="/css/app.css">
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex antialiased">
    <main class="py-8 flex-1">
        @yield('content')
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
