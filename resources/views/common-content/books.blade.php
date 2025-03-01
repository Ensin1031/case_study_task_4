<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex gap-4 w-full pb-6">
                    <x-books-filter :route="$redirect_to" :categories="$categories" :authors="$authors" :statuses="$statuses"></x-books-filter>
                </div>
                <div class="flex flex-col gap-4 w-full">
                    @foreach($books as $book)
                        <x-book-card :book="$book" :need_subs_manager="true" :redirect_to="$redirect_to" class="mt-2"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
