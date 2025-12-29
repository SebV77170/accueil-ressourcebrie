<x-app-layout>

<div
    x-data="{
        open: false,
        showDetail: false,
        openMenuId: null,

        categories: @js($categories),

        siteDetail: {
            nom: '',
            description: '',
            url: ''
        },

        // 🔹 AJOUT POUR LES CATEGORIES
        newCategoryName: '',

        async addCategory() {
            if (!this.newCategoryName.trim()) {
                return;
            }

            const response = await fetch('/categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name=csrf-token]')
                        .content
                },
                body: JSON.stringify({
                    nom: this.newCategoryName
                })
            });

            if (!response.ok) {
                alert('Erreur lors de la création de la catégorie');
                return;
            }

            const category = await response.json();

            // Ajout immédiat dans la liste
            this.categories.push(category);

            // Sélection automatique si on est dans une modale site
            if (this.$store.site) {
                this.$store.site.category_id = category.id;
            }

            this.newCategoryName = '';
            this.$store.ui.showAddCategory = false;
        }
    }"
>


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


    {{-- MENU CATEGORIES --}}
    <div class="filters text-center mb-8 mt-8">
        <button class="filter-btn" onclick="filterSites('all')">Tous</button>

       @foreach ($categories as $categorie)
            <button
                class="filter-btn"
                onclick="filterSites({{ $categorie->id }})">
                {{ $categorie->nom }}
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
                    class="icon relative"
                    data-category-id="{{ $site->categoryId }}"
                    x-data="{ menu: false }"
                >

                    {{-- Bouton … --}}
                   <button
                        class="absolute top-1 right-1 text-gray-500 hover:text-black z-10"
                        @click.stop="
                            openMenuId === {{ $site->id }}
                                ? openMenuId = null
                                : openMenuId = {{ $site->id }}
                        "
                    >
                        ⋯
                    </button>


                    {{-- Menu contextuel --}}
                    <div
                        x-show="openMenuId === {{ $site->id }}"
                        x-transition
                        @click.outside="openMenuId = null"
                        style="display:none"
                        class="absolute right-1 top-7 bg-white border rounded shadow text-sm z-20"
                    >

                        <button
                            type="button"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                            @click="
                                openMenuId = null;
                                $store.site = {
                                    id: {{ $site->id }},
                                    nom: @js($site->nom),
                                    url: @js($site->url),
                            
                                    description: @js($site->description),
                                    category_id: {{ $site->categoryId }}

                                };
                                $store.ui.showEdit = true;
                            "
                        >
                            Modifier
                        </button>

                        <button
                            type="button"
                            class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600"
                            @click="
                                openMenuId = null;
                                $store.site = {
                                    id: {{ $site->id }},
                                    nom: @js($site->nom)
                                };
                                $store.ui.showDelete = true;
                            "
                        >
                            Supprimer
                        </button>
                    </div>

                    {{-- Icône (clic = détail) --}}
                    <div
                        @click="
                            siteDetail = {
                                nom: @js($site->nom),
                                description: @js($site->description),
                                url: @js($site->url)
                            };
                            showDetail = true;
                        "
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

                </div>

            @endforeach
        </div>
    </div>

    {{-- Modales --}}
@include('sites.modals.add')
@include('sites.modals.edit')
@include('sites.modals.delete')
@include('sites.modals.details')
@include('sites.modals.add-category')

</div>



{{-- SCRIPTS --}}
<script>
function filterSites(categoryId) {
    document.querySelectorAll('.icon').forEach(icon => {
        const iconCategoryId = icon.dataset.categoryId;

        icon.style.display =
            (categoryId === 'all' || iconCategoryId == categoryId)
                ? 'block'
                : 'none';
    });
}
</script>


</x-app-layout>
