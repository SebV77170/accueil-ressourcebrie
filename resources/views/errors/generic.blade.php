@php
    $title = $title ?? 'Erreur';
    $message = $message ?? "Une erreur est survenue.";
@endphp

@auth
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $title }}
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="rounded-lg border border-red-200 bg-red-50 p-6 text-red-700">
                    <p class="font-semibold">{{ $message }}</p>
                </div>
            </div>
        </div>
    </x-app-layout>
@else
    <x-guest-layout>
        <div class="rounded-lg border border-red-200 bg-red-50 p-6 text-red-700">
            <h1 class="text-lg font-semibold">{{ $title }}</h1>
            <p class="mt-2">{{ $message }}</p>
        </div>
    </x-guest-layout>
@endauth
