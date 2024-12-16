<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="max-w-md mx-auto">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Rules Acceptance -->
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="accept_rules" required class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-600">
                    I accept the rules and understand the entry fee is $25 per entry
                </span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3 bg-blue-600 text-white hover:bg-blue-700 px-2 py-2 rounded" onclick="window.location.href='{{ route('login') }}'">
                {{ __('Already registered?') }}
            </x-primary-button>

            <x-primary-button class="ml-3 bg-blue-600 text-white hover:bg-blue-700 px-2 py-2 rounded">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>