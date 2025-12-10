@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-bold mb-8 text-white text-center uppercase tracking-widest">Championship Standings</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        
        <!-- DRIVERS CHAMPIONSHIP -->
        <div>
            <h2 class="text-2xl font-bold text-red-500 mb-4 border-b border-gray-700 pb-2">Drivers</h2>
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3">Pos</th>
                            <th class="px-6 py-3">Driver</th>
                            <th class="px-6 py-3 text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 divide-y divide-gray-700">
                        @foreach($drivers as $index => $driver)
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-6 py-4 font-bold text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 flex items-center space-x-3">
                                <div>
                                    <p class="font-bold text-white">{{ $driver->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $driver->team->name ?? 'Privateer' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-red-400 text-lg">
                                {{ intval($driver->total_points) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CONSTRUCTORS CHAMPIONSHIP -->
        <div>
            <h2 class="text-2xl font-bold text-blue-500 mb-4 border-b border-gray-700 pb-2">Constructors</h2>
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 text-gray-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3">Pos</th>
                            <th class="px-6 py-3">Team</th>
                            <th class="px-6 py-3 text-right">Points</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 divide-y divide-gray-700">
                        @foreach($teams as $index => $team)
                        <tr class="hover:bg-gray-700 transition" style="border-left: 4px solid {{ $team->primary_color ?? '#fff' }}">
                            <td class="px-6 py-4 font-bold text-white">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-white">{{ $team->name }}</p>
                                <p class="text-xs text-gray-500">{{ $team->car_brand }}</p>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-blue-400 text-lg">
                                {{ floatval($team->total_points) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection