{{-- MODALE DETAIL SITE (ALPINE) --}}
<div
    x-show="showDetail"
    x-transition
    style="display:none"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
>
    <div
        @click.outside="showDetail = false"
        class="bg-white rounded-lg shadow-lg w-full max-w-md p-6"
    >
        <button
            class="absolute top-2 right-3 text-xl text-gray-500 hover:text-black"
            @click="showDetail = false"
        >
            ✕
        </button>

        <h2
            class="text-lg font-semibold mb-2"
            x-text="siteDetail.nom"
        ></h2>

        <p
            class="text-gray-700 mb-4"
            x-text="siteDetail.description"
        ></p>

        <a
            :href="siteDetail.url"
            target="_blank"
            class="text-blue-600 hover:underline"
        >
            Accéder au site
        </a>
    </div>
</div>
