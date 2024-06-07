@if ($project->users()->exists())
    <x-card>
        <div x-data="{ 'cancelAction': '' }">
            <header>
                <h2 class="text-lg font-medium">
                    {{ __("Team Members") }}
                </h2>
            </header>

            <!-- Team Member Invitations -->
            <div class="mt-10 sm:mt-5">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __("Email") }}</x-th>
                            <x-th>{{ __("Added On") }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    @foreach ($project->users as $user)
                        <x-tr>
                            <x-td>{{ $user->email }}</x-td>
                            <x-td>
                                <x-datetime :value="$user->created_at" />
                            </x-td>
                            <x-td>
                                <div class="flex items-center justify-end">
                                    @if (Gate::check("delete", $project))
                                        <button
                                            class="ms-6 cursor-pointer text-sm text-red-500 focus:outline-none"
                                            @click.prevent="cancelAction  = '{{ route("project-members.destroy", ["project" => $project, "user" => $user->id]) }}'; $dispatch('open-modal', 'confirm-project-member-delete')"
                                        >
                                            {{ __("Delete") }}
                                        </button>
                                    @endif
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-table>
            </div>

            <x-modal name="confirm-project-member-delete" focusable>
                <form id="delete-project-member-form" method="post" x-bind:action="cancelAction" class="p-6">
                    @csrf
                    @method("delete")

                    <h2 class="text-lg font-medium">
                        {{ __("Are you sure you want to remove this user from project?") }}
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            {{ __("No") }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __("Yes") }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </x-card>
@endif
