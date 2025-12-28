<x-app-layout>

<div x-data="{ open: false }">

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Outils de la Ressourcerie
            </h2>

        </div>
    </x-slot>

      <button
                @click="open = true"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition"
            >
                ➕ Ajouter un site
            </button>

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
                    <input name="categorie" required class="w-full rounded-md border-gray-300" />
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

    {{-- MENU CATEGORIES --}}
    <div class="filters text-center mb-8 mt-8">
        <button class="filter-btn" onclick="filterSites('all')">Tous</button>

        @foreach ($categories as $categorie)
            <button
                class="filter-btn"
                onclick="filterSites('{{ $categorie }}')">
                {{ ucfirst(str_replace('_', ' ', $categorie)) }}
            </button>
        @endforeach
    </div>

    {{-- GRILLE D’ICÔNES --}}
    <div class="grid-wrapper">
        <div class="grid">
            @foreach ($sites as $site)
                @php
                    $host = parse_url($site->url, PHP_URL_HOST);
                    $domain = preg_replace('/^(www|app)\./', '', $host);
                @endphp
    
                <div
                    class="icon"
                    data-categorie="{{ $site->categorie }}"
                    onclick="openModal(
                        '{{ $site->nom }}',
                        '{{ $site->description }}',
                        '{{ $site->url }}'
                    )"
                >
                    <img
                        src="https://www.google.com/s2/favicons?domain={{ $domain }}&sz=64"
                        alt="{{ $site->nom }}"
                        width="32"
                        height="32"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='{{ asset('images/sites/default.png') }}';"
                    >

                    <div class="icon-name">{{ $site->nom }}</div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- MODALE DETAIL SITE --}}
<div class="modal" id="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">✕</span>
        <h2 id="modal-title"></h2>
        <p id="modal-description"></p>
        <a id="modal-link" href="#" target="_blank">Accéder au site</a>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    function filterSites(categorie) {
        document.querySelectorAll('.icon').forEach(icon => {
            icon.style.display =
                (categorie === 'all' || icon.dataset.categorie === categorie)
                ? 'block'
                : 'none';
        });
    }

    function openModal(title, description, url) {
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-description').innerText = description;
        document.getElementById('modal-link').href = url;
        document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
</script>

</x-app-layout>
