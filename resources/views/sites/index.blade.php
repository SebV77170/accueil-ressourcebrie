@extends('layouts.app')

@section('title', 'Outils de la Ressourcerie')

@section('content')

    {{-- Menu catégories --}}
    <div class="filters" style="text-align: center; margin-bottom: 2rem;">
        <button class="filter-btn" onclick="filterSites('all')">Tous</button>

        @foreach ($categories as $categorie)
            <button
                class="filter-btn"
                onclick="filterSites('{{ $categorie }}')">
                {{ ucfirst(str_replace('_', ' ', $categorie)) }}
            </button>
        @endforeach
    
    </div>

    {{-- Grille d’icônes --}}
    <div class="grid-wrapper">
        <div class="grid">
            @foreach ($sites as $site)
                <div class="icon"
                    data-categorie="{{ $site->categorie }}"
                    onclick="openModal(
                        '{{ $site->nom }}',
                        '{{ $site->description }}',
                        '{{ $site->url }}'
                    )">

                    <img src="{{ asset('images/sites/' . ($site->icone ?? 'default.png')) }}"
                        alt="{{ $site->nom }}">

                    <div class="icon-name">{{ $site->nom }}</div>
                </div>
            @endforeach
        </div>
    </div>


    {{-- Modal --}}
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">✕</span>
            <h2 id="modal-title"></h2>
            <p id="modal-description"></p>
            <a id="modal-link" href="#" target="_blank">Accéder au site</a>
        </div>
    </div>

@endsection

@section('scripts')
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
@endsection
