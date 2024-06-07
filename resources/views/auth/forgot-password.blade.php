<x-guest-layout>
    <div class="space-y-6">
        <div class="flex w-full items-center justify-start">
            <x-application-logo class="h-8 w-8 rounded-md" />
            <h5 class="ml-2 text-xl font-medium text-gray-900 dark:text-white">{{ __("Forgot your password?") }}</h5>
        </div>

        <div class="text-sm">
            {{ __("No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.") }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form class="space-y-6" method="POST" action="{{ route("password.email") }}">
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
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="block">
                <x-primary-button class="w-[100%]">
                    {{ __("Email Password Reset Link") }}
                </x-primary-button>
            </div>

            <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                Already have an account?
                <a href="{{ route("login") }}" class="text-blue-700 hover:underline dark:text-blue-500">
                    Login to your account
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
