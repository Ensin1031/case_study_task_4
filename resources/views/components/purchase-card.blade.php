@props(['book', 'purchase', 'user', 'can_edit' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($book && $purchase && $user)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">
                <div class="w-full" style="height: 10rem;overflow: hidden;">
                    <img src="{{ url('Image/books/'.$book->image) }}" alt="">
                </div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <div class="flex items-center justify-between pb-2 mr-4" style="height: 2.6rem;">
                    <div class="flex items-center gap-2" style="font-size: 1.25rem;">
                        Книга: <a class="hover:underline" href="{{ route('book.show', $book->id) }}" target="_blank"><h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ $book->title }}</h2></a>
                    </div>
                    <x-book-purchase-status :status="$purchase->status" />
                </div>
                <div class="flex items-center pb-2 gap-2">
                    В корзине с: <h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ date_create($purchase->created_at)->format('d.m.Y') }}</h2>
                </div>
                @if(!!($purchase->in_paid_status()) || !!($purchase->in_buy_status()))
                    <div class="flex items-center pb-2 gap-2">
                        Дата оплаты: <h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ date_create($purchase->paid_at)->format('d.m.Y') }}</h2>
                    </div>
                @endif
                @if(!!($purchase->in_buy_status()))
                    <div class="flex items-center pb-2 gap-2">
                        Дата получения: <h2 class="text-lg font-medium text-gray-900" style="display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden;">{{ date_create($purchase->buy_at)->format('d.m.Y') }}</h2>
                    </div>
                @endif
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4" style="min-width: 13rem;">
                    @if(!!($purchase->in_buy_status() && !$book->is_user_purchase(user: $user) && $book->can_buy_book()))
                        <x-primary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-buy-book-{{ $book->id }}')"
                        >{{ __('Повторить покупку') }}</x-primary-button>
                    @endif
                    @if(!!($purchase->in_buy_status()))
                        <x-danger-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'delete-buy-book-{{ $book->id }}')"
                        >{{ __('Удалить') }}</x-danger-button>
                    @elseif(!!($purchase->in_paid_status()))
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'update-buy-status-{{ $book->id }}')"
                        >{{ __('Получить') }}</x-secondary-button>
                    @elseif(!!($purchase->in_basket_status()))
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'update-buy-status-{{ $book->id }}')"
                        >{{ __('Оплатить') }}</x-secondary-button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)

        <x-modal name="create-buy-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('shop.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Купить книгу еще раз') }}?
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
                    <x-text-input id="user_id" type="number" name="user_id" :value="$user->id" required/>
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

        <x-modal name="update-buy-status-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('shop.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    @if(!!($purchase->in_paid_status()))
                        {{ __('Получить книгу') }}
                    @elseif(!!($purchase->in_basket_status()))
                        {{  __('Оплатить книгу') }}
                    @endif
                    ?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$purchase->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        @if(!!($purchase->in_paid_status()))
                            {{ __('Получить') }}
                        @elseif(!!($purchase->in_basket_status()))
                            {{  __('Оплатить') }}
                        @endif
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-buy-book-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('shop.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись покупки книги') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$purchase->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Удалить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

    @endif

@endif
