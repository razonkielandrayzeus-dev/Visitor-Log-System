<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200/60 p-6 transition-all hover:shadow-md">
                <div class="text-slate-700">
                    {{ __("Welcome back! You're logged in.") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
