<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex w-full">
                        <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">
                            <div class="w-full" style="height: 10rem;overflow: hidden;">
                                <img src="{{ url('Image/books/'.$book->image) }}" alt="">
                            </div>
                        </div>
                        <div class="flex flex-col pl-4 w-full">
                            <div class="flex items-center justify-between mr-4 pb-2">
                                <div class="flex items-center gap-2">
                                    Название: <h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->title }}</h2>
                                </div>
                                <x-book-status :status="$book->status" />
                            </div>
                            <div class="flex items-center gap-2 pb-2">
                                Категория: <a class="hover:underline" href="{{ route('category.show', $book->category_id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->category->category_name }}</h2></a>
                            </div>
                            <div class="flex items-center gap-2 pb-2">
                                Автор: <a class="hover:underline" href="{{ route('author.show', $book->author_id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->author->author_name }}</h2></a>
                            </div>
                            <div class="flex items-center gap-2 pb-2">
                                Год публикации: <h2 class="text-lg font-medium text-gray-900">{{ $book->published_year }} г.</h2>
                            </div>
                            <div class="flex items-center gap-2 pb-2">
                                Цена: <h2 class="text-lg font-medium text-gray-900">{{ $book->price }} ₽</h2>
                            </div>
                            <div>{{ $book->description }}</div>
                        </div>
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
                            @if(!!$book->is_user_purchase(user: Auth::user()))
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
                            @if(!!$book->is_user_rent(user: Auth::user()))
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-modal name="sub-or-unsub-book-{{ $book->id }}" focusable>
    <form method="post" action="{{ route('book.update-subs', ['redirect_to' => 'book.show', 'query_parameters' => $book->id]) }}" class="p-6">
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
    <form method="post" action="{{ route('shop.create', ['redirect_to' => 'book.show', 'query_parameters' => $book->id]) }}" class="p-6">
        @csrf
        @method('post')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Положить книгу в корзину') }}?
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Продолжить покупку можно будет в магазине ресурса') }}
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
    <form method="post" action="{{ route('rent.create', ['redirect_to' => 'book.show', 'query_parameters' => $book->id]) }}" class="p-6">
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
        <div style="width: 49%;">
            <x-input-label for="rent_period_days" :value="__('Год публикации')" />
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
