@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white uppercase tracking-widest flex items-center gap-2">
            <span class="text-red-500">Incident Report</span> #{{ $report->id }}
        </h1>
        <a href="/dashboard" class="text-gray-400 hover:text-white transition font-bold text-sm">&larr; Back to Dashboard</a>
    </div>

    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-2xl">
        
        <!-- CABECERA ESTADO -->
        <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-900">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wide mb-1">Current Status</p>
                @php
                    $statusColor = match($report->status) {
                        'pending' => 'text-gray-400',
                        'investigating' => 'text-yellow-400',
                        'resolved' => 'text-red-500',
                        'dismissed' => 'text-green-500',
                    };
                @endphp
                <p class="text-2xl font-black uppercase {{ $statusColor }}">{{ $report->status }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wide mb-1">Event</p>
                <p class="text-white font-bold text-lg">{{ $report->race->track->name }}</p>
                <p class="text-gray-500 text-xs">Round {{ $report->race->round_number }}</p>
            </div>
        </div>

        <!-- DETALLES DEL INCIDENTE -->
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Reporter -->
                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                    <span class="block text-xs text-blue-400 uppercase font-bold mb-1">Reporter</span>
                    <span class="text-white font-bold text-lg block">{{ $report->reporter->name }}</span>
                    <span class="text-gray-500 text-xs">{{ $report->reporter->team->name ?? 'Privateer' }}</span>
                </div>
                <!-- Accused -->
                <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                    <span class="block text-xs text-red-400 uppercase font-bold mb-1">Accused Driver</span>
                    <span class="text-white font-bold text-lg block">{{ $report->reported->name }}</span>
                    <span class="text-gray-500 text-xs">{{ $report->reported->team->name ?? 'Privateer' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <span class="block text-xs text-gray-500 uppercase font-bold mb-2">Location</span>
                    <p class="text-white bg-gray-900 p-3 rounded border border-gray-600 font-mono">{{ $report->lap_corner }}</p>
                </div>
                <div class="md:col-span-2">
                    <span class="block text-xs text-gray-500 uppercase font-bold mb-2">Video Evidence</span>
                    <a href="{{ $report->video_url }}" target="_blank" class="block w-full text-blue-400 hover:text-blue-300 bg-gray-900 p-3 rounded border border-gray-600 truncate hover:border-blue-500 transition">
                        {{ $report->video_url }} &nearr;
                    </a>
                </div>
            </div>

            <div>
                <span class="block text-xs text-gray-500 uppercase font-bold mb-2">Incident Description</span>
                <div class="text-gray-300 bg-gray-900 p-6 rounded-lg border border-gray-600 italic leading-relaxed">
                    "{{ $report->description }}"
                </div>
            </div>
        </div>

        <!-- DECISIÓN DE LOS COMISARIOS (Solo si existe) -->
        @if($report->steward_notes)
            <div class="bg-black/40 p-8 border-t border-gray-700">
                <h3 class="text-white font-bold uppercase tracking-widest mb-6 flex items-center gap-3 text-lg border-b border-gray-700 pb-4">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    Stewards Decision
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-bold mb-1">Verdict / Penalty</span>
                        <p class="text-white text-2xl font-black">{{ $report->penalty_applied ?? 'No Further Action' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-bold mb-2">Reasoning</span>
                        <p class="text-gray-300 leading-relaxed">{{ $report->steward_notes }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection