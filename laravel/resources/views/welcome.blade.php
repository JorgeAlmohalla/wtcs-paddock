@extends('layouts.app')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-5xl font-bold mb-4 text-white">Welcome to the Grid</h1>
        <p class="text-xl text-gray-400 mb-8">Official Management Platform for the World Touring Car Series.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            
            <!-- Tarjeta 1: PRÓXIMA CARRERA (Con Timer Real) -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 relative overflow-hidden group">
                <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest relative z-10">Next Race</h3>
                
                @if($nextRace)
                    <div class="relative z-10">
                        <p class="text-3xl font-mono text-white leading-none">{{ $nextRace->track->name }}</p>
                        <p class="text-lg text-gray-300 mt-1 mb-4">{{ $nextRace->title ?? 'Round ' . $nextRace->round_number }}</p>
                        
                        <!-- CUENTA ATRÁS CON ALPINE.JS -->
                        <div x-data="{
                                countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
                                endTime: new Date('{{ $nextRace->race_date->format('Y-m-d H:i:s') }}').getTime(),
                                now: new Date().getTime(),
                                timer: null,
                                update() {
                                    this.now = new Date().getTime();
                                    const distance = this.endTime - this.now;

                                    if (distance < 0) {
                                        this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00' };
                                        clearInterval(this.timer);
                                        return;
                                    }

                                    this.countdown.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                                    this.countdown.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                                    this.countdown.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                                    this.countdown.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                                }
                             }"
                             x-init="update(); timer = setInterval(() => update(), 1000)"
                             class="bg-black/60 p-3 rounded-lg border border-red-900/30 backdrop-blur-sm">
                            
                            <div class="flex justify-between text-center font-mono">
                                <div>
                                    <span class="text-2xl font-bold text-white block" x-text="countdown.days"></span>
                                    <span class="text-xs text-gray-400 uppercase">Days</span>
                                </div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div>
                                    <span class="text-2xl font-bold text-white block" x-text="countdown.hours"></span>
                                    <span class="text-xs text-gray-400 uppercase">Hrs</span>
                                </div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div>
                                    <span class="text-2xl font-bold text-white block" x-text="countdown.minutes"></span>
                                    <span class="text-xs text-gray-400 uppercase">Min</span>
                                </div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div>
                                    <span class="text-2xl font-bold text-red-500 block" x-text="countdown.seconds"></span>
                                    <span class="text-xs text-red-500/80 uppercase">Sec</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Imagen de fondo -->
                    @if($nextRace->track->layout_image_url)
                        <img src="{{ asset('storage/' . $nextRace->track->layout_image_url) }}" 
                             class="absolute top-0 right-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition duration-500 pointer-events-none">
                    @endif
                @else
                    <p class="text-gray-500 italic mt-4">No races scheduled yet.</p>
                @endif
            </div>

            <!-- Tarjeta 2: LÍDER DEL MUNDIAL (Ahora con enlace) -->
            <a href="{{ route('standings') }}" 
               class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 hover:border-red-500 hover:shadow-red-900/20 transition duration-300 group block">
                
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest group-hover:text-red-400">Championship Leader</h3>
                    <!-- Icono de flecha que aparece al pasar el ratón -->
                    <span class="text-gray-500 group-hover:text-white transition">&rarr;</span>
                </div>
                
                @if($leader)
                    <p class="text-4xl font-bold text-white group-hover:scale-105 transition origin-left">{{ $leader->name }}</p>
                    <p class="text-red-400 text-lg mt-1 font-bold">{{ intval($leaderPoints) }} PTS</p>
                    
                    @if($leader->team)
                        <div class="mt-4 inline-block px-3 py-1 rounded border border-gray-600 text-sm text-gray-300 group-hover:border-gray-400">
                            {{ $leader->team->name }}
                        </div>
                    @else
                        <p class="text-gray-500 text-sm mt-2">Privateer</p>
                    @endif
                @else
                    <p class="text-gray-500 italic mt-4">Season hasn't started.</p>
                @endif
            </a>

            <!-- Tarjeta 3: NOTICIAS -->
            <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 relative overflow-hidden group">
                <h3 class="text-xl font-bold text-red-500 mb-2 uppercase tracking-widest relative z-10">Latest News</h3>
                
                @if($latestPost)
                    <div class="relative z-10">
                        <p class="text-lg text-white font-semibold leading-tight mb-2">{{ $latestPost->title }}</p>
                        <p class="text-xs text-gray-400 mb-4">{{ $latestPost->published_at->format('M d, Y') }}</p>
                        
                        <!-- Botón leer más (Aún no lleva a ningún sitio, luego haremos la página individual) -->
                        <a href="{{ route('post.show', $latestPost) }}" class="text-sm text-red-400 hover:text-white transition cursor-pointer font-bold">
                            Read Article &rarr;
                        </a>
                    </div>

                    <!-- Imagen de fondo de la noticia -->
                    @if($latestPost->image_url)
                        <div class="absolute inset-0 opacity-20 bg-cover bg-center group-hover:scale-105 transition duration-700"
                             style="background-image: url('{{ asset('storage/' . $latestPost->image_url) }}');">
                        </div>
                        <!-- Gradiente para que se lea el texto -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                    @endif
                @else
                    <p class="text-gray-500 italic mt-4">No news published yet.</p>
                @endif

                    <div class="mt-4 border-t border-gray-700 pt-4 text-center relative z-10">
                        <a href="{{ route('news.index') }}" class="text-xs text-gray-500 hover:text-white uppercase tracking-widest transition">
                            View All News
                        </a>
                    </div>
            </div>
@endsection