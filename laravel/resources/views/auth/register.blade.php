<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Driver Name</label>
            <input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Email</label>
            <input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Steam ID -->
        <div class="mt-4">
            <label for="steam_id" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Steam ID (64-bit)</label>
            <input id="steam_id" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm" type="text" name="steam_id" :value="old('steam_id')" required />
            <p class="text-xs text-gray-600 mt-1">Found in your Steam profile URL.</p>
            <x-input-error :messages="$errors->get('steam_id')" class="mt-2" />
        </div>

        <!-- Nationality -->
        <div class="mt-4">
            <label for="nationality" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Nationality (ISO Code)</label>
            <input id="nationality" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm uppercase" type="text" name="nationality" :value="old('nationality')" required maxlength="2" placeholder="ES" />
            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Password</label>
            <input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block font-bold text-sm text-gray-400 uppercase tracking-wider mb-1">Confirm Password</label>
            <input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-lg focus:ring-red-500 focus:border-red-500 shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="underline text-sm text-gray-500 hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                Already registered?
            </a>

            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105 uppercase tracking-wide text-xs">
                Register
            </button>
        </div>
    </form>
</x-guest-layout>