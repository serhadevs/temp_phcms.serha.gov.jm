@extends('partials.layouts.layout')

@section('title', 'Online Users')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <h3 class="fw-bold fs-4">Online Users</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @include('partials.tables.currentUsers')
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    </script>
@endsection
