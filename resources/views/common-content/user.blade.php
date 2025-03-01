<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex gap-4 w-full pb-6">
                        <h2 class="text-lg font-medium text-gray-900">{{ __('Описание пользователя') }}</h2>
                    </div>
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
                                    <span>Имя: </span><span>{{ $user->name }}</span>
                                </h2>
                            </div>
                            <div><span>Email: </span><span>{{ $user->email }}</span></div>
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
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Книги автора') }}</h2>
                </div>
                <div class="flex gap-4 w-full pb-6">
                    <x-books-filter :route="'user.show'" :query_parameters="$user->id" :authors="$authors" :categories="$categories" :statuses="$statuses"></x-books-filter>
                </div>
                <div class="flex flex-col gap-4 w-full">
                    @foreach($books as $book)
                        <x-book-card :book="$book" :redirect_to="'user.show'" :query_parameters="$user->id" class="mt-2"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
