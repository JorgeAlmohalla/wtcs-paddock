@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-gray-800 rounded-xl overflow-hidden shadow-2xl border border-gray-700">
    
    @if($post->image_url)
        <div class="h-64 md:h-96 w-full bg-cover bg-center" 
             style="background-image: url('{{ asset('storage/' . $post->image_url) }}');">
        </div>
    @endif

    <div class="p-8 md:p-12">
        <a href="/" class="text-sm text-gray-400 hover:text-white mb-6 block">&larr; Back to Paddock</a>
        
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">{{ $post->title }}</h1>
        <p class="text-red-400 font-mono text-sm mb-8 border-b border-gray-700 pb-4">
            PUBLISHED ON {{ $post->published_at->format('d M Y') }}
        </p>

        <div class="prose prose-invert prose-lg max-w-none text-gray-300">
            {!! $post->content !!}
        </div>
    </div>
</div>
@endsection