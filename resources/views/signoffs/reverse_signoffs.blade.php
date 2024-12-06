@extends('partials.layouts.layout')

@section('title', 'Reverse Sign Off Requests')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-muted fw-bold">
                            Reverse Sign Off Requests
                        </h3>
                    </div>
                    <div class="card-body">
                        @include('partials.tables.reverse_signoff_requests_table')
                    </div>
                </div>
            </div>
        </main>

    </div>
@endsection
