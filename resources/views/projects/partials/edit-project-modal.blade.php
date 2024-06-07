<x-modal name="create-project-modal">
    <div class="p-6">
        <h2 class="text-lg font-medium">
            {{ __("Edit Project") }}
        </h2>

        @include("projects.partials.create-project-form", ["first" => true])
    </div>
</x-modal>
