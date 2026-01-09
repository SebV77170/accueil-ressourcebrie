<div class="hidden md:block bg-white shadow rounded p-4">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b">
                <th class="p-2 text-left w-10"></th>
                <th class="p-2 text-left">Titre</th>
                <th class="p-2 text-left">Sous-tâches</th>
                <th class="p-2 text-left">Responsables</th>
                <th class="p-2 text-left">Description</th>
                <th class="p-2 text-left">Créée</th>
                <th class="p-2 text-left">Faite</th>
                <th class="p-2 text-right">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($tasks as $task)

                {{-- IMPORTANT : x-data englobe les 2 tr (tâche + détail) --}}
                <tbody x-data="taskToggle({{ $task->id }})" class="border-b">

                    {{-- LIGNE TÂCHE --}}
                    <tr class="align-top hover:bg-gray-50">

                        {{-- TOGGLE (seulement ici) --}}
                        <td class="p-2 cursor-pointer" @click="open = !open">
                            <div class="w-8 h-8 border rounded flex items-center justify-center bg-gray-100">
                                <span x-show="!open">+</span>
                                <span x-show="open">−</span>
                            </div>
                        </td>

                        {{-- TITRE (cliquable) --}}
                        <td class="p-2 font-semibold cursor-pointer" @click="open = !open">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-2">
                                    <span class="mt-2 h-2.5 w-2.5 rounded-full" style="background-color: {{ $taskColor }}"></span>
                                    @if($task->subTasksCount === 0)
                                        <form method="POST" action="{{ route('ca.tasks.complete', $task->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" @click.stop
                                                class="flex h-5 w-5 items-center justify-center rounded border {{ $task->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                                @if($task->estTerminee) ✓ @endif
                                            </button>
                                        </form>
                                    @elseif($task->subTasksCount > 0 && $task->completedSubTasksCount === $task->subTasksCount)
                                        <span class="flex h-5 w-5 items-center justify-center rounded border bg-green-500 text-white" aria-label="Tâche terminée">
                                            ✓
                                        </span>
                                    @endif
                                    <span>{{ $task->titre }}</span>
                                </div>
                                <span class="text-xs text-gray-500" x-text="open ? 'Réduire' : 'Voir'"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $task->completedSubTasksCount }} / {{ $task->subTasksCount }} sous-tâches complétées
                            </p>
                        </td>

                        {{-- SOUS-TACHES (compteur) --}}
                        <td class="p-2">
                            @if($task->subTasksCount)
                                <span class="font-semibold">{{ $task->completedSubTasksCount }}</span>
                                / {{ $task->subTasksCount }}
                            @else
                                <span class="text-gray-500">Aucune</span>
                            @endif
                        </td>

                        {{-- RESPONSABLES --}}
                        <td class="p-2">
                            {{ $task->responsablesNoms ? implode(', ', $task->responsablesNoms) : '-' }}
                        </td>

                        {{-- DESCRIPTION --}}
                        <td class="p-2">
                            {{ $task->description }}
                        </td>

                        {{-- CRÉÉE --}}
                        <td class="p-2">
                            {{ $task->dateCreation?->format('d/m/Y') ?? '-' }}
                        </td>

                        {{-- FAITE --}}
                        <td class="p-2">
                            {{ $task->dateEffectuee?->format('d/m/Y') ?? '-' }}
                        </td>

                        {{-- ACTIONS (ne doit pas toggler) --}}
                        <td class="p-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- COMMENTS TASK --}}
                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('open-modal', 'comments-{{ $task->id }}')"
                                    class="px-2 py-1 bg-indigo-500 text-white rounded">
                                    💬({{ $task->commentsCount }})
                                </button>

                                {{-- ACTIONS MENU --}}
                                <div class="relative" x-data="{ openActions: false }">
                                    <button
                                        type="button"
                                        class="px-2 py-1 text-gray-500 hover:text-black"
                                        @click.stop="openActions = !openActions"
                                    >
                                        ⋯
                                    </button>

                                    <div
                                        x-show="openActions"
                                        x-transition
                                        @click.outside="openActions = false"
                                        style="display:none"
                                        class="absolute right-0 mt-2 w-40 rounded border bg-white text-left text-sm shadow z-20"
                                    >
                                        <button
                                            type="button"
                                            class="block w-full px-4 py-2 hover:bg-gray-100"
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
                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100"
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
                                                class="block w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100"
                                                @click="openActions = false"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- DÉPLOIEMENT SOUS-TÂCHES (masqué par défaut) --}}
                    <tr x-show="open" x-transition.opacity.duration.200ms x-cloak class="bg-gray-50">
                        <td colspan="8" class="p-4">

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">Sous-tâches</p>
                                    <p class="text-sm text-gray-600">Cliquez sur une sous-tâche pour la compléter ou la modifier.</p>
                                </div>

                                <button
                                    type="button"
                                    x-data
                                    @click="$dispatch('open-modal', 'create-subtask-{{ $task->id }}')"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow">
                                    Ajouter une sous-tâche
                                </button>
                            </div>

                            <div class="mt-4 space-y-3">
                                @forelse($task->subTasks as $subTask)
                                    <div class="border rounded p-3 bg-white border-l-4" style="border-left-color: {{ $subTaskColor }}">
                                        <div class="flex justify-between items-start gap-3">

                                            <div class="flex items-start gap-3">
                                                <span class="mt-1 h-2.5 w-2.5 rounded-full" style="background-color: {{ $subTaskColor }}"></span>
                                                {{-- COMPLETE SUBTASK --}}
                                                <form method="POST" action="{{ route('ca.tasks.subTasks.complete', [$task->id, $subTask->id]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="w-6 h-6 border rounded flex items-center justify-center {{ $subTask->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                                        @if($subTask->estTerminee) ✓ @endif
                                                    </button>
                                                </form>

                                                <div>
                                                    <div class="font-semibold">{{ $subTask->titre }}</div>
                                                    <div class="text-sm text-gray-700">{{ $subTask->description ?? '-' }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Responsables : {{ $subTask->responsablesNoms ? implode(', ', $subTask->responsablesNoms) : '-' }}
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ACTIONS SUBTASK --}}
                                            <div class="flex items-center gap-2">
                                                {{-- COMMENTS SUBTASK --}}
                                                <button
                                                    type="button"
                                                    x-data
                                                    @click="$dispatch('open-modal', 'comments-sub-{{ $task->id }}-{{ $subTask->id }}')"
                                                    class="px-2 py-1 bg-indigo-500 text-white rounded">
                                                    💬({{ $subTask->commentsCount }})
                                                </button>

                                                {{-- ACTIONS MENU --}}
                                                <div class="relative" x-data="{ openActions: false }">
                                                    <button
                                                        type="button"
                                                        class="px-2 py-1 text-gray-500 hover:text-black"
                                                        @click.stop="openActions = !openActions"
                                                    >
                                                        ⋯
                                                    </button>

                                                    <div
                                                        x-show="openActions"
                                                        x-transition
                                                        @click.outside="openActions = false"
                                                        style="display:none"
                                                        class="absolute right-0 mt-2 w-40 rounded border bg-white text-left text-sm shadow z-20"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="block w-full px-4 py-2 hover:bg-gray-100"
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
                                                                    class="block w-full px-4 py-2 text-left hover:bg-gray-100"
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
                                                                class="block w-full px-4 py-2 text-left text-red-600 hover:bg-gray-100"
                                                                @click="openActions = false"
                                                            >
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-2 text-xs text-gray-500 flex justify-between">
                                            <span>Créée : {{ $subTask->dateCreation?->format('d/m/Y') ?? '-' }}</span>
                                            <span>Faite : {{ $subTask->dateEffectuee?->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-600 text-sm">Aucune sous-tâche pour le moment.</p>
                                @endforelse
                            </div>

                        </td>
                    </tr>
                </tbody>

            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">
                        Aucune tâche enregistrée.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
