<!DOCTYPE html>
<html>
<head>
    <title>Championship Standings</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        h1 { text-align: center; text-transform: uppercase; margin-bottom: 5px; }
        h2 { text-align: center; color: #666; font-size: 14px; margin-top: 0; }
        .section-title { background: #000; color: white; padding: 5px 10px; font-weight: bold; margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #eee; padding: 5px; text-align: left; font-size: 12px; }
        td { padding: 5px; border-bottom: 1px solid #ddd; font-size: 12px; }
        .pos { width: 30px; text-align: center; font-weight: bold; }
        .points { text-align: right; font-weight: bold; }
        .privateer-row { background-color: #bfdeffff; }
    </style>
</head>
<body>
    <h1>WTCS Championship Standings</h1>
    <h2>{{ $seasonName }} - Generated on {{ $date }}</h2>

    <!-- DRIVERS -->
    <div class="section-title">DRIVERS CHAMPIONSHIP</div>
    <table>
        <thead><tr><th>Pos</th><th>Driver</th><th>Team</th><th class="points">PTS</th></tr></thead>
        <tbody>
            @foreach($drivers as $i => $d)
            <tr>
                <td class="pos">{{ $i + 1 }}</td>
                <td>{{ $d->name }}</td>
                <td>{{ $d->team->name ?? 'Privateer' }}</td>
                <td class="points">{{ intval($d->total_points) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="page-break-before: always;"></div>

    <!-- TEAMS -->
    <div class="section-title">CONSTRUCTORS CHAMPIONSHIP</div>
    <table>
        <thead><tr><th>Pos</th><th>Team</th><th>Car</th><th class="points">PTS</th></tr></thead>
        <tbody>
           <tbody>
            <!-- Si aquí usas $t -->
            @foreach($teams as $i => $t) 
            <tr class="{{ $t->type === 'privateer' ? 'privateer-row' : '' }}">
                <td class="pos">{{ $i + 1 }}</td>
                <td>
                    {{ $t->name }} <!-- AQUÍ TAMBIÉN $t -->
                    @if($t->type === 'privateer')
                        <span style="font-size: 10px; color: #666; font-style: italic;">(P)</span>
                    @endif
                </td>
                <td>{{ $t->car_model }}</td>
                <td class="points">{{ intval($t->total_points) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MANUFACTURERS -->
    <div class="section-title">MANUFACTURERS CUP</div>
    <table>
        <thead><tr><th>Pos</th><th>Manufacturer</th><th class="points">PTS</th></tr></thead>
        <tbody>
            @foreach($manufacturers as $i => $m)
            <tr>
                <td class="pos">{{ $i + 1 }}</td>
                <td>{{ $m->name }}</td>
                <td class="points">{{ intval($m->total_points) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>