@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold mb-4 text-white text-center uppercase tracking-widest">Teams</h1>
        
        <!-- BUSCADOR -->
        <div class="max-w-md mx-auto relative">
            <form method="GET" action="{{ route('teams') }}">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search team or car..." 
                       class="w-full bg-gray-800 text-white border border-gray-600 rounded-full py-3 px-6 pl-12 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none shadow-lg transition duration-200 placeholder-gray-500">
                <svg class="w-5 h-5 text-gray-500 absolute left-4 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($teams as $team)
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 flex flex-col hover:shadow-2xl transition duration-300 group">
                
                <!-- CABECERA (Con Logo Flotante) -->
                <div class="h-28 flex items-center px-6 relative" 
                     style="background: linear-gradient(135deg, {{ $team->primary_color ?? '#333' }} 0%, #1f2937 100%);">
                    
                    <!-- Nombre y Coche -->
                    <div class="relative z-10 w-3/4">
                        <a href="{{ route('team.show', $team) }}" class="text-3xl font-black text-white hover:underline decoration-white/50 underline-offset-4 transition leading-none drop-shadow-md block">
                            {{ $team->name }}
                        </a>
                        <p class="text-white/80 font-mono text-xs mt-2 uppercase tracking-widest font-bold">{{ $team->car_brand }} // {{ $team->car_model }}</p>
                    </div>

                    <!-- Logo Flotante (Círculo blanco) -->
                    <div class="absolute right-6 -bottom-8 z-20 h-20 w-20 bg-gray-800 rounded-full border-4 border-gray-700 p-1 shadow-xl flex items-center justify-center overflow-hidden">
                        @if($team->logo_url)
                            <img src="{{ asset('storage/' . $team->logo_url) }}" class="max-h-full max-w-full object-contain" alt="logo">
                        @else
                            <span class="text-2xl font-black text-gray-600">{{ $team->short_name }}</span>
                        @endif
                    </div>
                </div>

                <div class="p-6 pt-10 flex-grow flex flex-col">
                    
                    <!-- BARRA DE ESTADÍSTICAS -->
                    <div class="flex justify-between items-center bg-black/20 rounded-lg p-3 mb-6 border border-gray-700/50">
                        <div class="text-center">
                            <span class="block text-2xl font-black text-yellow-500 leading-none">{{ $team->stats['wins'] }}</span>
                            <span class="text-[10px] text-gray-500 uppercase font-bold">Wins</span>
                        </div>
                        <div class="text-center border-l border-gray-700 pl-4">
                            <span class="block text-2xl font-black text-gray-300 leading-none">{{ $team->stats['podiums'] }}</span>
                            <span class="text-[10px] text-gray-500 uppercase font-bold">Podiums</span>
                        </div>
                        <div class="text-center border-l border-gray-700 pl-4">
                            <span class="block text-2xl font-black text-blue-400 leading-none">{{ intval($team->stats['points']) }}</span>
                            <span class="text-[10px] text-gray-500 uppercase font-bold">Points</span>
                        </div>
                    </div>

                    <!-- BIO (Extracto) -->
                    @if($team->bio)
                        <p class="text-gray-400 text-sm mb-6 line-clamp-2 italic">"{{ $team->bio }}"</p>
                    @endif

                    <!-- ROSTER (Igual que antes) -->
                    <div class="mt-auto">
                        <div class="flex justify-between items-end mb-3 border-b border-gray-700 pb-1">
                            <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Active Roster</p>
                            <span class="text-[10px] px-2 py-0.5 rounded {{ $team->type === 'works' ? 'bg-green-900 text-green-400' : 'bg-blue-900 text-blue-400' }} font-bold uppercase border border-white/10">
                                {{ $team->type }}
                            </span>
                        </div>

                        @if($team->drivers->count() > 0)
                            <div class="space-y-2">
                                @foreach($team->drivers->sortBy('contract_type') as $driver)
                                    <a href="{{ route('driver.show', $driver) }}" class="flex items-center justify-between p-2 rounded hover:bg-white/5 transition group">
                                        <div class="flex items-center gap-3">
                                            <div class="h-6 w-1 rounded-full {{ $driver->contract_type === 'reserve' ? 'bg-gray-600' : '' }}" 
                                                 style="{{ $driver->contract_type === 'primary' ? 'background-color: '.$team->primary_color : '' }}"></div>
                                            <span class="text-white font-bold text-sm group-hover:text-red-400 transition {{ $driver->contract_type === 'reserve' ? 'text-gray-500' : '' }}">
                                                {{ $driver->name }}
                                            </span>
                                        </div>
                                        @if($driver->isTeamPrincipal())
                                            <span class="text-[10px] text-yellow-500 font-bold border border-yellow-500/30 px-1.5 rounded bg-yellow-500/10">TP</span>
                                        @endif
                                    </a>
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