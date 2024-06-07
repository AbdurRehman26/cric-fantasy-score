<x-card x-data="">
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Edit Project") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Here you can edit the project") }}
        </p>
    </header>

    <form
        id="edit-project-form"
        hx-post="{{ route("projects.edit", ["project" => $project]) }}"
        hx-swap="outerHTML"
        hx-select="#edit-project-form"
        class="mt-6 space-y-6"
    >
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="$project->name"
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __("Save") }}</x-primary-button>

            @if (session("status") === "project-updated")
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
