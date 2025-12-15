@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    
    <!-- Cabecera -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black text-white uppercase tracking-wider">Report Incident</h1>
        <p class="text-gray-400 mt-2">Submit a formal report to the Stewards office.</p>
    </div>

    <!-- Formulario -->
    <div class="bg-gray-800 rounded-xl p-6 md:p-8 border border-gray-700 shadow-2xl">
        <form method="POST" action="{{ route('report.store') }}" class="space-y-6">
            @csrf

            <!-- 1. Selección de Carrera -->
            <div>
                <label for="race_id" class="block text-sm font-bold text-gray-300 uppercase mb-2">Select Race</label>
                <select id="race_id" name="race_id" required 
                        class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="" disabled selected>Choose the event...</option>
                    @foreach($races as $race)
                        <option value="{{ $race->id }}">
                            R{{ $race->round_number }} - {{ $race->track->name }} ({{ $race->race_date->format('d M') }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('race_id')" class="mt-2" />
            </div>

            <!-- 2. Piloto Acusado -->
            <div>
                <label for="reported_id" class="block text-sm font-bold text-gray-300 uppercase mb-2">Driver Involved</label>
                <select id="reported_id" name="reported_id" required 
                        class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="" disabled selected>Who are you reporting?</option>
                    @foreach($drivers as $driver)
                        @if($driver->id !== Auth::id()) <!-- No te puedes reportar a ti mismo -->
                            <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->team->name ?? 'Privateer' }})</option>
                        @endif
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('reported_id')" class="mt-2" />
            </div>

            <!-- 3. Vuelta y Curva -->
            <div>
                <label for="lap_corner" class="block text-sm font-bold text-gray-300 uppercase mb-2">Location</label>
                <input type="text" id="lap_corner" name="lap_corner" placeholder="e.g. Lap 4, Turn 1" required
                       class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <x-input-error :messages="$errors->get('lap_corner')" class="mt-2" />
            </div>

            <!-- 4. Descripción -->
            <div>
                <label for="description" class="block text-sm font-bold text-gray-300 uppercase mb-2">Description</label>
                <textarea id="description" name="description" rows="4" required placeholder="Describe what happened..."
                          class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- 5. Enlace al Vídeo -->
            <div>
                <label for="video_url" class="block text-sm font-bold text-gray-300 uppercase mb-2">Video Evidence (URL)</label>
                <input type="url" id="video_url" name="video_url" placeholder="https://youtube.com/..." required
                       class="w-full bg-gray-900 border border-gray-600 text-white rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <p class="text-xs text-gray-500 mt-1">Please upload your clip to YouTube, Streamable or Google Drive and paste the link here.</p>
                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white font-bold transition">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold shadow-lg shadow-red-900/20 transition transform hover:scale-105">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection