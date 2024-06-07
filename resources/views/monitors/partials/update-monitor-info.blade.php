<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Monitor Info") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Update the monitor's general information") }}
        </p>
    </header>

    <form
        id="update-monitor-info"
        hx-post="{{ route("monitors.update-info", ["monitor" => $monitor]) }}"
        hx-swap="outerHTML"
        hx-select="#update-monitor-info"
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
                :value="old('name', $monitor->name)"
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button id="btn-save-info">{{ __("Save") }}</x-primary-button>
        </div>
    </form>
</x-card>
