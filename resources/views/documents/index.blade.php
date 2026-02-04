<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Documents administratifs') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Consultez, téléchargez ou retirez les documents disponibles dans l’application.') }}
                </p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Dossier: public/documents') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form
                        action="{{ route('documents.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="mb-6"
                        x-ref="uploadForm"
                        x-data="{
                            isDragging: false,
                            uploadError: null,
                            maxUploadBytes: {{ $uploadLimitBytes }},
                            maxUploadLabel: '{{ $uploadLimitLabel ?? '' }}',
                            checkFile(file) {
                                if (!file) {
                                    return false;
                                }

                                if (this.maxUploadBytes > 0 && file.size > this.maxUploadBytes) {
                                    this.uploadError = `Le document dépasse la limite d'envoi (${this.maxUploadLabel}).`;
                                    return false;
                                }

                                this.uploadError = null;
                                return true;
                            }
                        }"
                        x-on:dragover.prevent="isDragging = true"
                        x-on:dragleave.prevent="isDragging = false"
                        x-on:drop.prevent="
                            isDragging = false;
                            if ($event.dataTransfer.files.length && checkFile($event.dataTransfer.files[0])) {
                                $refs.documentInput.files = $event.dataTransfer.files;
                                $refs.uploadForm.submit();
                            }
                        "
                    >
                        @csrf
                        <div
                            class="flex flex-col gap-4 rounded-lg border-2 border-dashed p-6 transition"
                            :class="isDragging ? 'border-blue-500 bg-blue-50/60 dark:bg-blue-950/30' : 'border-gray-200 dark:border-gray-700'"
                        >
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    {{ __('Importer un document') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ __('Glissez-déposez un fichier ou utilisez le bouton pour sélectionner un document.') }}
                                </p>
                                @if ($uploadLimitLabel)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('Limite serveur actuelle : :limit', ['limit' => $uploadLimitLabel]) }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-4">
                                <label class="inline-flex cursor-pointer items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-blue-500">
                                    {{ __('Importer') }}
                                    <input
                                        x-ref="documentInput"
                                        type="file"
                                        name="document"
                                        class="hidden"
                                        x-on:change="if (checkFile($event.target.files[0])) { $refs.uploadForm.submit(); }"
                                    />
                                </label>
                            </div>
                            <template x-if="uploadError">
                                <p class="text-xs text-red-600 dark:text-red-300" x-text="uploadError"></p>
                            </template>
                            @error('document')
                                <p class="text-xs text-red-600 dark:text-red-300">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </form>

                    @if (session('status'))
                        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/40 dark:text-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($documents->isEmpty())
                        <div class="rounded-md border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-gray-600 dark:text-gray-300">
                            <p class="text-lg font-semibold">{{ __('Aucun document pour le moment.') }}</p>
                            <p class="mt-2 text-sm">
                                {{ __('Ajoutez des fichiers dans public/documents pour les rendre disponibles ici.') }}
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900/40">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            {{ __('Document') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            {{ __('Dernière mise à jour') }}
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            {{ __('Taille') }}
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($documents as $document)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                            <td class="px-4 py-4">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $document['name'] }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $document['updated_at']->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $document['size'] }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col items-end gap-2 sm:flex-row sm:justify-end">
                                                    <a
                                                        href="{{ route('documents.download', $document['name']) }}"
                                                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-blue-500"
                                                    >
                                                        {{ __('Télécharger') }}
                                                    </a>
                                                    <form method="POST" action="{{ route('documents.destroy', $document['name']) }}" onsubmit="return confirm('{{ __('Supprimer ce document ?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-red-700 transition hover:border-red-300 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200"
                                                        >
                                                            {{ __('Retirer') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
