<form
    id="create-project-form"
    hx-post="{{ route("projects.create") }}"
    hx-swap="outerHTML"
    hx-select="#create-project-form"
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
            :value="old('name')"
            autocomplete="name"
        />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __("Create") }}</x-primary-button>
    </div>
</form>
