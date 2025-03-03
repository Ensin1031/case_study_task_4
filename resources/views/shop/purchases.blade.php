@extends('layouts.shop')
@section('shop-content')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex flex-col gap-4 w-full">
                    @foreach($purchases as $purchase)
                        <x-purchase-card :book="$purchase->book" :purchase="$purchase" :user="$purchase->user" :can_edit="Auth::user()->id === $purchase->user->id" :redirect_to="'shop.purchases'" class="mt-2"/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
