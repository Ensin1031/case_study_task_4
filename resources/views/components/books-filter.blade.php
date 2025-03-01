@props(['route', 'query_parameters' => [], 'categories' => [], 'authors' => [], 'statuses' => [], 'need_categories' => true, 'need_authors' => true])

<details class="w-full" {{!!(request()->query('book_title') || request()->query('book_category') || request()->query('book_author') || request()->query('book_year') || request()->query('book_status')) ? 'open="open"' : ''}}>
    <summary class="flex flex-row gap-2 items-center" style="cursor: pointer;">
        <span class="flex items-center justify-center" style="width: 1.5rem;height: 1.5rem;font-size: 1.3rem;padding-bottom: .12rem;border: 1px solid #111827; border-radius: 3px;">-</span>
        <span class="text-lg font-medium text-gray-900">{{ __('Поиск по записям книг') }}</span>
    </summary>
    <form method="get" action="{{ route($route, $query_parameters) }}" class="flex flex-col gap-2 w-full" enctype="multipart/form-data">

        <!-- Поиск по наименованию -->
        <div class="mt-2">
            <x-input-label for="book_title" :value="__('По наименованию')" />
            <x-text-input id="book_title" name="book_title" type="text" class="mt-1 block w-full" :value="request()->query('book_title')" autofocus autocomplete="title" />
        </div>

        <div class="flex justify-between gap-4">
            <!-- Поиск по категории -->
            @if(!!$need_categories)
                <div style="width: {{ !$need_authors ? '100%' : '49%' }};">
                    <x-input-label for="book_category" :value="__('По категории')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="book_category" id="book_category" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                            <option value="">------------</option>
                            @foreach($categories as $category)
                                @if ($category->id == request()->query('book_category'))
                                    <option value="{{ $category->id }}" selected>{{ $category->category_name }}</option>
                                @else
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <!-- Поиск по автору -->
            @if(!!$need_authors)
                <div style="width: {{ !$need_categories ? '100%' : '49%' }};">
                    <x-input-label for="book_author" :value="__('По автору')" />
                    <div class="flex justify-between gap-4 mt-1">
                        <select name="book_author" id="book_author" class="block w-full" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1))">
                            <option value="">------------</option>
                            @foreach($authors as $author)
                                @if ($author->id == request()->query('book_author'))
                                    <option value="{{ $author->id }}" selected>{{ $author->author_name }}</option>
                                @else
                                    <option value="{{ $author->id }}">{{ $author->author_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <div class="flex justify-between gap-4">
            <!-- Поиск по статусу -->
            <div style="width: 49%;">
                <x-input-label for="book_status" :value="__('По статусу')" />
                <select name="book_status" id="book_status" class="block w-full mt-1" style="border-radius: 5px;border: 1px solid rgb(209 213 219 / var(--tw-border-opacity, 1));">
                    <option value="">------------</option>
                    @foreach($statuses as $status)
                        @if ($status['id'] == request()->query('book_status'))
                            <option value="{{ $status['id'] }}" selected>{{ $status['title'] }}</option>
                        @else
                            <option value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Поиск по году -->
            <div style="width: 49%;">
                <x-input-label for="book_year" :value="__('По году публикации')" />
                <x-text-input id="book_year" name="book_year" type="number" min="-9999" max="9999" class="mt-1 block w-full" :value="request()->query('book_year')" autocomplete="published_year" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-2">
            <x-primary-button>{{ __('Поиск') }}</x-primary-button>
        </div>
    </form>
</details>
