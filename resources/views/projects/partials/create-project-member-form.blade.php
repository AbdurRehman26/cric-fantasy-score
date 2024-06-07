<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Add Team Member") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Add new team member to your project, allowing them to collaborate with you.") }}
        </p>
    </header>

    <form
        id="project-member-add-form"
        hx-post="{{ route("project-members.store", ["project" => $project]) }}"
        hx-swap="outerHTML"
        hx-select="#project-member-add-form"
        class="mt-6 space-y-6"
        x-data="{ role: 'editor' }"
    >
        @csrf

        <div class="col-span-6">
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                {{ __("Please provide the email address of the person you would like to add to this team.") }}
            </div>
        </div>

        <!-- Member Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-input-label for="email" value="{{ __('Email') }}" />
            <x-text-input id="email" type="email" name="email" class="mt-1 block w-full" :value="old('email')" />
            <x-input-error for="email" :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __("Add") }}</x-primary-button>

            @if (session("status") === "member-added")
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 2000)"
                    class="text-sm"
                >
                    {{ __("Added.") }}
                </p>
            @endif
        </div>
    </form>
</x-card>
