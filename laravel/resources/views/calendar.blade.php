@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-4xl font-bold mb-10 text-white text-center uppercase tracking-widest">Season Calendar</h1>

    <div class="space-y-8">
        <!-- BUCLE DE RONDAS (Agrupado) -->
@foreach($rounds as $roundNumber => $roundRaces)
            @php
                $mainRace = $roundRaces->first(); 
                // Comprobamos si la ronda entera ha terminado (si la Feature está completed)
                $isCompleted = $roundRaces->last()->status === 'completed';
                $isNext = $roundRaces->where('status', 'scheduled')->where('race_date', '>=', now())->isNotEmpty();
                
                // Sacamos los ganadores de Sprint y Feature para mostrarlos resumidos
                $sprintWinner = $roundRaces->first()->results->first()?->driver;
                $featureWinner = $roundRaces->last()->results->first()?->driver;
            @endphp

            <!-- TARJETA UNIFICADA DE RONDA -->
            <a href="{{ route('rounds.show', $roundNumber) }}" class="block relative bg-gray-800 rounded-xl overflow-hidden shadow-lg border {{ $isNext ? 'border-red-500 ring-2 ring-red-500/50' : 'border-gray-700' }} hover:border-gray-500 transition group h-48 md:h-56">
                
                <!-- Fondo con imagen -->
                @if($mainRace->track->layout_image_url)
                    <div class="absolute inset-0 opacity-20 bg-center bg-cover transition group-hover:scale-105 duration-700" 
                         style="background-image: url('{{ asset('storage/' . $mainRace->track->layout_image_url) }}');">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/80 to-transparent"></div>
                @endif

                <div class="relative h-full flex flex-col md:flex-row p-6 md:p-8 z-10 justify-between items-center">
                    
                    <!-- IZQUIERDA: Datos del Evento -->
                    <div class="flex items-center gap-6 w-full md:w-auto">
                        <div class="text-center">
                            <span class="block text-4xl md:text-6xl font-black text-white leading-none">{{ $roundNumber }}</span>
                            <span class="text-xs text-red-500 font-bold tracking-widest uppercase">Round</span>
                        </div>
                        
                        <div class="border-l border-gray-600 pl-6 h-full flex flex-col justify-center">
                            <h2 class="text-3xl md:text-4xl font-bold text-white uppercase group-hover:text-red-400 transition mb-1">
                                {{ $mainRace->track->name }}
                            </h2>
                            <div class="flex items-center gap-3 text-gray-400">
                                @if($mainRace->track->country_code)
                                    <img src="https://flagcdn.com/24x18/{{ strtolower($mainRace->track->country_code) }}.png" class="h-4 rounded shadow-sm" alt="flag">
                                @endif
                                
                                <span class="font-mono text-sm uppercase tracking-wide">
                                    @php
                                        // Calcular rango de fechas
                                        $startDate = $roundRaces->min('race_date');
                                        $endDate = $roundRaces->max('race_date');
                                    @endphp

                                    @if($startDate->isSameDay($endDate))
                                        <!-- Mismo día: 11 Oct 2025 -->
                                        {{ $startDate->format('d M Y') }}
                                    @elseif($startDate->isSameMonth($endDate))
                                        <!-- Mismo mes: 11-12 Oct 2025 -->
                                        {{ $startDate->format('d') }}-{{ $endDate->format('d M Y') }}
                                    @else
                                        <!-- Distinto mes: 30 Sep - 02 Oct 2025 -->
                                        {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- DERECHA: Estado / Ganadores -->
                    <div class="mt-4 md:mt-0 w-full md:w-auto flex flex-col items-end gap-2">
                        @if($isCompleted)
                            <!-- Resumen de Ganadores -->
                            <div class="flex flex-col gap-1 text-right">
                                @if($sprintWinner)
                                    <div class="text-xs text-gray-400">
                                        Sprint: <span class="text-white font-bold">{{ $sprintWinner->name }} 🏆</span>
                                    </div>
                                @endif
                                @if($featureWinner)
                                    <div class="text-sm text-yellow-500 font-bold border border-yellow-500/30 bg-yellow-500/10 px-3 py-1 rounded">
                                        Feature: {{ $featureWinner->name }} 🏆
                                    </div>
                                @endif
                            </div>
                        @elseif($isNext)
                            <div class="px-6 py-2 bg-red-600 text-white font-bold uppercase tracking-widest rounded shadow-lg shadow-red-600/20 animate-pulse">
                                Next Event
                            </div>
                        @else
                            <div class="px-4 py-2 border border-gray-600 text-gray-400 text-xs font-bold uppercase tracking-widest rounded">
                                Upcoming
                            </div>
                        @endif
                    </div>

                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection