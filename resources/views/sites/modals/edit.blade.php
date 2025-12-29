<div
    x-show="$store.ui.showEdit"
    x-transition
    style="display:none"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
    <div
        @click.outside="$store.ui.showEdit = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6"
    >
        <h3 class="text-lg font-semibold mb-4">
            Modifier un site
        </h3>

        <form
            method="POST"
            :action="`/sites/${$store.site.id}`"
            class="space-y-4"
        >
            @csrf
            @method('PUT')

            <input
                name="nom"
                x-model="$store.site.nom"
                class="w-full border rounded p-2"
                required
            >

            <input
                name="url"
                x-model="$store.site.url"
                class="w-full border rounded p-2"
                required
            >

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



            <textarea
                name="description"
                x-model="$store.site.description"
                class="w-full border rounded p-2"
            ></textarea>

            <div class="flex justify-end gap-2 pt-4">
                <button
                    type="button"
                    @click="$store.ui.showEdit = false"
                    class="border px-4 py-2 rounded"
                >
                    Annuler
                </button>

                <button
                    class="bg-green-600 text-white px-4 py-2 rounded"
                >
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
