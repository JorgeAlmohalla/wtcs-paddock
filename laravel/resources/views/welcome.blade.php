@extends('layouts.app')

@section('content')
    <div class="text-center py-12">
        <h1 class="text-5xl font-bold mb-4 text-white">Welcome to the Grid</h1>
        <p class="text-xl text-gray-400 mb-8">Official  Platform for the World Touring Car Series.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 items-stretch">
            
            <!-- TARJETA 1: PRÓXIMA CARRERA -->
            <a href="{{ route('calendar') }}" 
               class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 hover:border-red-500 hover:shadow-red-900/20 transition duration-300 relative overflow-hidden group block flex flex-col h-full">
                
                <h3 class="text-xl font-bold text-red-500 mb-4 uppercase tracking-widest relative z-10 group-hover:text-red-400">Next Race</h3>
                
                @if($nextRace)
                    <div class="relative z-10 flex flex-col flex-grow justify-center items-center pb-4">
                        <p class="text-3xl font-mono text-white leading-none text-center group-hover:scale-105 transition mb-2">{{ $nextRace->track->name }}</p>
                        <p class="text-lg text-gray-300 mb-6">{{ $nextRace->title ?? 'Round ' . $nextRace->round_number }}</p>
                        
                        <!-- CUENTA ATRÁS -->
                        <div x-data="{
                                countdown: { days: '00', hours: '00', minutes: '00', seconds: '00' },
                                endTime: new Date('{{ $nextRace->race_date->format('Y-m-d H:i:s') }}').getTime(),
                                now: new Date().getTime(),
                                timer: null,
                                update() {
                                    this.now = new Date().getTime();
                                    const distance = this.endTime - this.now;
                                    if (distance < 0) { this.countdown = { days: '00', hours: '00', minutes: '00', seconds: '00' }; clearInterval(this.timer); return; }
                                    this.countdown.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                                    this.countdown.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                                    this.countdown.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                                    this.countdown.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                                }
                             }"
                             x-init="update(); timer = setInterval(() => update(), 1000)"
                             class="bg-black/60 p-3 rounded-lg border border-red-900/30 backdrop-blur-sm w-full max-w-[280px]">
                            
                            <div class="flex justify-between text-center font-mono">
                                <div><span class="text-2xl font-bold text-white block" x-text="countdown.days"></span><span class="text-[10px] text-gray-400 uppercase">Days</span></div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div><span class="text-2xl font-bold text-white block" x-text="countdown.hours"></span><span class="text-[10px] text-gray-400 uppercase">Hrs</span></div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div><span class="text-2xl font-bold text-white block" x-text="countdown.minutes"></span><span class="text-[10px] text-gray-400 uppercase">Min</span></div>
                                <span class="text-xl text-gray-600 self-start mt-1">:</span>
                                <div><span class="text-2xl font-bold text-red-500 block" x-text="countdown.seconds"></span><span class="text-[10px] text-red-500/80 uppercase">Sec</span></div>
                            </div>
                        </div>
                    </div>
                    
                    @if($nextRace->track->layout_image_url)
                        <img src="{{ asset('storage/' . $nextRace->track->layout_image_url) }}" 
                             class="absolute top-0 right-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition duration-500 pointer-events-none">
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center flex-grow">
                        <p class="text-gray-500 italic">No races scheduled yet.</p>
                    </div>
                @endif
            </a>

            <!-- TARJETA 2: LÍDER DEL MUNDIAL -->
            <a href="{{ route('standings') }}" 
               class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700 hover:border-red-500 hover:shadow-red-900/20 transition duration-300 group flex flex-col items-center justify-center text-center h-full relative">
                
                <div class="flex items-center justify-center gap-2 mb-4 w-full">
                    <h3 class="text-xl font-bold text-red-500 uppercase tracking-widest group-hover:text-red-400">Championship Leader</h3>
                </div>
                <!-- Flecha absoluta -->
                <span class="text-gray-500 group-hover:text-white transition absolute top-6 right-6">&rarr;</span>
                
                @if($leader)
                    <div class="flex flex-col items-center justify-center flex-grow">
                        <p class="text-3xl md:text-4xl font-black text-white group-hover:scale-105 transition duration-300 leading-tight">
                            {{ $leader->name }}
                        </p>
                        
                        <p class="text-red-400 text-xl mt-2 font-mono font-bold tracking-wide">
                            {{ intval($leaderPoints) }} PTS
                        </p>
                        
                        @if($leader->team)
                            <div class="mt-4 px-4 py-1.5 rounded border border-gray-600 text-sm font-bold text-gray-300 group-hover:border-gray-400 group-hover:text-white transition uppercase tracking-wide bg-black/20">
                                {{ $leader->team->name }}
                            </div>
                        @else
                            <p class="text-gray-500 text-sm mt-4 uppercase tracking-widest">Privateer</p>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center flex-grow">
                        <p class="text-gray-500 italic">Season hasn't started.</p>
                    </div>
                @endif
            </a>

