<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Monitor Configs") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Update the monitor's configs") }}
        </p>
    </header>

    <form
        id="update-monitor-configs"
        hx-post="{{ route("monitors.update-configs", ["monitor" => $monitor]) }}"
        hx-swap="outerHTML"
        hx-select="#update-monitor-configs"
        class="mt-6 space-y-6"
        hx-ext="disable-element"
        hx-disable-element="#btn-save-configs"
    >
        @csrf
        @method("patch")

        @include("monitors.partials.config-update-fields." . $monitor->type)

        <div class="flex items-center gap-4">
            <x-primary-button id="btn-save-configs">{{ __("Save") }}</x-primary-button>
        </div>
    </form>
</x-card>
