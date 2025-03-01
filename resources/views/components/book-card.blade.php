@props(['book', 'redirect_to' => '', 'query_parameters' => [], 'can_edit' => false])

@if ($book)

    <div class="p-4 w-full" style="border-radius: .5rem;box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5), -1px -1px 4px rgba(255, 255, 255, 0.1);">
        <div class="flex w-full">
            <div class="flex flex-col items-center" style="width: 12rem;min-height: 12rem;overflow: hidden;">
                <div class="w-full" style="height: 10rem;overflow: hidden;">
                    <img src="{{ url('Image/books/'.$book->image) }}" alt="">
                </div>
            </div>
            <div class="flex flex-col pl-4 w-full">
                <h2 class="pb-6 text-lg font-medium text-gray-900">{{ $book->title }}</h2>
                <div>{{ $book->description }}</div>
            </div>
            @if(!!$can_edit)
                <div class="flex items-center whitespace-nowrap flex-col gap-2 pl-4">
                    <x-primary-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'update-author-{{ $book->id }}')"
                    >{{ __('Редактировать') }}</x-primary-button>
                    <x-danger-button
                        class="w-full"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'delete-author-{{ $book->id }}')"
                    >{{ __('Удалить') }}</x-danger-button>
                </div>
            @endif
        </div>
    </div>

    @if(!!$can_edit)
            <!-- TODO -->
    @endif

@endif
