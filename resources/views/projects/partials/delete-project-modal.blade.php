<x-modal name="delete-project" :show="$errors->deleteProject->isNotEmpty()">
    <form id="delete-project-form" method="post" x-bind:action="deleteAction" class="p-6">
        @csrf
        @method("delete")

        <h2 class="text-lg font-medium">
            {{ __("Are you sure you want to delete this project?") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __('Once you delete this project all of it\'s resources will be deleted as well') }}
        </p>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __("Cancel") }}
            </x-secondary-button>

            <x-danger-button class="ml-3">
                {{ __("Delete Project") }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
