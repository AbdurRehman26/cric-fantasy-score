<div class="mx-auto">
    <x-card-header>
        @if (isset($title))
            <x-slot name="title">{{ $title }}</x-slot>
        @endif

        @if (isset($description))
            <x-slot name="description">{{ $description }}</x-slot>
        @endif

        @if (isset($aside))
            <x-slot name="aside">{{ $aside }}</x-slot>
        @endif
    </x-card-header>

    <div>
        <div
            class="{{ isset($actions) ? "rounded-tl-md rounded-tr-md" : "rounded-md" }} border bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
        >
            {{ $slot }}
        </div>

        @if (isset($actions))
            <div
                class="flex items-center justify-end rounded-bl-md rounded-br-md border border-b border-l border-r border-gray-200 border-t-transparent bg-gray-50 px-4 px-6 py-3 text-right dark:border-gray-700 dark:border-t-transparent dark:bg-gray-800 dark:bg-opacity-70"
            >
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
