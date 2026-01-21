@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-bold mb-10 text-white text-center uppercase tracking-widest">Latest News</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <!-- Tarjeta de Noticia -->
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 flex flex-col h-full hover:border-gray-500 transition group">
                
                <!-- Imagen -->
                <a href="{{ route('post.show', $post) }}" class="block overflow-hidden h-48 relative">
                    @if($post->image_url)
                        <img src="{{ asset('storage/' . $post->image_url) }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500" alt="card">
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif
                </a>

                <!-- Contenido -->
                <div class="p-6 flex flex-col flex-grow">
                    <p class="text-xs text-red-400 font-bold mb-2 uppercase">
                        {{ $post->published_at->format('M d, Y') }}
                    </p>
                    
                    <h2 class="text-xl font-bold text-white mb-3 leading-tight group-hover:text-red-500 transition">
                        <a href="{{ route('post.show', $post) }}">
                            {{ $post->title }}
                        </a>
                    </h2>
                    
                    <!-- Botón pegado al fondo -->
                    <div class="mt-auto pt-4">
                        <a href="{{ route('post.show', $post) }}" class="text-sm text-gray-400 hover:text-white font-semibold">
                            Read Article &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación (Botones de Siguiente/Anterior) -->
    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>
@endsection