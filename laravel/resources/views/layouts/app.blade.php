<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WTCS Paddock</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans antialiased flex flex-col min-h-screen">

    <nav x-data="{ open: false }" class="bg-red-600 shadow-lg relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo -->
                <a href="/" class="text-2xl font-bold tracking-wider uppercase flex items-center gap-2">
                    <span class="text-white">WTCS</span>
                    <span class="bg-black text-white text-xs px-1 py-0.5 rounded font-mono">PADDOCK</span>
                </a>

                <!-- Menú Escritorio -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('calendar') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Calendar</a>
                    <a href="{{ route('standings') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Standings</a>
                    <a href="{{ route('drivers') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Drivers</a>
                    <a href="{{ route('teams') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">Teams</a>
                    <a href="{{ route('news.index') }}" class="text-white font-semibold hover:text-gray-200 transition text-sm uppercase tracking-wide">News</a>
                    
                    <!-- SELECTOR DE TEMPORADA (NUEVO) -->
                    @if(isset($allSeasons) && $allSeasons->count() > 1)
                        <div x-data="{ openSeason: false }" class="relative">
                            <button @click="openSeason = !openSeason" class="flex items-center text-white font-semibold hover:text-gray-200 transition text-xs uppercase tracking-wide bg-red-700/50 px-3 py-1 rounded border border-red-500/30">
                                <span>{{ $currentSeason->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="openSeason" @click.away="openSeason = false" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-700">
                                @foreach($allSeasons as $season)
                                    <a href="?season_id={{ $season->id }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white {{ $season->id === $currentSeason->id ? 'font-bold text-red-400' : '' }}">
                                        {{ $season->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- LOGIN -->
                    @auth
                        <div class="flex items-center gap-4 border-l border-red-400 pl-4">
                            <span class="text-xs font-bold text-white">{{ Auth::user()->name }}</span>
                            <a href="{{ url('/dashboard') }}" class="bg-white text-red-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition">Dashboard</a>
                        </div>
                    @else
                        <div class="flex items-center gap-2 border-l border-red-400 pl-4">
                            <a href="{{ route('login') }}" class="text-white hover:text-gray-200 text-sm font-bold uppercase">Log in</a>
                            <a href="{{ route('register') }}" class="bg-black text-white px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition border border-gray-700">Join</a>
                        </div>
                    @endauth
                </div>

                <!-- Botón Hamburguesa -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg x-show="!open" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" x-cloak class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú Móvil -->
        <div x-show="open" x-transition class="md:hidden bg-red-700 border-t border-red-500 absolute w-full left-0 shadow-xl" style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('calendar') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800">Calendar</a>
                <a href="{{ route('standings') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800">Standings</a>
                <a href="{{ route('drivers') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800">Drivers</a>
                <a href="{{ route('teams') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800">Teams</a>
                <a href="{{ route('news.index') }}" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-red-800">News</a>
                
                <!-- Selector Temporada Móvil -->
                @if(isset($allSeasons) && $allSeasons->count() > 1)
                    <div class="py-2 border-t border-red-500 mt-2">
                        <p class="px-3 text-xs font-bold text-red-300 uppercase mb-2">Select Season</p>
                        @foreach($allSeasons as $season)
                            <a href="?season_id={{ $season->id }}" class="block px-3 py-2 rounded-md text-sm {{ $season->id === $currentSeason->id ? 'bg-red-900 text-white font-bold' : 'text-red-100 hover:bg-red-800' }}">
                                {{ $season->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="border-t border-red-500 my-2 pt-2">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-3 rounded-md text-base font-bold text-red-600 bg-white text-center">DASHBOARD</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-3 rounded-md text-base font-bold text-white bg-black/20 text-center">LOGIN / JOIN</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8 px-4 flex-grow">
        @yield('content')
    </main>

    <footer class="text-center text-gray-500 py-6 text-sm">
        &copy; 2025 WTCS Paddock - SimRacing League
    </footer>
</body>
</html>