<!-- TARJETA 3: LATEST NEWS (VERSIÓN LIMPIA) -->
            <div class="bg-gray-800 p-0 rounded-lg shadow-md border border-gray-700 hover:border-red-500 hover:shadow-red-900/20 transition duration-300 relative overflow-hidden group flex flex-col h-full">
               
               <!-- ENLACE PRINCIPAL -->
               <a href="{{ $latestPost ? route('post.show', $latestPost) : '#' }}" class="flex-grow relative flex flex-col p-6 z-10">
                   
                   <h3 class="text-xl font-bold text-red-500 mb-4 uppercase tracking-widest text-center relative z-20">Latest News</h3>
                   
                   @if($latestPost)
                        <div class="flex flex-col flex-grow justify-center items-center text-center relative z-20">
                            <!-- Título blanco puro -->
                            <p class="text-xl md:text-2xl text-white font-bold leading-tight mb-3 group-hover:text-red-100 transition">
                                {{ $latestPost->title }}
                            </p>
                            
                            <p class="text-xs text-gray-400 mb-6 font-mono uppercase">
                                {{ $latestPost->published_at->format('M d, Y') }}
                            </p>
                            
                            <span class="text-sm text-red-400 font-bold border-b border-red-500/50 pb-0.5 group-hover:text-white transition">
                                Read Article &rarr;
                            </span>
                        </div>

                        <!-- Imagen de fondo (Muy oscura para no molestar) -->
                        @if($latestPost->image_url)
                            <div class="absolute inset-0 opacity-100 bg-cover bg-center group-hover:scale-105 transition duration-700 z-0"
                                 style="background-image: url('{{ asset('storage/' . $latestPost->image_url) }}');">
                            </div>
                            <!-- Degradado suave -->
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-transparent z-0"></div>
                        @endif
                   @else
                        <div class="flex flex-col items-center justify-center flex-grow text-center">
                            <p class="text-gray-500 italic">No news published yet.</p>
                        </div>
                   @endif
               </a>

               <!-- FOOTER SEPARADO (Fondo sólido) -->
               <div class="bg-gray-900 border-t border-gray-700 p-3 text-center relative z-30">
                    <a href="{{ route('news.index') }}" class="text-xs text-gray-400 hover:text-white hover:underline transition font-bold tracking-widest uppercase">
                        View All News
                    </a>
               </div>
            </div>

        </div>

        <!-- BANNER DISCORD (Con Imagen de Fondo) -->
        <a href="https://discord.gg/22naxm8N" target="_blank" class=" h-80 mt-12 relative block rounded-xl overflow-hidden group h-64 shadow-2xl border border-gray-700 hover:border-[#5865F2] transition duration-500">
            
            <!-- Imagen de Fondo (Coches) -->
            <div class="absolute inset-0 bg-cover bg-[center_73%] transition duration-700 transform group-hover:scale-105" 
                 style="background-image: url('{{ asset('images/discord-bg.png') }}');">
            </div>
            
            <!-- Capa Oscura (Gradiente) -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#5865F2]/90 via-[#5865F2]/60 to-black/80 group-hover:from-[#4752C4]/95 transition duration-500"></div>

            <!-- Contenido -->
            <div class="absolute inset-0 flex flex-col md:flex-row items-center justify-between p-8 md:p-12 z-10">
                
                <div class="flex items-center gap-6">
                    <!-- Logo Discord Flotante -->
                    <div class="bg-white p-4 rounded-full shadow-lg transform group-hover:rotate-12 transition duration-500">
                        <svg class="w-10 h-10 text-[#5865F2]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037 13.46 13.46 0 0 0-.585 1.206 18.423 18.423 0 0 0-5.534 0 13.34 13.34 0 0 0-.588-1.206.077.077 0 0 0-.08-.037A19.736 19.736 0 0 0 3.682 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.086 2.157 2.419 0 1.334-.956 2.419-2.157 2.419zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.086 2.157 2.419 0 1.334-.946 2.419-2.157 2.419z"/>
                        </svg>
                    </div>

                    <div class="text-left text-white">
                        <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tight leading-none mb-1">Join the Paddock</h2>
                        <p class="text-indigo-100 font-medium text-lg">Connect with drivers, discuss setups and get live race updates.</p>
                    </div>
                </div>
                
                <!-- Botón Acción -->
                <div class="mt-6 md:mt-0">
                    <span class="bg-white text-[#5865F2] px-8 py-3 rounded-full font-black uppercase tracking-widest shadow-xl group-hover:bg-indigo-50 transition transform group-hover:scale-105 flex items-center gap-2">
                        Join Server &rarr;
                    </span>
                </div>

            </div>
        </a>

<!-- HALL OF FAME -->
        @if(isset($pastChampions) && $pastChampions->count() > 0)
            <div class="mt-16 text-center">
                <!-- Título Nuevo -->
                <h2 class="text-3xl font-black text-white mb-8 uppercase tracking-widest">
                Our Past <span class="text-yellow-500">Champions</span>
                </h2>

                <!-- CONTENEDOR FLEX (Esto es lo que te faltaba para que salgan en fila) -->
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach($pastChampions as $record)
                        <div class="bg-gray-800 rounded-xl p-6 border border-yellow-500/30 shadow-lg text-center w-64 transform hover:scale-105 transition flex-shrink-0 relative overflow-hidden group">
                            
                            <!-- Fondo dorado sutil -->
                            <div class="absolute inset-0 bg-gradient-to-b from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            
                            <div class="relative z-10">
                                <!-- COPA (Imagen real o Emoji) -->
                                <div class="mb-4 flex justify-center">
                                    <span class="text-4xl">🏆</span> 
                                </div>
                                
                                <h3 class="text-white font-bold text-xl truncate">{{ $record['driver']->name }}</h3>
                                <p class="text-yellow-500 text-sm font-mono uppercase font-bold tracking-widest mt-1">{{ $record['season'] }} Champion</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection