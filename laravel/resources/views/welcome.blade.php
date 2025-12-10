@extends('layouts.app')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-5xl font-bold mb-4">Welcome to the Grid</h1>
        <p class="text-xl text-gray-400 mb-8">Official Management Platform for the World Touring Car Series.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <!-- Tarjeta 1 (Falsa por ahora) -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
                <h3 class="text-xl font-bold text-red-500 mb-2">Next Race</h3>
                <p class="text-3xl font-mono">Silverstone GP</p>
                <p class="text-gray-400 text-sm mt-2">In 3 days</p>
            </div>

            <!-- Tarjeta 2 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
                <h3 class="text-xl font-bold text-red-500 mb-2">Championship Leader</h3>
                <p class="text-3xl">Nando Norris</p>
                <p class="text-gray-400 text-sm mt-2">Red Bull Racing</p>
            </div>

            <!-- Tarjeta 3 -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
                <h3 class="text-xl font-bold text-red-500 mb-2">Latest News</h3>
                <p class="text-lg">Season 3 Registrations are now OPEN!</p>
            </div>
        </div>
    </div>
@endsection