<x-guest-layout>
    <div class="flex w-full items-center justify-start">
        <x-application-logo class="h-8 w-8 rounded-md" />
        <h5 class="ml-2 text-xl font-medium text-gray-900 dark:text-white">{{ __("Create an account") }}</h5>
    </div>

    <form class="space-y-6" method="POST" action="{{ route("register") }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                class="mt-1 block w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input
                id="password_confirmation"
                class="mt-1 block w-full"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="block">
            <x-primary-button class="w-[100%]">
                {{ __("Register") }}
            </x-primary-button>
        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
            Already registered?
            <a href="{{ route("login") }}" class="text-blue-700 hover:underline dark:text-blue-500">
                Login to your account
            </a>
        </div>
    </form>
</x-guest-layout>
