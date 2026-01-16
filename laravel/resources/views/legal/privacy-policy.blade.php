@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    
    <!-- CABECERA -->
    <div class="mb-10 border-b border-gray-700 pb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-widest mb-2">Privacy Policy</h1>
        <p class="text-sm text-gray-500 font-mono">Last Updated: January 2026</p>
    </div>

    <!-- CUERPO DEL DOCUMENTO -->
    <div class="bg-gray-900 rounded-lg p-8 border border-gray-800 shadow-inner">
        <div class="space-y-8 text-gray-300 leading-relaxed text-sm">

            <!-- SECCIÓN 1 -->
            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">1.</span> Information Collection
                </h3>
                <p class="mb-3">WTCS Paddock collects only the data necessary to facilitate league operations and driver identification.</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-400 marker:text-gray-600">
                    <li><strong>Identity Data:</strong> Name, Email address, and Steam ID (64-bit).</li>
                    <li><strong>Racing Telemetry:</strong> Race results, lap times, and incident reports automatically parsed from game server logs.</li>
                    <li><strong>Technical Logs:</strong> IP address and browser user-agent string for security auditing.</li>
                </ul>
            </div>

            <!-- SECCIÓN 2 -->
            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">2.</span> Data Usage
                </h3>
                <p>Your personal information is strictly used for:</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-400 marker:text-gray-600">
                    <li>Calculating championship standings and statistics.</li>
                    <li>Verifying driver identity on game servers via Steam ID.</li>
                    <li>Official communication regarding race schedules and steward decisions.</li>
                </ul>
            </div>

            <!-- SECCIÓN 3 -->
            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">3.</span> Data Protection & Privacy
                </h3>
                <p>We do not sell, trade, or transfer your personal data to outside parties. Your Steam ID is public information by nature of the platform, but your email address is kept private and visible only to league administrators.</p>
            </div>

            <!-- SECCIÓN 4 -->
            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">4.</span> Cookies Policy
                </h3>
                <p>We use essential session cookies solely to maintain your login state (`laravel_session`) and to protect forms against CSRF attacks (`XSRF-TOKEN`). We do not use third-party tracking or advertising cookies.</p>
            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="mt-8 text-center">
        <a href="/" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition border-b border-transparent hover:border-white pb-0.5">
            Return to Homepage
        </a>
    </div>

</div>
@endsection