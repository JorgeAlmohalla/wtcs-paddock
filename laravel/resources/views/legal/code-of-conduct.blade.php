@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    
    <div class="mb-10 border-b border-gray-700 pb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-widest mb-2">Code of Conduct</h1>
        <p class="text-sm text-gray-500 font-mono">Sporting Regulations - Appendix A</p>
    </div>

    <!-- Principio Core -->
    <div class="bg-red-900/10 border-l-4 border-red-500 p-6 mb-8 rounded-r-lg">
        <p class="text-red-400 font-bold uppercase text-xs tracking-widest mb-2">Core Principle</p>
        <p class="text-white text-lg italic">"Respect your fellow drivers. We are here to race hard but fair."</p>
    </div>

    <div class="bg-gray-900 rounded-lg p-8 border border-gray-800 shadow-inner">
        <div class="space-y-8 text-gray-300 leading-relaxed text-sm">

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">1.</span> On-Track Etiquette
                </h3>
                <ul class="list-disc pl-5 space-y-2 text-gray-400 marker:text-gray-600">
                    <li><strong>The Golden Rule:</strong> Leave space. If you are not significantly alongside at the apex, back out.</li>
                    <li><strong>Rejoining:</strong> Always rejoin the track safely and parallel to the racing line. Unsafe rejoins carry heavy penalties.</li>
                    <li><strong>Blocking:</strong> Only one defensive move is allowed per straight. No moving under braking.</li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">2.</span> Communication
                </h3>
                <p>Zero tolerance for toxicity in chat or voice channels. Heated moments happen, but personal insults, racism, or discrimination will result in an immediate permanent ban.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">3.</span> Connection Quality
                </h3>
                <p>Drivers must ensure a stable internet connection. Cars with excessive lag ("warping") may be asked to retire from the race for safety reasons.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">4.</span> Attendance
                </h3>
                <p>If you cannot attend a race, you must notify the admins at least 24 hours in advance via the dedicated channel in Discord.</p>
            </div>

        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="/" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition border-b border-transparent hover:border-white pb-0.5">Return to Homepage</a>
    </div>
</div>
@endsection