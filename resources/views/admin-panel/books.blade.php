@extends('layouts.admin-panel')
@section('admin-panel-content')

    @if(Auth::user()->is_superuser())
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex" style="width: 100%;">
                        @include('admin-panel.partials.create-book-form')
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex gap-4 w-full pb-6">
                        <x-books-filter :route="$redirect_to" :categories="$categories" :authors="$authors" :statuses="$statuses"></x-books-filter>
                    </div>
                    <div class="flex flex-col gap-4 w-full">
                        @foreach($books as $book)
                            <x-book-card :book="$book" :categories="$categories" :authors="$authors" :statuses="$statuses" :can_edit="Auth::user()->is_superuser()" :redirect_to="$redirect_to" class="mt-2"/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection
