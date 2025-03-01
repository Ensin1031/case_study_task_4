<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Аренда') }}
            </h2>
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('rent.active')" :active="request()->routeIs('rent.active')">
                    {{ __('Текущие записи') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('rent.archive')" :active="request()->routeIs('rent.archive')">
                    {{ __('Архивные записи') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    @yield('rent-content')

</x-app-layout>
