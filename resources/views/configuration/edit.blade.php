<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Configuration
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Couleurs des tâches</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Choisissez une couleur pour les tâches et une autre pour les sous-tâches.
                    </p>

                    @if (session('status') === 'colors-updated')
                        <p class="mt-3 text-sm text-green-600">Couleurs enregistrées.</p>
                    @endif

                    <form method="POST" action="{{ route('configuration.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Couleur des tâches</label>
                            <div class="mt-2 flex flex-wrap items-center gap-4">
                                <input
                                    id="task_color"
                                    name="task_color"
                                    type="color"
                                    value="{{ old('task_color', $user->task_color) }}"
                                    class="h-10 w-16 rounded border-gray-300 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2" data-color-palette="task_color">
                                    @foreach(['#3B82F6', '#22C55E', '#F97316', '#EF4444', '#14B8A6', '#8B5CF6', '#0EA5E9'] as $color)
                                        <button
                                            type="button"
                                            class="h-7 w-7 rounded-full border border-gray-200"
                                            style="background-color: {{ $color }}"
                                            data-color-value="{{ $color }}"
                                            aria-label="Couleur {{ $color }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('task_color')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Couleur de fond des tâches</label>
                            <div class="mt-2 flex flex-wrap items-center gap-4">
                                <input
                                    id="task_background_color"
                                    name="task_background_color"
                                    type="color"
                                    value="{{ old('task_background_color', $user->task_background_color) }}"
                                    class="h-10 w-16 rounded border-gray-300 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2" data-color-palette="task_background_color">
                                    @foreach(['#3B82F6', '#22C55E', '#F97316', '#EF4444', '#14B8A6', '#8B5CF6', '#0EA5E9'] as $color)
                                        <button
                                            type="button"
                                            class="h-7 w-7 rounded-full border border-gray-200"
                                            style="background-color: {{ $color }}"
                                            data-color-value="{{ $color }}"
                                            aria-label="Couleur {{ $color }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('task_background_color')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Couleur des sous-tâches</label>
                            <div class="mt-2 flex flex-wrap items-center gap-4">
                                <input
                                    id="sub_task_color"
                                    name="sub_task_color"
                                    type="color"
                                    value="{{ old('sub_task_color', $user->sub_task_color) }}"
                                    class="h-10 w-16 rounded border-gray-300 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2" data-color-palette="sub_task_color">
                                    @foreach(['#A855F7', '#FACC15', '#06B6D4', '#F43F5E', '#10B981', '#6366F1', '#FB7185'] as $color)
                                        <button
                                            type="button"
                                            class="h-7 w-7 rounded-full border border-gray-200"
                                            style="background-color: {{ $color }}"
                                            data-color-value="{{ $color }}"
                                            aria-label="Couleur {{ $color }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            @error('sub_task_color')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Enregistrer</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-color-palette]').forEach((palette) => {
            palette.addEventListener('click', (event) => {
                const target = event.target.closest('[data-color-value]');
                if (!target) {
                    return;
                }

                const inputId = palette.getAttribute('data-color-palette');
                const input = document.getElementById(inputId);
                if (input) {
                    input.value = target.getAttribute('data-color-value');
                }
            });
        });
    </script>
</x-app-layout>
