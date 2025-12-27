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
                            <th class="p-2 text-left">✔</th>
                            <th class="p-2 text-left">Titre</th>
                            <th class="p-2 text-left">Responsables</th>
                            <th class="p-2 text-left">Commentaire</th>
                            <th class="p-2 text-left">Créée</th>
                            <th class="p-2 text-left">Faite</th>
                            <th class="p-2 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tasks as $task)
                        <tr class="border-b">

                            {{-- COMPLETE --}}
                            <td class="p-2">
                                <form method="POST" action="{{ route('ca.tasks.complete', $task->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="w-6 h-6 border rounded flex items-center justify-center
                                               {{ $task->estTerminee ? 'bg-green-500 text-white' : '' }}">
                                        @if($task->estTerminee) ✓ @endif
                                    </button>
                                </form>
                            </td>

                            {{-- TITRE --}}
                            <td class="p-2 font-semibold">
                                {{ $task->titre }}
                            </td>

                            {{-- RESPONSABLES --}}
                            <td class="p-2">
                                {{ $task->responsables ? implode(', ', $task->responsables) : '-' }}
                            </td>

                            {{-- COMMENTAIRE --}}
                            <td class="p-2">
                                {{ $task->commentaire }}
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
                            <td class="p-2 text-right space-x-1">

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

                        @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
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
