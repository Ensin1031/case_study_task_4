@extends('layouts.admin-panel')
@section('admin-panel-content')

    @if(Auth::user()->is_superuser())

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex w-full">
                        @include('admin-panel.partials.create-author-form')
                    </div>
                </div>
            </div>
        </div>

        <div class="pb-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex flex-col gap-4 w-full">
                        @foreach($authors as $author)
                            <x-author-card :author="$author" :can_edit="Auth::user()->is_superuser()" :redirect_to="'admin-panel.authors'" class="mt-2"/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection
