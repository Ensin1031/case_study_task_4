@props(['user', 'redirect_to' => '', 'query_parameters' => [], 'can_edit' => false])

@if ($user)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 13rem;min-height: 6rem;overflow: hidden;">
                <div class="flex items-center justify-center text-center text-white text-sm w-full mb-2">
                    @switch(true)
                        @case(!$user->is_active_user())
                            <span class="w-full" style="padding: .325rem .7rem;border-radius: 7px;background-color: darkred;">Заблокированный пользователь</span>
                            @break
                        @case(($user->is_active_user() && !$user->is_superuser()))
                            <span class="w-full" style="padding: .325rem .7rem;border-radius: 7px;background-color: green;">Активный пользователь</span>
                            @break
                        @case($user->is_superuser())
                            <span class="w-full" style="padding: .325rem .7rem;border-radius: 7px;background-color: darkblue;">Администратор</span>
                            @break
                    @endswitch
                </div>
                <div class="text-center">
                    <div class="text-sm">Книг в подписках: </div>
                    <div class="text-lg">{{ count($user->sub_books) }}</div>
                </div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <div class="flex flex-row items-center justify-between pb-2" style="height: 2.7rem;">
                    <h2 class="text-lg font-medium text-gray-900">
                        <span>Имя: </span><a class="hover:underline" href="{{ route('user.show', $user->id) }}" target="_blank"><span>{{ $user->name }}</span></a>
                    </h2>
                </div>
                <div><span>Email: </span><span>{{ $user->email }}</span></div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-user-{{ $user->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    @if(!(Auth::user()->id === $user->id))
                        @if($user->is_active_user() && !(Auth::user()->id === $user->id))
                            <x-danger-button
                                class="w-full"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                            >{{ __('Заблокировать') }}</x-danger-button>
                        @else
                            <x-secondary-button
                                class="w-full"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                            >{{ __('Разблокировать') }}</x-secondary-button>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)

        <x-modal name="update-user-{{ $user->id }}" focusable>
            <form method="post" action="{{ route('user.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать данные пользователя') }}
                </h2>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$user->id" required/>
                </div>

                <!-- name -->
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Наименование категории')" />
                    <x-text-input id="name" class="block mt-1 w-full" :value="old('name', $user->name)" minlength="3" maxlength="30" type="text" name="name" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input :disabled="true" id="email" class="block mt-1 w-full" :value="$user->email" type="text" name="name" required/>
                </div>

                <!-- is_public -->
                <div class="mt-4">
                    <label for="is_admin" class="inline-flex items-center">
                        @if ($user->is_superuser())
                            <input id="is_admin" type="checkbox"  checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_admin">
                        @else
                            <input id="is_admin" type="checkbox" {{ old('is_admin') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_admin">
                        @endif
                        <span class="ms-2 text-sm text-gray-600">{{ __('Администратор') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-4">
                        {{ __('Сохранить') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal name="delete-user-{{ $user->id }}" focusable>
            <form method="post" action="{{ route('user.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ !!$user->is_active_user() ? __('Заблокировать пользователя') : __('Разблокировать пользователя') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ !!$user->is_active_user() ? __('Заблокированный пользователь не сможет зайти в систему') : __('Разблокированный пользователь получит доступ в систему') }}
                </p>

                <!-- id -->
                <div style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$user->id" required/>
                </div>

                <!-- is_active -->
                <div style="display: none;">
                    <label for="is_active" class="inline-flex items-center">
                        @if ($user->is_active_user())
                            <input id="is_active" type="checkbox" name="is_active">
                        @else
                            <input id="is_active" type="checkbox" checked name="is_active">
                        @endif
                        <span class="ms-2 text-sm text-gray-600">{{ __('Администратор') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Отмена') }}
                    </x-secondary-button>
                    <x-danger-button class="ms-3">
                        {{ !!$user->is_active_user() ? __('Заблокировать') : __('Разблокировать') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

    @endif

@endif
