<div class="bg-white shadow rounded p-4 md:hidden">
    <div class="space-y-3">
        @forelse($tasks as $task)
            <div x-data="taskToggle({{ $task->id }})" class="border rounded-xl bg-white shadow-sm border-l-4" style="border-left-color: {{ $taskColor }}">
                <div class="w-full p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <span class="mt-2 h-2.5 w-2.5 rounded-full" style="background-color: {{ $taskColor }}"></span>
                            @if($task->subTasksCount === 0)
                                <form method="POST" action="{{ route('ca.tasks.complete', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="flex h-6 w-6 items-center justify-center rounded border {{ $task->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                        @if($task->estTerminee) ✓ @endif
                                    </button>
                                </form>
                            @endif
                            <button type="button" class="text-left" @click="open = !open">
                                <p class="text-base font-semibold text-gray-900">{{ $task->titre }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $task->completedSubTasksCount }} / {{ $task->subTasksCount }} sous-tâches complétées
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Responsables : {{ $task->responsablesNoms ? implode(', ', $task->responsablesNoms) : '-' }}
                                </p>
                            </button>
                        </div>
                        <button type="button" class="flex flex-col items-end gap-2 text-xs text-gray-500" @click="open = !open">
                            <span x-text="open ? 'Masquer' : 'Voir'"></span>
                            <span class="flex h-8 w-8 items-center justify-center rounded-full border bg-gray-100 text-base">
                                <span x-show="!open">+</span>
                                <span x-show="open">−</span>
                            </span>
                        </button>
                    </div>
                </div>

                <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="border-t px-4 pb-4">
                    <div class="mt-3 space-y-3 text-sm text-gray-700">
                        <p class="text-gray-700">{{ $task->description ?: 'Aucune description.' }}</p>

                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
                            <div>
                                <span class="font-semibold text-gray-700">Créée</span>
                                <div>{{ $task->dateCreation?->format('d/m/Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Faite</span>
                                <div>{{ $task->dateEffectuee?->format('d/m/Y') ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                x-data
                                @click="$dispatch('open-modal', 'comments-{{ $task->id }}')"
                                class="rounded bg-indigo-500 px-2 py-1 text-xs text-white">
                                💬({{ $task->commentsCount }})
                            </button>

                            <div class="relative" x-data="{ openActions: false }">
                                <button
                                    type="button"
                                    class="rounded border px-2 py-1 text-xs text-gray-500 hover:text-gray-900"
                                    @click.stop="openActions = !openActions"
                                >
                                    ⋯
                                </button>

                                <div
                                    x-show="openActions"
                                    x-transition
                                    @click.outside="openActions = false"
                                    style="display:none"
                                    class="absolute right-0 mt-2 w-36 rounded border bg-white text-left text-xs shadow z-20"
                                >
                                    <button
                                        type="button"
                                        class="block w-full px-3 py-2 hover:bg-gray-100"
                                        @click="
                                            openActions = false;
                                            $dispatch('open-modal', 'edit-task-{{ $task->id }}')
                                        "
                                    >
                                        Modifier
                                    </button>

                                    @if(! $task->estArchivee)
                                        <form method="POST" action="{{ route('ca.tasks.archive', $task->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="block w-full px-3 py-2 text-left hover:bg-gray-100"
                                                @click="openActions = false"
                                            >
                                                Archiver
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('ca.tasks.destroy', $task->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="block w-full px-3 py-2 text-left text-red-600 hover:bg-gray-100"
                                            @click="openActions = false"
                                        >
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Sous-tâches</p>
                                    <p class="text-xs text-gray-500">Touchez une sous-tâche pour la gérer.</p>
                                </div>
                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('open-modal', 'create-subtask-{{ $task->id }}')"
                                    class="rounded bg-green-600 px-2 py-1 text-xs text-white shadow">
                                    Ajouter
                                </button>
                            </div>

                            <div class="mt-3 space-y-2">
                                @forelse($task->subTasks as $subTask)
                                    <div class="rounded-lg border bg-white p-3 border-l-4" style="border-left-color: {{ $subTaskColor }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <span class="mt-1 h-2.5 w-2.5 rounded-full" style="background-color: {{ $subTaskColor }}"></span>
                                                <form method="POST" action="{{ route('ca.tasks.subTasks.complete', [$task->id, $subTask->id]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="flex h-6 w-6 items-center justify-center rounded border {{ $subTask->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                                        @if($subTask->estTerminee) ✓ @endif
                                                    </button>
                                                </form>
                                                <div>
                                                    <p class="font-semibold text-gray-800">{{ $subTask->titre }}</p>
                                                    <p class="text-xs text-gray-600">{{ $subTask->description ?? '-' }}</p>
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        Responsables : {{ $subTask->responsablesNoms ? implode(', ', $subTask->responsablesNoms) : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                            <button
                                                type="button"
                                                x-data
                                                @click="$dispatch('open-modal', 'comments-sub-{{ $task->id }}-{{ $subTask->id }}')"
                                                class="rounded bg-indigo-500 px-2 py-1 text-white">
                                                💬({{ $subTask->commentsCount }})
                                            </button>

                                            <div class="relative" x-data="{ openActions: false }">
                                                <button
                                                    type="button"
                                                    class="rounded border px-2 py-1 text-xs text-gray-500 hover:text-gray-900"
                                                    @click.stop="openActions = !openActions"
                                                >
                                                    ⋯
                                                </button>

                                                <div
                                                    x-show="openActions"
                                                    x-transition
                                                    @click.outside="openActions = false"
                                                    style="display:none"
                                                    class="absolute right-0 mt-2 w-36 rounded border bg-white text-left text-xs shadow z-20"
                                                >
                                                    <button
                                                        type="button"
                                                        class="block w-full px-3 py-2 hover:bg-gray-100"
                                                        @click="
                                                            openActions = false;
                                                            $dispatch('open-modal', 'edit-subtask-{{ $task->id }}-{{ $subTask->id }}')
                                                        "
                                                    >
                                                        Modifier
                                                    </button>

                                                    @if(! $subTask->estArchivee)
                                                        <form method="POST" action="{{ route('ca.tasks.subTasks.archive', [$task->id, $subTask->id]) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button
                                                                type="submit"
                                                                class="block w-full px-3 py-2 text-left hover:bg-gray-100"
                                                                @click="openActions = false"
                                                            >
                                                                Archiver
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form method="POST" action="{{ route('ca.tasks.subTasks.destroy', [$task->id, $subTask->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="block w-full px-3 py-2 text-left text-red-600 hover:bg-gray-100"
                                                            @click="openActions = false"
                                                        >
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 flex justify-between text-[11px] text-gray-500">
                                            <span>Créée : {{ $subTask->dateCreation?->format('d/m/Y') ?? '-' }}</span>
                                            <span>Faite : {{ $subTask->dateEffectuee?->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">Aucune sous-tâche pour le moment.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg border border-dashed p-4 text-center text-sm text-gray-500">
                Aucune tâche enregistrée.
            </div>
        @endforelse
    </div>
</div>
