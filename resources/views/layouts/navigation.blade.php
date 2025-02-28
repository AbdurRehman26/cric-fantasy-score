<nav
    class="fixed top-0 z-50 flex h-[64px] w-full items-center border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800"
>
    <div class="w-full px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button
                    data-drawer-target="logo-sidebar"
                    data-drawer-toggle="logo-sidebar"
                    aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center rounded-md p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 sm:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                >
                    <span class="sr-only">Open sidebar</span>
                    <x-heroicon-o-bars-3-center-left class="h-6 w-6" />
                </button>
                <a href="{{ route("monitors.index") }}" class="ms-2 flex md:me-24">
                    <x-application-logo class="h-8 rounded-sm" />
                </a>
            </div>
            <div class="flex items-center">
                <div class="mr-3">
                    @include("layouts.partials.color-scheme")
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex rounded-full text-sm focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        >
                            <x-heroicon-o-cog-6-tooth class="h-8 w-8 rounded-full" />
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-300" role="none">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <x-dropdown-link :href="route('profile')">
                            {{ __("Profile") }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('projects')">
                            {{ __("Projects") }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route("logout") }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault();this.closest('form').submit();"
                            >
                                {{ __("Log Out") }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
