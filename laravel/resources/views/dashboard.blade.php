@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
    <!-- Cabecera con Botones de Acción -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-widest">Driver Dashboard</h1>
            <p class="text-gray-400 mt-1">Welcome back, <span class="text-white font-bold">{{ Auth::user()->name }}</span>!</p>
        </div>
        
        <div class="flex gap-4">
            <!-- Botón Editar Perfil -->
            <a href="{{ route('profile.edit') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition border border-gray-600 flex items-center gap-2">
                <span>⚙️ Settings</span>
            </a>

            <!-- Botón Log Out -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition border border-red-500 flex items-center gap-2">
                    <span>🚪 Log Out</span>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Tarjeta de Equipo -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-4">Current Team</h3>
            
            @if(Auth::user()->team)
                <div class="flex items-center gap-4">
                    <div class="h-12 w-1 bg-red-500 rounded-full" style="background-color: {{ Auth::user()->team->primary_color }}"></div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ Auth::user()->team->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->team->car_brand }}</p>
                    </div>
                </div>
            @else
                <div class="bg-yellow-900/30 border border-yellow-700/50 p-4 rounded-lg">
                    <p class="text-yellow-500 font-bold">Free Agent</p>
                    <p class="text-xs text-yellow-200/70 mt-1">You are not signed to any team yet.</p>
                </div>
            @endif
        </div>

        <!-- Tarjeta de Datos de Licencia (Steam/Nacionalidad) -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-4">License Data</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Nationality</span>
                    <span class="text-white font-mono flex items-center gap-2">
                        @if(Auth::user()->nationality)
                            <img src="https://flagcdn.com/16x12/{{ strtolower(Auth::user()->nationality) }}.png">
                        @endif
                        {{ Auth::user()->nationality ?? 'N/A' }}
                    </span>
                </div>
                <div class="flex justify-between border-b border-gray-700 pb-2">
                    <span class="text-gray-500">Steam ID</span>
                    <span class="text-white font-mono text-sm">{{ Auth::user()->steam_id ?? 'Not Linked' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="text-green-400 font-bold uppercase text-sm">{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Estadísticas (Falsa por ahora, para V4) -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg opacity-50 cursor-not-allowed">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-4">Season Stats</h3>
            <p class="text-center text-gray-500 py-4">Coming soon...</p>
        </div>

    </div>
</div>
@endsection