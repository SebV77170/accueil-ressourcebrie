<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Gestionnaire de fichiers</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Accès WebDAV via l’API applicative.</p>
            </div>
        </div>
    </x-slot>

    <div
        x-data="fileManager()"
        x-init="init()"
        class="py-6 sm:py-8"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <template x-for="(crumb, index) in breadcrumbs()" :key="crumb.path">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-gray-700"
                            @click="goTo(crumb.path)"
                        >
                            <span x-text="crumb.name"></span>
                            <span x-show="index < breadcrumbs().length - 1">/</span>
                        </button>
                    </template>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            x-model="newFolderName"
                            placeholder="Nouveau dossier"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        />
                        <button
                            type="button"
                            @click="createFolder"
                            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                        >
                            Créer
                        </button>
                    </div>
                    <label class="inline-flex cursor-pointer items-center justify-center rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <span>Importer</span>
                        <input
                            type="file"
                            class="sr-only"
                            @change="handleUpload"
                        />
                    </label>
                </div>
            </div>

            <div
                x-show="error"
                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                x-text="error"
            ></div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-4 py-3 text-sm font-semibold text-gray-700 dark:border-gray-700 dark:text-gray-200">
                    Contenu du dossier
                </div>

                <div class="p-4">
                    <template x-if="loading">
                        <div class="text-sm text-gray-500">Chargement en cours...</div>
                    </template>

                    <template x-if="!loading && items.length === 0">
                        <div class="flex flex-col items-center gap-2 rounded-lg border border-dashed border-gray-300 px-6 py-10 text-center text-sm text-gray-500">
                            <span>Aucun fichier ou dossier pour le moment.</span>
                            <span class="text-xs">Utilisez les actions ci-dessus pour ajouter du contenu.</span>
                        </div>
                    </template>

                    <div class="hidden sm:block">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-200">
                            <thead class="text-xs uppercase text-gray-400">
                                <tr>
                                    <th class="py-2">Nom</th>
                                    <th class="py-2">Taille</th>
                                    <th class="py-2">Modifié</th>
                                    <th class="py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in items" :key="item.path">
                                    <tr class="border-t border-gray-100 dark:border-gray-700">
                                        <td class="py-3">
                                            <button
                                                type="button"
                                                class="flex items-center gap-2 text-left font-medium text-gray-800 hover:text-blue-600 dark:text-gray-100 dark:hover:text-blue-300"
                                                @click="open(item)"
                                            >
                                                <span x-text="item.type === 'dir' ? '📁' : '📄'"></span>
                                                <span x-text="item.name"></span>
                                            </button>
                                        </td>
                                        <td class="py-3" x-text="formatSize(item.size)"></td>
                                        <td class="py-3" x-text="formatDate(item.last_modified)"></td>
                                        <td class="py-3">
                                            <div class="flex justify-end gap-2">
                                                <button
                                                    type="button"
                                                    class="rounded-md border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700"
                                                    @click="renameItem(item)"
                                                >
                                                    Renommer
                                                </button>
                                                <button
                                                    type="button"
                                                    class="rounded-md border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700"
                                                    x-show="item.type === 'file'"
                                                    @click="downloadItem(item)"
                                                >
                                                    Télécharger
                                                </button>
                                                <button
                                                    type="button"
                                                    class="rounded-md border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-300 dark:hover:bg-red-900/20"
                                                    @click="deleteItem(item)"
                                                >
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid gap-4 sm:hidden">
                        <template x-for="item in items" :key="item.path">
                            <div class="rounded-xl border border-gray-200 p-4 shadow-sm dark:border-gray-700">
                                <div class="flex items-start justify-between">
                                    <button
                                        type="button"
                                        class="flex items-center gap-2 text-left font-semibold text-gray-800 dark:text-gray-100"
                                        @click="open(item)"
                                    >
                                        <span x-text="item.type === 'dir' ? '📁' : '📄'"></span>
                                        <span x-text="item.name"></span>
                                    </button>
                                    <span class="text-xs text-gray-400" x-text="formatDate(item.last_modified)"></span>
                                </div>
                                <div class="mt-2 text-xs text-gray-500" x-text="formatSize(item.size)"></div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700"
                                        @click="renameItem(item)"
                                    >
                                        Renommer
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border border-gray-200 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700"
                                        x-show="item.type === 'file'"
                                        @click="downloadItem(item)"
                                    >
                                        Télécharger
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-md border border-red-200 px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-300 dark:hover:bg-red-900/20"
                                        @click="deleteItem(item)"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fileManager() {
            return {
                path: '',
                items: [],
                loading: false,
                error: null,
                newFolderName: '',

                init() {
                    this.fetchItems();
                },

                breadcrumbs() {
                    const parts = this.path ? this.path.split('/') : [];
                    const crumbs = [{ name: 'Racine', path: '' }];
                    let current = '';

                    parts.forEach((part) => {
                        current = current ? `${current}/${part}` : part;
                        crumbs.push({ name: part, path: current });
                    });

                    return crumbs;
                },

                async fetchItems() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch(`/api/files?path=${encodeURIComponent(this.path)}`);

                        if (!response.ok) {
                            throw new Error('Impossible de charger les fichiers.');
                        }

                        const data = await response.json();
                        this.items = data.items || [];
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                goTo(path) {
                    this.path = path;
                    this.fetchItems();
                },

                open(item) {
                    if (item.type === 'dir') {
                        this.path = item.path;
                        this.fetchItems();
                    }
                },

                async createFolder() {
                    if (!this.newFolderName.trim()) {
                        return;
                    }

                    await this.sendJson('/api/files/folders', {
                        path: this.path,
                        name: this.newFolderName.trim(),
                    });

                    this.newFolderName = '';
                    this.fetchItems();
                },

                async handleUpload(event) {
                    const [file] = event.target.files;
                    if (!file) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('path', this.path);

                    try {
                        const response = await fetch('/api/files/upload', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': this.csrfToken(),
                            },
                            body: formData,
                        });

                        if (!response.ok) {
                            throw new Error('Impossible de téléverser le fichier.');
                        }

                        await response.json();
                        this.fetchItems();
                    } catch (error) {
                        this.error = error.message;
                    } finally {
                        event.target.value = '';
                    }
                },

                async deleteItem(item) {
                    if (!confirm(`Supprimer ${item.name} ?`)) {
                        return;
                    }

                    await this.sendJson('/api/files', { path: item.path }, 'DELETE');
                    this.fetchItems();
                },

                async renameItem(item) {
                    const name = prompt('Nouveau nom', item.name);
                    if (!name || name.trim() === '' || name === item.name) {
                        return;
                    }

                    const parts = item.path.split('/');
                    parts.pop();
                    const target = parts.length ? `${parts.join('/')}/${name.trim()}` : name.trim();

                    await this.sendJson('/api/files/move', {
                        from: item.path,
                        to: target,
                    }, 'PATCH');

                    this.fetchItems();
                },

                downloadItem(item) {
                    window.location = `/api/files/download?path=${encodeURIComponent(item.path)}`;
                },

                async sendJson(url, payload, method = 'POST') {
                    try {
                        const response = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken(),
                            },
                            body: JSON.stringify(payload),
                        });

                        if (!response.ok) {
                            throw new Error('Une erreur est survenue.');
                        }

                        return await response.json();
                    } catch (error) {
                        this.error = error.message;
                        throw error;
                    }
                },

                formatSize(size) {
                    if (size === null || size === undefined) {
                        return '—';
                    }

                    if (size === 0) {
                        return '0 o';
                    }

                    const units = ['o', 'Ko', 'Mo', 'Go', 'To'];
                    const index = Math.floor(Math.log(size) / Math.log(1024));
                    const value = (size / Math.pow(1024, index)).toFixed(1);

                    return `${value} ${units[index]}`;
                },

                formatDate(timestamp) {
                    if (!timestamp) {
                        return '—';
                    }

                    return new Date(timestamp * 1000).toLocaleString('fr-FR');
                },

                csrfToken() {
                    return document.querySelector('meta[name=csrf-token]').getAttribute('content');
                },
            };
        }
    </script>
</x-app-layout>
