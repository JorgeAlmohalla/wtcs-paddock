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