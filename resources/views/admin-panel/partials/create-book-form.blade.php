<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Создать запись книги') }}
        </h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- title --}}
        <div>
            <x-input-label for="title" :value="__('Наименование')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        {{-- description --}}
        <div>
            <x-input-label for="description" :value="__('Краткое описание')" />
            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" required autocomplete="description" />
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        {{-- status --}}

        {{-- price --}}
        <div>
            <x-input-label for="price" :value="__('Цена')" />
            <x-text-input id="price" name="price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('price')" required autocomplete="price" />
            <x-input-error class="mt-2" :messages="$errors->get('price')" />
        </div>

        {{-- category --}}

        {{-- author --}}

        {{-- published_year --}}
        <div>
            <x-input-label for="published_year" :value="__('Год публикации')" />
            <x-text-input id="published_year" name="published_year" type="number" min="-9999" max="9999" class="mt-1 block w-full" :value="old('published_year')" required autocomplete="published_year" />
            <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
        </div>
    </form>
</section>
