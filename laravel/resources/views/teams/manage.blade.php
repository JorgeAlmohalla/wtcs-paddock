@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white uppercase tracking-widest">Team Management</h1>
        <a href="/dashboard" class="text-gray-400 hover:text-white">Back to Dashboard</a>
    </div>

    <!-- 1. EDITAR EQUIPO -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg mb-8">
        <h2 class="text-xl font-bold text-white mb-4 border-b border-gray-600 pb-2">Team Details</h2>
        
        <form method="POST" action="{{ route('team.update') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Team Name</label>
                <input type="text" name="name" value="{{ $team->name }}" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-blue-500 focus:ring-blue-500">
            </div>

             <!-- SUBIDA DE COCHE -->
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Car Livery Photo</label>
                @if($team->car_image_url)
                    <img src="{{ asset('storage/' . $team->car_image_url) }}" class="h-32 w-auto rounded mb-2 border border-gray-600">
                @endif
                <input type="file" name="car_image" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Car Model</label>
                    <input type="text" name="car_model" value="{{ $team->car_model }}" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Primary Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="primary_color" value="{{ $team->primary_color }}" class="h-10 w-10 rounded cursor-pointer border-0">
                        <input type="text" value="{{ $team->primary_color }}" class="flex-1 bg-gray-900 border border-gray-600 rounded p-2 text-white uppercase font-mono" readonly>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded transition shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- 2. FICHAR PILOTO (SIGN DRIVER) -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg mb-8">
        <h2 class="text-xl font-bold text-white mb-4 border-b border-gray-600 pb-2">Sign Driver</h2>
        <p class="text-gray-400 text-sm mb-4">Select a free agent to join your team roster.</p>
        
        <form method="POST" action="{{ route('team.addDriver') }}" class="flex flex-col md:flex-row gap-4 items-end">
            @csrf
            
            <!-- Selector de Piloto -->
            <div class="flex-1 w-full">
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Driver</label>
                <select name="driver_id" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-green-500 focus:ring-green-500">
                    <option value="" disabled selected>Select a Free Agent...</option>
                    @foreach($freeAgents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->nationality }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Selector de Contrato (NUEVO) -->
            <div class="w-full md:w-48">
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Contract Type</label>
                <select name="contract_type" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-green-500 focus:ring-green-500">
                    <option value="primary">Primary Driver</option>
                    <option value="reserve">Reserve Driver</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-6 rounded transition shadow-md">
                Sign
            </button>
        </form>
    </div>

    <!-- 3. LISTA DE PILOTOS (ROSTER) -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
        <div class="flex justify-between items-center mb-4 border-b border-gray-600 pb-2">
            <h2 class="text-xl font-bold text-white">Current Roster</h2>
            <span class="text-gray-500 text-sm">{{ $team->drivers->count() }} Drivers</span>
        </div>

        <div class="space-y-3">
            @foreach($team->drivers->sortByDesc(fn($d) => $d->isTeamPrincipal()) as $driver)
                <div class="flex items-center justify-between bg-gray-900/50 p-4 rounded border border-gray-700 hover:border-gray-500 transition">
                    
                    <!-- Info Piloto -->
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-gray-400 border border-gray-600">
                            {{ substr($driver->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-white font-bold">{{ $driver->name }}</p>
                                @if($driver->nationality)
                                    <img src="https://flagcdn.com/16x12/{{ strtolower($driver->nationality) }}.png" class="opacity-80">
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ $driver->email }}</p>
                        </div>
                    </div>

                    <!-- Botones / Etiquetas -->
                    <div class="flex items-center gap-4">
                        @if($driver->isTeamPrincipal())
                            <span class="bg-yellow-500/20 text-yellow-500 text-[10px] font-bold px-3 py-1 rounded border border-yellow-500/30 uppercase tracking-wider">
                                TEAM PRINCIPAL
                            </span>
                        @else
                            <span class="text-gray-500 text-xs uppercase font-bold tracking-wider mr-2">Driver</span>
                            
                            <!-- Botón DESPEDIR (Remove) -->
                            <form method="POST" action="{{ route('team.removeDriver', $driver) }}" onsubmit="return confirm('Are you sure you want to remove {{ $driver->name }} from the team?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-bold uppercase border border-red-500/30 hover:border-red-500 px-3 py-1 rounded transition">
                                    Remove
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection