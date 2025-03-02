@props(['book', 'categories' => [], 'authors' => [], 'statuses' => [], 'redirect_to' => '', 'query_parameters' => [], 'can_edit' => false, 'need_category' => true, 'need_author' => true, 'need_subs_manager' => false])

@if ($book)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">
                <div class="w-full" style="height: 10rem;overflow: hidden;">
                    <img src="{{ url('Image/books/'.$book->image) }}" alt="">
                </div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <div class="flex items-center justify-between mr-4 pb-2">
                    <div class="flex items-center gap-2">
                        Название: <a class="hover:underline" href="{{ route('book.show', $book->id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->title }}</h2></a>
                    </div>
                    <x-book-status :status="$book->status" />
                </div>
                @if($need_category)
                    <div class="flex items-center gap-2 pb-2">
                        Категория: <a class="hover:underline" href="{{ route('category.show', $book->category_id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->category->category_name }}</h2></a>
                    </div>
                @endif
                @if($need_author)
                    <div class="flex items-center gap-2 pb-2">
                        Автор: <a class="hover:underline" href="{{ route('author.show', $book->author_id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->author->author_name }}</h2></a>
                    </div>
                @endif
                <div class="flex items-center gap-2 pb-2">
                    Год публикации: <h2 class="text-lg font-medium text-gray-900">{{ $book->published_year }} г.</h2>
                </div>
                <div class="flex items-center gap-2 pb-2">
                    Цена: <h2 class="text-lg font-medium text-gray-900">{{ $book->price }} ₽</h2>
                </div>
                <div style="display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->description }}</div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-book-{{ $book->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    <x-secondary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-book-status-{{ $book->id }}')"
                    >{{ __('Изменить статус') }}</x-secondary-button>
                    <x-danger-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-book-{{ $book->id }}')"
                    >{{ __('Удалить') }}</x-danger-button>
                </div>
            @endif
            @if(!!$need_subs_manager)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4" style="min-width: 13rem;">
                    @if($book->is_user_sub(Auth::user()))
                        <x-danger-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'sub-or-unsub-book-{{ $book->id }}')"
                        >{{ __('Отписаться') }}</x-danger-button>
                    @else
                        <x-primary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'sub-or-unsub-book-{{ $book->id }}')"
                        >{{ __('Подписаться') }}</x-primary-button>
                    @endif

                    @if(!!($book->is_user_purchase(user: Auth::user()) && $book->can_buy_book()))
                        <a href="{{ route('shop.basket') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Продолжить покупку') }}
                        </a>
                    @elseif(!!$book->can_buy_book())
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-buy-book-{{ $book->id }}')"
                        >{{ __('Купить') }}</x-secondary-button>
                    @endif
                    @if(!!$book->is_user_rent(user: Auth::user()) && $book->can_rent_book())
                        <a href="{{ route('rent.active') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('В аренде') }}
                        </a>
                    @elseif(!!$book->can_rent_book())
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-rent-book-{{ $book->id }}')"
                        >{{ __('Арендовать') }}</x-secondary-button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)

        <x-modal name="update-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать запись книги') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
                </div>

                {{-- category --}}
                <div>
                    <x-input-label for="category_id_{{ $book->id }}" :value="__('Категория')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="category_id" id="category_id_{{ $book->id }}" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if ($category->id == old('category_id', $book->category_id))
                                        selected
                                    @endif
                                >{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- author --}}
                <div>
                    <x-input-label for="author_id" :value="__('Автор')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="author_id" id="author_id" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}"
                                    @if ($author->id == old('author_id', $book->author_id))
                                        selected
                                    @endif
                                >{{ $author->author_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- title --}}
                <div>
                    <x-input-label for="title" :value="__('Наименование')" />
                    <x-text-input id="title" name="title" type="text" minlength="3" maxlength="120" class="mt-1 block w-full" :value="old('title', $book->title)" required autofocus autocomplete="title" />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                {{-- description --}}
                <div>
                    <x-input-label for="description" :value="__('Краткое описание')" />
                    <textarea id="description" name="description" rows="7" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="description">{{ old('description', $book->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-between gap-4">
                    {{-- price --}}
                    <div style="width: 49%;">
                        <x-input-label for="price" :value="__('Цена')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0.00" class="mt-1 block w-full" :value="old('price', $book->price)" required autocomplete="price" />
                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                    </div>

                    {{-- published_year --}}
                    <div style="width: 49%;">
                        <x-input-label for="published_year" :value="__('Год публикации')" />
                        <x-text-input id="published_year" name="published_year" type="number" min="-9999" max="9999" class="mt-1 block w-full" :value="old('published_year', $book->published_year)" required autocomplete="published_year" />
                        <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
                    </div>
                </div>

                <div class="flex justify-between gap-4">
                    {{-- status --}}
                    <div style="width: 49%;">
                        <x-input-label for="status_{{ $book->id }}" :value="__('Статус')" />
                        <select name="status" id="status_{{ $book->id }}" class="block w-full mt-1" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                            @foreach($statuses as $status)
                                <option value="{{ $status['id'] }}"
                                    @if ($status['id'] == old('status', $book->status))
                                        selected
                                    @endif
                                >{{ $status['title'] }}</option>
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

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="update-book-status-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.update-status', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    Изменить статус книги на "{{ $book->next_status()['title'] }}"?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
                </div>

                <!-- status -->
                <div style="display: none;">
                    <x-text-input id="status" type="number" name="status" :value="$book->next_status()['id']" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Изменить статус') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись книги') }}
                </h2>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$book->id" required/>
                </div>

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

    @endif

    @if(!!$need_subs_manager)

        <x-modal name="sub-or-unsub-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('book.update-subs', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ !!($book->is_user_sub(Auth::user())) ? 'Отписаться от книги' : 'Подписаться на книгу' }}?
                </h2>

                <!-- book_id -->
                <div style="display: none;">
                    <x-text-input id="book_id" type="number" name="book_id" :value="$book->id" required/>
                </div>

                <!-- user_id -->
                <div style="display: none;">
                    <x-text-input id="user_id" type="number" name="user_id" :value="Auth::user()->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ !!($book->is_user_sub(Auth::user())) ? 'Отписаться' : 'Подписаться' }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="create-buy-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('shop.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Положить книгу в корзину') }}?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Продолжить покупку можно будет во вкладке магазина') }}
                </p>

                <!-- book_id -->
                <div style="display: none;">
                    <x-text-input id="book_id" type="number" name="book_id" :value="$book->id" required/>
                </div>

                <!-- user_id -->
                <div style="display: none;">
                    <x-text-input id="user_id" type="number" name="user_id" :value="Auth::user()->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Положить в корзину') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="create-rent-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('rent.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Арендовать книгу') }}?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Узнать данные по арендованным книгам можно во вкладке аренды') }}
                </p>

                <!-- book_id -->
                <div style="display: none;">
                    <x-text-input id="book_id" type="number" name="book_id" :value="$book->id" required/>
                </div>

                <!-- user_id -->
                <div style="display: none;">
                    <x-text-input id="user_id" type="number" name="user_id" :value="Auth::user()->id" required/>
                </div>

                {{-- rent_period_days --}}
                <div class="w-full">
                    <x-input-label for="rent_period_days" :value="__('Дней аренды')" />
                    <x-text-input id="rent_period_days" name="rent_period_days" type="number" min="1" max="90" class="mt-1 block w-full" :value="old('rent_period_days')" required autocomplete="rent_period_days" />
                    <x-input-error class="mt-2" :messages="$errors->get('rent_period_days')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Арендовать') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

    @endif

@endif
