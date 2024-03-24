@extends('partials.layouts.layout')

@section('title', "Food Handler's Downloads")

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p class="text-success"><strong>{{ $message }}</strong></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p class="text-danger font-weight-bold"><strong>{{ $message }}</strong></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <h2 class="text-muted mb-3">
                        Food Establishment Downloads
                    </h2>
                    @include('partials.tables.downloads')
                </div>
            </div>
        </div>
    </div>
@endsection
