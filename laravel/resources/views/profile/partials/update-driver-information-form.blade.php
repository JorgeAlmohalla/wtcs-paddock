<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Driver Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __("Update your SimRacing credentials and nationality.") }}
        </p>
    </header>

    <!-- CAMBIO AQUÍ: route('profile.driver.update') -->
    <form method="post" action="{{ route('profile.driver.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="steam_id" :value="__('Steam ID (64-bit)')" class="text-gray-300" />
            <x-text-input id="steam_id" name="steam_id" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 text-white" :value="old('steam_id', $user->steam_id)" required />
            <x-input-error class="mt-2" :messages="$errors->get('steam_id')" />
        </div>

        <div>
            <x-input-label for="nationality" :value="__('Nationality (ISO Code)')" class="text-gray-300" />
            <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full uppercase bg-gray-900 border-gray-600 text-white" :value="old('nationality', $user->nationality)" required maxlength="2" />
            <x-input-error class="mt-2" :messages="$errors->get('nationality')" />
        </div>

        <!-- Equipment -->
        <div>
            <x-input-label for="equipment" :value="__('Input Method')" class="text-gray-300" />
            <select id="equipment" name="equipment" class="mt-1 block w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                <option value="wheel" {{ old('equipment', $user->equipment) === 'wheel' ? 'selected' : '' }}>Steering Wheel 🏎️</option>
                <option value="pad" {{ old('equipment', $user->equipment) === 'pad' ? 'selected' : '' }}>Controller / Gamepad 🎮</option>
                <option value="keyboard" {{ old('equipment', $user->equipment) === 'keyboard' ? 'selected' : '' }}>Keyboard ⌨️</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('equipment')" />
        </div>

        <!-- Bio / Experience -->
        <div>
            <x-input-label for="bio" :value="__('Experience / Bio')" class="text-gray-300" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="I have 500 hours in ACC and won 2 leagues...">{{ old('bio', $user->bio) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Share your SimRacing background with other drivers.</p>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <!-- Race Number -->
        <div>
            <x-input-label for="driver_number" :value="__('Race Number')" class="text-gray-300" />
            <x-text-input id="driver_number" name="driver_number" type="number" class="mt-1 block w-24 bg-gray-900 border-gray-600 text-white" :value="old('driver_number', $user->driver_number)" min="0" max="999" />
            <x-input-error class="mt-2" :messages="$errors->get('driver_number')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'driver-info-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400"
                >{{ __('Saved successfully.') }}</p>
            @endif
        </div>
    </form>
</section>