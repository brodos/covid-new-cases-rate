<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Covid New Case Rate</title>
    <link rel="stylesheet" href="/css/app.css">
    @livewireStyles
</head>
<body class="bg-gray-900 text-white min-h-screen flex antialiased flex flex-col">
    <main class="py-8 flex-1">
        @yield('content')
    </main>

    <footer class="mt-auto py-4">
        <p class="text-center text-gray-600 text-sm">
            Created by <a href="https://brodos.ro" target="_blank" rel="noopener noreferer" class="text-gray-100">@brodos</span>
        </p>
    </footer>
    @livewireScripts
    @stack('scripts')
</body>
</html>
