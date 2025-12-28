<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Explications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                   <h3 class="text-lg font-semibold mb-4">
    {{ __("Bienvenue sur l’application") }}
</h3>

<ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
    <li>
        {{ __("Un accès centralisé aux outils et sites utilisés fréquemment.") }}
    </li>
    <li>
        {{ __("Un espace dédié au suivi des tâches à réaliser.") }}
    </li>
    <li>
        {{ __("De nouvelles fonctionnalités à venir, dont un gestionnaire de fichiers pour consulter et organiser les documents.") }}
    </li>
</ul>


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
