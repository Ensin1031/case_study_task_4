@props(['author', 'redirect_to' => '', 'query_parameters' => [], 'can_edit' => false])

@if ($author)
    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">
                <div class="w-full" style="height: 10rem;overflow: hidden;">
                    <img src="{{ url('Image/authors/'.$author->author_photo) }}" alt="">
                </div>
                <div class="flex flex-col items-center">
                    <div class="text-sm">Книг автора: </div>
                    <div class="text-lg">{{ count($author->books) }}</div>
                </div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <h2 class="pb-6 text-lg font-medium text-gray-900">{{ $author->author_name }}</h2>
                <div>{{ $author->about_author }}</div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-author-{{ $author->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    <x-danger-button
                        class="w-full"
                        :disabled="(count($author->books) > 0)"
                        style="{{ !!(count($author->books) > 0) ? 'opacity: .5;background-color: rgb(239 68 68);' : '' }}"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-author-{{ $author->id }}')"
                    >{{ __('Удалить') }}</x-danger-button>
                </div>
            @endif
        </div>
    </div>
    @if(!!$can_edit)
        <x-modal name="update-author-{{ $author->id }}" focusable>
            <form method="post" action="{{ route('author.update', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Редактировать запись автора книги') }}
                </h2>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$author->id" required/>
                </div>

                <!-- author_name -->
                <div class="mt-4">
                    <x-input-label for="author_name" :value="__('Автор')" />
                    <x-text-input id="author_name" class="block mt-1 w-full" :value="old('author_name', $author->author_name)" minlength="3" maxlength="70" type="text" name="author_name" required autofocus autocomplete="author_name" />
                    <x-input-error :messages="$errors->get('author_name')" class="mt-2" />
                </div>

                {{-- author_photo --}}
                <div>
                    <x-input-label for="author_photo" :value="__('Фото автора')" />
                    <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                        <x-text-input id="author_photo" name="author_photo" type="file" class="block w-full" required />
                    </div>
                    <x-input-error :messages="$errors->get('author_photo')" class="mt-2" />
                </div>

                {{-- about_author --}}
                <div>
                    <x-input-label for="about_author" :value="__('Краткое описание')" />
                    <textarea id="about_author" name="about_author" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="about_author">{{$author->about_author}}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('about_author')" />
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

        <x-modal name="delete-author-{{ $author->id }}" focusable>
            <form method="post" action="{{ route('author.destroy', ['redirect_to' => $redirect_to, 'query_parameters' => $query_parameters]) }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Удалить запись автора книги') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Удалить можно только запись автора книги, к которой не привязана ни одна книга
                </p>

                <!-- id -->
                <div class="mt-4" style="display: none;">
                    <x-text-input id="id" type="number" name="id" :value="$author->id" required/>
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
