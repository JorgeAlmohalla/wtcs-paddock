@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter">Driver Lineup</h1>
        <p class="text-gray-400 mt-2 font-mono text-sm uppercase tracking-widest">{{ $drivers->count() }} Active Drivers</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($drivers as $driver)
            <!-- TARJETA CLICABLE -->
            <a href="{{ route('driver.show', $driver) }}" 
               class="block bg-gray-800 rounded-xl overflow-hidden shadow-lg border-t-4 transition transform hover:-translate-y-1 hover:shadow-2xl group border-gray-700 hover:border-t-4"
               style="border-top-color: {{ $driver->team->primary_color ?? '#6b7280' }}">
                
                <div class="p-6 flex items-center space-x-4">
                    <!-- Avatar (Foto o Inicial) -->
                    <div class="flex-shrink-0">
                        @if($driver->avatar_url)
                            <img src="{{ asset('storage/' . $driver->avatar_url) }}" 
                                 class="h-16 w-16 rounded-full object-cover border-2 border-gray-600 group-hover:border-white transition shadow-md">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-700 flex items-center justify-center text-xl font-bold text-gray-400 border-2 border-gray-600 group-hover:border-white transition">
                                {{ substr($driver->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <!-- Nombre y Bandera -->
                        <h2 class="text-xl font-bold text-white flex items-center gap-2 group-hover:text-red-400 transition truncate">
                            {{ $driver->name }}
                            @if($driver->nationality)
                                <img src="https://flagcdn.com/16x12/{{ strtolower($driver->nationality) }}.png" class="h-3 rounded-sm opacity-80 shadow-sm">
                            @endif
                        </h2>
                        
                        <!-- Equipo -->
                        <p class="text-sm font-semibold mt-1 truncate" style="color: {{ $driver->team->primary_color ?? '#9ca3af' }}">
                            {{ $driver->team->name ?? 'Free Agent' }}
                        </p>

                        <!-- Etiquetas Rol -->
                        <div class="mt-2 flex gap-2">
                             @if($driver->contract_type === 'reserve')
                                <span class="text-[10px] bg-gray-700 text-gray-300 px-2 py-0.5 rounded border border-gray-600 uppercase font-bold">Res</span>
                            @endif
                            @if($driver->isTeamPrincipal())
                                <span class="text-[10px] bg-yellow-900/30 text-yellow-500 px-2 py-0.5 rounded border border-yellow-700/50 uppercase font-bold">Principal</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Barra inferior -->
                <div class="bg-gray-900/50 px-6 py-3 flex justify-between items-center text-xs text-gray-500 border-t border-gray-700 group-hover:bg-gray-900 transition">
                    <span class="uppercase tracking-wider font-bold">{{ $driver->team ? ($driver->team->type === 'works' ? 'Works Driver' : 'Privateer') : 'Unsigned' }}</span>
                    @if($driver->driver_number)
                        <span class="text-xl font-black text-white/20 group-hover:text-white/40 transition">#{{ $driver->driver_number }}</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection