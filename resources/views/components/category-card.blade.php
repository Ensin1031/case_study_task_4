@props(['category', 'redirect_to' => '', 'query_parameters' => [], 'can_edit' => false])

@if ($category)
    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 12rem;min-height: 6rem;overflow: hidden;">
                <div class="text-sm">Книг в категории: </div>
                <div class="text-lg">{{ count($category->books) }}</div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <h2 class="pb-6 text-lg font-medium text-gray-900">{{ $category->category_name }}</h2>
                <div>{{ $category->about_category }}</div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-category-{{ $category->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    <x-danger-button
                        class="w-full"
                        :disabled="(count($category->books) > 0)"
                        style="{{ !!(count($category->books) > 0) ? 'opacity: .5;background-color: rgb(239 68 68);' : '' }}"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-category-{{ $category->id }}')"
                    >{{ __('Удалить') }}</x-danger-button>
                </div>
            @endif
        </div>
    </div>
    @if(!!$can_edit)
        <x-modal name="update-category-{{ $category->id }}" focusable>
            <form method="post" action="{{ route('category.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать запись категории книги') }}
                </h2>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$category->id" required/>
                </div>

                <!-- category_name -->
                <div class="mt-4">
                    <x-input-label for="category_name" :value="__('Наименование категории')" />
                    <x-text-input id="category_name" class="block mt-1 w-full" :value="old('category_name', $category->category_name)" minlength="3" maxlength="30" type="text" name="category_name" required autofocus autocomplete="category_name" />
                    <x-input-error :messages="$errors->get('category_name')" class="mt-2" />
                </div>

                <!-- about_category -->
                <div>
                    <x-input-label for="about_category" :value="__('Краткое описание')" />
                    <textarea id="about_category" name="about_category" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="about_category">{{$category->about_category}}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('about_category')" />
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

        <x-modal name="delete-category-{{ $category->id }}" focusable>
            <form method="post" action="{{ route('category.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись категории книги') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Удалить можно только запись категории книги, к которой не привязана ни одна книга
                </p>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$category->id" required/>
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
@endif
