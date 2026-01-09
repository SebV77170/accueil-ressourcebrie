<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des tâches du CA
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER + BUTTON --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
                <h3 class="text-lg font-bold">Liste des tâches</h3>

                <button
                    x-data
                    type="button"
                    @click="$dispatch('open-modal', 'createTaskModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow">
                    Nouvelle tâche
                </button>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow rounded p-4 md:hidden">
                <div class="space-y-3">
                    @forelse($tasks as $task)
                        <div x-data="{ open: false }" class="border rounded-xl bg-white shadow-sm">
                            <div class="w-full p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3">
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

                                    <div class="flex flex-wrap gap-2">
                                        <button
                                            type="button"
                                            x-data
                                            @click="$dispatch('open-modal', 'comments-{{ $task->id }}')"
                                            class="rounded bg-indigo-500 px-2 py-1 text-xs text-white">
                                            💬({{ $task->commentsCount }})
                                        </button>

                                        <button
                                            type="button"
                                            x-data
                                            @click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')"
                                            class="rounded bg-yellow-400 px-2 py-1 text-xs">
                                            ✏
                                        </button>

                                        @if(! $task->estArchivee)
                                            <form method="POST" action="{{ route('ca.tasks.archive', $task->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="rounded bg-gray-400 px-2 py-1 text-xs">📦</button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('ca.tasks.destroy', $task->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded bg-red-500 px-2 py-1 text-xs text-white">🗑</button>
                                        </form>
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
                                                <div class="rounded-lg border bg-white p-3">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div class="flex items-start gap-3">
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

                                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                                        <button
                                                            type="button"
                                                            x-data
                                                            @click="$dispatch('open-modal', 'comments-sub-{{ $task->id }}-{{ $subTask->id }}')"
                                                            class="rounded bg-indigo-500 px-2 py-1 text-white">
                                                            💬({{ $subTask->commentsCount }})
                                                        </button>

                                                        <button
                                                            type="button"
                                                            x-data
                                                            @click="$dispatch('open-modal', 'edit-subtask-{{ $task->id }}-{{ $subTask->id }}')"
                                                            class="rounded bg-yellow-400 px-2 py-1">
                                                            ✏
                                                        </button>

                                                        @if(! $subTask->estArchivee)
                                                            <form method="POST" action="{{ route('ca.tasks.subTasks.archive', [$task->id, $subTask->id]) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="rounded bg-gray-400 px-2 py-1">📦</button>
                                                            </form>
                                                        @endif

                                                        <form method="POST" action="{{ route('ca.tasks.subTasks.destroy', [$task->id, $subTask->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="rounded bg-red-500 px-2 py-1 text-white">🗑</button>
                                                        </form>
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
                            <tbody x-data="{ open: false }" class="border-b">

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
                                                @if($task->subTasksCount === 0)
                                                    <form method="POST" action="{{ route('ca.tasks.complete', $task->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" @click.stop
                                                            class="flex h-5 w-5 items-center justify-center rounded border {{ $task->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                                            @if($task->estTerminee) ✓ @endif
                                                        </button>
                                                    </form>
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
                                    <td class="p-2 text-right space-x-1">

                                        {{-- COMMENTS TASK --}}
                                        <button
                                            type="button"
                                            x-data
                                            @click="$dispatch('open-modal', 'comments-{{ $task->id }}')"
                                            class="px-2 py-1 bg-indigo-500 text-white rounded">
                                            💬({{ $task->commentsCount }})
                                        </button>

                                        {{-- EDIT TASK --}}
                                        <button
                                            type="button"
                                            x-data
                                            @click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')"
                                            class="px-2 py-1 bg-yellow-400 rounded">
                                            ✏
                                        </button>

                                        {{-- ARCHIVE TASK --}}
                                        @if(! $task->estArchivee)
                                            <form class="inline" method="POST" action="{{ route('ca.tasks.archive', $task->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2 py-1 bg-gray-400 rounded">📦</button>
                                            </form>
                                        @endif

                                        {{-- DELETE TASK --}}
                                        <form class="inline" method="POST" action="{{ route('ca.tasks.destroy', $task->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded">🗑</button>
                                        </form>
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
                                                <div class="border rounded p-3 bg-white">
                                                    <div class="flex justify-between items-start gap-3">

                                                        <div class="flex items-start gap-3">
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
                                                        <div class="space-x-1">

                                                            {{-- COMMENTS SUBTASK --}}
                                                            <button
                                                                type="button"
                                                                x-data
                                                                @click="$dispatch('open-modal', 'comments-sub-{{ $task->id }}-{{ $subTask->id }}')"
                                                                class="px-2 py-1 bg-indigo-500 text-white rounded">
                                                                💬({{ $subTask->commentsCount }})
                                                            </button>

                                                            {{-- EDIT SUBTASK --}}
                                                            <button
                                                                type="button"
                                                                x-data
                                                                @click="$dispatch('open-modal', 'edit-subtask-{{ $task->id }}-{{ $subTask->id }}')"
                                                                class="px-2 py-1 bg-yellow-400 rounded">
                                                                ✏
                                                            </button>

                                                            {{-- ARCHIVE SUBTASK --}}
                                                            @if(! $subTask->estArchivee)
                                                                <form class="inline" method="POST" action="{{ route('ca.tasks.subTasks.archive', [$task->id, $subTask->id]) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="px-2 py-1 bg-gray-400 rounded">📦</button>
                                                                </form>
                                                            @endif

                                                            {{-- DELETE SUBTASK --}}
                                                            <form class="inline" method="POST" action="{{ route('ca.tasks.subTasks.destroy', [$task->id, $subTask->id]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded">🗑</button>
                                                            </form>
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
        </div>
    </div>

    {{-- MODALS --}}
    @foreach($tasks as $task)
        <x-modal name="comments-{{ $task->id }}">
            <div class="p-6 space-y-4">
                <h2 class="text-lg font-bold">Commentaires</h2>

                {{-- LISTE DES COMMENTAIRES --}}
                <div class="max-h-72 overflow-y-auto space-y-2">
                    @forelse($task->comments as $comment)
                        <div class="border rounded p-2">
                            <div class="text-sm text-gray-600">
                                {{ $comment->createdAt->format('d/m/Y H:i') }}
                                @if($comment->userName)
                                    — {{ $comment->userName }}
                                @endif
                            </div>
                            <div>{{ $comment->content }}</div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Aucun commentaire.</p>
                    @endforelse
                </div>

                {{-- FORMULAIRE --}}
                <form method="POST" action="{{ route('ca.tasks.comments.store', $task->id) }}">
                    @csrf
                    <textarea name="content" class="w-full border rounded" placeholder="Votre commentaire..."></textarea>

                    <div class="mt-2 flex justify-end">
                        <x-primary-button>Ajouter</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>

        <x-modal name="edit-task-{{ $task->id }}">
            <form method="POST" action="{{ route('ca.tasks.update', $task->id) }}" class="p-6">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-bold mb-4">Modifier la tâche</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Titre</label>
                        <input type="text" name="titre"
                            value="{{ old('titre', $task->titre) }}"
                            class="mt-1 w-full border rounded">
                    </div>

                    <div>
                        <label>Description</label>
                        <textarea name="description" class="mt-1 w-full border rounded">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Responsables</label>
                        <select name="responsables[]" multiple class="mt-1 w-full border rounded">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    @selected(in_array($user->id, old('responsables', $task->responsables ?? [])))>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Sélectionnez une ou plusieurs personnes.</p>
                    </div>

                    <div>
                        <label>Commentaire</label>
                        <textarea name="commentaire" class="mt-1 w-full border rounded">{{ old('commentaire', $task->commentaire) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Annuler
                    </x-secondary-button>

                    <x-primary-button>
                        Enregistrer
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="create-subtask-{{ $task->id }}">
            <form method="POST" action="{{ route('ca.tasks.subTasks.store', $task->id) }}" class="p-6">
                @csrf

                <h2 class="text-lg font-bold mb-4">Nouvelle sous-tâche</h2>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Titre</label>
                        <input type="text" name="titre" class="mt-1 w-full border rounded">
                    </div>

                    <div>
                        <label>Description</label>
                        <textarea name="description" class="mt-1 w-full border rounded"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Responsables</label>
                        <select name="responsables[]" multiple class="mt-1 w-full border rounded">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(in_array($user->id, old('responsables', [])))>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Sélectionnez une ou plusieurs personnes.</p>
                    </div>

                    <div>
                        <label>Commentaire</label>
                        <textarea name="commentaire" class="mt-1 w-full border rounded"></textarea>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Annuler
                    </x-secondary-button>

                    <x-primary-button>
                        Créer
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        @foreach($task->subTasks as $subTask)
            <x-modal name="comments-sub-{{ $task->id }}-{{ $subTask->id }}">
                <div class="p-6 space-y-4">
                    <h2 class="text-lg font-bold">Commentaires</h2>

                    <div class="max-h-72 overflow-y-auto space-y-2">
                        @forelse($subTask->comments as $comment)
                            <div class="border rounded p-2">
                                <div class="text-sm text-gray-600">
                                    {{ $comment->createdAt->format('d/m/Y H:i') }}
                                    @if($comment->userName)
                                        — {{ $comment->userName }}
                                    @endif
                                </div>
                                <div>{{ $comment->content }}</div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">Aucun commentaire.</p>
                        @endforelse
                    </div>

                    <form method="POST" action="{{ route('ca.tasks.subTasks.comments.store', [$task->id, $subTask->id]) }}">
                        @csrf
                        <textarea name="content" class="w-full border rounded" placeholder="Votre commentaire..."></textarea>
                        <div class="mt-2 flex justify-end">
                            <x-primary-button>Ajouter</x-primary-button>
                        </div>
                    </form>
                </div>
            </x-modal>

            <x-modal name="edit-subtask-{{ $task->id }}-{{ $subTask->id }}">
                <form method="POST" action="{{ route('ca.tasks.subTasks.update', [$task->id, $subTask->id]) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <h2 class="text-lg font-bold mb-4">Modifier la sous-tâche</h2>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium">Titre</label>
                            <input type="text" name="titre"
                                value="{{ old('titre', $subTask->titre) }}"
                                class="mt-1 w-full border rounded">
                        </div>

                        <div>
                            <label>Description</label>
                            <textarea name="description" class="mt-1 w-full border rounded">{{ old('description', $subTask->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Responsables</label>
                            <select name="responsables[]" multiple class="mt-1 w-full border rounded">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        @selected(in_array($user->id, old('responsables', $subTask->responsables ?? [])))>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Sélectionnez une ou plusieurs personnes.</p>
                        </div>

                        <div>
                            <label>Commentaire</label>
                            <textarea name="commentaire" class="mt-1 w-full border rounded">{{ old('commentaire', $subTask->commentaire) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Annuler
                        </x-secondary-button>

                        <x-primary-button>
                            Enregistrer
                        </x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endforeach
    @endforeach

    {{-- CREATE TASK MODAL --}}
    <x-modal name="createTaskModal">
        <form method="POST" action="{{ route('ca.tasks.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-bold mb-4">Nouvelle tâche</h2>

            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium">Titre</label>
                    <input type="text" name="titre" class="mt-1 w-full border rounded">
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" class="mt-1 w-full border rounded"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Responsables</label>
                    <select name="responsables[]" multiple class="mt-1 w-full border rounded">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(in_array($user->id, old('responsables', [])))>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Sélectionnez une ou plusieurs personnes.</p>
                </div>

                <div>
                    <label>Commentaire</label>
                    <textarea name="commentaire" class="mt-1 w-full border rounded"></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end space-x-2">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Annuler
                </x-secondary-button>

                <x-primary-button>
                    Créer
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</x-app-layout>
