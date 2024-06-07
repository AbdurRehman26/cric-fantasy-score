<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Profile Information") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route("verification.send") }}">
        @csrf
    </form>

    <form
        id="update-profile-information"
        hx-post="{{ route("profile.update") }}"
        hx-swap="outerHTML"
        hx-select="#update-profile-information"
        class="mt-6 space-y-6"
        hx-ext="disable-element"
        hx-disable-element="#btn-save-info"
    >
        @csrf
        @method("patch")

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                        {{ __("Your email address is unverified.") }}

                        <button
                            form="send-verification"
                            class="rounded-md text-sm underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        >
                            {{ __("Click here to re-send the verification email.") }}
                        </button>
                    </p>

                    @if (session("status") === "verification-link-sent")
                        <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                            {{ __("A new verification link has been sent to your email address.") }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="timezone" :value="__('Timezone')" />
            <x-select-input
                id="timezone"
                name="timezone"
                class="mt-1 block w-full"
                :value="old('timezone', $user->timezone)"
            >
                @foreach (timezone_identifiers_list() as $timezone)
                    <option value="{{ $timezone }}" @if($timezone==$user->timezone) selected @endif>
                        {{ $timezone }}
                    </option>
                @endforeach
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
        </div>

        <div class="flex items-center gap-4" id="update-profile">
            <x-primary-button id="btn-save-info">{{ __("Save") }}</x-primary-button>

            @if (session("status") === "profile-updated")
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 2000)"
                    class="text-sm"
                >
                    {{ __("Saved.") }}
                </p>
            @endif
        </div>
    </form>
</x-card>
