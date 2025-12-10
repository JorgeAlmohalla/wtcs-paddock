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
            <div class="relative bg-gray-800 rounded-xl overflow-hidden shadow-lg border {{ $isNext ? 'border-red-500 ring-2 ring-red-500/50' : 'border-gray-700' }} transition hover:scale-[1.01]">
                
                <!-- Fondo con imagen del circuito (oscurecida) -->
                @if($race->track->layout_image_url)
                    <div class="absolute inset-0 opacity-10 bg-center bg-cover" 
                         style="background-image: url('{{ asset('storage/' . $race->track->layout_image_url) }}');">
                    </div>
                @endif

                <div class="relative p-6 flex flex-col md:flex-row items-center justify-between z-10">
                    
                    <!-- Izquierda: Fecha y Ronda -->
                    <div class="flex items-center space-x-6 w-full md:w-auto">
                        <div class="text-center bg-gray-900/80 p-3 rounded-lg min-w-[80px]">
                            <span class="block text-sm text-gray-400 uppercase">Round</span>
                            <span class="block text-3xl font-bold text-white">{{ $race->round_number }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $race->track->name }}</h2>
                            <p class="text-gray-400">{{ $race->title ?? $race->track->country_code }}</p>
                            <p class="text-sm {{ $isCompleted ? 'text-gray-500 line-through' : 'text-red-400 font-bold' }}">
                                {{ $race->race_date->format('d M Y - H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Derecha: Estado o Ganador -->
                    <div class="mt-4 md:mt-0 w-full md:w-auto text-center md:text-right">
                        @if($isCompleted && $winner)
                            <div class="inline-block bg-yellow-500/20 border border-yellow-500/50 px-4 py-2 rounded-lg">
                                <span class="text-xs text-yellow-500 uppercase tracking-widest block">Winner</span>
                                <span class="text-lg font-bold text-white">🏆 {{ $winner->name }}</span>
                            </div>
                        @elseif($isCompleted)
                            <span class="px-3 py-1 bg-gray-700 text-gray-300 rounded text-sm">Completed</span>
                        @elseif($race->status === 'cancelled')
                            <span class="px-3 py-1 bg-red-900 text-red-200 rounded text-sm">Cancelled</span>
                        @else
                            <a href="#" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded transition shadow-lg shadow-red-600/20">
                                Race Info
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection