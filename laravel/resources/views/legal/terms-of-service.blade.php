@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    
    <div class="mb-10 border-b border-gray-700 pb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-widest mb-2">Terms of Service</h1>
        <p class="text-sm text-gray-500 font-mono">Last Updated: January 2026</p>
    </div>

    <div class="bg-gray-900 rounded-lg p-8 border border-gray-800 shadow-inner">
        <div class="space-y-8 text-gray-300 leading-relaxed text-sm">

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">1.</span> Acceptance of Terms
                </h3>
                <p>By registering on WTCS Paddock, you agree to comply with these terms and the Official Sporting Regulations of the league.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">2.</span> User Conduct
                </h3>
                <p>You agree to use this platform exclusively for league-related activities. Any attempt to exploit, hack, or disrupt the service will result in an immediate ban and report to relevant authorities.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">3.</span> Account Responsibility
                </h3>
                <p>You are responsible for maintaining the confidentiality of your account password. You agree to notify administrators immediately of any unauthorized use of your account.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">4.</span> Authority
                </h3>
                <p>Decisions made by the Stewards regarding race incidents and penalties are final and binding. Using this platform to harass admins regarding decisions is strictly prohibited.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                    <span class="text-red-500">5.</span> Termination
                </h3>
                <p>We reserve the right to suspend or terminate your account if you violate the Code of Conduct or these Terms.</p>
            </div>

        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="/" class="text-xs font-bold text-gray-500 hover:text-white uppercase tracking-widest transition border-b border-transparent hover:border-white pb-0.5">Return to Homepage</a>
    </div>
</div>
@endsection