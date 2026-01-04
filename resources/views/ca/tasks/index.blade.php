<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des tâches du CA
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">


            {{-- HEADER + BUTTON --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Liste des tâches</h3>

                <button
                    x-data
                    @click="$dispatch('open-modal', 'createTaskModal')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow">
                    Nouvelle tâche
                </button>

            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow rounded p-4">
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
                        <tr x-data="{ open: false }" class="border-b align-top">

                            <td class="p-2">
                                <button type="button"
                                    @click.stop="open = !open"
                                    class="w-8 h-8 border rounded flex items-center justify-center bg-gray-100">
                                    <span x-show="!open">+</span>
                                    <span x-show="open">−</span>
                                </button>
                            </td>

                            {{-- TITRE --}}
                            <td class="p-2 font-semibold" @click="open = !open">
                                <div class="flex items-start justify-between">
                                    <span>{{ $task->titre }}</span>
                                    <span class="text-xs text-gray-500" x-text="open ? 'Réduire' : 'Voir'"></span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $task->completedSubTasksCount }} / {{ $task->subTasksCount }} sous-tâches complétées
                                </p>
                            </td>

                            {{-- SOUS-TACHES --}}
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
                                {{ $task->responsables ? implode(', ', $task->responsables) : '-' }}
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

                            {{-- ACTIONS --}}
                            <td class="p-2 text-right space-x-1" @click.stop>

                                <button
                                    x-data
                                    @click="$dispatch('open-modal', 'comments-{{ $task->id }}')"
                                    class="px-2 py-1 bg-indigo-500 text-white rounded">
                                    💬({{ $task->commentsCount }})
                                </button>

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



                                {{-- EDIT --}}
                                <button
                                    x-data
                                    @click="$dispatch('open-modal', 'edit-task-{{ $task->id }}')"
                                    class="px-2 py-1 bg-yellow-400 rounded">
                                    ✏
                                </button>

                                {{-- ARCHIVE --}}
                                @if(! $task->estArchivee)
                                <form class="inline" method="POST" action="{{ route('ca.tasks.archive', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-2 py-1 bg-gray-400 rounded">📦</button>
                                </form>
                                @endif

                                {{-- DELETE --}}
                                <form class="inline" method="POST" action="{{ route('ca.tasks.destroy', $task->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 bg-red-500 text-white rounded">
                                        🗑
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <tr x-show="open" x-transition x-cloak class="bg-gray-50 border-b hidden">
                            <td colspan="8" class="p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold">Sous-tâches</p>
                                        <p class="text-sm text-gray-600">Cliquez sur une sous-tâche pour la compléter ou la modifier.</p>
                                    </div>
                                    <button
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
                                                    <form method="POST" action="{{ route('ca.tasks.subTasks.complete', [$task->id, $subTask->id]) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button
                                                            class="w-6 h-6 border rounded flex items-center justify-center {{ $subTask->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                                            @if($subTask->estTerminee) ✓ @endif
                                                        </button>
                                                    </form>
                                                    <div>
                                                        <div class="font-semibold">{{ $subTask->titre }}</div>
                                                        <div class="text-sm text-gray-700">{{ $subTask->description ?? '-' }}</div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Responsables : {{ $subTask->responsables ? implode(', ', $subTask->responsables) : '-' }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="space-x-1" @click.stop>
                                                    <button
                                                        x-data
                                                        @click="$dispatch('open-modal', 'comments-sub-{{ $task->id }}-{{ $subTask->id }}')"
                                                        class="px-2 py-1 bg-indigo-500 text-white rounded">
                                                        💬({{ $subTask->commentsCount }})
                                                    </button>

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

                                                    <button
                                                        x-data
                                                        @click="$dispatch('open-modal', 'edit-subtask-{{ $task->id }}-{{ $subTask->id }}')"
                                                        class="px-2 py-1 bg-yellow-400 rounded">
                                                        ✏
                                                    </button>

                                                    @if(! $subTask->estArchivee)
                                                    <form class="inline" method="POST" action="{{ route('ca.tasks.subTasks.archive', [$task->id, $subTask->id]) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="px-2 py-1 bg-gray-400 rounded">📦</button>
                                                    </form>
                                                    @endif

                                                    <form class="inline" method="POST" action="{{ route('ca.tasks.subTasks.destroy', [$task->id, $subTask->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="px-2 py-1 bg-red-500 text-white rounded">🗑</button>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="mt-2 text-xs text-gray-500 flex justify-between">
                                                <span>Créée : {{ $subTask->dateCreation?->format('d/m/Y') ?? '-' }}</span>
                                                <span>Faite : {{ $subTask->dateEffectuee?->format('d/m/Y') ?? '-' }}</span>
                                            </div>
                                        </div>

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
                                    @empty
                                        <p class="text-gray-600 text-sm">Aucune sous-tâche pour le moment.</p>
                                    @endforelse
                                </div>
                            </td>
                        </tr>

                        {{-- EDIT MODAL --}}
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

    {{-- CREATE MODAL --}}
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
