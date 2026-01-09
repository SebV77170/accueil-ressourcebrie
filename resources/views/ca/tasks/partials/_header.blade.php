<div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
    <div class="space-y-2">
        <h3 class="text-lg font-bold">Liste des tâches</h3>
        <div class="flex flex-wrap gap-2 text-sm">
            @php
                $filters = [
                    'pending' => 'Non complétées',
                    'completed' => 'Complétées',
                    'archived' => 'Archivées',
                ];
            @endphp
            @foreach($filters as $key => $label)
                <a
                    href="{{ route('ca.tasks.index', ['status' => $key, 'per_page' => $perPage]) }}"
                    class="rounded-full border px-3 py-1 {{ $status === $key ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
        <form method="GET" action="{{ route('ca.tasks.index') }}" class="flex items-center gap-2 text-sm">
            <input type="hidden" name="status" value="{{ $status }}">
            <label for="per_page" class="text-gray-600">Tâches par page</label>
            <select
                id="per_page"
                name="per_page"
                class="rounded border px-2 py-1 text-sm"
                onchange="this.form.submit()"
            >
                @foreach([5, 10, 20, 50] as $size)
                    <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </form>

        <button
            x-data
            type="button"
            @click="$dispatch('open-modal', 'createTaskModal')"
            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow">
            Nouvelle tâche
        </button>
    </div>
</div>
