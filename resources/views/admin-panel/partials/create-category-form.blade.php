<section style="width: 100%;">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Создать запись категории книг') }}
        </h2>
    </header>

    <form method="post" action="{{ route('category.create', ['redirect_to' => 'admin-panel.categories']) }}" class="mt-6 space-y-6">
        @csrf
        @method('post')

        <!-- category_name -->
        <div class="mt-4">
            <x-input-label for="category_name" :value="__('Наименование категории')" />
            <x-text-input id="category_name" class="block mt-1 w-full" minlength="3" maxlength="30" type="text" name="category_name" required autofocus autocomplete="category_name" />
            <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
        </div>

        {{-- about_category --}}
        <div>
            <x-input-label for="about_category" :value="__('Краткое описание')" />
            <textarea id="about_category" name="about_category" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="about_category"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('about_category')" />
        </div>

        <div class="flex items-center justify-end gap-4">
            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
        </div>
    </form>
</section>

<x-modal name="delete-category" focusable>
    <form method="post" action="{{ route('category.destroy', ['redirect_to' => 'admin-panel.categories']) }}" class="p-6">
        @csrf
        @method('post')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Удалить категорию книги') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Удалить можно только категорию, к которой не привязана ни одна книга
        </p>
        <x-input-error :messages="$errors->get('id')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Отмена') }}
            </x-secondary-button>
            <x-danger-button class="ms-3">
                {{ __('Удалить') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
