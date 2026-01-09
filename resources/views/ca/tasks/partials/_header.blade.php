<div class="mb-4 space-y-3">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-bold">Liste des tâches</h3>
        <button
            x-data
            type="button"
            @click="$dispatch('open-modal', 'createTaskModal')"
            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow"
        >
            Nouvelle tâche
        </button>
    </div>

    @php
        $hasFilters = $status !== 'all' || $responsableId;
        $filters = [
            'pending' => 'Non complétées',
            'completed' => 'Complétées',
            'archived' => 'Archivées',
        ];
    @endphp

    <div class="rounded-lg border bg-gray-50" x-data="{ openFilters: false }">
        <button
            type="button"
            class="flex w-full flex-wrap items-center justify-between gap-3 px-4 py-3 text-sm"
            @click="openFilters = !openFilters"
        >
            <div class="flex flex-wrap items-center gap-2 text-gray-600">
                <span class="font-semibold text-gray-800">Filtres</span>
                <span class="text-xs text-gray-500">Afficher et affiner</span>
                @if($hasFilters)
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">
                        Actifs
                    </span>
                @endif
            </div>
            <span class="text-xs text-gray-500" x-text="openFilters ? 'Masquer ▴' : 'Afficher ▾'"></span>
        </button>

        <div x-show="openFilters" x-transition x-cloak class="border-t px-4 py-3 text-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    @foreach($filters as $key => $label)
                        <a
                            href="{{ route('ca.tasks.index', ['status' => $key, 'per_page' => $perPage, 'responsable' => $responsableId]) }}"
                            class="rounded-full border px-3 py-1 text-xs font-semibold {{ $status === $key ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                    <a
                        href="{{ route('ca.tasks.index', ['status' => 'all', 'per_page' => $perPage, 'responsable' => $responsableId]) }}"
                        class="rounded-full border px-3 py-1 text-xs font-semibold {{ $status === 'all' ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}"
                    >
                        Toutes
                    </a>
                </div>

                <form method="GET" action="{{ route('ca.tasks.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 lg:items-end">
                    <input type="hidden" name="status" value="{{ $status }}">

                    <label class="space-y-1 text-xs font-semibold text-gray-600">
                        Responsable
                        <select
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
                    </label>

                    <label class="space-y-1 text-xs font-semibold text-gray-600">
                        Tâches par page
                        <select
                            name="per_page"
                            class="w-full rounded border px-2 py-1 text-sm"
                        >
                            @foreach([5, 10, 20, 50] as $size)
                                <option value="{{ $size }}" @selected($perPage === $size)>{{ $size }}</option>
                            @endforeach
                        </select>
                    </label>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full rounded bg-indigo-600 px-3 py-2 text-sm font-semibold text-white"
                        >
                            Appliquer
                        </button>
                    </div>
                </form>
            </div>

            @if($hasFilters)
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                    <span class="font-semibold text-gray-600">Filtres actifs :</span>
                    <span class="rounded-full bg-white px-2 py-0.5">
                        Statut : {{ $status === 'all' ? 'Tous' : ($filters[$status] ?? 'Toutes') }}
                    </span>
                    @if($responsableId)
                        <span class="rounded-full bg-white px-2 py-0.5">
                            Responsable : {{ $users->firstWhere('id', $responsableId)?->name ?? 'N/A' }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
