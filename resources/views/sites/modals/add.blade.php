{{-- MODALE AJOUT SITE --}}
    <div
        x-show="open"
        x-transition
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div
            @click.outside="open = false"
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg p-6"
        >
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                Ajouter un site
            </h3>

            <form method="POST" action="{{ route('sites.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium">Nom</label>
                    <input name="nom" required class="w-full rounded-md border-gray-300" />
                </div>

                <div>
                    <label class="block text-sm font-medium">URL</label>
                    <input name="url" type="url" required class="w-full rounded-md border-gray-300" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Catégorie</label>
                    <select
                        x-model="$store.site.category_id"
                        name="category_id"
                        class="w-full border rounded p-2"
                    >
                        <template x-for="cat in categories" :key="cat.id">
                            <option :value="cat.id" x-text="cat.nom"></option>
                        </template>
                    </select>

                    <button
                        type="button"
                        class="text-sm text-blue-600"
                        @click="$store.ui.showAddCategory = true"
                    >
                        + Nouvelle catégorie
                    </button>


                </div>

                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" class="w-full rounded-md border-gray-300"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 text-sm rounded-md border"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>