@extends('layouts.app')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-5xl font-bold mb-4 text-white">Welcome to the Grid</h1>
        <p class="text-xl text-gray-400 mb-8">Official Management Platform for the World Touring Car Series.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            
            <!-- Tarjeta 1: PRÓXIMA CARRERA -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 relative overflow-hidden group">
                <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest">Next Race</h3>
                
                @if($nextRace)
                    <!-- Si hay carrera programada -->
                    <div class="relative z-10">
                        <p class="text-3xl font-mono text-white">{{ $nextRace->track->name }}</p>
                        <p class="text-lg text-gray-300 mt-1">{{ $nextRace->title ?? 'Round ' . $nextRace->round_number }}</p>
                        
                        <!-- Cuenta atrás sencilla (formato fecha) -->
                        <div class="mt-4 bg-black/50 p-2 rounded text-red-400 font-mono font-bold">
                            {{ $nextRace->race_date->format('d M Y - H:i') }}
                        </div>
                    </div>
                    
                    <!-- Imagen de fondo del circuito (efecto visual) -->
                    @if($nextRace->track->layout_image_url)
                        <img src="{{ asset('storage/' . $nextRace->track->layout_image_url) }}" 
                             class="absolute top-0 right-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition duration-500">
                    @endif
                @else
                    <!-- Si NO hay carrera -->
                    <p class="text-gray-500 italic mt-4">No races scheduled yet.</p>
                @endif
            </div>

            <!-- Tarjeta 2: LÍDER DEL MUNDIAL -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
                <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest">Championship Leader</h3>
                
                @if($leader)
                    <p class="text-4xl font-bold text-white">{{ $leader->name }}</p>
                    <p class="text-red-400 text-lg mt-1 font-bold">{{ $leaderPoints }} PTS</p>
                    
                    @if($leader->team)
                        <div class="mt-4 inline-block px-3 py-1 rounded border border-gray-600 text-sm text-gray-300">
                            {{ $leader->team->name }}
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mt-2">Privateer</p>
                    @endif
                @else
                    <p class="text-gray-500 italic mt-4">Season hasn't started.</p>
                @endif
            </div>

            <!-- Tarjeta 3: NOTICIAS (Aún estática hasta la V2) -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
                <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest">Latest News</h3>
                <p class="text-lg text-white">Season 3 Registrations are now OPEN!</p>
                <a href="#" class="text-sm text-gray-400 hover:text-white underline mt-2 block">Read more -></a>
            </div>
        </div>
    </div>
@endsection