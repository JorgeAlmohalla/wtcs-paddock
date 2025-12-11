@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-4xl font-bold mb-10 text-white text-center uppercase tracking-widest">Season Calendar</h1>

    <div class="space-y-6">
        @foreach($races as $race)
            @php
                $isCompleted = $race->status === 'completed';
                $isNext = $race->status === 'scheduled' && $race->race_date >= now();
                $winner = $race->results->first()?->driver;
            @endphp

            <!-- Tarjeta de Carrera -->
            <a href="{{ route('races.show', $race) }}" class="block relative bg-gray-800 rounded-xl overflow-hidden shadow-lg border {{ $isNext ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-700' }} transition hover:border-gray-500 group">
                
                <!-- Fondo con imagen del circuito -->
                @if($race->track->layout_image_url)
                    <div class="absolute inset-0 opacity-10 bg-center bg-cover transition group-hover:opacity-20 duration-500" 
                         style="background-image: url('{{ asset('storage/' . $race->track->layout_image_url) }}');">
                    </div>
                @endif

                <!-- Contenedor Flex Principal -->
                <div class="relative p-4 md:p-6 flex flex-col md:flex-row items-center gap-6 z-10">
                    
                    <!-- IZQUIERDA: Ronda y Datos (Usa flex-1 para ocupar todo el hueco disponible) -->
                    <div class="flex items-center gap-6 flex-1 w-full">
                        
                        <!-- Caja Número Ronda -->
                        <div class="text-center bg-gray-900/90 p-4 rounded-lg min-w-[90px] border border-gray-700 shadow-inner">
                            <span class="block text-xs text-gray-500 uppercase tracking-widest">Round</span>
                            <span class="block text-4xl font-black text-white">{{ $race->round_number }}</span>
                        </div>

                        <!-- Textos -->
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-white leading-tight group-hover:text-red-500 transition">
                                {{ $race->track->name }}
                            </h2>
                            <div class="flex flex-col md:flex-row md:items-center gap-1 md:gap-3 text-gray-400 mt-1">
                                <span>{{ $race->title ?? $race->track->country_code }}</span>
                                <span class="hidden md:inline text-gray-600">•</span>
                                <span class="{{ $isCompleted ? 'text-gray-500' : 'text-red-400 font-bold' }}">
                                    {{ $race->race_date->format('d M Y - H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- DERECHA: Ganador o Estado (No se encoge: shrink-0) -->
                    <div class="w-full md:w-auto shrink-0">
                        @if($isCompleted && $winner)
                            <!-- Caja Ganador Grande -->
                            <div class="bg-gradient-to-r from-yellow-900/40 to-yellow-600/20 border border-yellow-600/50 rounded-lg p-4 flex items-center gap-4 min-w-[250px]">
                                <div class="bg-yellow-500/20 p-3 rounded-full">
                                    <span class="text-2xl">🏆</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-yellow-500 uppercase tracking-widest font-bold">Winner</span>
                                    <span class="block text-xl font-bold text-white">{{ $winner->name }}</span>
                                </div>
                            </div>
                        @elseif($race->status === 'cancelled')
                            <div class="bg-red-900/30 border border-red-800 px-6 py-3 rounded-lg text-center w-full md:w-auto">
                                <span class="text-red-400 font-bold uppercase tracking-widest">Cancelled</span>
                            </div>
                        @else
                            <!-- Botón View Details -->
                            <div class="bg-gray-700/50 border border-gray-600 px-6 py-3 rounded-lg text-center w-full md:w-auto group-hover:bg-red-600 group-hover:border-red-500 transition">
                                <span class="text-gray-300 font-bold uppercase tracking-widest text-sm group-hover:text-white">View Results &rarr;</span>
                            </div>
                        @endif
                    </div>

                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection