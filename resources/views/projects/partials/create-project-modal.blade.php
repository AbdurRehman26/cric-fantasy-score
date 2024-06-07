<x-modal name="create-project-modal" :show="request()->query('create') === 'open'">
    <div class="p-6">
        <h2 class="text-lg font-medium">
            {{ __("Create new Project") }}
        </h2>

        @include("projects.partials.create-project-form", ["first" => true])
    </div>
</x-modal>
