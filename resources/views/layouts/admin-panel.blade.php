<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Панель администратора') }}
            </h2>
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('admin-panel.users')" :active="request()->routeIs('admin-panel.users')">
                    {{ __('Пользователи') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('admin-panel.books')" :active="request()->routeIs('admin-panel.books')">
                    {{ __('Книги') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('admin-panel.categories')" :active="request()->routeIs('admin-panel.categories')">
                    {{ __('Категории') }}
                </x-nav-link>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <x-nav-link :href="route('admin-panel.authors')" :active="request()->routeIs('admin-panel.authors')">
                    {{ __('Авторы') }}
                </x-nav-link>
            </div>
        </div>
    </x-slot>

    @yield('admin-panel-content')

</x-app-layout>
