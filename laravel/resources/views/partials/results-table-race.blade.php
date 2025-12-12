<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <!-- CABECERA -->
        <thead class="bg-gray-900 text-gray-400 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4 text-center w-16">Grid</th>
                <th class="px-6 py-4 text-center w-16">Pos</th>
                <th class="px-6 py-4">Driver</th>
                <th class="px-6 py-4 hidden md:table-cell">Team</th>
                <th class="px-6 py-4 text-center">Laps</th>
                <th class="px-6 py-4 text-right">Time/Gap</th>
                <th class="px-6 py-4 text-center">Best Lap</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-right">PTS</th>
            </tr>
        </thead>

        <!-- CUERPO -->
        <tbody class="divide-y divide-gray-700 text-sm bg-gray-800">
            @foreach($results as $result)
                <tr class="hover:bg-gray-700/50 transition duration-150">
                    
                    <!-- GRID -->
                    <td class="px-6 py-4 text-center text-gray-500 font-mono">
                        {{ $result->grid_position ?? '-' }}
                    </td>

                    <!-- POSICIÓN FINAL -->
                    <td class="px-6 py-4 text-center">
                        <span class="font-black text-lg text-white block">{{ $result->position }}</span>
                    </td>

                    <!-- PILOTO -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <!-- Color del equipo (barrita) -->
                            <div class="w-1 h-8 rounded-full" style="background-color: {{ $result->team->primary_color ?? '#666' }}"></div>
                            
                            <div>
                                <div class="font-bold text-white text-base">{{ $result->driver->name }}</div>
                                <div class="text-xs text-gray-500 hidden sm:block md:hidden">{{ $result->team->name ?? 'Privateer' }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- EQUIPO (Desktop) -->
                    <td class="px-6 py-4 hidden md:table-cell text-gray-300 font-medium">
                        {{ $result->team->name ?? 'Privateer' }}
                    </td>

                    <!-- VUELTAS -->
                    <td class="px-6 py-4 text-center text-gray-300">
                        {{ $result->laps_completed ?? '-' }}
                    </td>

                    <!-- TIEMPO / GAP -->
                    <td class="px-6 py-4 text-right font-mono">
                        <div class="{{ in_array($result->status, ['dnf', 'dns']) ? 'text-red-500 font-bold' : 'text-white' }}">
                            <!-- MOSTRAR TIEMPO SI EXISTE, AUNQUE SEA +1 LAP -->
                            @if($result->race_time)
                                {{ $result->race_time }}
                            @elseif(in_array($result->status, ['dnf', 'dns', 'dsq']))
                                - <!-- Si es abandono y no tiene tiempo, guion -->
                            @else
                                {{ strtoupper($result->status) }} <!-- Si es +1 Lap y no tiene tiempo, muestra "+1 LAP" -->
                            @endif
                        </div>
                        
                        @if($result->penalty_seconds > 0)
                            <div class="text-xs text-red-400 font-bold mt-1">
                                +{{ $result->penalty_seconds }}s pen
                            </div>
                        @endif
                    </td>

                    <!-- BEST LAP (Morado) -->
                    <td class="px-6 py-4 text-center font-mono">
                        @if($result->fastest_lap)
                            <span class="text-purple-400 font-bold">{{ $result->fastest_lap_time ?? 'Yes' }}</span>
                            <span class="block text-[10px] text-purple-500/80 uppercase tracking-widest font-bold">Fastest</span>
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
                    </td>

                    <!-- ESTADO (Badges) -->
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusColor = match($result->status) {
                                'finished' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                'dnf', 'dns' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                'dsq' => 'bg-gray-700 text-gray-300 border-gray-600',
                                default => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20', // +1 Lap
                            };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase border {{ $statusColor }}">
                            {{ $result->status === '+1 lap' ? '+1 LAP' : strtoupper($result->status) }}
                        </span>
                    </td>

                    <!-- PUNTOS -->
                    <td class="px-6 py-4 text-right">
                        @if($result->points > 0)
                            <span class="font-black text-white text-lg">{{ intval($result->points) }}</span>
                        @else
                            <span class="text-gray-600">0</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>