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
        
        <form method="POST" action="{{ route('team.update') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- NOMBRE Y MODELO BÁSICO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Team Name</label>
                    <input type="text" name="name" value="{{ $team->name }}" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Car Model Name</label>
                    <input type="text" name="car_model" value="{{ $team->car_model }}" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- FOTO DEL COCHE -->
            <div class="border-t border-gray-700 pt-4">
                <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Car Livery Photo</label>
                <div class="flex items-center gap-4">
                    @if($team->car_image_url)
                        <img src="{{ asset('storage/' . $team->car_image_url) }}" class="h-24 w-auto rounded border border-gray-600 object-cover">
                    @endif
                    <input type="file" name="car_image" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                </div>
            </div>

            <!-- ESPECIFICACIONES TÉCNICAS (NUEVO BLOQUE) -->
            <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                <h3 class="text-gray-400 font-bold uppercase text-xs mb-3 tracking-wider">Technical Homologation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Columna 1 -->
                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Chassis / Production Years</label>
                        <input type="text" name="tech_chassis" value="{{ $team->tech_chassis }}" placeholder="e.g. Sedan (1998-2002)" class="w-full bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Engine Type</label>
                        <input type="text" name="tech_engine" value="{{ $team->tech_engine }}" placeholder="e.g. 2.0L I4 NA" class="w-full bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                    </div>
                    
                    <!-- Columna 2 -->
                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Power Output</label>
                        <input type="text" name="tech_power" value="{{ $team->tech_power }}" placeholder="e.g. 310 bhp" class="w-full bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Drivetrain & Gearbox</label>
                        <div class="flex gap-2">
                            <select name="tech_drivetrain" class="w-1/3 bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                                <option value="FF" {{ $team->tech_drivetrain == 'FF' ? 'selected' : '' }}>FF</option>
                                <option value="FR" {{ $team->tech_drivetrain == 'FR' ? 'selected' : '' }}>FR</option>
                                <option value="MR" {{ $team->tech_drivetrain == 'MR' ? 'selected' : '' }}>MR</option>
                                <option value="RR" {{ $team->tech_drivetrain == 'RR' ? 'selected' : '' }}>RR</option>
                                <option value="4WD" {{ $team->tech_drivetrain == '4WD' ? 'selected' : '' }}>4WD</option>
                            </select>
                            <input type="text" name="tech_gearbox" value="{{ $team->tech_gearbox }}" placeholder="e.g. 6-Speed Seq" class="w-2/3 bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                        </div>
                    </div>

                    <!-- Peso y Año -->
                    <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Weight (Kg)</label>
                        <input type="number" name="tech_weight" value="{{ $team->tech_weight }}" placeholder="975" class="w-full bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                    </div>
                     <div>
                        <label class="block text-gray-500 text-[10px] font-bold uppercase mb-1">Model Year</label>
                        <input type="number" name="car_year" value="{{ $team->car_year }}" placeholder="1999" class="w-full bg-gray-800 border border-gray-600 rounded p-2 text-white text-sm">
                    </div>
                </div>
            </div>

            <!-- LOGO Y COLOR -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-700">
                <div class="md:col-span-1">
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Team Logo</label>
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 bg-gray-900 rounded-full border-2 border-gray-600 flex items-center justify-center overflow-hidden">
                            @if($team->logo_url)
                                <img src="{{ asset('storage/' . $team->logo_url) }}" class="max-h-full max-w-full p-2 object-contain">
                            @else
                                <span class="text-gray-600 text-xl font-black">{{ $team->short_name }}</span>
                            @endif
                        </div>
                        <input type="file" name="team_logo" class="block w-full text-xs text-gray-400">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Primary Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="primary_color" value="{{ $team->primary_color }}" class="h-10 w-10 rounded cursor-pointer border-0">
                        <input type="text" value="{{ $team->primary_color }}" class="flex-1 bg-gray-900 border border-gray-600 rounded p-2 text-white uppercase font-mono" readonly>
                    </div>
                </div>
                
                <div class="md:col-span-3">
                    <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Bio</label>
                    <textarea name="bio" rows="3" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white text-sm">{{ old('bio', $team->bio) }}</textarea>
                </div>
            </div>

            <div class="text-right pt-4 border-t border-gray-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded transition shadow-lg transform hover:scale-105">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- 2. FICHAR PILOTO (SIGN DRIVER) -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg mb-8">
        <h2 class="text-xl font-bold text-white mb-4 border-b border-gray-600 pb-2">Sign Driver</h2>
        
        <form method="POST" action="{{ route('team.addDriver') }}" class="flex flex-col md:flex-row gap-4 items-end">
            @csrf
            
            <div class="flex-1 w-full">
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Driver</label>
                <select name="driver_id" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white">
                    <option value="" disabled selected>Select Free Agent...</option>
                    @foreach($freeAgents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Contract Type</label>
                <select name="contract_type" class="w-full bg-gray-900 border border-gray-600 rounded p-2 text-white">
                    <option value="primary">Primary</option>
                    <option value="reserve">Reserve</option>
                </select>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-6 rounded transition">
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
            @foreach($team->drivers->sortBy('contract_type') as $driver)
                <div class="flex items-center justify-between bg-gray-900/50 p-4 rounded border border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-gray-400 border border-gray-600">
                            {{ substr($driver->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-white font-bold">{{ $driver->name }}</p>
                            @if($driver->contract_type === 'reserve')
                                <span class="text-xs text-gray-500 uppercase font-bold">Reserve Driver</span>
                            @else
                                <span class="text-xs text-green-500 uppercase font-bold">Primary Driver</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        @if($driver->isTeamPrincipal())
                            <span class="bg-yellow-500/20 text-yellow-500 text-[10px] font-bold px-3 py-1 rounded border border-yellow-500/30">PRINCIPAL</span>
                        @else
                            <form method="POST" action="{{ route('team.removeDriver', $driver) }}" onsubmit="return confirm('Remove driver?');">
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