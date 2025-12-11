@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <h2 class="font-semibold text-xl text-white leading-tight mb-4 uppercase tracking-widest border-b border-gray-700 pb-4">
                Profile Settings
            </h2>

            <!-- 1. Formulario de Datos Personales (Nombre/Email) -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- 2. Formulario de Datos de Piloto (Steam/Nacionalidad) -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-driver-information-form')
                </div>
            </div>

            <!-- 3. Formulario de Contraseña -->
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg border border-gray-700">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 4. Borrar Cuenta -->
            <div class="p-4 sm:p-8 bg-red-900/20 shadow sm:rounded-lg border border-red-900/50">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection