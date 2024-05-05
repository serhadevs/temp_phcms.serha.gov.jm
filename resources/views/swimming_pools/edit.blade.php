@extends('partials.layouts.layout')

@section('title', 'Edit Swimming Pool')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Edit Swimming Pool Application {{ $application->firstname }} {{ $application->lastname }}
                    </h2>
                    <hr>
                    <form action="{{ route('swimming-pools.update', ['id' => $application->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.swimming_pool_app_form')
                        <button class="btn btn-primary mt-4">
                            Update Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
