@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 text-center">
    <h1 class="text-4xl font-bold text-white mb-4">{{ $title }}</h1>
    <p class="text-gray-400">This is a placeholder page for the {{ strtolower($title) }} document.</p>
    <a href="/" class="text-red-500 hover:text-red-400 mt-8 block">Return Home</a>
</div>
@endsection