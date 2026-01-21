<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WTCS Paddock</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans antialiased flex flex-col min-h-screen">

    <nav x-data="{ open: false }" class="bg-red-600 shadow-lg relative z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                
<!-- Logo -->
<a href="/" class="flex items-center gap-3 group">
                    <!-- SIN FILTROS, SOLO SHADOW PARA RESALTAR -->
                    <img src="{{ asset('images/wtcs-logo-white.png') }}" class="h-8 w-auto object-contain drop-shadow-md" alt="WTCS Logo"> 
                    
                    <span class="bg-black text-white px-2 py-0.5 rounded text-sm font-black uppercase tracking-widest border border-white/20 shadow-md group-hover:bg-gray-800 transition">
                        PADDOCK
                    </span>
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

                    <!-- LOGIN / USER MENU -->
                    @auth
                        <div class="flex items-center gap-4 border-l border-red-400 pl-4">
                            <span class="text-xs font-bold text-white hidden lg:inline">{{ Auth::user()->name }}</span>
                            
                            <!-- Botón Dashboard -->
                            <a href="{{ url('/dashboard') }}" class="bg-white text-red-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-gray-100 transition">
                                Dashboard
                            </a>

                            <!-- Botón Logout (Icono) -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-white transition" title="Log Out">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
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

                <div class="border-t border-red-500 my-2 pt-2 space-y-2">
                    @auth
                        <!-- Botón Dashboard -->
                        <a href="{{ url('/dashboard') }}" class="block px-3 py-3 rounded-md text-base font-bold text-red-600 bg-white text-center shadow-md">
                            MY DASHBOARD
                        </a>

                        <!-- Botón Logout (Formulario) -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full block px-3 py-3 rounded-md text-base font-bold text-white bg-red-900/50 hover:bg-red-900 text-center border border-red-800 transition">
                                LOG OUT
                            </button>
                        </form>
                    @else
                        <!-- Botón Login -->
                        <a href="{{ route('login') }}" class="block px-3 py-3 rounded-md text-base font-bold text-white bg-black/20 text-center hover:bg-black/40 transition">
                            LOGIN / JOIN
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8 px-4 flex-grow">
        @yield('content')
    </main>

<footer class="bg-black border-t border-gray-800 text-gray-400 py-12 mt-auto">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            
<!-- Columna 1: Marca -->
<div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <!-- SIN FILTROS -->
                    <img src="{{ asset('images/wtcs-logo-white.png') }}" class="h-7 w-auto object-contain drop-shadow-md" alt="PADDOCK">
                    <span class="font-black text-white tracking-tighter text-lg">PADDOCK</span>
                </div>
                
                <p class="text-xs leading-relaxed mb-6 text-gray-500">
                    The ultimate management platform for the World Touring Car Series community. Built for drivers, by drivers.
                </p>
                
<!-- REDES SOCIALES -->
                <div class="flex gap-4 mt-6">
                    
                    <!-- DISCORD (Icono Mando) -->
                    <a href="https://discord.gg/22naxm8N" target="_blank" class="text-gray-400 hover:text-[#5865F2] transition transform hover:scale-110">
                        <span class="sr-only">Discord</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037 13.46 13.46 0 0 0-.585 1.206 18.423 18.423 0 0 0-5.534 0 13.34 13.34 0 0 0-.588-1.206.077.077 0 0 0-.08-.037A19.736 19.736 0 0 0 3.682 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.086 2.157 2.419 0 1.334-.956 2.419-2.157 2.419zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.086 2.157 2.419 0 1.334-.946 2.419-2.157 2.419z"/>
                        </svg>
                    </a>

                    <!-- TWITTER / X -->
                    <a href="#" class="text-gray-400 hover:text-white transition transform hover:scale-110">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>

                    <!-- YOUTUBE -->
                    <a href="#" class="text-gray-400 hover:text-red-600 transition transform hover:scale-110">
                        <span class="sr-only">YouTube</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>

                </div>
            </div>

            <!-- Columna 2: Enlaces Rápidos -->
            <div>
                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('calendar') }}" class="hover:text-red-500 transition">Race Calendar</a></li>
                    <li><a href="{{ route('standings') }}" class="hover:text-red-500 transition">Standings</a></li>
                    <li><a href="{{ route('drivers') }}" class="hover:text-red-500 transition">Driver Market</a></li>
                    <li><a href="{{ route('news.index') }}" class="hover:text-red-500 transition">Newsroom</a></li>
                </ul>
            </div>

            <!-- Columna 3: Legal -->
            <div>
                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-4">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="https://docs.google.com/document/d/1XhYiHHJVKqsPFc26H69Gun1A5X8ymElK2kenBgBfanw/edit?tab=t.0" class="hover:text-white transition">Rulebook v3.0</a></li>
                    <li><a href="{{ route('legal.show', 'privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.show', 'terms-of-service') }}" class="hover:text-white transition">Terms of Service</a></li>
                    <li><a href="{{ route('legal.show', 'code-of-conduct') }}" class="hover:text-white transition">Code of Conduct</a></li>
                </ul>
            </div>

<!-- Columna 4: Estado -->
            <div>
                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-4">System Status</h4>
                <div class="flex items-center gap-2 text-sm">
                    <!-- Puntito de color dinámico -->
                    <span class="w-2 h-2 rounded-full bg-{{ $systemStatus['color'] ?? 'green' }}-500 animate-pulse"></span>
                    
                    <!-- Texto dinámico -->
                    <span class="text-{{ $systemStatus['color'] ?? 'green' }}-400">
                        {{ $systemStatus['text'] ?? 'All Systems Operational' }}
                    </span>
                </div>
                <p class="text-xs mt-2 text-gray-500 font-mono">Server Time: {{ now()->format('H:i:s T') }}</p>
                
                <!-- Versión sacada de config o git (simulado) -->
                <p class="text-xs text-gray-600">v2.8.1 (Stable)</p>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 text-center text-xs text-gray-600">
            &copy; 2026 WTCS Paddock. Not affiliated with FIA. All trademarks belong to their respective owners.
            <br>Designed & Developed by Jorge Caro.
        </div>
    </footer>
</body>
</html>