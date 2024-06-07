<x-app-layout>
    <x-slot name="pageTitle">{{ __("Pages") }}</x-slot>

    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>{{ __("Pages") }}</x-header>
            <div x-data="">
                <x-primary-button x-on:click="$dispatch('open-modal', 'create-page-modal')">
                    Create new Page
                </x-primary-button>
                {{-- @include('pages.partials.create-page-modal') --}}
            </div>
        </div>
    </x-slot>

    <div x-data="{ deleteAction: '' }">
        <x-container class="py-8">
            <div
                id="pages-list"
                hx-get="{{ request()->url() }}"
                hx-trigger="every 10s"
                hx-select="#pages-list"
                hx-swap="outerHTML"
            >
                @if (isset($pages[0]))
                    <div class="space-y-6">
                        @foreach ($pages as $page)
                            <x-simple-card
                                class="grid grid-cols-2 gap-5 lg:grid-cols-5 lg:gap-2"
                                id="page-row-{{ $page->id }}"
                            >
                                <div class="col-span-1 flex items-center">
                                    {{ $page->name }}
                                </div>
                                <div class="col-span-1 flex items-center justify-end uppercase lg:justify-start"></div>
                                <div class="col-span-1 flex items-center"></div>
                                <div class="col-span-1 flex items-center justify-end lg:justify-start"></div>
                                <div class="col-span-2 flex justify-end lg:col-span-1"></div>
                            </x-simple-card>
                        @endforeach
                    </div>
                @else
                    <x-container class="max-w-3xl">
                        <x-simple-card>
                            <p class="text-center dark:text-white">
                                {{ __("You don't have a page yet! Create the first one now") }}
                            </p>
                        </x-simple-card>
                    </x-container>
                @endif
            </div>
        </x-container>
    </div>
</x-app-layout>
