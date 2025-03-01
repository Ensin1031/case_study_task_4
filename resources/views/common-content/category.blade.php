<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex gap-4 w-full pb-6">
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Описание категории') }}</h2>
                    </div>
                    <div class="flex w-full">
                        <div class="flex flex-col items-center" style="width: 12rem;min-height: 6rem;overflow: hidden;">
                            <div class="text-sm">Книг в категории: </div>
                            <div class="text-lg">{{ count($category->books) }}</div>
                        </div>
                        <div class="flex flex-col pl-4 w-full">
                            <h2 class="pb-6 text-lg font-medium text-gray-900">{{ $category->category_name }}</h2>
                            <div>{{ $category->about_category }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex gap-4 w-full pb-6">
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Книги категории') }}</h2>
                </div>
                <div class="flex gap-4 w-full pb-6">
                    <x-books-filter :route="'category.show'" :query_parameters="$category->id" :need_categories="false" :authors="$authors" :statuses="$statuses"></x-books-filter>
                </div>
                <div class="flex flex-col gap-4 w-full">
                    @foreach($books as $book)
                        <x-book-card :book="$book" :need_category="false" :need_subs_manager="true" :redirect_to="'category.show'" :query_parameters="$category->id" class="mt-2"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
