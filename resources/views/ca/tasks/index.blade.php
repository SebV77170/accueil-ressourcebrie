<x-app-layout>
    @php
        $taskColor = Auth::user()?->task_color ?? '#3B82F6';
        $subTaskColor = Auth::user()?->sub_task_color ?? '#A855F7';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestion des tâches du CA
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER + BUTTON --}}
            @include('ca.tasks.partials._header')

            {{-- MOBILE VIEW --}}
            @include('ca.tasks.partials._mobile')

            {{-- DESKTOP VIEW --}}
            @include('ca.tasks.partials._desktop')
        </div>
    </div>

    @include('ca.tasks.partials._modals')

    @include('ca.tasks.partials._scripts')

</x-app-layout>
