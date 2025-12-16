@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ tab: 'drivers' }">
    
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-white uppercase tracking-widest inline-block">Championship Standings</h1>
        <!-- Botón PDF -->
        <div class="mt-4">
            <a href="#" class="text-gray-500 hover:text-white text-xs uppercase font-bold tracking-widest transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF (Coming Soon)
            </a>
        </div>
    </div>

    <!-- NAVEGACIÓN DE PESTAÑAS -->
    <div class="flex justify-center gap-4 md:gap-8 mb-8 border-b border-gray-800 pb-1">
        <button @click="tab = 'drivers'" 
                :class="tab === 'drivers' ? 'text-red-500 border-red-500' : 'text-gray-500 border-transparent hover:text-white'"
                class="pb-3 text-lg font-bold uppercase tracking-wider border-b-4 transition px-4">
            Drivers
        </button>
        <button @click="tab = 'teams'" 
                :class="tab === 'teams' ? 'text-blue-500 border-blue-500' : 'text-gray-500 border-transparent hover:text-white'"
                class="pb-3 text-lg font-bold uppercase tracking-wider border-b-4 transition px-4">
            Constructors
        </button>
        <button @click="tab = 'manufacturers'" 
                :class="tab === 'manufacturers' ? 'text-yellow-500 border-yellow-500' : 'text-gray-500 border-transparent hover:text-white'"
                class="pb-3 text-lg font-bold uppercase tracking-wider border-b-4 transition px-4">
            Manufacturers
        </button>
    </div>

    <!-- CONTENIDO: DRIVERS -->
    <div x-show="tab === 'drivers'" x-transition.opacity>
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">Pos</th>
                        <th class="px-6 py-4">Driver</th>
                        <th class="px-6 py-4 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-sm">
                    @foreach($drivers as $index => $driver)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-black text-white text-center text-lg">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($driver->nationality)
                                    <img src="https://flagcdn.com/20x15/{{ strtolower($driver->nationality) }}.png" class="shadow-sm">
                                @endif
                                <div>
                                    <p class="font-bold text-white text-base">{{ $driver->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono uppercase">{{ $driver->team->name ?? 'Privateer' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-black text-red-500 text-xl">
                            {{ intval($driver->total_points) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- CONTENIDO: CONSTRUCTORS -->
    <div x-show="tab === 'teams'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">Pos</th>
                        <th class="px-6 py-4">Team</th>
                        <th class="px-6 py-4 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-sm">
                    @foreach($teams as $index => $team)
                    <tr class="hover:bg-gray-700/50 transition border-l-4" style="border-left-color: {{ $team->primary_color ?? '#333' }}">
                        <td class="px-6 py-4 font-black text-white text-center text-lg">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-white text-lg">{{ $team->name }}</p>
                            <p class="text-xs text-gray-400 font-mono uppercase">{{ $team->car_model }}</p>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-black text-blue-500 text-xl">
                            {{ intval($team->total_points) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- CONTENIDO: MANUFACTURERS -->
    <div x-show="tab === 'manufacturers'" x-transition.opacity style="display: none;">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">Pos</th>
                        <th class="px-6 py-4">Manufacturer</th>
                        <th class="px-6 py-4 text-center">Entries</th>
                        <th class="px-6 py-4 text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-sm">
                    @foreach($manufacturers as $index => $man)
                    <tr class="hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-black text-white text-center text-lg">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-white text-xl uppercase tracking-tight">
                            {{ $man->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-700 text-white px-2 py-1 rounded text-xs font-bold">{{ $man->team_count }} Teams</span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-black text-yellow-500 text-xl">
                            {{ intval($man->total_points) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection