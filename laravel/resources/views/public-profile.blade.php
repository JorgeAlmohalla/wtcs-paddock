@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 space-y-8">
    
    <!-- CABECERA PERFIL PÚBLICO -->
    <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 shadow-2xl relative overflow-hidden">
        <!-- Fondo con color del equipo -->
        <div class="absolute top-0 left-0 w-full h-2" style="background-color: {{ $user->team->primary_color ?? '#666' }}"></div>
        
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start relative z-10">
            <!-- Avatar -->
            <div class="h-32 w-32 rounded-full bg-gray-700 flex items-center justify-center text-4xl font-bold text-gray-400 border-4 border-gray-600 shadow-lg">
                {{ substr($user->name, 0, 1) }}
            </div>

            <!-- Datos -->
            <div class="text-center md:text-left flex-1">
                <h1 class="text-4xl font-black text-white uppercase tracking-tight">{{ $user->name }}</h1>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-3">
                    @if($user->team)
                        <span class="px-3 py-1 rounded text-sm font-bold bg-black/30 border border-gray-600 text-white">
                            {{ $user->team->name }}
                        </span>
                    @endif
                    
                    @if($user->nationality)
                        <div class="flex items-center gap-2 px-3 py-1 rounded bg-black/30 border border-gray-600">
                            <img src="https://flagcdn.com/20x15/{{ strtolower($user->nationality) }}.png">
                            <span class="text-sm font-mono text-gray-300">{{ $user->nationality }}</span>
                        </div>
                    @endif

                    <span class="px-3 py-1 rounded text-sm font-bold bg-black/30 border border-gray-600 text-gray-400">
                         {{ $user->equipment === 'wheel' ? '🏎️ Wheel' : '🎮 Controller' }}
                    </span>
                </div>

                <!-- Bio -->
                @if($user->bio)
                    <p class="mt-6 text-gray-400 italic max-w-2xl">"{{ $user->bio }}"</p>
                @endif
            </div>
            
            <!-- Número -->
            @if($user->driver_number)
                <div class="text-6xl font-black text-white/10 select-none">#{{ $user->driver_number }}</div>
            @endif
        </div>
    </div>

    <!-- ESTADÍSTICAS (Igual que Dashboard) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <!-- (Copia aquí los cuadros de Stats del Dashboard: Starts, Wins, Podiums...) -->
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-white">{{ $stats['starts'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Starts</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-yellow-400">{{ $stats['wins'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Wins</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-gray-300">{{ $stats['podiums'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Podiums</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center"><span class="block text-3xl font-black text-purple-400">{{ $stats['poles'] }}</span><span class="text-xs text-gray-500 uppercase font-bold">Poles</span></div>
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-700 text-center bg-gradient-to-br from-red-900/20 to-gray-800"><span class="block text-3xl font-black text-red-500">{{ intval($stats['points']) }}</span><span class="text-xs text-gray-500 uppercase font-bold">Total Points</span></div>
    </div>

    <!-- GRÁFICA -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg">
        <h3 class="text-gray-400 text-sm uppercase tracking-widest font-bold mb-6">Season Performance</h3>
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
                                    borderColor: '{{ $user->team->primary_color ?? '#ef4444' }}', // Usa el color del equipo
                                    backgroundColor: 'rgba(255, 255, 255, 0.05)',
                                    borderWidth: 3,
                                    tension: 0.3,
                                    fill: true
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { grid: { color: '#374151' } }, x: { display: false } } }
                        });
                    }
                }"
            ></canvas>
        </div>
    </div>

</div>
@endsection