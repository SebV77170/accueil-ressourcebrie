{{-- MODALE AJOUT CATEGORIE --}}
<div
    x-show="$store.ui.showAddCategory"
    x-transition
    style="display:none"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    @keydown.escape.window="$store.ui.showAddCategory = false"
>
    <div
        @click.outside="$store.ui.showAddCategory = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6"
    >
        <h3 class="text-lg font-semibold mb-4">
            Ajouter une catégorie
        </h3>

        <form
            @submit.prevent="addCategory"
            class="space-y-4"
        >
            <div>
                <label class="block text-sm font-medium">
                    Nom de la catégorie
                </label>

                <input
                    type="text"
                    x-model="newCategoryName"
                    class="w-full border rounded p-2"
                    required
                >
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="$store.ui.showAddCategory = false"
                    class="px-4 py-2 border rounded"
                >
                    Annuler
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>
