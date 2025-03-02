@extends('layouts.rent')
@section('rent-content')

    @if(Auth::user()->is_superuser())

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex flex-col gap-4 w-full">
                        @foreach($overdue_rents as $rent)
                            <x-rent-card :book="$rent->book" :rent="$rent" :user="$rent->user" :can_edit="Auth::user()->id === $rent->user->id" :redirect_to="'rent.active'" class="mt-2"/>
                        @endforeach
                        @foreach($active_rents as $rent)
                            <x-rent-card :book="$rent->book" :rent="$rent" :user="$rent->user" :can_edit="Auth::user()->id === $rent->user->id" :redirect_to="'rent.active'" class="mt-2"/>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection
