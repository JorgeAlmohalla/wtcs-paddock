@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'sprint' }"> <!-- Por defecto Sprint -->
    
    <!-- Cabecera del Evento -->
    <div class="mb-8 text-center relative">
        <!-- Botón Global de PDF (Opcional, lo dejo por si quieres descargar el pack completo) -->
        <div class="md:absolute md:top-0 md:right-0 mt-4 md:mt-0">
            <a href="{{ route('rounds.pdf', $roundNumber) }}" class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-600 px-4 py-2 rounded transition text-sm font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Full Report
            </a>
        </div>

        <p class="text-red-500 font-bold tracking-widest uppercase text-sm mb-1">Round {{ $roundNumber }}</p>
        <h1 class="text-4xl md:text-6xl font-black text-white mb-2 uppercase">{{ $track->name }}</h1>
        <div class="flex justify-center items-center gap-2">
            @if($track->country_code)
                <img src="https://flagcdn.com/24x18/{{ strtolower($track->country_code) }}.png" class="h-5 rounded shadow">
            @endif
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
            @include('partials.results-table-qualy', ['results' => $qualy->sortBy('position')])
        </div>
    </div>
    @endif

    <!-- CONTENIDO: SPRINT -->
    <div x-show="tab === 'sprint'" x-transition.opacity>
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            
            <!-- Cabecera Sprint -->
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $sprint->title ?? 'Sprint Race' }}</h3>
                    <span class="text-xs font-mono text-gray-400 uppercase">{{ $sprint->status }}</span>
                </div>

                <!-- Botón FIA Doc Sprint -->
                <a href="{{ route('races.doc', $sprint) }}" target="_blank" 
                   class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-600 px-3 py-1.5 rounded transition text-xs font-bold uppercase tracking-wide">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Steward Decision
                </a>
            </div>

            @include('partials.results-table-race', ['results' => $sprint->results->sortBy('position')])
        </div>
    </div>

    <!-- CONTENIDO: FEATURE -->
    @if($feature)
    <div x-show="tab === 'feature'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            
            <!-- Cabecera Feature -->
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $feature->title ?? 'Feature Race' }}</h3>
                    <span class="text-xs font-mono text-gray-400 uppercase">{{ $feature->status }}</span>
                </div>

                <!-- Botón FIA Doc Feature -->
                <a href="{{ route('races.doc', $feature) }}" target="_blank" 
                   class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white border border-gray-600 px-3 py-1.5 rounded transition text-xs font-bold uppercase tracking-wide">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Steward Decision
                </a>
            </div>

            @include('partials.results-table-race', ['results' => $feature->results->sortBy('position')])
        </div>
    </div>
    @endif

</div>
@endsection