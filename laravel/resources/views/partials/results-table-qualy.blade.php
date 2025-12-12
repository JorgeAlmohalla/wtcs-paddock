<div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-900 text-gray-400 text-xs uppercase font-bold">
            <tr>
                <th class="px-6 py-4 text-center w-16">Pos</th>
                <th class="px-6 py-4">Driver</th>
                <th class="px-6 py-4 hidden md:table-cell">Team</th>
                <th class="px-6 py-4 text-right">Time</th>
                <th class="px-6 py-4 text-center w-24">Tyre</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700 text-sm bg-gray-800">
            @foreach($results as $qResult)
                <tr class="hover:bg-gray-700/50 transition">
                    <!-- POSICIÓN -->
                    <td class="px-6 py-4 text-center font-black text-white text-lg">
                        {{ $qResult->position }}
                    </td>

                    <!-- PILOTO -->
                    <td class="px-6 py-4">
                        <div class="font-bold text-white text-base">{{ $qResult->driver->name }}</div>
                    </td>

                    <!-- EQUIPO -->
                    <td class="px-6 py-4 hidden md:table-cell text-gray-300 font-medium">
                        {{ $qResult->team->name ?? 'Privateer' }}
                    </td>

                    <!-- TIEMPO -->
                    <td class="px-6 py-4 text-right font-mono text-white tracking-wide">
                        {{ $qResult->best_time ?? '-' }}
                    </td>

                    <!-- NEUMÁTICO (CON COLORES) -->
                    <td class="px-6 py-4 text-center">
                        @if($qResult->tyre_compound)
                            @php
                                $tyreClass = match(strtolower($qResult->tyre_compound)) {
                                    'soft' => 'bg-red-600 text-white border-red-500',
                                    'medium' => 'bg-yellow-400 text-black border-yellow-500 font-bold', // Medium se ve mejor en negro
                                    'hard' => 'bg-gray-100 text-black border-white font-bold',
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