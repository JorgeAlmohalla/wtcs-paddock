<!DOCTYPE html>
<html>
<head>
    <title>Round {{ $roundNumber }} Report</title>
    <style>
        body { font-family: sans-serif; color: #111; }
        .header { text-align: center; border-bottom: 3px solid #cc0000; padding-bottom: 10px; margin-bottom: 20px; }
        h1 { margin: 0; text-transform: uppercase; font-size: 24px; }
        h2 { margin: 5px 0; font-size: 16px; color: #555; }
        .session-title { background: #000; color: #fff; padding: 5px 10px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #eee; padding: 6px; text-align: left; border-bottom: 2px solid #000; }
        td { padding: 6px; border-bottom: 1px solid #ddd; }
        .pos { width: 30px; text-align: center; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .red { color: #cc0000; }
        .privateer-row { background-color: #bfdeffff; } 

        /* Colores de Neumáticos */
        .badge { padding: 2px 6px; border-radius: 4px; font-weight: bold; color: white; font-size: 10px; text-transform: uppercase; }
        .tyre-soft { background-color: #cc0000; border: 1px solid #990000; }
        .tyre-medium { background-color: #ffcc00; color: black; border: 1px solid #e6b800; }
        .tyre-hard { background-color: #e0e0e0; color: black; border: 1px solid #ccc; }
        
        /* Colores de Estado */
        .status-dnf { color: #cc0000; font-weight: bold; }
        .status-dsq { color: #000; background: #ddd; padding: 2px 4px; font-weight: bold; }
        .status-lap { color: #d97706; font-weight: bold; } /* Naranja */
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>WTCS Official Event Report</h1>
        <h2>Round {{ $roundNumber }} - {{ $track->name }}</h2>
        <p style="font-size: 12px; margin: 0;">Generated on {{ $date }}</p>
    </div>

    <!-- QUALY -->
    @if($qualy && $qualy->count() > 0)
        <div class="session-title">Qualifying Session</div>
        <table>
            <thead>
                <tr>
                    <th class="pos">Pos</th>
                    <th>Driver</th>
                    <th>Team</th>
                    <th>Model</th>
                    <th class="text-right">Time</th>
                    <th class="text-center">Tyre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($qualy as $q)
                <tr class="{{ ($q->team->type ?? '') === 'privateer' ? 'privateer-row' : '' }}">
                    <td class="pos">{{ $q->position }}</td>
                    <td class="bold">{{ $q->driver->name }}</td>
                    <td>{{ $q->team->name ?? 'Privateer' }}</td>
                    <td style="font-size: 10px; color: #555;">
                        {{ $q->car_name ?? ($q->team->car_model ?? '-') }}
                    </td>
                    <td class="text-right">{{ $q->best_time }}</td>
                    <td class="text-center">
                        @if($q->tyre_compound)
                            @php
                                $class = match(strtolower($q->tyre_compound)) {
                                    'soft' => 'tyre-soft',
                                    'medium' => 'tyre-medium',
                                    'hard' => 'tyre-hard',
                                    default => 'tyre-hard',
                                };
                            @endphp
                            <span class="badge {{ $class }}">{{ ucfirst($q->tyre_compound) }}</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- SPRINT -->
    <!-- SALTO DE PÁGINA ANTES DE SPRINT -->
    <div style="page-break-before: always;"></div>
    <div class="session-title">Sprint Race</div>
    <table>
        <thead>
            <tr>
                <th class="pos">Pos</th>
                <th>Driver</th>
                <th>Team</th>
                <th>Car</th> <!-- NUEVA COLUMNA -->
                <th class="text-center">Laps</th>
                <th class="text-right">Time/Gap</th>
                <th class="text-right">PTS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sprint->results->sortBy('position') as $res)
            <tr class="{{ ($res->team->type ?? '') === 'privateer' ? 'privateer-row' : '' }}">
                <td class="pos">{{ $res->position }}</td>
                <td class="bold">
                    {{ $res->driver->name }}
                    @if($res->fastest_lap) <span style="font-size: 9px; color: purple;">(FL)</span> @endif
                </td>
                <td>{{ $res->team->name ?? 'Privateer' }}</td>
                
                <!-- COCHE (Usando el histórico car_name si existe, o el actual) -->
                <td style="font-size: 10px; color: #555;">{{ $res->car_name ?? ($res->team->car_model ?? '-') }}</td>

                <td class="text-center">{{ $res->laps_completed }}</td>
                <td class="text-right">
                    @if($res->status === 'finished')
                        {{ $res->race_time }}
                        @if($res->penalty_seconds > 0) <span class="red">(+{{ $res->penalty_seconds }}s)</span> @endif
                    @else
                        @php
                            $statusClass = match($res->status) {
                                'dnf', 'dns' => 'status-dnf',
                                'dsq' => 'status-dsq',
                                default => 'status-lap',
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ strtoupper($res->status) }}</span>
                    @endif
                </td>
                <td class="text-right bold">{{ intval($res->points) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FEATURE -->
    @if($feature)
        <div style="page-break-before: always;"></div> <!-- Salto de página -->
        <div class="session-title">Feature Race</div>
        <table>
            <thead>
                <tr>
                    <th class="pos">Pos</th>
                    <th>Driver</th>
                    <th>Team</th>
                    <th>Car</th> <!-- NUEVA COLUMNA -->
                    <th class="text-center">Laps</th>
                    <th class="text-right">Time/Gap</th>
                    <th class="text-right">PTS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feature->results->sortBy('position') as $res)
                <tr class="{{ ($res->team->type ?? '') === 'privateer' ? 'privateer-row' : '' }}">
                    <td class="pos">{{ $res->position }}</td>
                    <td class="bold">
                        {{ $res->driver->name }}
                        @if($res->fastest_lap) <span style="font-size: 9px; color: purple;">(FL)</span> @endif
                    </td>
                    <td>{{ $res->team->name ?? 'Privateer' }}</td>
                    
                    <!-- COCHE -->
                    <td style="font-size: 10px; color: #555;">{{ $res->car_name ?? ($res->team->car_model ?? '-') }}</td>

                    <td class="text-center">{{ $res->laps_completed }}</td>
                    <td class="text-right">
                        @if($res->status === 'finished')
                            {{ $res->race_time }}
                            @if($res->penalty_seconds > 0) <span class="red">(+{{ $res->penalty_seconds }}s)</span> @endif
                        @else
                            @php
                                $statusClass = match($res->status) {
                                    'dnf', 'dns' => 'status-dnf',
                                    'dsq' => 'status-dsq',
                                    default => 'status-lap',
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ strtoupper($res->status) }}</span>
                        @endif
                    </td>
                    <td class="text-right bold">{{ intval($res->points) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        WTCS Paddock System - Official Document
    </div>
</body>
</html>