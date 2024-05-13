@extends('partials.layouts.layout')

@section('title', 'Renew Swimming Pool')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Renew Swimming Pool Application for {{ $application->firstname . ' ' . $application->lastname }}
                    </h2>
                    <hr>
                    <form action="{{ route('swimming-pools.renew', ['id' => $application->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        @include('partials.forms.swimming_pool_app_form')
                        <button class="btn btn-primary mt-4" type="button" onclick="showLoading(this)">
                            Submit Renewal
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
