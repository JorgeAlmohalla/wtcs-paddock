@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'sprint' }"> <!-- Por defecto Sprint -->
    
    <!-- Cabecera del Evento -->
    <div class="mb-8 text-center relative">
        <p class="text-red-500 font-bold tracking-widest uppercase text-sm mb-1">Round {{ $roundNumber }}</p>
        <h1 class="text-4xl md:text-6xl font-black text-white mb-2 uppercase">{{ $track->name }}</h1>
        <div class="flex justify-center items-center gap-2">
            <img src="https://flagcdn.com/24x18/{{ strtolower($track->country_code) }}.png" class="h-5 rounded shadow">
            <p class="text-gray-400 text-xl font-mono">{{ $sprint->race_date->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Navegación de Pestañas -->
    <div class="flex justify-center gap-2 md:gap-6 mb-8 border-b border-gray-800 pb-1 overflow-x-auto">
        
        <!-- Pestaña QUALY -->
        @if($qualy && $qualy->count() > 0)
            <button @click="tab = 'qualy'" 
                    :class="tab === 'qualy' ? 'text-red-500 border-red-500' : 'text-gray-500 border-transparent hover:text-white'"
                    class="pb-3 text-sm md:text-lg font-bold uppercase tracking-wider border-b-4 transition px-4 whitespace-nowrap">
                Qualifying
            </button>
        @endif

        <!-- Pestaña SPRINT -->
        <button @click="tab = 'sprint'" 
                :class="tab === 'sprint' ? 'text-red-500 border-red-500' : 'text-gray-500 border-transparent hover:text-white'"
                class="pb-3 text-sm md:text-lg font-bold uppercase tracking-wider border-b-4 transition px-4 whitespace-nowrap">
            Sprint Race
        </button>

        <!-- Pestaña FEATURE -->
        @if($feature)
            <button @click="tab = 'feature'" 
                    :class="tab === 'feature' ? 'text-red-500 border-red-500' : 'text-gray-500 border-transparent hover:text-white'"
                    class="pb-3 text-sm md:text-lg font-bold uppercase tracking-wider border-b-4 transition px-4 whitespace-nowrap">
                Feature Race
            </button>
        @endif
    </div>

    <!-- CONTENIDO: QUALY -->
    @if($qualy)
    <div x-show="tab === 'qualy'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-bold text-white">Qualifying Session</h3>
            </div>
            <!-- Reutiliza tu tabla de Qualy aquí -->
            @include('partials.results-table-qualy', ['results' => $qualy->sortBy('position')])
        </div>
    </div>
    @endif

    <!-- CONTENIDO: SPRINT -->
    <div x-show="tab === 'sprint'" x-transition.opacity>
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">{{ $sprint->title ?? 'Sprint Race' }}</h3>
                <span class="text-xs font-mono text-gray-400">{{ $sprint->status }}</span>
            </div>
            @include('partials.results-table-race', ['results' => $sprint->results->sortBy('position')])
        </div>
    </div>

    <!-- CONTENIDO: FEATURE -->
    @if($feature)
    <div x-show="tab === 'feature'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">{{ $feature->title ?? 'Feature Race' }}</h3>
                <span class="text-xs font-mono text-gray-400">{{ $feature->status }}</span>
            </div>
            @include('partials.results-table-race', ['results' => $feature->results->sortBy('position')])
        </div>
    </div>
    @endif

</div>
@endsection