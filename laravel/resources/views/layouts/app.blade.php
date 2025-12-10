<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WTCS Paddock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans antialiased flex flex-col min-h-screen">

    <!-- Navbar Responsive con Alpine.js -->
    <nav x-data="{ open: false }" class="bg-red-600 shadow-lg relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo -->
                <a href="/" class="text-2xl font-bold tracking-wider uppercase flex items-center gap-2">
                    <span class="text-white">WTCS</span>
                    <span class="bg-black text-white text-xs px-1 py-0.5 rounded font-mono">PADDOCK</span>
                </a>

                <!-- Menú Escritorio (Hidden en móvil) -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('calendar') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Calendar</a>
                    <a href="{{ route('standings') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Standings</a>
                    <a href="{{ route('drivers') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Drivers</a>
                    <a href="{{ route('teams') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Teams</a>
                    <a href="{{ route('news.index') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">News</a>
                    
                    <a href="/admin" class="bg-black text-white px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition border border-gray-700">
                        Login
                    </a>
                </div>

                <!-- Botón Hamburguesa (Visible solo en móvil) -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg x-show="!open" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú Móvil Desplegable -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-red-700 border-t border-red-500 absolute w-full left-0 shadow-xl"
             style="display: none;">
            
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('calendar') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800 transition">Calendar</a>
                <a href="{{ route('standings') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800 transition">Standings</a>
                <a href="{{ route('drivers') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800 transition">Drivers</a>
                <a href="{{ route('teams') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800 transition">Teams</a>
                <a href="{{ route('news.index') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800 transition">News</a>
                
                <div class="border-t border-red-500 my-2 pt-2">
                    <a href="/admin" class="block px-3 py-3 rounded-md text-base font-bold text-white bg-black/20 hover:bg-black/40 text-center">
                        ADMIN LOGIN
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ESTO ES LO QUE FALTABA -->
    <main class="container mx-auto py-8 px-4 flex-grow">
        @yield('content')
    </main>

    <footer class="text-center text-gray-500 py-6 text-sm">
        &copy; 2025 WTCS Paddock - SimRacing League
    </footer>

</body>
</html>