<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Document - {{ $race->track->name }}</title>
    <style>
        /* Estructura Base */
        body { 
            background: #555; 
            margin: 0; 
            padding: 40px; 
            font-family: 'Arial', 'Helvetica', sans-serif; 
            color: #000;
        }
        .page { 
            background: white; 
            max-width: 850px; /* Un poco más ancho como el original */
            margin: 0 auto; 
            padding: 60px 80px; /* Márgenes amplios */
            box-shadow: 0 0 20px rgba(0,0,0,0.5); 
            min-height: 1000px; 
            position: relative; 
            display: flex;
            flex-direction: column;
        }

        .content { 
            margin-bottom: 50px; 
            text-align: justify;
            flex-grow: 1; /* <--- ESTO EMPUJA EL FOOTER ABAJO */
        }



        /* 1. LOGOS (Ajustados para igualar tamaño visual) */
        .header-logos { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            gap: 40px; 
            margin-bottom: 50px; 
        }
        .logo-fia { 
            height: 90px; /* Mucho más grande */
            width: auto; 
        }
        .logo-wtcs { 
            height: 60px; /* Un poco más pequeño para compensar */
            width: auto; 
        }

        /* 2. BLOQUE DE INFORMACIÓN (Las líneas negras) */
        .info-box { 
            border-top: 2px solid black; 
            border-bottom: 2px solid black; 
            padding: 15px 0; 
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .info-col { font-size: 14px; line-height: 1.6; }
        .info-row { display: flex; }
        
        /* Etiquetas en negrita con ancho fijo */
        .label { 
            font-weight: bold; 
            width: 90px; 
            display: inline-block; 
        }

        /* 3. CONTENIDO */
        .intro-text { margin-bottom: 30px; }
        
        .penalty-block { 
            margin-bottom: 25px; 
            text-align: justify;
            font-size: 14px;
        }
        
        .driver-name { 
            font-weight: bold; 
            text-transform: uppercase; 
        }

        /* 4. FIRMA */
        .footer { 
            margin-top: auto; 
            text-align: center; 
        }
        .signature { 
            font-family: "Brush Script MT", "Comic Sans MS", cursive; /* Fuente manuscrita */
            font-size: 32px; 
            margin-bottom: 10px;
        }
        .steward-title { 
            font-size: 14px; 
            font-style: italic;
        }

        /* Ajustes para impresión */
        @media print {
            body { background: white; padding: 0; }
            .page { box-shadow: none; margin: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="page">
        
        <!-- 1. LOGOS -->
        <div class="header-logos">
            <!-- Asegúrate de que las imágenes existen en public/images -->
            <img src="{{ asset('images/fia-logo.png') }}" class="logo-fia">
            <img src="{{ asset('images/wtcs-logo.png') }}" class="logo-wtcs">
        </div>

        <!-- 2. INFO BOX (Idéntico a la referencia) -->
        <div class="info-box">
            <div class="info-col">
                <div class="info-row">
                    <span class="label">From</span> 
                    <span>The Stewards</span>
                </div>
                <div class="info-row">
                    <span class="label">To</span> 
                    <span>All Teams, All Drivers</span>
                </div>
            </div>
            
            <div class="info-col">
                <div class="info-row">
                    <span class="label">Document</span> 
                    <span>{{ $docNumber }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date</span> 
                    <span>{{ $date }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Time</span> 
                    <span>{{ $time }}</span>
                </div>
            </div>
        </div>

        <!-- INTRODUCCIÓN -->
        <div class="intro-text">
            Following the conclusion of the {{ $race->season->name }} WTCS {{ $race->track->name }} Race, the stewards have awarded the following penalties:
        </div>

        <!-- 3. LISTA DE SANCIONES -->
        <div class="content">
            @forelse($penalties as $penalty)
                <div class="penalty-block">
                    <!-- Formato: NOMBRE - 5 second penalty for reason. explanation. -->
                    <span class="driver-name">{{ $penalty->reported->name }}</span> 
                    - {{ $penalty->penalty_applied }} penalty for {{ Str::lower($penalty->description) }}.
                    <br>
                    {{ $penalty->steward_notes }}
                </div>
            @empty
                <p style="text-align: center; font-style: italic; color: #666; margin-top: 50px;">
                    No penalties were awarded during this session.
                </p>
            @endforelse
        </div>

        <!-- 4. FIRMA -->
        <div class="footer">
            <!-- Puedes subir una imagen de firma real si quieres -->
            <div class="signature">Herra Foxi</div>
            <div class="steward-title">
                World Touring Car Series steward for the {{ $race->track->name }} weekend
            </div>
        </div>
    </div>
</body>
</html>