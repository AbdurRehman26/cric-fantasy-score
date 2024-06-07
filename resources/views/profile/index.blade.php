<x-app-layout>
    <x-slot name="header">
        <x-header>
            {{ __("Profile") }}
        </x-header>
    </x-slot>

    <x-container class="max-w-3xl space-y-6">
        @include("profile.partials.update-profile-information-form")

        @include("profile.partials.update-password-form")

        @include("profile.partials.delete-user-form")
    </x-container>
</x-app-layout>
