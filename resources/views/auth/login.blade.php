<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="flex w-full items-center justify-start">
        <x-application-logo class="h-8 w-8 rounded-md" />
        <h5 class="ml-2 text-xl font-medium text-gray-900 dark:text-white">{{ __("Sign in") }}</h5>
    </div>

    <form class="space-y-6" method="POST" action="{{ route("login") }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input
                id="password"
                class="mt-1 block w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
            <x-checkbox id="remember" name="remember" :value="old('remember')">
                {{ __("Remember me") }}
            </x-checkbox>
            @if (Route::has("password.request"))
                <a
                    class="rounded-md text-sm underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    href="{{ route("password.request") }}"
                >
                    {{ __("Forgot your password?") }}
                </a>
            @endif
        </div>

        <div class="mt-4 block">
            <x-primary-button class="w-[100%]">
                {{ __("Log in to your account") }}
            </x-primary-button>
        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
            Not registered?
            <a href="{{ route("register") }}" class="text-primary-700 hover:underline dark:text-blue-500">
                Create account
            </a>
        </div>
    </form>
</x-guest-layout>
