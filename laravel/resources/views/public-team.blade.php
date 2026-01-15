@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-12">
    
<!-- 1. CABECERA EQUIPO -->
    <div class="rounded-xl overflow-hidden shadow-2xl border border-gray-700 relative min-h-[280px] flex items-end bg-gray-900">
        <!-- Fondo -->
        <div class="absolute inset-0 z-0" 
             style="background: linear-gradient(135deg, {{ $team->primary_color ?? '#333' }} 0%, #111827 90%);">
        </div>
        
        <!-- Marca de Agua -->
        <span class="text-[10rem] font-black text-white/5 absolute -bottom-10 right-0 select-none pointer-events-none z-0 leading-none">
            {{ $team->short_name }}
        </span>

        <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row justify-between items-end w-full">
            
            <!-- Izquierda: Textos -->
            <div class="mb-8 md:mb-0 max-w-3xl">
                <p class="text-white/70 font-bold tracking-[0.2em] uppercase text-xs mb-3 flex items-center gap-2">
                    <span class="w-8 h-0.5 bg-white/50"></span> {{ $team->car_brand }} WORKSHOP
                </p>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-[0.9] mb-6 drop-shadow-xl">
                    {{ $team->name }}
                </h1>
                <div class="inline-block bg-black/40 backdrop-blur-md px-5 py-2.5 rounded-lg text-white font-mono border border-white/10 text-sm">
                    {{ $team->car_model }}
                </div>
            </div>
            
            <!-- Derecha: Logo + Etiqueta (RECTO) -->
            <div class="flex flex-col items-center relative mr-4 md:mr-8">
                
                <!-- Etiqueta -->
                <span class="mb-3 px-5 py-1.5 rounded text-xs font-black uppercase tracking-widest bg-white text-black shadow-xl border-2 border-transparent">
                    {{ $team->type }} TEAM
                </span>

                <!-- Logo -->
                <div class="bg-white p-4 rounded-2xl shadow-2xl relative z-20 w-32 h-32 flex items-center justify-center transition hover:scale-105 duration-300">
                    @if($team->logo_url)
                        <img src="{{ asset('storage/' . $team->logo_url) }}" class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-3xl font-black text-gray-300">{{ $team->short_name }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ESTADÍSTICAS (Estilo Original Horizontal) -->
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

    <!-- 3. BIO Y ROSTER (Ahora en Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Columna Izquierda: Bio (2/3) -->
        <div class="lg:col-span-2 space-y-8">
            @if($team->bio)
                <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-lg relative">
                    <span class="text-6xl text-gray-700 absolute top-0 left-4 font-serif">"</span>
                    <h3 class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-3 pl-2">Team History</h3>
                    <p class="text-gray-300 text-lg leading-relaxed relative z-10 italic pl-6">{{ $team->bio }}</p>
                </div>
            @endif
        </div>

        <!-- Columna Derecha: Roster (1/3) -->
        <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg h-fit">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Team Roster</h3>
            </div>
            <div class="p-4 space-y-3">
                @foreach($team->drivers->sortBy('contract_type') as $driver)
                    <a href="{{ route('driver.show', $driver) }}" class="flex items-center gap-3 bg-gray-900/50 p-3 rounded border border-gray-700 hover:border-white/50 transition group">
                        <div class="h-10 w-10 rounded-full bg-gray-800 flex items-center justify-center font-bold text-gray-500 border border-gray-600">
                            {{ substr($driver->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-white group-hover:text-red-400 transition">{{ $driver->name }}</h3>
                            <span class="text-[10px] text-gray-500 font-mono uppercase">{{ $driver->contract_type === 'reserve' ? 'RESERVE' : 'PRIMARY' }}</span>
                        </div>
                        @if($driver->isTeamPrincipal())
                            <div class="bg-yellow-500/20 px-2 py-0.5 rounded text-yellow-500 border border-yellow-500/30 text-[9px] font-bold uppercase">BOSS</div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

    </div>

    <!-- 4. COCHE Y FICHA TÉCNICA (Abajo) -->
    @if($team->car_image_url)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-8 border-t border-gray-800">
            
            <!-- Foto (2/3) -->
            <div class="lg:col-span-2 bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg flex flex-col">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Official Livery</h3>
                </div>
                <div class="flex-grow bg-black flex items-center justify-center p-4 min-h-[350px]">
                    <img src="{{ asset('storage/' . $team->car_image_url) }}" class="max-h-[300px] w-auto object-contain shadow-2xl hover:scale-105 transition duration-700">
                </div>
            </div>

<!-- Specs (1/3) -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg flex flex-col justify-center h-full">
                <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6 border-b border-gray-600 pb-2">Technical Data</h3>
                <div class="space-y-5 text-sm">
                    
                    <!-- CHASSIS CORREGIDO -->
                    <div class="flex justify-between items-start border-b border-gray-700 pb-1 gap-4">
                        <span class="text-gray-500 font-bold uppercase shrink-0">Chassis</span>
                        <span class="text-white font-mono text-right">{{ $team->tech_chassis ?? '-' }}</span>
                    </div>

                    <!-- ENGINE (Añadido text-right por consistencia) -->
                    <div class="flex justify-between border-b border-gray-700 pb-1 gap-4">
                        <span class="text-gray-500 font-bold uppercase shrink-0">Engine</span>
                        <span class="text-white font-mono text-right">{{ $team->tech_engine ?? '-' }}</span>
                    </div>

                    <!-- POWER -->
                    <div class="flex justify-between border-b border-gray-700 pb-1">
                        <span class="text-gray-500 font-bold uppercase">Power</span>
                        <span class="text-red-400 font-black text-lg">{{ $team->tech_power ?? '-' }}</span>
                    </div>

                    <!-- DRIVETRAIN -->
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 font-bold uppercase">Drivetrain</span>
                        <span class="bg-gray-700 text-white px-2 py-0.5 rounded text-xs font-bold border border-gray-600">{{ $team->tech_drivetrain ?? '-' }}</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-700 text-center">
                    <span class="text-xs text-gray-500 uppercase tracking-widest">Model Year: <strong class="text-white">{{ $team->car_year ?? '1999' }}</strong></span>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection