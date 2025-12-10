@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold mb-10 text-white text-center uppercase tracking-widest">Driver Lineup</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($drivers as $driver)
            <!-- Tarjeta de Piloto -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border-t-4 transition hover:-translate-y-1 hover:shadow-2xl group"
                 style="border-color: {{ $driver->team->primary_color ?? '#6b7280' }}">
                
                <div class="p-6 flex items-center space-x-4">
                    <!-- Avatar (Círculo con iniciales si no hay foto) -->
                    <div class="h-16 w-16 rounded-full bg-gray-700 flex items-center justify-center text-xl font-bold text-gray-400 border-2 border-gray-600 group-hover:border-white transition">
                        {{ substr($driver->name, 0, 1) }}
                    </div>

                    <div>
                        <!-- Nombre y Bandera -->
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            {{ $driver->name }}
                            @if($driver->nationality)
                                <!-- Usamos una API de banderas simple (flagcdn) -->
                                <img src="https://flagcdn.com/24x18/{{ strtolower($driver->nationality) }}.png" class="h-4 rounded-sm">
                            @endif
                        </h2>
                        
                        <!-- Equipo -->
                        <p class="text-sm font-semibold mt-1" style="color: {{ $driver->team->primary_color ?? '#9ca3af' }}">
                            {{ $driver->team->name ?? 'Free Agent' }}
                        </p>

                        <!-- Steam ID (Solo si existe) -->
                        @if($driver->steam_id)
                            <p class="text-xs text-gray-500 mt-2 font-mono">ID: {{ $driver->steam_id }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Barra inferior decorativa -->
                <div class="bg-gray-900/50 px-6 py-2 flex justify-between items-center text-xs text-gray-500">
                    <span>{{ $driver->team ? ($driver->team->type === 'works' ? 'Works Driver' : 'Privateer') : 'Looking for seat' }}</span>
                    @if($driver->driver_number)
                        <span class="text-lg font-bold text-white opacity-50">#{{ $driver->driver_number }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection