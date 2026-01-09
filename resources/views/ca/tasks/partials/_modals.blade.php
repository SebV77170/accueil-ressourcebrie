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
