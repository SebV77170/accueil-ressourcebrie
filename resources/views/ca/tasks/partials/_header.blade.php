<div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
    <div class="space-y-2">
        <h3 class="text-lg font-bold">Liste des tâches</h3>
        <div class="flex flex-wrap items-center gap-2 text-sm">
            @php
                $hasFilters = $status !== 'all' || $responsableId;
            @endphp
            <span class="inline-flex items-center rounded-full border px-3 py-1 {{ $hasFilters ? 'border-amber-500 bg-amber-500 text-white' : 'border-indigo-600 bg-indigo-600 text-white' }}">
                {{ $hasFilters ? 'Tâches filtrées' : 'Toutes les tâches' }}
            </span>

            <div class="relative" x-data="{ openFilters: false }">
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full border bg-white px-3 py-1 text-gray-600"
                    @click="openFilters = !openFilters"
                >
                    Filtres
                    <span class="text-xs">▾</span>
                </button>

                <div
                    x-show="openFilters"
                    x-transition
                    @click.outside="openFilters = false"
                    style="display:none"
                    class="absolute left-0 mt-2 w-56 rounded border bg-white p-2 text-sm shadow z-20"
                >
                    <div class="space-y-1">
                        @php
                            $filters = [
                                'pending' => 'Non complétées',
                                'completed' => 'Complétées',
                                'archived' => 'Archivées',
                            ];
                        @endphp
                        @foreach($filters as $key => $label)
                            <a
                                href="{{ route('ca.tasks.index', ['status' => $key, 'per_page' => $perPage, 'responsable' => $responsableId]) }}"
                                class="flex items-center justify-between rounded px-3 py-1 hover:bg-gray-100 {{ $status === $key ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600' }}"
                            >
                                {{ $label }}
                                @if($status === $key)
                                    <span class="text-xs">✓</span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <div class="my-2 border-t"></div>

                    <form method="GET" action="{{ route('ca.tasks.index') }}" class="space-y-2">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="per_page" value="{{ $perPage }}">

                        <label for="responsable" class="block text-xs font-semibold text-gray-600">Responsable</label>
                        <select
                            id="responsable"
                            name="responsable"
                            class="w-full rounded border px-2 py-1 text-sm"
                        >
                            <option value="">Tous les responsables</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected($responsableId === $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <button
                            type="submit"
                            class="w-full rounded bg-indigo-600 px-3 py-1 text-white"
                        >
                            Appliquer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
        <form method="GET" action="{{ route('ca.tasks.index') }}" class="flex items-center gap-2 text-sm">
            <input type="hidden" name="status" value="{{ $status }}">
            @if($responsableId)
                <input type="hidden" name="responsable" value="{{ $responsableId }}">
            @endif
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
