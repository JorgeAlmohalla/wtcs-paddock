@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
            <div class="p-6 text-white">
                <h1 class="text-2xl font-bold mb-4">Driver Dashboard</h1>
                <p class="text-gray-300">Welcome back, {{ Auth::user()->name }}!</p>
                
                <div class="mt-6 p-4 bg-gray-900 rounded border border-gray-700">
                    <p class="text-sm text-gray-400 uppercase tracking-widest mb-2">My Team</p>
                    <p class="text-xl font-bold text-red-500">{{ Auth::user()->team->name ?? 'Free Agent' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection