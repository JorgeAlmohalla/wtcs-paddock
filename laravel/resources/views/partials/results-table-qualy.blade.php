<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-900 text-gray-400 text-xs uppercase font-bold tracking-wider">
            <tr>
                <th class="px-6 py-4 text-center w-16">Pos</th>
                <th class="px-6 py-4">Driver</th>
                <th class="px-6 py-4 hidden md:table-cell">Team</th>
                <th class="px-6 py-4 hidden lg:table-cell">Car</th>
                <th class="px-6 py-4 text-right">Time</th>
                <th class="px-6 py-4 text-center w-24">Tyre</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700 text-sm bg-gray-800">
            @foreach($results as $qResult)
                <tr class="hover:bg-gray-700/50 transition duration-150 {{ ($qResult->team->type ?? '') === 'privateer' ? 'bg-cyan-400/25' : '' }}">
                    
                    <!-- POSICIÓN -->
                    <td class="px-6 py-4 text-center font-black text-white text-lg">
                        {{ $qResult->position }}
                    </td>

                    <!-- PILOTO -->
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <!-- Color Equipo -->
                            <div class="w-1 h-8 rounded-full" style="background-color: {{ $qResult->team->primary_color ?? '#666' }}"></div>
                            
                            <!-- Nombre y Número -->
                            <div>
                                <div class="flex items-baseline gap-2"> <!-- items-baseline alinea el texto por la base -->
                                    <span class="text-gray-500 font-mono text-sm font-bold">
                                        #{{ $qResult->driver_number ?? $qResult->driver->driver_number }}
                                    </span>
                                    <span class="font-bold text-white text-base">
                                        {{ $qResult->driver->name }}
                                    </span>
                                </div>
                                
                                <!-- Equipo en móvil -->
                                <div class="text-xs text-gray-500 hidden sm:block md:hidden mt-0.5">
                                    {{ $qResult->team->name ?? 'Privateer' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- EQUIPO -->
                    <td class="px-6 py-4 hidden md:table-cell text-gray-300 font-medium">
                        {{ $qResult->team->name ?? 'Privateer' }}
                    </td>

                    <!-- COCHE -->
                    <td class="px-6 py-4 hidden lg:table-cell text-gray-400 text-xs font-mono uppercase tracking-wide">
                        {{ $qResult->car_name ?? ($qResult->team->car_model ?? '-') }}
                    </td>

                    <!-- TIEMPO -->
                    <td class="px-6 py-4 text-right font-mono text-white tracking-wide">
                        {{ $qResult->best_time ?? '-' }}
                    </td>

                    <!-- NEUMÁTICO -->
                    <td class="px-6 py-4 text-center">
                        @if($qResult->tyre_compound)
                            @php
                                $tyreClass = match(strtolower($qResult->tyre_compound)) {
                                    'soft' => 'bg-red-600 text-white border-red-500',
                                    'medium' => 'bg-yellow-400 text-black border-yellow-500 font-bold',
                                    'hard' => 'bg-gray-100 text-black border-white font-bold',
                                    'wet' => 'bg-blue-600 text-white border-blue-500',
                                    'inter' => 'bg-green-600 text-white border-green-500',
                                    default => 'bg-gray-700 text-gray-300 border-gray-600',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border shadow-sm {{ $tyreClass }}">
                                {{ ucfirst($qResult->tyre_compound) }}
                            </span>
                        @else
                            <span class="text-gray-600">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>