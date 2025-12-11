@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'race' }">
    
    <!-- Cabecera del Evento -->
    <div class="mb-8 text-center">
        <p class="text-red-500 font-bold tracking-widest uppercase text-sm">Round {{ $race->round_number }}</p>
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-2">{{ $race->track->name }}</h1>
        <p class="text-gray-400 text-xl">{{ $race->race_date->format('d F Y') }}</p>
    </div>

    <!-- Navegación de Pestañas -->
    <div class="flex justify-center gap-4 mb-8 border-b border-gray-800 pb-1">
        <button @click="tab = 'race'" 
                :class="{ 'text-red-500 border-red-500': tab === 'race', 'text-gray-500 border-transparent hover:text-white': tab !== 'race' }"
                class="pb-3 text-lg font-bold uppercase tracking-wider border-b-4 transition px-4">
            Race Results
        </button>
        
        @if($race->qualifyingResults->count() > 0)
            <button @click="tab = 'qualy'" 
                    :class="{ 'text-red-500 border-red-500': tab === 'qualy', 'text-gray-500 border-transparent hover:text-white': tab !== 'qualy' }"
                    class="pb-3 text-lg font-bold uppercase tracking-wider border-b-4 transition px-4">
                Qualifying
            </button>
        @endif
    </div>

    <!-- TABLA: RACE RESULTS -->
    <div x-show="tab === 'race'" x-transition.opacity>
        @if($race->results->count() > 0)
            <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 text-gray-400 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4 text-center">Pos</th>
                            <th class="px-6 py-4">Driver</th>
                            <th class="px-6 py-4 hidden md:table-cell">Team</th>
                            <th class="px-6 py-4 text-right">Time/Gap</th>
                            <th class="px-6 py-4 text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-sm">
                        @foreach($race->results as $result)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-center font-bold text-white text-lg">{{ $result->position }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white text-base">{{ $result->driver->name }}</div>
                                    @if($result->fastest_lap)
                                        <span class="text-xs text-purple-400 font-bold uppercase">Fastest Lap</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell text-gray-300">{{ $result->team->name ?? 'Privateer' }}</td>
                                <td class="px-6 py-4 text-right font-mono {{ in_array($result->status, ['dnf', 'dns']) ? 'text-red-500 font-bold' : 'text-white' }}">
                                    {{ $result->status === 'finished' ? $result->race_time : strtoupper($result->status) }}
                                    @if($result->penalty_seconds > 0)
                                        <span class="block text-xs text-red-400">+{{ $result->penalty_seconds }}s pen</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-white text-lg">{{ intval($result->points) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 py-12">No race results available yet.</p>
        @endif
    </div>

    <!-- TABLA: QUALIFYING RESULTS -->
    <div x-show="tab === 'qualy'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-gray-400 text-xs uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4 text-center">Pos</th>
                        <th class="px-6 py-4">Driver</th>
                        <th class="px-6 py-4 hidden md:table-cell">Team</th>
                        <th class="px-6 py-4 text-right">Time</th>
                        <th class="px-6 py-4 text-center">Tyre</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-sm">
                    @foreach($race->qualifyingResults as $qResult)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-center font-bold text-white text-lg">{{ $qResult->position }}</td>
                            <td class="px-6 py-4 font-bold text-white">{{ $qResult->driver->name }}</td>
                            <td class="px-6 py-4 hidden md:table-cell text-gray-300">{{ $qResult->team->name ?? 'Privateer' }}</td>
                            <td class="px-6 py-4 text-right font-mono text-white">{{ $qResult->best_time }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($qResult->tyre_compound)
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-gray-700 text-white border border-gray-600">
                                        {{ ucfirst($qResult->tyre_compound) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection