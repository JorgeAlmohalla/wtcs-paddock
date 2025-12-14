@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-8">
    
    <!-- CABECERA: PERFIL Y BIO -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg flex flex-col md:flex-row gap-8 items-start">
        <!-- Avatar y Datos Básicos -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left min-w-[200px]">
            <div class="h-24 w-24 rounded-full bg-gray-700 flex items-center justify-center text-3xl font-bold text-gray-400 border-4 border-gray-600 mb-4">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
            <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                @if($user->nationality)
                    <img src="https://flagcdn.com/20x15/{{ strtolower($user->nationality) }}.png" class="rounded shadow">
                @endif
                <span class="text-gray-400 font-mono text-sm">{{ $user->nationality }}</span>
            </div>
            <div class="mt-4">
                <span class="px-3 py-1 rounded text-xs font-bold uppercase bg-gray-900 text-gray-300 border border-gray-600">
                    {{ $user->equipment === 'wheel' ? '🏎️ Wheel' : ($user->equipment === 'pad' ? '🎮 Controller' : '⌨️ Keyboard') }}
                </span>
            </div>
        </div>

        <!-- Bio y Equipo -->
        <div class="flex-1 w-full">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 uppercase text-xs font-bold tracking-widest mb-1">Current Team</h3>
                    @if($user->team)
                        <span class="text-2xl font-bold text-white">{{ $user->team->name }}</span>
                    @else
                        <span class="text-xl font-bold text-yellow-500">Free Agent</span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('profile.edit') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 rounded text-xs font-bold transition">⚙️ Edit</a>
                    
                    <!-- Botón de Reporte (Futuro) -->
                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-xs font-bold transition flex items-center gap-1 opacity-50 cursor-not-allowed" title="Coming soon">
                        ⚠️ Report Incident
                    </button>
                </div>
            </div>

            <div class="mt-6 p-4 bg-gray-900/50 rounded-lg border border-gray-700/50">
                <h3 class="text-gray-500 uppercase text-xs font-bold tracking-widest mb-2">Driver Bio</h3>
                <p class="text-gray-300 text-sm italic">
                    {{ $user->bio ?: "No bio provided yet. Update your profile to add your experience!" }}
                </p>
            </div>
        </div>
    </div>

    <!-- FILA DE ESTADÍSTICAS (BIG NUMBERS) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center">
            <span class="block text-3xl font-black text-white">{{ $stats['starts'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold">Starts</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center">
            <span class="block text-3xl font-black text-yellow-400">{{ $stats['wins'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold">Wins</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center">
            <span class="block text-3xl font-black text-gray-300">{{ $stats['podiums'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold">Podiums</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center">
            <span class="block text-3xl font-black text-purple-400">{{ $stats['poles'] }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold">Poles</span>
        </div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center col-span-2 md:col-span-1 bg-gradient-to-br from-red-900/20 to-gray-800">
            <span class="block text-3xl font-black text-red-500">{{ intval($stats['points']) }}</span>
            <span class="text-xs text-gray-500 uppercase font-bold">Total Points</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- GRÁFICA DE RENDIMIENTO -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
            <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6">Season Progression</h3>
            <div class="relative h-64 w-full">
                <canvas id="pointsChart"
                    x-data="{
                        labels: {{ Js::from($labels) }},
                        data: {{ Js::from($data) }},
                        init() {
                            new Chart(document.getElementById('pointsChart'), {
                                type: 'line',
                                data: {
                                    labels: this.labels,
                                    datasets: [{
                                        label: 'Points',
                                        data: this.data,
                                        borderColor: '#ef4444',
                                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                        borderWidth: 2,
                                        pointRadius: 4,
                                        pointHoverRadius: 6,
                                        tension: 0.3,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: { grid: { color: '#374151' }, ticks: { color: '#9ca3af' } },
                                        x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                                    }
                                }
                            });
                        }
                    }"
                ></canvas>
            </div>
        </div>

        <!-- TABLA DE QUALIFYING (LO QUE PEDÍAS) -->
        <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-lg">
            <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Qualifying History</h3>
            </div>
            <div class="overflow-y-auto max-h-72">
                <table class="w-full text-left">
                    <thead class="bg-gray-900 text-gray-500 text-xs uppercase">
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
                                <td class="px-6 py-3 text-center">
                                    <span class="font-bold {{ $q->position == 1 ? 'text-purple-400' : 'text-white' }}">{{ $q->position }}</span>
                                </td>
                                <td class="px-6 py-3 text-right font-mono text-gray-300">{{ $q->best_time }}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="w-3 h-3 rounded-full inline-block 
                                        {{ strtolower($q->tyre_compound) === 'soft' ? 'bg-red-500' : (strtolower($q->tyre_compound) === 'medium' ? 'bg-yellow-500' : 'bg-white') }}">
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">No qualifying data yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection