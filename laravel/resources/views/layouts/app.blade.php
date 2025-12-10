<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WTCS Paddock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans antialiased">

    <!-- Navbar (Borrador) -->
    <nav class="bg-red-600 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold tracking-wider uppercase">WTCS Paddock</a>
            <div class="hidden md:flex space-x-6">
                <a href="#" class="hover:text-gray-300">Drivers</a>
                <a href="#" class="hover:text-gray-300">Teams</a>
                <a href="#" class="hover:text-gray-300">Standings</a>
                <a href="/admin" class="bg-black px-4 py-2 rounded text-sm font-bold hover:bg-gray-800 transition">Login</a>
            </div>
        </div>
    </nav>

    <!-- Contenido Variable -->
    <main class="container mx-auto py-8 px-4">
        @yield('content')
    </main>

    <footer class="text-center text-gray-500 py-6 text-sm">
        &copy; 2025 WTCS Paddock - SimRacing League
    </footer>

</body>
</html>