<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Магазин') }}
            </h2>
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('shop.basket')" :active="request()->routeIs('shop.basket')">
                    {{ __('Корзина') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('shop.purchases')" :active="request()->routeIs('shop.purchases')">
                    {{ __('История покупок') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    @yield('shop-content')

</x-app-layout>
