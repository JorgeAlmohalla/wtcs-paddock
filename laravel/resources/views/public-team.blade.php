@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-8">
    
    <!-- CABECERA EQUIPO -->
    <div class="rounded-xl overflow-hidden shadow-2xl border border-gray-700 relative">
        <!-- Fondo Degradado -->
        <div class="absolute inset-0 z-0" 
             style="background: linear-gradient(135deg, {{ $team->primary_color ?? '#333' }} 0%, #111827 90%);">
        </div>
        
        <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row justify-between items-end">
            <div>
                <p class="text-white/60 font-bold tracking-widest uppercase text-sm mb-2">{{ $team->car_brand }} WORKSHOP</p>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-none mb-4">{{ $team->name }}</h1>
                <div class="inline-block bg-black/30 backdrop-blur px-4 py-2 rounded text-white font-mono border border-white/10">
                    {{ $team->car_model }}
                </div>
            </div>
            
            <div class="text-right mt-6 md:mt-0">
                <span class="text-9xl font-black text-white/10 absolute bottom-[-20px] right-[-20px] select-none">
                    {{ $team->short_name }}
                </span>
                <span class="px-4 py-2 rounded text-sm font-bold uppercase tracking-wider bg-white text-black shadow-lg relative z-20">
                    {{ $team->type }} Team
                </span>
            </div>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 text-center shadow-lg">
            <span class="block text-4xl font-black text-white">{{ $stats['drivers'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Active Drivers</span>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 text-center shadow-lg">
            <span class="block text-4xl font-black text-yellow-400">{{ $stats['wins'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Wins</span>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 text-center shadow-lg">
            <span class="block text-4xl font-black text-gray-300">{{ $stats['podiums'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Podiums</span>
        </div>
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 text-center shadow-lg bg-gradient-to-br from-blue-900/20 to-gray-800">
            <span class="block text-4xl font-black text-blue-400">{{ intval($stats['points']) }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Points</span>
        </div>
    </div>

    <!-- LISTA DE PILOTOS (ROSTER) -->
    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
        <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Team Roster</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
            @foreach($team->drivers->sortBy('contract_type') as $driver)
                <a href="{{ route('driver.show', $driver) }}" class="flex items-center gap-4 bg-gray-900 p-4 rounded-lg border border-gray-700 hover:border-white/50 transition group">
                    <!-- Avatar -->
                    <div class="h-16 w-16 rounded-full bg-gray-800 flex items-center justify-center font-bold text-gray-500 text-xl border-2 border-gray-700 group-hover:border-white transition">
                        {{ substr($driver->name, 0, 1) }}
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-white group-hover:text-red-400 transition">{{ $driver->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            @if($driver->nationality)
                                <img src="https://flagcdn.com/16x12/{{ strtolower($driver->nationality) }}.png" class="opacity-70">
                            @endif
                            <span class="text-xs text-gray-500 font-mono">{{ $driver->contract_type === 'reserve' ? 'RESERVE' : 'PRIMARY' }}</span>
                        </div>
                    </div>

                    @if($driver->isTeamPrincipal())
                        <div class="bg-yellow-500/20 p-2 rounded text-yellow-500 border border-yellow-500/30" title="Team Principal">
                            Team Principal
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- FOTO DEL COCHE (Contenida) -->
    @if($team->car_image_url)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8"> <!-- Grid de 2 columnas -->
            
            <!-- Columna Izquierda: Foto -->
            <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Official Livery</h3>
                </div>
                <!-- Aspect Ratio 16:9 y object-contain para que se vea entera sin recortar -->
                <div class="aspect-video w-full bg-black flex items-center justify-center">
                    <img src="{{ asset('storage/' . $team->car_image_url) }}" class="max-w-full max-h-full object-contain">
                </div>
            </div>

            <!-- Columna Derecha: Espacio para Specs (Vacío por ahora) -->
            <div class="hidden md:flex items-center justify-center text-gray-600 border-2 border-dashed border-gray-700 rounded-xl">
                <p class="text-sm uppercase tracking-widest font-bold">Tech Specs Coming Soon</p>
            </div>

        </div>
    @endif

</div>
@endsection