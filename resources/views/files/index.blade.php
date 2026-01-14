<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fichiers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold">{{ __('Gestionnaire WebDav AlwaysData') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ __('Glissez-déposez vos fichiers pour les ajouter à votre espace centralisé, puis consultez-les dans la liste ci-dessous. La connexion WebDav s’appuie sur les paramètres définis dans le fichier .env.') }}
                        </p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                        <div class="space-y-4">
                            <form data-upload-form class="space-y-4">
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center transition" data-drop-zone>
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="h-14 w-14 rounded-full bg-indigo-50 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-300">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-9m0 0 3.75 3.75M12 7l-3.75 3.75" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 16.5A4.5 4.5 0 0015.75 12h-7.5A4.5 4.5 0 003.75 16.5v.75A3.75 3.75 0 007.5 21h9a3.75 3.75 0 003.75-3.75v-.75z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold">{{ __('Déposez vos fichiers ici') }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Ou cliquez pour sélectionner plusieurs fichiers.') }}</p>
                                        </div>
                                        <label class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition cursor-pointer">
                                            <span>{{ __('Ajouter des fichiers') }}</span>
                                            <input id="fileInput" name="files[]" type="file" class="hidden" multiple>
                                        </label>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                        <h4 class="font-semibold">{{ __('Files en cours') }}</h4>
                                        <button class="text-sm text-indigo-600 dark:text-indigo-300 font-semibold" type="button" data-clear-list>
                                            {{ __('Vider la sélection') }}
                                        </button>
                                    </div>
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700" data-file-list>
                                        <li class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Aucun fichier sélectionné pour le moment.') }}
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center justify-end gap-3">
                                    <span class="text-sm text-emerald-600" data-upload-status></span>
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500 transition">
                                        {{ __('Téléverser sur WebDav') }}
                                    </button>
                                </div>
                            </form>

                            <div class="rounded-xl border border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <h4 class="font-semibold">{{ __('Fichiers disponibles') }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Cliquez sur un fichier pour le télécharger depuis votre espace AlwaysData.') }}
                                    </p>
                                </div>
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700" data-available-files>
                                    <li class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Chargement des fichiers...') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <aside class="space-y-4">
                            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-5 space-y-4">
                                <h4 class="font-semibold">{{ __('Actions rapides') }}</h4>
                                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                        <p>{{ __('Consulter un fichier : cliquez sur une ligne pour déclencher un téléchargement.') }}</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-indigo-500"></span>
                                        <p>{{ __('Glisser-déposer pour téléverser de nouveaux fichiers vers votre WebDav AlwaysData.') }}</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                                        <p>{{ __('Suivi des transferts : le retour de confirmation indiquera l’état des uploads.') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 p-5">
                                <h4 class="font-semibold mb-2">{{ __('Connexion WebDav') }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ __('Définissez WEBDAV_URL, WEBDAV_USERNAME, WEBDAV_PASSWORD et WEBDAV_ROOT dans le fichier .env pour activer la synchronisation.') }}
                                </p>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropZone = document.querySelector('[data-drop-zone]');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.querySelector('[data-file-list]');
        const clearButton = document.querySelector('[data-clear-list]');
        const uploadForm = document.querySelector('[data-upload-form]');
        const uploadStatus = document.querySelector('[data-upload-status]');
        const availableList = document.querySelector('[data-available-files]');
        const apiIndexUrl = @json(route('api.files.index'));
        const apiStoreUrl = @json(route('api.files.store'));
        const apiDownloadUrl = @json(route('api.files.download', ['path' => '']))
            .replace(/\/$/, '');

        const formatSize = (bytes) => {
            if (!bytes) return '0 octet';
            const units = ['octets', 'Ko', 'Mo', 'Go'];
            let size = bytes;
            let unitIndex = 0;
            while (size >= 1024 && unitIndex < units.length - 1) {
                size /= 1024;
                unitIndex += 1;
            }
            return `${size.toFixed(size < 10 && unitIndex > 0 ? 1 : 0)} ${units[unitIndex]}`;
        };

        const renderSelection = (files) => {
            fileList.innerHTML = '';

            if (!files.length) {
                const emptyItem = document.createElement('li');
                emptyItem.className = 'px-4 py-3 text-sm text-gray-500 dark:text-gray-400';
                emptyItem.textContent = 'Aucun fichier sélectionné pour le moment.';
                fileList.appendChild(emptyItem);
                return;
            }

            Array.from(files).forEach((file) => {
                const item = document.createElement('li');
                item.className = 'px-4 py-3 flex items-center justify-between gap-4';

                const details = document.createElement('div');
                details.className = 'text-sm';

                const name = document.createElement('p');
                name.className = 'font-medium text-gray-900 dark:text-gray-100';
                name.textContent = file.name;

                const meta = document.createElement('p');
                meta.className = 'text-xs text-gray-500 dark:text-gray-400';
                meta.textContent = `${formatSize(file.size)} · ${file.type || 'Type inconnu'}`;

                details.appendChild(name);
                details.appendChild(meta);

                const action = document.createElement('span');
                action.className = 'text-sm font-semibold text-indigo-600 dark:text-indigo-300';
                action.textContent = 'Prêt à téléverser';

                item.appendChild(details);
                item.appendChild(action);
                fileList.appendChild(item);
            });
        };

        const renderAvailable = (files) => {
            availableList.innerHTML = '';

            if (!files.length) {
                const emptyItem = document.createElement('li');
                emptyItem.className = 'px-4 py-3 text-sm text-gray-500 dark:text-gray-400';
                emptyItem.textContent = 'Aucun fichier sur WebDav pour le moment.';
                availableList.appendChild(emptyItem);
                return;
            }

            files.forEach((file) => {
                const item = document.createElement('li');
                item.className = 'px-4 py-3 flex items-center justify-between gap-4';

                const details = document.createElement('div');
                details.className = 'text-sm';

                const name = document.createElement('p');
                name.className = 'font-medium text-gray-900 dark:text-gray-100';
                name.textContent = file.basename || file.path;

                const meta = document.createElement('p');
                meta.className = 'text-xs text-gray-500 dark:text-gray-400';
                meta.textContent = formatSize(file.size || 0);

                details.appendChild(name);
                details.appendChild(meta);

                const link = document.createElement('a');
                link.className = 'text-sm font-semibold text-indigo-600 dark:text-indigo-300';
                link.textContent = 'Télécharger';
                link.href = `${apiDownloadUrl}/${encodeURIComponent(file.path)}`;

                item.appendChild(details);
                item.appendChild(link);
                availableList.appendChild(item);
            });
        };

        const loadAvailableFiles = async () => {
            const response = await fetch(apiIndexUrl, { headers: { Accept: 'application/json' } });
            if (!response.ok) {
                renderAvailable([]);
                return;
            }
            const data = await response.json();
            renderAvailable(data.files || []);
        };

        const handleFiles = (files) => {
            if (!files || !files.length) return;
            renderSelection(files);
        };

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (event) => {
            handleFiles(event.target.files);
        });

        dropZone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-500/10');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-500/10');
        });

        dropZone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-500/10');
            fileInput.files = event.dataTransfer.files;
            handleFiles(event.dataTransfer.files);
        });

        clearButton.addEventListener('click', () => {
            fileInput.value = '';
            renderSelection([]);
        });

        uploadForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            uploadStatus.textContent = '';

            if (!fileInput.files.length) {
                uploadStatus.textContent = 'Veuillez sélectionner des fichiers.';
                return;
            }

            const formData = new FormData();
            Array.from(fileInput.files).forEach((file) => {
                formData.append('files[]', file);
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(apiStoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: formData,
            });

            if (!response.ok) {
                uploadStatus.textContent = 'Le téléversement a échoué.';
                return;
            }

            uploadStatus.textContent = 'Téléversement terminé.';
            fileInput.value = '';
            renderSelection([]);
            await loadAvailableFiles();
        });

        loadAvailableFiles();
    </script>
</x-app-layout>
