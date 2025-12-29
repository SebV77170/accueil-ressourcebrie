{{-- MODALE SUPPRESSION SITE --}}
<div
    x-show="$store.ui.showDelete"
    x-transition
    style="display:none"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    @keydown.escape.window="$store.ui.showDelete = false"
>
    <div
        @click.outside="$store.ui.showDelete = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6"
    >
        <h3 class="text-lg font-semibold mb-4 text-gray-800">
            Supprimer le site
        </h3>

        <p class="mb-6 text-gray-700">
            Voulez-vous vraiment supprimer
            <strong x-text="$store.site.nom"></strong> ?
        </p>

        <div class="flex justify-end gap-3">
            <button
                type="button"
                @click="$store.ui.showDelete = false"
                class="px-4 py-2 border rounded hover:bg-gray-100"
            >
                Annuler
            </button>

            <form
                method="POST"
                :action="`/sites/${$store.site.id}`"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
