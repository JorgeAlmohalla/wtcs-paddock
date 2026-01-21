@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-8">
    
    <!-- 1. CABECERA -->
    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-10 bg-gradient-to-r from-gray-900 to-gray-800"></div>
        <div class="absolute left-0 top-0 bottom-0 w-2 z-10" style="background-color: {{ $user->team->primary_color ?? '#666' }}"></div>
        
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start relative z-10 pl-4">
            <!-- Avatar -->
            <div class="relative flex-shrink-0">
                @if($user->avatar_url)
                    <img src="{{ asset('storage/' . $user->avatar_url) }}" class="h-32 w-32 rounded-full object-cover border-4 border-gray-600 shadow-xl" alt="avatar">
                @else
                    <div class="h-32 w-32 rounded-full bg-gray-700 flex items-center justify-center text-5xl font-bold text-gray-500 border-4 border-gray-600">{{ substr($user->name, 0, 1) }}</div>
                @endif
                @if($user->nationality)
                    <img src="https://flagcdn.com/28x21/{{ strtolower($user->nationality) }}.png" class="absolute bottom-0 right-0 rounded shadow border border-gray-800 transform translate-x-2" alt="nationality">
                @endif
            </div>

            <div class="text-center md:text-left flex-1 min-w-0">
                <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight truncate">{{ $user->name }}</h1>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-3">
                    <span class="px-3 py-1 rounded text-sm font-bold bg-black/40 border border-gray-600 text-white">
                        {{ $user->team->name ?? 'Free Agent' }}
                    </span>
                    <span class="px-3 py-1 rounded text-sm font-bold bg-black/40 border border-gray-600 text-gray-400">
                         {{ $user->equipment === 'wheel' ? '🏎️ Wheel' : ($user->equipment === 'pad' ? '🎮 Pad' : '⌨️ Keyboard') }}
                    </span>
                </div>

                @if($user->bio)
                    <div class="mt-6 p-4 bg-black/20 rounded-lg border-l-4 border-gray-600">
                        <p class="text-gray-300 italic text-sm">"{{ $user->bio }}"</p>
                    </div>
                @endif
            </div>
            
            @if($user->driver_number)
                <div class="text-8xl font-black text-white/5 select-none absolute right-0 top-0 md:relative">#{{ $user->driver_number }}</div>
            @endif
        </div>
    </div>

    <!-- 2. BIG NUMBERS -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-white">{{ $stats['starts'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Starts</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-yellow-400">{{ $stats['wins'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Wins</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-gray-300">{{ $stats['podiums'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Podiums</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-purple-400">{{ $stats['poles'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Poles</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center bg-gradient-to-br from-red-900/20 to-gray-800"><span class="block text-3xl font-black text-red-500">{{ intval($stats['points']) }}</span><span class="text-xs text-gray-500 uppercase font-bold">Points</span></div>
    </div>

    <!-- 3. GRÁFICAS POSICIÓN (Arriba) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Race Pos Chart -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6">Race Finish Position</h3>
            <div class="relative h-64 w-full">
                <canvas id="racePosChart"
                    x-data="{
                        labels: {{ Js::from($raceLabels) }},
                        data: {{ Js::from($raceData) }},
                        init() {
                            new Chart(document.getElementById('racePosChart'), {
                                type: 'line',
                                data: { labels: this.labels, datasets: [{ label: 'Pos', data: this.data, borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', borderWidth: 2, tension: 0.2, fill: 'start' }] },
                                options: {
                                    responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                                    scales: {
                                        y: { reverse: true, min: 1, grid: { color: '#374151' }, ticks: { stepSize: 1, color: '#9ca3af' } },
                                        x: { display: true, grid: { display: false }, ticks: { color: '#9ca3af' } } // EJE X VISIBLE
                                    }
                                }
                            });
                        }
                    }"
                ></canvas>
            </div>
        </div>

        <!-- Qualy Pos Chart -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6">Qualifying Pace</h3>
            <div class="relative h-64 w-full">
                <canvas id="qualyPosChart"
                    x-data="{
                        labels: {{ Js::from($qualyLabels) }}, // ANTES ERA $raceLabels
                        data: {{ Js::from($qualyData) }},
                        init() {
                            new Chart(document.getElementById('qualyPosChart'), {
                                type: 'line',
                                data: { labels: this.labels, datasets: [{ label: 'Grid', data: this.data, borderColor: '#a855f7', backgroundColor: 'rgba(168, 85, 247, 0.1)', borderWidth: 2, tension: 0.2, fill: 'start' }] },
                                options: {
                                    responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                                    scales: {
                                        y: { reverse: true, min: 1, grid: { color: '#374151' }, ticks: { stepSize: 1, color: '#9ca3af' } },
                                        x: { display: true, grid: { display: false }, ticks: { color: '#9ca3af' } } // EJE X VISIBLE
                                    }
                                }
                            });
                        }
                    }"
                ></canvas>
            </div>
        </div>
    </div>

    <!-- 4. PUNTOS Y TABLA (Abajo) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Points Chart -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6">Championship Points</h3>
            <div class="relative h-64 w-full">
                <canvas id="pointsChart"
                    x-data="{
                        labels: {{ Js::from($raceLabels) }},
                        data: {{ Js::from($racePointsData) }},
                        init() {
                            new Chart(document.getElementById('pointsChart'), {
                                type: 'line',
                                data: { labels: this.labels, datasets: [{ label: 'Total', data: this.data, borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', borderWidth: 3, tension: 0.4, fill: true }] },
                                options: {
                                    responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                                    scales: {
                                        y: { beginAtZero: true, grid: { color: '#374151' }, ticks: { color: '#9ca3af' } },
                                        x: { display: true, grid: { display: false }, ticks: { color: '#9ca3af' } }
                                    }
                                }
                            });
                        }
                    }"
                ></canvas>
            </div>
        </div>

        <!-- Qualy Table -->
        <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg flex flex-col h-full">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Qualifying History</h3>
            </div>
            <div class="overflow-y-auto max-h-[300px]">
                <table class="w-full text-left">
                    <thead class="bg-gray-900/50 text-gray-500 text-xs uppercase sticky top-0">
                        <tr>
                            <th class="px-6 py-3">Round</th>
                            <th class="px-6 py-3 text-center">Grid</th>
                            <th class="px-6 py-3 text-right">Time</th>
                            <th class="px-6 py-3 text-center">Tyre</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-sm">
                        @forelse($qualyHistory as $q)
                            <tr class="hover:bg-gray-700/50">
                                <td class="px-6 py-3 font-bold text-white">{{ $q->race->track->name }}</td>
                                <td class="px-6 py-3 text-center"><span class="font-bold {{ $q->position == 1 ? 'text-purple-400' : 'text-white' }}">{{ $q->position }}</span></td>
                                <td class="px-6 py-3 text-right font-mono text-gray-300">{{ $q->best_time }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="w-3 h-3 rounded-full inline-block {{ strtolower($q->tyre_compound ?? '') === 'soft' ? 'bg-red-500' : (strtolower($q->tyre_compound ?? '') === 'medium' ? 'bg-yellow-500' : 'bg-white') }}"></span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No qualifying data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection