@extends('partials.layouts.layout')

@section('title', 'User Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.confirmmessage')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <h3 class="fw-bold fs-4">Current Users</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            @include('partials.tables.currentUsers')
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="row mt-5">
                        <div class="col">
                            <h3 class="fw-bold fs-4">Users</h3>
                        </div>
                        @if (in_array(auth()->user()->role_id, [1, 2]))
                            <div class="col text-end">
                                <a href="/settings/user/create" class="btn btn-sm btn-success">Add User</a>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col">
                            @include('partials.tables.users_table')
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
