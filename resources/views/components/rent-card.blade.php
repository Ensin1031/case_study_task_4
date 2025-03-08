@props(['book', 'rent', 'user', 'can_edit' => false, 'redirect_to' => '', 'query_parameters' => []])

@if ($book && $rent && $user)

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
                    <x-book-rent-status :status="$rent->status()" />
                </div>
                <div class="flex items-center pb-2 gap-2">
                    Дата аренды: <h2 class="text-lg font-medium text-gray-900">{{ date_create($rent->created_at)->format('d.m.Y') }}</h2>
                </div>
                <div class="flex items-center pb-2 gap-2">
                    Арендовано до: <h2 class="text-lg font-medium text-gray-900">{{ date_create($rent->end_at)->format('d.m.Y') }}</h2>
                </div>
                <div class="flex items-center pb-2 gap-2">
                    Дней до конца аренды: <h2 class="text-lg font-medium text-gray-900">{{ $rent->days_to_end() }}</h2>
                </div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4" style="min-width: 13rem;">
                    @if(!!($rent->in_archive_status() && !$book->is_user_rent(user: $user) && $book->can_rent_book()))
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-rent-{{ $book->id }}')"
                        >{{ __('Повторить аренду') }}</x-secondary-button>
                    @endif
                    @if(!!($rent->in_archive_status()))
                        <x-danger-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'delete-rent-{{ $book->id }}')"
                        >{{ __('Удалить') }}</x-danger-button>
                    @elseif(!!($rent->in_overdue_status()))
                        <x-primary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'extend-rent-{{ $book->id }}')"
                        >{{ __('Продлить') }}</x-primary-button>
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'close-rent-{{ $book->id }}')"
                        >{{ __('Вернуть') }}</x-secondary-button>
                    @elseif(!!($rent->in_active_status()))
                        <x-secondary-button
                            class="w-full"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'close-rent-{{ $book->id }}')"
                        >{{ __('Вернуть') }}</x-secondary-button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)

        <x-modal name="create-rent-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('rent.create', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('post')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Арендовать книгу повторно') }}?
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

        <x-modal name="extend-rent-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('rent.extend-update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Продлить аренду книги') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$rent->id" required/>
                </div>

                {{-- rent_period_days --}}
                <div class="w-full">
                    <x-input-label for="rent_period_days" :value="__('Дней аренды')" />
                    <x-text-input id="rent_period_days" name="rent_period_days" type="number" min="1" max="90" class="mt-1 block w-full" :value="old('rent_period_days', $rent->rent_period_days)" required autocomplete="rent_period_days" />
                    <x-input-error class="mt-2" :messages="$errors->get('rent_period_days')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Продлить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="close-rent-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('rent.close-update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Вернуть арендованную книгу') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$rent->id" required/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Вернуть') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-rent-{{ $book->id }}" focusable>
            <form method="post" action="{{ route('rent.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись аренды книги') }}?
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$rent->id" required/>
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
