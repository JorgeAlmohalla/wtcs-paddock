<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - WTCS Paddock</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900 relative">
            
            <!-- Decoración de fondo -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-red-600/10 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -right-24 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Logo -->
            <div class="mb-8 relative z-10">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <img src="{{ asset('images/wtcs-logo-white.png') }}" class="h-12 w-auto drop-shadow-lg filter"> 
                    <!-- (Si tu imagen ya es blanca, quita el filter) -->
                    <span class="text-white font-black tracking-[0.3em] text-xs uppercase border-t border-red-500 pt-2 mt-1">Paddock Access</span>
                </a>
            </div>

            <!-- Caja del Formulario -->
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-gray-800 border border-gray-700 shadow-2xl overflow-hidden sm:rounded-xl relative z-10">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-gray-600 text-xs relative z-10">
                &copy; 2026 WTCS Paddock System
            </div>
        </div>
    </body>
</html>