@extends('partials.layouts.layout')

@section('title', 'Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @if ($message = Session::get('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="text-success"><strong>{{ $message }}</strong></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="text-danger font-weight-bold">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Users</h3>
                    <div class="row">
                        <div class="col">
                            @include('partials.tables.users_table')
                        </div>
                    </div>


                    <script>
                        const hamBurger = document.querySelector(".toggle-btn");

                        hamBurger.addEventListener("click", function() {
                            document.querySelector("#sidebar").classList.toggle("expand");
                        });
                    </script>

                @endsection
