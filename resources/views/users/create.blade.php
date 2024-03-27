@extends('partials.layouts.layout')

@section('title', 'Add User')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Add A User</h3>
                    <div class="row">
                        <div class="col">
                           @include('partials.forms._addUserForm')
                        </div>
                    </div>


                    <script>
                        const hamBurger = document.querySelector(".toggle-btn");

                        hamBurger.addEventListener("click", function() {
                            document.querySelector("#sidebar").classList.toggle("expand");
                        });
                    </script>

                @endsection
