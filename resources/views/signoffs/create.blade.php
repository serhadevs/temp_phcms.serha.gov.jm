@extends('partials.layouts.layout')

@section('title', 'Create')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')

        <main class="contentpy-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <div class="card shadow">
                        <h4 class="card-header">Sign Off Applications</h4>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col mx-auto">
                                    @include('partials.forms._signoffForm')
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
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
