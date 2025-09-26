@extends('partials.layouts.layout')

@section('title', 'Administrative Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                @include('partials.messages.confirmmessage')
                <div class="mb-3">
                    <div class="col-12 col-md">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="fw-bold">
                                   Payment Types Per Facility 
                                </h4>
                            </div>
                            <div class="card-body py-4">
                                @include('partials.tables.payment_type_facilities')
                            </div>
                        </div>
                    </div>
                </div>
        </main>
    </div>
@endsection
