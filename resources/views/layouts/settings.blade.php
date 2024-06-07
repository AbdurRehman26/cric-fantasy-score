<x-app-layout>
    @if (isset($pageTitle))
        <x-slot name="pageTitle">{{ $pageTitle }}</x-slot>
    @endif

    @if (isset($header))
        <x-slot name="header">{{ $header }}</x-slot>
    @elseif (isset($pageTitle))
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ $pageTitle }}
            </h2>
        </x-slot>
    @endif

    <x-slot name="sidebar">
        <div class="flex h-16 items-center justify-center border-b border-gray-200 px-3 py-2 dark:border-gray-800">
            <div class="w-full">
                <span class="text-black dark:text-white">{{ __("Account") }}</span>
            </div>
        </div>
        <div class="space-y-2 p-3">
            <x-secondary-sidebar-link :href="route('profile')" :active="request()->routeIs('profile')">
                <x-heroicon-o-user class="mr-2 h-6 w-6" />
                {{ __("Profile") }}
            </x-secondary-sidebar-link>
            <x-secondary-sidebar-link
                :href="route('projects')"
                :active="request()->is('projects') || request()->is('projects/*')"
            >
                <x-heroicon-o-clipboard-list class="mr-2 h-6 w-6" />
                {{ __("Projects") }}
            </x-secondary-sidebar-link>
        </div>
    </x-slot>

    <x-container class="flex">
        <div class="w-full">
            {{ $slot }}
        </div>
    </x-container>
</x-app-layout>
