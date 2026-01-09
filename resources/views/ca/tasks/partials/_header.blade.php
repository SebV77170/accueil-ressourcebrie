<div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
    <h3 class="text-lg font-bold">Liste des tâches</h3>

    <button
        x-data
        type="button"
        @click="$dispatch('open-modal', 'createTaskModal')"
        class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow">
        Nouvelle tâche
    </button>
</div>
