<section class="w-full">
    <details class="w-full" {{!!(session('cu_category_id') || session('cu_author_id')) ? 'open="open"' : ''}}>
        <summary class="flex flex-row gap-2 items-center" style="cursor: pointer;">
            <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">+</span>
            <span class="text-lg font-medium text-gray-900">
                {{ __('Создать запись книги') }}
            </span>
        </summary>
        <form method="post" action="{{ route('book.create', ['redirect_to' => 'admin-panel.books']) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('post')

            {{-- category --}}
            <div>
                <x-input-label for="category_id" :value="__('Категория')" />
                <div class="flex justify-between gap-4 mt-1">
                    <select name="category_id" id="category_id" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                        <option value="">------------</option>
                        @foreach($categories as $category)
                            @if ($category->id == session('cu_category_id'))
                                <option value="{{ $category->id }}" selected>{{ $category->category_name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <x-secondary-button
                            style="padding: 0 !important;margin: 0 !important;min-width: 42px;;font-size: 2rem;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-category')"
                    >+</x-secondary-button>
                </div>
            </div>

            {{-- author --}}
            <div>
                <x-input-label for="author_id" :value="__('Автор')" />
                <div class="flex justify-between gap-4 mt-1">
                    <select name="author_id" id="author_id" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                        <option value="">------------</option>
                        @foreach($authors as $author)
                            @if ($author->id == session('cu_author_id'))
                                <option value="{{ $author->id }}" selected>{{ $author->author_name }}</option>
                            @else
                                <option value="{{ $author->id }}">{{ $author->author_name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <x-secondary-button
                            style="padding: 0 !important;margin: 0 !important;min-width: 42px;;font-size: 2rem;"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-author')"
                    >+</x-secondary-button>
                </div>
            </div>

            {{-- title --}}
            <div>
                <x-input-label for="title" :value="__('Наименование')" />
                <x-text-input id="title" name="title" type="text" minlength="3" maxlength="120" class="mt-1 block w-full" :value="old('title')" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            {{-- description --}}
            <div>
                <x-input-label for="description" :value="__('Краткое описание')" />
                <textarea id="description" name="description" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="description"></textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <div class="flex justify-between gap-4">
                {{-- price --}}
                <div style="width: 49%;">
                    <x-input-label for="price" :value="__('Цена')" />
                    <x-text-input id="price" name="price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('price')" required autocomplete="price" />
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                {{-- published_year --}}
                <div style="width: 49%;">
                    <x-input-label for="published_year" :value="__('Год публикации')" />
                    <x-text-input id="published_year" name="published_year" type="number" min="-9999" max="9999" class="mt-1 block w-full" :value="old('published_year')" required autocomplete="published_year" />
                    <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
                </div>
            </div>

            <div class="flex justify-between gap-4">
                {{-- status --}}
                <div style="width: 49%;">
                    <x-input-label for="status" :value="__('Статус')" />
                    <select name="status" id="status" class="block w-full mt-1" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                        @foreach($statuses as $status)
                            <option value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>

                {{-- image --}}
                <div style="width: 49%;">
                    <x-input-label for="image" :value="__('Изображение книги')" />
                    <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                        <x-text-input id="image" name="image" type="file" class="block w-full" :value="old('image')" required/>
                    </div>
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
            </div>
        </form>
    </details>
</section>

<x-modal name="create-category" focusable>
    <form method="post" action="{{ route('category.create', ['redirect_to' => 'admin-panel.books']) }}" class="p-6">
        @csrf
        @method('post')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Создать запись категории книги') }}
        </h2>

        <!-- title -->
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

        <!-- cu_author_id -->
        <div style="display: none;">
            <x-text-input id="cu_author_id" :value="session('cu_author_id')" type="text" name="cu_author_id"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Отмена') }}
            </x-secondary-button>
            <x-primary-button class="ms-4">
                {{ __('Создать') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>

<x-modal name="create-author" focusable>
    <form method="post" action="{{ route('author.create', ['redirect_to' => 'admin-panel.books']) }}" class="p-6" enctype="multipart/form-data">
        @csrf
        @method('post')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Создать запись автора книги') }}
        </h2>

        <!-- title -->
        <div class="mt-4">
            <x-input-label for="author_name" :value="__('Автор')" />
            <x-text-input id="author_name" class="block mt-1 w-full" minlength="3" maxlength="70" type="text" name="author_name" required autofocus autocomplete="author_name" />
            <x-input-error :messages="$errors->get('author_name')" class="mt-2" />
        </div>

        {{-- author_photo --}}
        <div>
            <x-input-label for="author_photo" :value="__('Фото автора')" />
            <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                <x-text-input id="author_photo" name="author_photo" type="file" class="block w-full" :value="old('author_photo')" required/>
            </div>
            <x-input-error :messages="$errors->get('author_photo')" class="mt-2" />
        </div>

        {{-- about_author --}}
        <div>
            <x-input-label for="about_author" :value="__('Краткое описание')" />
            <textarea id="about_author" name="about_author" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="about_author"></textarea>
            <x-input-error class="mt-2" :messages="$errors->get('about_author')" />
        </div>

        <!-- cu_category_id -->
        <div style="display: none;">
            <x-text-input id="cu_category_id" :value="session('cu_category_id')" type="text" name="cu_category_id"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Отмена') }}
            </x-secondary-button>
            <x-primary-button class="ms-4">
                {{ __('Создать') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
