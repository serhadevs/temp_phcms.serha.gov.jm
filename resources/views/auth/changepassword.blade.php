@extends('partials.layouts.layout')

@section('title', 'Change Password')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')

      

        <main class="content px-3 py-4 ">
            <div class="container-fluid">
                <div class="mb-3">
                    @include('partials.messages.messages')
                    <h3 class="fw-bold fs-4 mb-3">Change Password</h3>
                    <div class="row">
                        <div class="card shadow">
                           
                            <div class="card-body">
                                @include('partials.forms._changePasswordForm')
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            const hamBurger = document.querySelector(".toggle-btn");

            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>

    @endsection
