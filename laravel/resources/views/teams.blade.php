@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold mb-10 text-white text-center uppercase tracking-widest">Constructors</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($teams as $team)
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                
                <!-- Cabecera con el color del equipo -->
                <div class="h-24 flex items-center px-6 relative overflow-hidden" 
                     style="background: linear-gradient(135deg, {{ $team->primary_color ?? '#333' }} 0%, #1f2937 100%);">
                    
                    <h2 class="text-3xl font-bold text-white relative z-10">{{ $team->name }}</h2>
                    <span class="text-6xl font-black text-white opacity-5 absolute -right-4 -bottom-8 select-none">
                        {{ $team->short_name }}
                    </span>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-gray-400 text-sm uppercase tracking-wide">Manufacturer</p>
                            <p class="text-xl text-white font-semibold">{{ $team->car_brand ?? 'Unknown' }}</p>

                            @if($team->car_model)
                                <p class="text-sm text-gray-500 mt-1 font-mono uppercase">{{ $team->car_model }}</p>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded text-xs font-bold uppercase tracking-wider {{ $team->type === 'works' ? 'bg-green-900 text-green-200' : 'bg-yellow-900 text-yellow-200' }}">
                            {{ $team->type }}
                        </span>
                    </div>

                    <!-- Lista de Pilotos del equipo -->
                    <div class="border-t border-gray-700 pt-4">
                        <p class="text-gray-500 text-sm mb-3">Active Drivers</p>
                        @if($team->drivers->count() > 0)
                            <div class="space-y-2">
                                @foreach($team->drivers as $driver)
                                    <div class="flex items-center gap-3">
                                        <div class="h-2 w-2 rounded-full" style="background-color: {{ $team->primary_color }}"></div>
                                        <span class="text-white font-medium">{{ $driver->name }}</span>
                                        @if($driver->nationality)
                                            <img src="https://flagcdn.com/16x12/{{ strtolower($driver->nationality) }}.png" class="opacity-70">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 italic text-sm">No drivers assigned.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection