@extends('partials.layouts.layout')

@section('title', 'Create New Swimming Pool')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Create New Swimming Pool
                    </h2>
                    <hr>
                    <form action="{{ route('swimming-pools.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        @include('partials.forms.swimming_pool_app_form')
                        <button class="btn btn-primary mt-4" type="submit">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
