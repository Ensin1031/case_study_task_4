<section class="w-full">
    <details class="w-full">
        <summary class="flex flex-row gap-2 items-center" style="cursor: pointer;">
            <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">+</span>
            <span class="text-lg font-medium text-gray-900">
                {{ __('Создать запись автора книги') }}
            </span>
        </summary>
        <form method="post" action="{{ route('author.create', ['redirect_to' => 'admin-panel.authors']) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('post')

            <!-- author_name -->
            <div class="mt-4">
                <x-input-label for="author_name" :value="__('Автор')" />
                <x-text-input id="author_name" class="block mt-1 w-full" minlength="3" maxlength="70" type="text" name="author_name" required autofocus autocomplete="author_name" />
                <x-input-error :messages="$errors->get('author_name')" class="mt-2" />
            </div>

            <!-- author_photo -->
            <div>
                <x-input-label for="author_photo" :value="__('Фото автора')" />
                <div class="flex w-full mt-1 items-center" style="height: 2.6rem;">
                    <x-text-input id="author_photo" name="author_photo" type="file" class="block w-full" :value="old('author_photo')" required/>
                </div>
                <x-input-error :messages="$errors->get('author_photo')" class="mt-2" />
            </div>

            <!-- about_author -->
            <div>
                <x-input-label for="about_author" :value="__('Краткое описание')" />
                <textarea id="about_author" name="about_author" rows="2" cols="33" class="mt-1 block w-full" style="resize: none;border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));" required autocomplete="about_author"></textarea>
                <x-input-error class="mt-2" :messages="$errors->get('about_author')" />
            </div>

            <div class="flex items-center justify-end gap-4">
                <x-primary-button>{{ __('Сохранить') }}</x-primary-button>
            </div>
        </form>
    </details>
</section>
