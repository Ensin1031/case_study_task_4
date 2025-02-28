@extends('layouts.admin-panel')
@section('admin-panel-content')

    @if(Auth::user()->is_superuser())
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex" style="width: 100%;">

                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
